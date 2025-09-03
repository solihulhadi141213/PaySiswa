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
    $required = ['id_organization_class','class_level','class_name'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $class_level            = validateAndSanitizeInput($_POST['class_level']);
    $class_name             = validateAndSanitizeInput($_POST['class_name']);

    //Buka Data Lama
    $class_level_old=GetDetailData($Conn,'organization_class','id_organization_class',$id_organization_class,'class_level');
    $class_name_old=GetDetailData($Conn,'organization_class','id_organization_class',$id_organization_class,'class_name');

    if($class_level!==$class_level_old&&$class_name!==$class_name_old){
        
        //Validasi Duplikat Data
        $stmt = $Conn->prepare("SELECT COUNT(*) FROM  organization_class  WHERE class_level=? AND class_name=?");
        $stmt->bind_param("ss", $class_level, $class_name);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo '<div class="alert alert-danger"><small>Data yang anda masukan sudah terdaftar</small></div>';
            exit;
        }
    }

    // Update Data
    $UpdateData = mysqli_query($Conn,"UPDATE organization_class SET 
        class_level='$class_level',
        class_name='$class_name'
    WHERE id_organization_class='$id_organization_class'") or die(mysqli_error($Conn)); 
    if($UpdateData){
        $kategori_log="Kelas";
        $deskripsi_log="Update Kelas Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiEditBerhasil">Success</code>';
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan log</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat update data kelas</small></div>';
    }
?>