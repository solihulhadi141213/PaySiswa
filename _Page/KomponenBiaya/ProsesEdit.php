<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi Session Akses
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }

    //Validasi Form Required
    $required = ['id_fee_component','component_name','component_category','periode_start','periode_end','fee_nominal'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }
    
    //Buat Variabel
    $id_fee_component   = validateAndSanitizeInput($_POST['id_fee_component']);
    $component_name     = validateAndSanitizeInput($_POST['component_name']);
    $component_category = validateAndSanitizeInput($_POST['component_category']);
    $periode_start      = validateAndSanitizeInput($_POST['periode_start']);
    $periode_end        = validateAndSanitizeInput($_POST['periode_end']);
    $fee_nominal        = validateAndSanitizeInput($_POST['fee_nominal']);
    $fee_nominal        = str_replace('.', '', $fee_nominal);

    //Update Data
    $UpdateEntitias = mysqli_query($Conn,"UPDATE fee_component SET 
        component_name='$component_name',
        component_category='$component_category',
        periode_start='$periode_start',
        periode_end='$periode_end',
        fee_nominal='$fee_nominal'
    WHERE id_fee_component='$id_fee_component'") or die(mysqli_error($Conn)); 
    if($UpdateEntitias){
        $kategori_log="Komponen Biaya";
        $deskripsi_log="Update Komponen Biaya Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiEditBerhasil">Success</code>';
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan Log</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data</small></div>';
    }
?>