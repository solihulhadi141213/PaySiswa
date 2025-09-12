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
    //Tangkap id_access_log
    if(empty($_POST['id_access_log'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    Tidak ada log aktivitas yang dipilih! Silahkan pilih item log terlebih dulu!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $jumlah_log=count($_POST['id_access_log']);

    if(empty($jumlah_log)){
        echo '
            <div class="alert alert-danger">
                <small>
                    Tidak ada log aktivitas yang dipilih! Silahkan pilih item log terlebih dulu!
                </small>
            </div>
        ';
        exit;
    }

    $id_access_log=$_POST['id_access_log'];
    foreach ($id_access_log as $id_access_log_list) {
        echo '<input type="hidden" name="id_access_log[]" value="'.$id_access_log_list.'">';
    }
    echo '
        <div class="alert alert-info">
            <small>
                <b>'.$jumlah_log.' log aktivitas dipilih</b> .<br>
                Apakah anda yakin akan menghapus log aktivitas tersebut?
            </small>
        </div>
    ';

?>