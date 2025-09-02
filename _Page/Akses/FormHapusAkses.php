<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }

    //Validasi id_access
    if(empty($_POST['id_access'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    ID Akses Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }
    $id_access=$_POST['id_access'];
    $id_access=validateAndSanitizeInput($_POST['id_access']);
    //Buka Data access
    $Qry = $Conn->prepare("SELECT * FROM access WHERE id_access = ?");
    $Qry->bind_param("i", $id_access);
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
        $id_access_group    =$Data['id_access_group'];
        $access_name        =$Data['access_name'];
        $access_email       =$Data['access_email'];
        $access_contact     =$Data['access_contact'];
        $access_foto_saya   =$Data['access_foto'];

        //Buka Nama Group
        $group_name=GetDetailData($Conn, 'access_group', 'id_access_group', $id_access_group, 'group_name');

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_access" value="'.$id_access.'">
            <div class="row mb-2">
                <div class="col-4"><small>Nama</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$access_name.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Email</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$access_email.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kontak</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$access_contact.'</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4"><small>Entitas/Group</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$group_name.'</small>
                </div>
            </div>
            <div class="row mb-3 mt-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <h2><i class="bi bi-exclamation-triangle"></i> Penting!</h2>
                        <small>
                            Dengan menghapus data akses tersebut akan menyebabkan yang bersangkutan tidak dapat masuk / mengakses aplikasi.<br>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </small>
                    </div>
                </div>
            </div>
        ';
    }
?>