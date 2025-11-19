<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    //Validasi Akses
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
    
    //Validasi 'id_fee_by_student'
    if (empty($_POST['id_fee_by_student'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Tagihan Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }
    
    //Validasi 'payment_datetime'
    if (empty($_POST['payment_datetime'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Tanggal Pembayaran Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }
    
    //Validasi 'payment_time'
    if (empty($_POST['payment_time'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Jam Pembayaran Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }

    //Validasi 'payment_method'
    if (empty($_POST['payment_method'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Metode Pembayaran Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }

    //Validasi 'payment_nominal'
    if (empty($_POST['payment_method'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Nomnal Pembayaran Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => '',
            'id_fee_by_student' => ''
        ]);
        exit;
    }

    //Buat Variabel
    $id_fee_by_student      = validateAndSanitizeInput($_POST['id_fee_by_student']);
    $payment_datetime       = validateAndSanitizeInput($_POST['payment_datetime']);
    $payment_time           = validateAndSanitizeInput($_POST['payment_time']);
    $payment_method         = validateAndSanitizeInput($_POST['payment_method']);
    $payment_nominal        = validateAndSanitizeInput($_POST['payment_nominal']);

    //Buka 'fee_by_student'
    $id_organization_class  = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_organization_class');
    $id_fee_component       = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_fee_component');
    $id_student             = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');
    
    //Format Variabel 'payment_nominal'
    $payment_nominal= str_replace('.', '', $payment_nominal);

    //Generate uuid
    $id_payment=generateRandomString(36);

    //Bentuk $payment_datetime
    $payment_datetime = "$payment_datetime $payment_time";
    
    // Insert Data Menggunakan Prepared Statement
    $stmt = $Conn->prepare("INSERT INTO payment (
    id_payment, 
    id_fee_by_student, 
    id_student, 
    id_organization_class, 
    id_fee_component, 
    payment_datetime, 
    payment_nominal, 
    payment_method
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiisss", $id_payment, $id_fee_by_student, $id_student, $id_organization_class, $id_fee_component, $payment_datetime, $payment_nominal, $payment_method);
    $Input = $stmt->execute();
    $stmt->close();

    if($Input){
        $kategori_log="Pembayaran";
        $deskripsi_log="Input Pembayaran Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo json_encode([
                'status' => 'success',
                'message' => 'Insert pembayaran berhasil!',
                'id_organization_class' => $id_organization_class,
                'id_student' => $id_student,
                'id_fee_by_student' => $id_fee_by_student
            ]);
            exit;
        }else{
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjad kesalahan pada saat menympan LOG!',
                'id_organization_class' => $id_organization_class,
                'id_student' => $id_student,
                'id_fee_by_student' => $id_fee_by_student
            ]);
            exit;
        }
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjad kesalahan pada saat nsert pembayaran!',
            'id_organization_class' => $id_organization_class,
            'id_student' => $id_student,
            'id_fee_by_student' => $id_fee_by_student
        ]);
        exit;
    }
?>