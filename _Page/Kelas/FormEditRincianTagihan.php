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
    //Tangkap id_fee_by_student
    if(empty($_POST['id_fee_by_student'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Rincian Tagihan Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka Data fee_by_student
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
    $fee_discount           = $Data['fee_discount'];

    $fee_nominal            = round($fee_nominal);
    $fee_discount           = round($fee_discount);

    //Buka Nama Komponen
    $component_name = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');

    //Buka Nama Siswa dan NIS
    $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_nisn = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nisn');

    //Buka Kelas
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka periode akademik
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Tampilkan Data
    echo '<input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">';
    echo '<input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">';
    echo '<input type="hidden" name="id_student" value="'.$id_student.'">';

    //Menampilkan Detail Tagihan
    echo '
        <div class="row mb-2">
            <div class="col-5"><small>Nama Siswa</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$student_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>NIS</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$student_nisn.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Jenjang/Level</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Kelas/Rombel</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Komponen Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$component_name.'</small></div>
        </div>
    ';
   

    //Menampilkan Form
    echo '
         <div class="row mb-2 mt-3">
            <div class="col-12">
                <label for="nominal_tagihan_siswa3"><small>Nominal Tagihan</small></label>
                <input type="text" class="form-control form-money" id="nominal_tagihan_siswa3" name="fee_nominal" placeholder="Rp" value="'.$fee_nominal.'">
            </div>
        </div>
    ';
    echo '
        <div class="row mb-2">
            <div class="col-12">
                <label for="nominal_diskon_siswa3"><small>Diskon</small></label>
                <input type="text" class="form-control form-money" id="nominal_diskon_siswa3" name="fee_discount" placeholder="Rp" value="'.$fee_discount.'">
            </div>
        </div>
    ';
?>

