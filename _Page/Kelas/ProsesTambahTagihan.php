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
    $required = ['id_organization_class','id_student','id_fee_component'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);

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

    //Proses Insert OR Update
    if(empty($_POST['id_fee_by_student'])){
        //Insert data
        $stmt = $Conn->prepare("INSERT INTO fee_by_student (id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss",$id_organization_class, $id_student, $id_fee_component, $fee_nominal, $fee_discount);
        $Input = $stmt->execute();
        $stmt->close();

        if($Input){
            echo '<code class="text-success" id="NotifikasiTambahTagihanBerhasil">Success</code>';
            
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat input data</small></div>';
        }
    }else{
        $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);
        
        //Update Data
        $UpdateEntitias = mysqli_query($Conn,"UPDATE fee_by_student SET 
            fee_nominal='$fee_nominal',
            fee_discount='$fee_discount'
        WHERE id_fee_by_student='$id_fee_by_student'") or die(mysqli_error($Conn)); 
        if($UpdateEntitias){
            echo '<code class="text-success" id="NotifikasiTambahTagihanBerhasil">Success</code>';
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data</small></div>';
        }
    }
?>  