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

    //Validasi id_fee_component
    if(empty($_POST['id_fee_component'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Komponen Biaya Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_fee_component=validateAndSanitizeInput($_POST['id_fee_component']);

    //Hapus Data
    $HapusData = mysqli_query($Conn, "DELETE FROM fee_component WHERE id_fee_component='$id_fee_component'") or die(mysqli_error($Conn));
    if($HapusData){

        //Simpan Log
        $kategori_log="Komponen Biaya";
        $deskripsi_log="Hapus Komponen Biaya";
        $InputLog=addLog($Conn,$SessionIdAccess,$now,$kategori_log,$deskripsi_log);
        if($InputLog=="Success"){
            echo '<small class="text-success" id="NotifikasisHapusBerhasil">Success</small>';
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