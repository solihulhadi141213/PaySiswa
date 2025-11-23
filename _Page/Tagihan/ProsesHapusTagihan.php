<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi Session Akses
    if (empty($SessionIdAccess)) {
            echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir! Silahkan Login Ulang!',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }

    if(empty($_POST['id_fee_by_student'])){
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Tagihan Siswa Tidak Boleh Kosong',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }
    //Buat Variabel Data
    $id_fee_by_student  = validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka 'id_student' dan 'id_organization_class'
    $id_student             = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');
    $id_organization_class  = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_organization_class');

    //Proses Hapus Data
    $HapusData = mysqli_query($Conn, "DELETE FROM fee_by_student WHERE id_fee_by_student='$id_fee_by_student'") or die(mysqli_error($Conn));
    if($HapusData){
        echo json_encode([
            'status' => 'success',
            'message' => 'Hapus Tagihan Siswa Berhasil',
            'id_student' => $id_student,
            'id_organization_class' => $id_organization_class
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi Kesalahan Pada Saat Hapus Data Tagihan',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }
?>