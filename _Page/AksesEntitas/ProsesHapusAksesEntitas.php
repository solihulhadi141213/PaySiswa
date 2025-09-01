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

    //Validasi id_access_group
    if(empty($_POST['id_access_group'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Entitas/Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_access_group=validateAndSanitizeInput($_POST['id_access_group']);

    //Hapus Data
    $HapusEntitias = mysqli_query($Conn, "DELETE FROM access_group WHERE id_access_group='$id_access_group'") or die(mysqli_error($Conn));
    if($HapusEntitias){

        //Simpan Log
        $kategori_log="Entitas Akses";
        $deskripsi_log="Hapus Entitas Akses";
        $InputLog=addLog($Conn,$SessionIdAccess,$now,$kategori_log,$deskripsi_log);
        if($InputLog=="Success"){
            echo '<small class="text-success" id="NotifikasiHapusAksesEntitasBerhasil">Success</small>';
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
                <small>Terjadi kesalahan pada saat menghapus entitas/group akses!</small>
            </div>
        ';
    }
?>