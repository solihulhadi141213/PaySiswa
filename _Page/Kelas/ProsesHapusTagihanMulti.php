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

    //Validasi Data tidak boleh kosong
    if(empty($_POST['id_fee_by_student'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    Tiidak ada data yang dipilih! Silahkan Pilih data terlebih dulu.
                </small>
            </div>
        ';
        exit;
    }
    if(empty(count($_POST['id_fee_by_student']))){
        echo '
            <div class="alert alert-danger">
                <small>
                    Tiidak ada data yang dipilih! Silahkan Pilih data terlebih dulu.
                </small>
            </div>
        ';
        exit;
    }
    
    //Buat variabel
    $jumlah_data = count($_POST['id_fee_by_student']);
    
    //Buat Variabel
    $arry = $_POST['id_fee_by_student'];

    //Looping
    $jumlah_berhasil = 0;
    foreach ($arry as $id_fee_by_student) {
        //Hapus Data
        $HapusTagihan = mysqli_query($Conn, "DELETE FROM fee_by_student WHERE id_fee_by_student='$id_fee_by_student'") or die(mysqli_error($Conn));

        //Jika Berhasil
        if($HapusTagihan){
            $jumlah_berhasil = $jumlah_berhasil+1;
        }else{
            $jumlah_berhasil = $jumlah_berhasil+0;
        }
    }

    //Jika Semua Berhasil Dihapus
    if($jumlah_berhasil==$jumlah_data){
        echo '
            <div class="alert alert-success">
                <small>
                    Semua data yang dipilih <b id="NotifikasiHapusTagihanSiswaMultipleBerhasil">Berhasil</b> Dihapus.
                </small>
            </div>
        ';
    }else{
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat menghapus data!
                </small>
            </div>
        ';
    }
?>