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
    // Menangkap 'id_fee_by_student'
    if(empty($_POST['id_fee_by_student'])){
        echo '
            <div class="alert alert-danger">
                <small>Tidak ada data yang dipilih! Silahkan pilih terlebih dulu sebelum melanjutkan.</small>
            </div>
        ';
        exit;
    }

    if(empty(count($_POST['id_fee_by_student']))){
        echo '
            <div class="alert alert-danger">
                T<small>idak ada data yang dipilih! Silahkan pilih terlebih dulu sebelum melanjutkan.</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_fee_by_student = $_POST['id_fee_by_student'];

    //Tangkap fee_nominal
    if(empty($_POST['fee_nominal'])){
        $fee_nominal = 0;
    }else{
        $fee_nominal = $_POST['fee_nominal'];
    }

    //Tangkap fee_discount
    if(empty($_POST['fee_discount'])){
        $fee_discount = 0;
    }else{
        $fee_discount = $_POST['fee_discount'];
    }

    //Jumlah Data
    $jumlah_data = count($id_fee_by_student);

    //Looping
    $validasi_berhasil = 0;
    foreach ($id_fee_by_student as $id_fee_by_student_list) {
        $UpdateData = mysqli_query($Conn,"UPDATE fee_by_student SET 
            fee_nominal='$fee_nominal',
            fee_discount='$fee_discount'
        WHERE id_fee_by_student='$id_fee_by_student_list'") or die(mysqli_error($Conn)); 
        if($UpdateData){
            $validasi_berhasil = $validasi_berhasil + 1;
        }else{
            $validasi_berhasil = $validasi_berhasil + 0;
        }
    }

    //Validasi Apakah semua data berhasil di update
    if($validasi_berhasil==$jumlah_data){
        echo '
            <div class="alert alert-success">
                <small>Update data ke database <b id="NotifikasiEditTagihanSiswaMultipleBerhasil">Berhasil</b> dilakukan</small>
            </div>
        ';
    }else{
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat update data ke database</small>
            </div>
        ';
    }
?>