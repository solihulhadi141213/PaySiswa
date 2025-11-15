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
            'message' => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Validasi 'id_fee_by_student'
    if (empty($_POST['id_fee_by_student'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Tagihan Siswa Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Validasi 'id_organization_class'
    if (empty($_POST['id_organization_class'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Kelas Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Validasi 'id_student'
    if (empty($_POST['id_student'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Siswa Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Buat Variabel
    $id_fee_by_student      = validateAndSanitizeInput($_POST['id_fee_by_student']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);

    //Tangkap nominal dan diskon
    if(empty($_POST['fee_nominal'])){
        $fee_nominal    = 0;
    }else{
        $fee_nominal    = $_POST['fee_nominal'];
        $fee_nominal    = str_replace('.', '', $fee_nominal);
    }
    if(empty($_POST['fee_discount'])){
        $fee_discount   = 0;
    }else{
        $fee_discount   = $_POST['fee_discount'];
        $fee_discount   = str_replace('.', '', $fee_discount);
    }

    // --- PREPARED STATEMENT UPDATE ---
    $Qry = $Conn->prepare("
        UPDATE fee_by_student 
        SET fee_nominal = ?, 
            fee_discount = ?
        WHERE id_fee_by_student = ?
    ");

    if (!$Qry) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Prepare statement gagal: ' . $Conn->error,
            'id_organization_class' => $id_organization_class,
            'id_student'            => $id_student
        ]);
        exit;
    }

    // Bind parameter
    // fee_nominal & fee_discount biasanya angka sebagai string → gunakan "s"
    // id_fee_by_student = integer → gunakan "i"
    $Qry->bind_param("ssi", $fee_nominal, $fee_discount, $id_fee_by_student);

    // Eksekusi
    if ($Qry->execute()) {
        echo json_encode([
            'status'                => 'success',
            'message'               => 'Update data ke database berhasil!',
            'id_organization_class' => $id_organization_class,
            'id_student'            => $id_student
        ]);
        exit;
    } else {
        echo json_encode([
            'status'                => 'error',
            'message'               => 'Terjadi kesalahan pada saat update data ke database! ' . $Qry->error,
            'id_organization_class' => $id_organization_class,
            'id_student'            => $id_student
        ]);
        exit;
    }

    // Tutup statement
    $Qry->close();
?>  