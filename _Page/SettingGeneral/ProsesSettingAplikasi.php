<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Time Now Tmp
    $now=date('Y-m-d H:i:s');

    // --- Validasi singkat ---
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }
    $required = ['app_title','app_keyword','app_description','app_base_url','app_author','app_year'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }
   // Sanitasi input
    $app_title          = validateAndSanitizeInput($_POST['app_title']);
    $app_keyword        = validateAndSanitizeInput($_POST['app_keyword']);
    $app_description    = validateAndSanitizeInput($_POST['app_description']);
    $app_base_url       = validateAndSanitizeInput($_POST['app_base_url']);
    $app_author         = validateAndSanitizeInput($_POST['app_author']);
    $app_year           = validateAndSanitizeInput($_POST['app_year']);

    //Ubah app_keyword menjadi json
    $keyword_array = array_map('trim', explode(',', $app_keyword));
    $json_keyword = json_encode($keyword_array, JSON_UNESCAPED_UNICODE);

    //Simpan Pengaturan
    $UpdateSetting = mysqli_query($Conn,"UPDATE app_configuration SET 
        app_title='$app_title',
        app_keyword='$json_keyword',
        app_description='$app_description',
        app_base_url='$app_base_url',
        app_author='$app_author',
        app_year='$app_year'
    WHERE id_configuration='1'") or die(mysqli_error($Conn)); 
    if($UpdateSetting){
        $_SESSION ["NotifikasiSwal"]="Simpan Setting General Berhasil";
        echo '<small class="text-success" id="NotifikasiSimpanSettingGeneralBerhasil">Success</small>';
    }else{
        echo '<small class="text-danger">Terjadi kesalahan pada saat update data pengaturan</small>';
    }
?>