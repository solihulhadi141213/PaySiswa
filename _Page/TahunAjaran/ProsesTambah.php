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
    $required = ['academic_period','academic_period_start','academic_period_end'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    // Buatkan Variabelnya
    $academic_period        = validateAndSanitizeInput($_POST['academic_period']);
    $academic_period_start  = validateAndSanitizeInput($_POST['academic_period_start']);
    $academic_period_end    = validateAndSanitizeInput($_POST['academic_period_end']);
    $academic_period_status = 1;

    if($academic_period_start>$academic_period_end){
         echo '
            <div class="alert alert-danger">
                <small>Periode mulai tidak boleh lebih besar dari periode akhir</small>
            </div>
        ';
        exit;
    }
    

    // Menggunakan Prepared Statement
    $stmt = $Conn->prepare("INSERT INTO academic_period (academic_period, academic_period_start, academic_period_end, academic_period_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $academic_period, $academic_period_start, $academic_period_end, $academic_period_status);
    $Input = $stmt->execute();
    $stmt->close();

    if($Input){
        $kategori_log="Periode Akademik";
        $deskripsi_log="Input Periode Akademik Akses";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiTambahBerhasil">Success</code>';
        }else{
            echo '<code class="text-danger">Terjadi kesalahan pada saat menyimpan Log</code>';
        }
    }else{
        echo '<code class="text-danger">Terjadi kesalahan pada saat menyimpan data</code>';
    }
?>
