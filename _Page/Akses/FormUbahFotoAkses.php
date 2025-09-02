<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
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

    //Tangkap id_access
    if(empty($_POST['id_access'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Access Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Sanitasi Variabel
    $id_access=validateAndSanitizeInput($_POST['id_access']);

    //Tampilkan Form
    echo '
        <input type="hidden" name="id_access" value="'.$id_access.'">

        <div class="row mb-3">
            <div class="col-12">
                <label for="image_akses_edit">
                    <small>Upload Foto Baru</small>
                </label>
                <input type="file" name="image_akses" id="image_akses_edit" class="form-control">
                <small>
                    <small class="text text-grayish">Maximum 2 Mb (File Type: PNG, JPG, GIF)</small>
                </small>
            </div>
        </div>
    ';
?>