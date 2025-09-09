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
    $required = ['id_academic_period'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    // Buatkan Variabelnya
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka status
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period_status = $Data['academic_period_status'];

    //routing berdasarkan status lama
    if($academic_period_status==true){
        $academic_period_status=false;
    }else{
        $academic_period_status=true;
    }

    // Menggunakan Prepared Statement untuk UPDATE
    $stmt = $Conn->prepare("UPDATE academic_period SET academic_period_status=? WHERE id_academic_period=?");
    $stmt->bind_param("si", $academic_period_status, $id_academic_period);
    $Update = $stmt->execute();
    $stmt->close();

    if($Update){
        $kategori_log="Periode Akademik";
        $deskripsi_log="Update Periode Akademik ID $id_academic_period";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiKunciBerhasil">Success</code>';
        }else{
            echo '<code class="text-danger">Terjadi kesalahan pada saat menyimpan Log</code>';
        }
    }else{
        echo '<code class="text-danger">Terjadi kesalahan pada saat memperbarui data</code>';
    }
?>
