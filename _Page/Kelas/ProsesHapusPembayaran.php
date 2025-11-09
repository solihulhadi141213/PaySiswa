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
    //Tangkap id_payment
    if(empty($_POST['id_payment'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Pembayaran Siswa Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_payment=validateAndSanitizeInput($_POST['id_payment']);
    
    //Proses hapus data
    $ProsesHapus = mysqli_query($Conn, "DELETE FROM payment WHERE id_payment='$id_payment'") or die(mysqli_error($Conn));
    if ($ProsesHapus) {
        echo '<span class="text-success" id="NotifikasiHapusPembayaranBerhasil">Success</span>';
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