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

    //Validasi 'id_fee_by_student'
    if (empty($_POST['id_fee_by_student'])) {
        echo '<div class="alert alert-danger"><small>ID Tagihan Siswa Tidak Boleh Kosong!</small></div>';
        exit;
    }

    //Buat Variabel
    $id_fee_by_student  = validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Tangkap nominal dan diskon
    if(empty($_POST['fee_nominal'])){
        $fee_nominal    =0;
    }else{
        $fee_nominal    =$_POST['fee_nominal'];
        $fee_nominal    = str_replace('.', '', $fee_nominal);
    }
    if(empty($_POST['fee_discount'])){
        $fee_discount   =0;
    }else{
        $fee_discount   =$_POST['fee_discount'];
        $fee_discount   = str_replace('.', '', $fee_discount);
    }

    //Update Data
    $UpdateEntitias = mysqli_query($Conn,"UPDATE fee_by_student SET 
        fee_nominal='$fee_nominal',
        fee_discount='$fee_discount'
    WHERE id_fee_by_student='$id_fee_by_student'") or die(mysqli_error($Conn)); 
    if($UpdateEntitias){
        echo '<code class="text-success" id="NotifiikasiEditRincianTagihanBerhasil">Success</code>';
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data</small></div>';
    }
?>  