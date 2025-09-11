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
    $required = ['id_fee_by_student','id_organization_class','id_fee_component','id_student','payment_datetime','payment_method'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_fee_by_student      = validateAndSanitizeInput($_POST['id_fee_by_student']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $payment_datetime       = validateAndSanitizeInput($_POST['payment_datetime']);
    $payment_method         = validateAndSanitizeInput($_POST['payment_method']);

    if(empty($_POST['payment_nominal'])){
        $payment_nominal=0;
    }else{
        $payment_nominal= validateAndSanitizeInput($_POST['payment_nominal']);
        $payment_nominal= str_replace('.', '', $payment_nominal);
    }

    //Generate uuid
    $id_payment=generateRandomString(36);
    
    // Insert Data Menggunakan Prepared Statement
    $stmt = $Conn->prepare("INSERT INTO payment (
    id_payment, 
    id_fee_by_student, 
    id_student, 
    id_organization_class, 
    id_fee_component, 
    payment_datetime, 
    payment_nominal, 
    payment_method
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiisss", $id_payment, $id_fee_by_student, $id_student, $id_organization_class, $id_fee_component, $payment_datetime, $payment_nominal, $payment_method);
    $Input = $stmt->execute();
    $stmt->close();

    if($Input){
        $kategori_log="Pembayaran";
        $deskripsi_log="Input Pembayaran Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiBayarBerhasil">Success</code>';
            echo '<input type="hidden" name="get_id_student" id="get_id_student" value="'.$id_student.'">';
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan log</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat input data pembayaran</small></div>';
    }
?>