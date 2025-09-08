<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }

    //Validasi Form Required
    $required = ['id_payment','payment_datetime','payment_method','payment_nominal'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_payment             = validateAndSanitizeInput($_POST['id_payment']);
    $payment_datetime       = validateAndSanitizeInput($_POST['payment_datetime']);
    $payment_nominal        = validateAndSanitizeInput($_POST['payment_nominal']);
    $payment_method         = validateAndSanitizeInput($_POST['payment_method']);

    if(empty($_POST['payment_nominal'])){
        $payment_nominal=0;
    }else{
        $payment_nominal= validateAndSanitizeInput($_POST['payment_nominal']);
        $payment_nominal= str_replace('.', '', $payment_nominal);
    }

    //Update Data
    $UpdateEntitias = mysqli_query($Conn,"UPDATE payment SET 
        payment_datetime='$payment_datetime',
        payment_method='$payment_method',
        payment_nominal='$payment_nominal'
    WHERE id_payment='$id_payment'") or die(mysqli_error($Conn)); 
    if($UpdateEntitias){
       echo '<code class="text-success" id="NotifikasiEditBerhasil">Success</code>';
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data</small></div>';
    }
?>