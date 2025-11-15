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
    $required = [
        'id_academic_period','component_name','component_category',
        'periode_month','periode_year','periode_start','periode_end','fee_nominal'
    ];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_academic_period = validateAndSanitizeInput($_POST['id_academic_period']);
    $component_name     = validateAndSanitizeInput($_POST['component_name']);
    $component_category = validateAndSanitizeInput($_POST['component_category']);
    $periode_month      = validateAndSanitizeInput($_POST['periode_month']);
    $periode_year       = validateAndSanitizeInput($_POST['periode_year']);
    $periode_year       = str_replace('.', '', $periode_year);

    $periode_start      = validateAndSanitizeInput($_POST['periode_start']);
    $periode_end        = validateAndSanitizeInput($_POST['periode_end']);

    $fee_nominal        = validateAndSanitizeInput($_POST['fee_nominal']);
    $fee_nominal        = str_replace('.', '', $fee_nominal);

    // Pastikan fee_nominal angka valid
    if (!is_numeric($fee_nominal)) {
        echo '<div class="alert alert-danger"><small>fee_nominal harus berupa angka!</small></div>';
        exit;
    }

    // --- PREPARED STATEMENT ---
    $query = "
        INSERT INTO fee_component (
            id_academic_period, 
            component_name, 
            component_category, 
            periode_month, 
            periode_year, 
            periode_start, 
            periode_end,
            fee_nominal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $Conn->prepare($query);

    // Debug jika prepare gagal
    if (!$stmt) {
        echo '<div class="alert alert-danger"><small>Prepare gagal: ' . $Conn->error . '</small></div>';
        exit;
    }

    // Binding parameter
    // Struktur tabel:
    // id_academic_period = int
    // component_name = string
    // component_category = string
    // periode_month = int
    // periode_year = int
    // periode_start = date (string 'YYYY-MM-DD')
    // periode_end = date
    // fee_nominal = decimal(10,0) → gunakan string atau int
    $bind = $stmt->bind_param(
        "issiissi",
        $id_academic_period,
        $component_name,
        $component_category,
        $periode_month,
        $periode_year,
        $periode_start,
        $periode_end,
        $fee_nominal
    );

    // Debug jika bind gagal
    if (!$bind) {
        echo '<div class="alert alert-danger"><small>Bind Param gagal: ' . $stmt->error . '</small></div>';
        exit;
    }

    // Eksekusi
    $execute = $stmt->execute();

    // Debug jika eksekusi gagal
    if (!$execute) {
        echo '<div class="alert alert-danger"><small>Error saat INSERT: ' . $stmt->error . '</small></div>';
        exit;
    }

    $stmt->close();

    // Jika berhasil → simpan log
    $kategori_log  = "Komponen Biaya";
    $deskripsi_log = "Input Komponen Biaya Berhasil";
    $InputLog      = addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);

    if ($InputLog == "Success") {
        echo '<code class="text-success" id="NotifikasiTambahBerhasil">Success</code>';
    } else {
        echo '<div class="alert alert-danger"><small>Data tersimpan, tetapi gagal menyimpan log!</small></div>';
    }
?>
