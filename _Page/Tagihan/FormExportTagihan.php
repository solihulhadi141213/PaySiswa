<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    //Validasi ID Periode Akademik
    if(empty($_POST['id_academic_period'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Periode Akademik Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Validasi ID Kelas
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Kelas Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

   //Buat variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Detail Periode Akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    //Buka class_name
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');

    //Form Hidden
    echo '
        <input type="hidden" name="id_academic_period" value="'.$id_academic_period.'">
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
    ';

    //Form Option Type File
    echo '
       <div class="row mb-2">
            <div class="col-5"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Periode Mulai</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$academic_period_start.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Periode Selesai</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$academic_period_end.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Level/Jenjang</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Kelas/Rombel</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
        </div>
    ';

?>