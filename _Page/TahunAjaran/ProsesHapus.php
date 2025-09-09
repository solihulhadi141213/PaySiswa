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

    //Validasi id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Periode Akademik Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Hapus Data
    $HapusTahunAkademik = mysqli_query($Conn, "DELETE FROM academic_period WHERE id_academic_period='$id_academic_period'") or die(mysqli_error($Conn));
    if($HapusTahunAkademik){

        //Simpan Log
        $kategori_log="Tahun Akademik";
        $deskripsi_log="Hapus Tahun Akademik Berhasil";
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