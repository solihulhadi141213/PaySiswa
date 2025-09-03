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
    $required = ['company_name','company_contact','company_email','company_address'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }
    
    // Sanitasi input
    $company_name       = validateAndSanitizeInput($_POST['company_name']);
    $company_contact    = validateAndSanitizeInput($_POST['company_contact']);
    $company_email      = validateAndSanitizeInput($_POST['company_email']);
    $company_address    = validateAndSanitizeInput($_POST['company_address']);

    // Buat array asosiatif
    $company_data = [
        "company_name"    => $company_name,
        "company_email"   => $company_email,
        "company_address" => $company_address,
        "company_contact" => $company_contact
    ];

    // Konversi ke JSON
    $json_company = json_encode($company_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    //Simpan Pengaturan
    $UpdateSetting = mysqli_query($Conn,"UPDATE app_configuration SET 
        app_company='$json_company'
    WHERE id_configuration='1'") or die(mysqli_error($Conn)); 
    if($UpdateSetting){
        $_SESSION ["NotifikasiSwal"]="Simpan Setting General Berhasil";
        echo '<small class="text-success" id="NotifikasiUpdateCompanyBerhasil">Success</small>';
    }else{
        echo '<small class="text-danger">Terjadi kesalahan pada saat update data pengaturan</small>';
    }
?>