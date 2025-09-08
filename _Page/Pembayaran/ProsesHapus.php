<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Datetime Sekarang
    $now=date('Y-m-d H:i:s');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_payment
    if(empty($_POST['id_payment'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Pembayaran Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_payment=validateAndSanitizeInput($_POST['id_payment']);

    //Hapus Data
    $HapusDataPayment = mysqli_query($Conn, "DELETE FROM payment WHERE id_payment='$id_payment'") or die(mysqli_error($Conn));
    if($HapusDataPayment){

        //Simpan Log
        $kategori_log="Pembayaran";
        $deskripsi_log="Hapus Pembayaran Berhasil";
        $InputLog=addLog($Conn,$SessionIdAccess,$now,$kategori_log,$deskripsi_log);
        if($InputLog=="Success"){
            echo '
                <small class="text-success" id="NotifikasiHapusBerhasil">Success</small>
            ';
        }else{
            echo '
                <div class="alert alert-danger">
                    <small>Terjadi kesalahan pada saat menyimpan Log!</small>
                </div>
            ';
        }
    }else{
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat menghapus data pada database!</small>
            </div>
        ';
    }
?>