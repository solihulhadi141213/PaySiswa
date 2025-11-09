<?php
    //Set Header Sebagai JSON
    header('Content-Type: application/json');

    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        $response = [
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir! Silahkan Login Ulang!',
            'id_organization_class' => ''
        ];
        echo json_encode($response);
        exit;
    }

    //Validasi Data tidak boleh kosong
    if(empty($_POST['id_organization_class'])){
        $response = [
            'status' => 'error',
            'message' => 'ID Kelas (Rombel) Tidak Boleh Kosong!',
            'id_organization_class' => ''
        ];
        echo json_encode($response);
        exit;
    }
    if(empty($_POST['id_student'])){
        $response = [
            'status' => 'error',
            'message' => 'ID Siswa Tidak Boleh Kosong!',
            'id_organization_class' => ''
        ];
        echo json_encode($response);
        exit;
    }
    
    //Buat variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    
    //Hapus Data
    $HapusTagihan = mysqli_query($Conn, "DELETE FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'") or die(mysqli_error($Conn));

    //Jika Berhasiil
    if($HapusTagihan){
        $response = [
            'status' => 'success',
            'message' => 'Hapus Data Berhasiil',
            'id_organization_class' => $id_organization_class
        ];
        echo json_encode($response);
        exit;
    }else{
        $response = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada menghapus data',
            'id_organization_class' => $id_organization_class
        ];
        echo json_encode($response);
        exit;
    }
?>