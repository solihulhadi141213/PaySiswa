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

    //Validasi id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    ID Kelas Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Sanitasi Variabel
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka Data Kelas
    $Qry = $Conn->prepare("SELECT * FROM organization_class WHERE id_organization_class = ?");
    $Qry->bind_param("i", $id_organization_class);
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
        $class_level    =$Data['class_level'];
        $class_name     =$Data['class_name'];

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
            <div class="row mb-2">
                <div class="col-4"><small>Level</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$class_level.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kelas</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$class_name.'</small>
                </div>
            </div>
            <div class="row mb-3 mt-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <h2><i class="bi bi-exclamation-triangle"></i> Penting!</h2>
                        <small>
                            Dengan menghapus data kelas tersebut akan menyebabkan siswa yang terhubung tidak akan teridentifikasi.<br>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </small>
                    </div>
                </div>
            </div>
        ';
    }
?>