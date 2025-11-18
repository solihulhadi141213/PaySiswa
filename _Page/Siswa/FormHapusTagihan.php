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

    //Validasi 'id_fee_by_student' 
    if (empty($_POST['id_fee_by_student'])) {
        echo '
            <div class="alert alert-danger">
                <small>
                    ID Tagihan Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }
    
    //Buat Variabel
    $id_fee_by_student  = validateAndSanitizeInput($_POST['id_fee_by_student']);
  
    //Buka 'fee_by_student'
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student  WHERE id_fee_by_student = ?");
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
    $fee_discount           = $Data['fee_discount'];
    
    //Buka Nama dan NIS siswa
    $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name   = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');


    //Buka Detail Informasi Kelas
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka Periode Akademik
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka Biaya Pendidikan
    $component_name    = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $periode_month    = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year    = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

    //Nama Bulan
    $nama_bulan=getNamaBulan($periode_month);

    //Pembulatan dan format
    $fee_nominal    = round($fee_nominal);
    $fee_discount   = round($fee_discount);

    $fee_nominal_format     = "Rp " . number_format($fee_nominal,0,',','.');
    $fee_discount_format    = "Rp " . number_format($fee_discount,0,',','.');

    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
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
            <div class="col-4"><small>Level/Kelas</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$class_level.'/'.$class_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Biaya Pendidiikan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$component_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Periode Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Nominal</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$fee_nominal_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Diskon</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$fee_discount_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-12 text-center">
                <div class="alert alert-danger">
                    <h3>PERHATIAN!</h3>
                    <small>
                        Menghapus data tagihan akan menyebabkan siswa bersangkutan tidak dapat melakukan pembayaran untuk komponen biaya pendidikan yang dipilih.
                        <p>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </p>
                    </small>
                </div>
            </div>
        </div>
    ';
?>