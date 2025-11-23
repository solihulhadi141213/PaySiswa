<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }
    //Tangkap id_organization_class
    if(empty($_POST['id_fee_by_student'])){
            echo '
            <div class="alert alert-danger">
                <small>
                    ID Tagihan Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka data fee_by_student
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_by_student = ?");
    $Qry->bind_param("i", $id_fee_by_student);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $id_organization_class  = $Data['id_organization_class'];
    $id_student             = $Data['id_student'];
    $id_fee_component       = $Data['id_fee_component'];
    $fee_nominal            = $Data['fee_nominal'];
    $fee_nominal            = round($fee_nominal);
    $fee_nominal            = str_replace('.', '', $fee_nominal);
    if($Data['fee_discount']=="0.00"){
        $fee_discount="";
    }else{
        $fee_discount= $Data['fee_discount'];
    }

    //Buka Periode Pendidikan
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    

    //Buka Detail Siswa
    $QrySiswa = $Conn->prepare("SELECT student_nis, student_name, student_gender FROM student WHERE id_student = ?");
    $QrySiswa->bind_param("i", $id_student);
    if (!$QrySiswa->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $ResultSiswa = $QrySiswa->get_result();
    $DataSswa = $ResultSiswa->fetch_assoc();
    $QrySiswa->close();

    //Buat Variabel
    $student_nis            = $DataSswa['student_nis'] ?? '-';
    $student_name           = $DataSswa['student_name'];
    $student_gender         = $DataSswa['student_gender'];

    //Routing gender
    if($student_gender=="Male"){
        $gender = "Laki-laki";
    }else{
        if($student_gender=="Female"){
            $gender = "Perempuan";
        }else{
            $gender = "-";
        }
    }

    //Buka Detail fee_component
    $QryComponent = $Conn->prepare("SELECT component_name, component_category, fee_nominal FROM fee_component WHERE id_fee_component = ?");
    $QryComponent->bind_param("i", $id_fee_component);
    if (!$QryComponent->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $ResultComponent = $QryComponent->get_result();
    $DataComponent = $ResultComponent->fetch_assoc();
    $QryComponent->close();

    //Buat Variabel
    $component_name         = $DataComponent['component_name'] ?? '-';
    $component_category     = $DataComponent['component_category'] ?? '-';
    $fee_nominal_component  = $DataComponent['fee_nominal'] ?? '-';
    
    //Format Rupiah
    $fee_nominal_format="Rp " . number_format($fee_nominal_component,0,',','.');

    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
    ';
    echo '
        <div class="row mb-2">
            <div class="col-12">
                <small><b># Periode Akademik</b></small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Jenjang/Level</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$class_level.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Kelas/Rombel</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$class_name.'</small></div>
        </div>
        <div class="row mb-2 mt-3">
            <div class="col-12">
                <small><b># Identitas Siswa</b></small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Nama Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$student_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>NIS</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$student_nis.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Jenis Kelamin</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$gender.'</small></div>
        </div>
        <div class="row mb-2 mt-3">
            <div class="col-12">
                <small><b># Komponen Biaya Pendidikan</b></small>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Kompnen Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$component_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Kategori Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$component_category.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Tarif Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$fee_nominal_format.'</small></div>
        </div>
        <div class="row mb-2 mt-3">
            <div class="col-12 text-center">
                <div class="alert alert-danger">
                    <small>
                        Menghapus data tagihan akan menyebabkan riwayat pembayaran dihapus dari database. Tindakan ini akan menyebabkan siswa tidak akan bisa melakukan pembayaran atas tagihan tersebut.<br>
                        <b>Apakah anda yakin akan menghapus data tersebut?</b>
                    </small>
                </div>
            </div>
        </div>
    ';
?>