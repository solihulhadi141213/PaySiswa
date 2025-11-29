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

    if(empty($_POST['fee_nominal'])){
        echo json_encode([
            'status' => 'error',
            'message' => 'Nominal Tagihan Tidak Boleh Kosong',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }
    
    //Buat Variabel
    $id_fee_by_student  = validateAndSanitizeInput($_POST['id_fee_by_student']);
    $fee_nominal        = validateAndSanitizeInput($_POST['fee_nominal']);
    if(empty($_POST['fee_discount'])){
        $fee_discount   = 0;
    }else{
        $fee_discount   = validateAndSanitizeInput($_POST['fee_discount']);
    }

    //Format Uang Jadi Angka
    $fee_nominal        = str_replace('.', '', $fee_nominal);
    $fee_discount       = str_replace('.', '', $fee_discount);

    //Buka 'id_student' dan 'id_organization_class'
    $id_student             = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');
    $id_organization_class  = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_organization_class');
    
    //Update Data
    $UpdateEntitias = mysqli_query($Conn,"UPDATE fee_by_student SET 
        fee_nominal='$fee_nominal',
        fee_discount='$fee_discount'
    WHERE id_fee_by_student='$id_fee_by_student'") or die(mysqli_error($Conn)); 
    if($UpdateEntitias){
        # Jika Proses Berhasil
        echo json_encode([
            'status' => 'success',
            'message' => 'Update Tagihan Siswa Berhasil',
            'id_student' => $id_student,
            'id_organization_class' => $id_organization_class
        ]);
        exit;
    }else{
        # Jika Proses Berhasil
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat update Tagihan Siswa',
            'id_student' => $id_student,
            'id_organization_class' => $id_organization_class
        ]);
        exit;
    }
?>