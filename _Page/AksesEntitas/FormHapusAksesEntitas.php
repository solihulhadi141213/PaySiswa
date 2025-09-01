<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_access_group
    if(empty($_POST['id_access_group'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Entitas/Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_access_group=validateAndSanitizeInput($_POST['id_access_group']);
    
    //Buka Data
    $Qry = $Conn->prepare("SELECT * FROM access_group WHERE id_access_group = ?");
    $Qry->bind_param("i", $id_access_group);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
    }else{
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $group_name             =$Data['group_name'];
        $group_description      =$Data['group_description'];

        echo '
            <input type="hidden" name="id_access_group" value="'.$id_access_group.'">
            <div class="row mb-3">
                <div class="col-4"><small>Entitas/Group</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$group_name.'</small></div>
            </div>
            <div class="row mb-3">
                <div class="col-4"><small>Deskripsi</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$group_description.'</small></div>
            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <h1><i class="bi bi-exclamation-triangle"></i> Penting!</h1>
                        <small>Menghapus Entitas/Group akses akan menyebabkan beberapa akun yang terhubung kehilangan ijin aksesnya.</small>
                        <p><b>Apakah anda yakin akan menghapus data ini?</b></p>
                    </div>
                </div>
            </div>
        ';
    }
?>