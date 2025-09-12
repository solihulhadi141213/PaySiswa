<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');

    //Time Now Tmp
    $now=date('Y-m-d H:i:s');

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
                    ID Log Akses Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_access_log=validateAndSanitizeInput($_POST['id_access_log']);

    //Proses hapus data
    $HapusLog = mysqli_query($Conn, "DELETE FROM access_log WHERE id_access_log='$id_access_log'") or die(mysqli_error($Conn));
    if ($HapusLog) {
        echo '<span class="text-success" id="NotifikasisHapusBerhasil">Success</span>';     
    }else{

        //Jika menghapus gagal
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat menghapus data!
                </small>
            </div>
        ';
    }
?>