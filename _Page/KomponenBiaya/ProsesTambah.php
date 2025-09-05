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
    $required = ['component_name','period_value','period_unit','periode_start','periode_end','fee_nominal'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }
    
    //Buat Variabel
    $component_name     = validateAndSanitizeInput($_POST['component_name']);
    $period_value       = validateAndSanitizeInput($_POST['period_value']);
    $period_unit        = validateAndSanitizeInput($_POST['period_unit']);
    $periode_start      = validateAndSanitizeInput($_POST['periode_start']);
    $periode_end        = validateAndSanitizeInput($_POST['periode_end']);
    $fee_nominal        = validateAndSanitizeInput($_POST['fee_nominal']);
    $fee_nominal        = str_replace('.', '', $fee_nominal);

    // Menggunakan Prepared Statement
    $stmt = $Conn->prepare("INSERT INTO fee_component (
        component_name, 
        period_value, 
        period_unit, 
        periode_start, 
        periode_end,
        fee_nominal
    ) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", 
        $component_name, 
        $period_value, 
        $period_unit, 
        $periode_start, 
        $periode_end,
        $fee_nominal
    );
    $Input = $stmt->execute();
    $stmt->close();

    if($Input){
        $kategori_log="Komponen Biaya";
        $deskripsi_log="Input Komponen Biaya Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiTambahBerhasil">Success</code>';
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan Log</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data</small></div>';
    }
?>