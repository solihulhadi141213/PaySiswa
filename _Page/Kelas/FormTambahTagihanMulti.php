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
    $required = ['id_academic_period','id_organization_class','id_fee_component'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);

    //Buka Detail Informasi Kelas
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

    //Buka Nominal dan diskon
    $fee_nominal    =GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'fee_nominal');
   

    echo '
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="id_academic_period" value="'.$id_academic_period.'">
        <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">
    ';
    echo '
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <small>
                        Berikut ini adalah form untuk mengisi biaya pendidikan siswa secara multiple.
                        Pada form ini anda bisa menambahkan biaya pendidikan pada satu komponen biaya untuk semua siswa sekaliigus.
                    </small>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Level/Kelas</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$class_level.'/'.$class_name.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Biaya Pendidiikan</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$component_name.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>Periode Biaya</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                <label for="fee_nominal">
                    <small>Nominal</small>
                </label>
            </div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <input type="text" name="fee_nominal" id="fee_nominal" class="form-control form-money" value="'.$fee_nominal.'">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                <label for="fee_discount">
                    <small>Diskon</small>
                </label>
            </div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7">
                <input type="text" name="fee_discount" id="fee_discount" class="form-control form-money" value="0">
                <div class="form-check">
                    <input class="form-check-input" checked type="checkbox" name="UpdateYangSudahAda" id="UpdateYangSudahAda" value="1">
                    <label class="form-check-label" for="UpdateYangSudahAda">
                        <small>Update data yang sudah ada</small>
                    </label>
                </div>
            </div>
        </div>
    ';
?>