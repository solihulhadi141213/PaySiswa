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
            'id_organization_class' => ''
        ]);
        exit;
    }
    //Tangkap id_fee_by_student
    if(empty($_POST['id_fee_by_student'])){
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
            'id_organization_class' => ''
        ]);
        exit;
    }

    //Buat variabel
    $id_fee_by_student=validateAndSanitizeInput($_POST['id_fee_by_student']);

    //Buka 'id_organization_class' dari tabel 'fee_by_student'
    $id_organization_class  = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_organization_class');

    //Proses hapus data
    $HapusKelas = mysqli_query($Conn, "DELETE FROM fee_by_student WHERE id_fee_by_student='$id_fee_by_student'") or die(mysqli_error($Conn));
    if ($HapusKelas) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Berhasil menghapus data dari database!',
            'id_organization_class' => $id_organization_class
        ]);
        exit;
    }else{

        //Jika menghapus gagal
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat menghapus data dari database!',
            'id_organization_class' => $id_organization_class
        ]);
        exit;
    }
?>