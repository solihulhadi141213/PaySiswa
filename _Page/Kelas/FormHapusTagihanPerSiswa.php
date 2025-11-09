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
    //Validasi Form Required
    $required = ['id_organization_class','id_student'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);

    //Buka Nama dan NIS siswa
    $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name   = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

    //Buka Detail Informasi Kelas
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    
    //Buka Periode Akademik
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Hitung Jumlah Tagihan
    $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
    $jumlah_nominal_tagihan         = $SumTagihan['total_tagihan'];
    $jumlah_nominal_tagihan_format  = "Rp " . number_format($jumlah_nominal_tagihan,0,',','.');

    //Hitung Jumlah Diskon
    $SumDiskon = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS total_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
    $jumlah_diskon = $SumDiskon['total_diskon'];
    $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');

    //Menghiitung Jumlah Tagihan
    $jumlah_tagihan         = $jumlah_nominal_tagihan - $jumlah_diskon;
    $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');

    echo '
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="id_student" value="'.$id_student.'">
    ';
    echo '
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
            <div class="col-4"><small>Nominal Tagihan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$jumlah_nominal_tagihan_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Diskon</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$jumlah_diskon_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Jumlah Tagihan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$jumlah_tagihan_format.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-12 text-center">
                <div class="alert alert-danger">
                    <h3>PERHATIAN!</h3>
                    <small>
                        Menghapus data tagihan ini, akan menyebabkan siswa bersangkutan tidak dapat melakukan pembayaran jika masih memiliki tunggakan.
                        <p>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </p>
                    </small>
                </div>
            </div>
        </div>
    ';
?>