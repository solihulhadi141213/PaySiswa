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
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }
    //Tangkap id_payment
    if(empty($_POST['id_payment'])){
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Pembayaran Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }

    //Buat variabel
    $id_payment=validateAndSanitizeInput($_POST['id_payment']);

    //Buka detail Pembayaran
    $id_fee_by_student      = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'id_fee_by_student');
    $id_student             = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'id_student');
    $id_fee_component       = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'id_fee_component');
    $id_organization_class  = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'id_organization_class');
    
    //Proses hapus data
    $ProsesHapus = mysqli_query($Conn, "DELETE FROM payment WHERE id_payment='$id_payment'") or die(mysqli_error($Conn));
    if ($ProsesHapus) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Hapus data dari database berhasil!',
            'id_organization_class' => $id_organization_class,
            'id_student' => $id_student,
            'id_fee_by_student' => $id_fee_by_student
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat proses hapus data dari database',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }
?>