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

    //looping
    $HapusLog = mysqli_query($Conn, "TRUNCATE TABLE access_log") or die(mysqli_error($Conn));

    //Proses hapus data
    echo '<span class="text-success" id="NotifikasiHapusSemuaBerhasil">Success</span>'; 
?>