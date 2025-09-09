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
    $required = ['id_academic_period','class_level','class_name'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_academic_period    = validateAndSanitizeInput($_POST['id_academic_period']);
    $class_level    = validateAndSanitizeInput($_POST['class_level']);
    $class_name     = validateAndSanitizeInput($_POST['class_name']);

    //Validasi Duplikat Data
    $stmt = $Conn->prepare("SELECT COUNT(*) FROM  organization_class  WHERE class_level=? AND class_name=? AND id_academic_period=?");
    $stmt->bind_param("ssi", $class_level, $class_name, $id_academic_period);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo '<div class="alert alert-danger"><small>Data yang anda masukan sudah terdaftar</small></div>';
        exit;
    }

    // Insert Data Menggunakan Prepared Statement
    $stmt = $Conn->prepare("INSERT INTO organization_class (id_academic_period, class_level, class_name) VALUES (?, ?, ?)");
    $stmt->bind_param("iss",$id_academic_period, $class_level, $class_name);
    $Input = $stmt->execute();
    $stmt->close();

    if($Input){
        $kategori_log="Kelas";
        $deskripsi_log="Input Kelas Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiTambahBerhasil">Success</code>';
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan log</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat input data kelas</small></div>';
    }
?>