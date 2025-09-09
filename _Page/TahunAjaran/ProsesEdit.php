<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');

    //Time Now Tmp
    $now=date('Y-m-d H:i:s');

    // Validasi Sesi Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    // Validasi Form tidak boleh kosong
    $required = ['id_academic_period','academic_period','academic_period_start','academic_period_end'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    // Buatkan Variabelnya
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);
    $academic_period        = validateAndSanitizeInput($_POST['academic_period']);
    $academic_period_start  = validateAndSanitizeInput($_POST['academic_period_start']);
    $academic_period_end    = validateAndSanitizeInput($_POST['academic_period_end']);
    $academic_period_status = 1;

    // Validasi range tanggal
    if($academic_period_start > $academic_period_end){
        echo '
            <div class="alert alert-danger">
                <small>Periode mulai tidak boleh lebih besar dari periode akhir</small>
            </div>
        ';
        exit;
    }

    // Menggunakan Prepared Statement untuk UPDATE
    $stmt = $Conn->prepare("UPDATE academic_period 
        SET academic_period=?, academic_period_start=?, academic_period_end=?, academic_period_status=? 
        WHERE id_academic_period=?");
    $stmt->bind_param("ssssi", $academic_period, $academic_period_start, $academic_period_end, $academic_period_status, $id_academic_period);
    $Update = $stmt->execute();
    $stmt->close();

    if($Update){
        $kategori_log="Periode Akademik";
        $deskripsi_log="Update Periode Akademik ID $id_academic_period";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiEditBerhasil">Success</code>';
        }else{
            echo '<code class="text-danger">Terjadi kesalahan pada saat menyimpan Log</code>';
        }
    }else{
        echo '<code class="text-danger">Terjadi kesalahan pada saat memperbarui data</code>';
    }
?>
