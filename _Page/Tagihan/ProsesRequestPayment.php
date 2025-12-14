<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d');

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

    //Menangkap 'id_fee_by_student'
    if(empty($_POST['id_fee_by_student'])){
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Tagihan Tidak Boleh Kosong!',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }

    //Menangkap 'expired_date'
    if(empty($_POST['expired_date'])){
        echo json_encode([
            'status' => 'error',
            'message' => 'Tanggal Expired Tidak Boleh Kosong!',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }

    // Membuat Variabel Dan Sanitasi
    $id_fee_by_student  = validateAndSanitizeInput($_POST['id_fee_by_student']);
    $expired_date       = validateAndSanitizeInput($_POST['expired_date']);

    //Validasi expired date
    if($expired_date<=$now){
        echo json_encode([
            'status' => 'error',
            'message' => 'Tanggal Expired Tidak Boleh Kurang Dari Sama Dengan Hari Sekarang!',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }

    // Buka Jumlah Tagihan 'fee_by_student'
    $Qry = $Conn->prepare("SELECT * FROM fee_by_student WHERE id_fee_by_student = ?");
    $Qry->bind_param("i", $id_fee_by_student);
    if (!$Qry->execute()) {
        $error=$Conn->error;

        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi Kesalahan Pada Saat Membuka Data Tagihan : '.$error.'',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $id_organization_class  = $Data['id_organization_class'];
    $id_student             = $Data['id_student'];
    $id_fee_component       = $Data['id_fee_component'];
    $fee_nominal            = $Data['fee_nominal'] ?? 0;
    $fee_discount           = $Data['fee_discount'] ?? 0;
    $jumlah_tagihan         = $fee_nominal-$fee_discount;

    //Validasi Duplikasi Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment_request FROM payment_request WHERE id_fee_by_student='$id_fee_by_student'"));
    if(!empty($jml_data)){
        echo json_encode([
            'status' => 'error',
            'message' => 'Permintaan Pembayaran Untuk Tagihan Tersebut Sudah Ada',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }

    //Membuat kode transaksi
    $kode_transaksi = generateRandomString(32);

    //Simpan Data Ke Database
    $stmt = $Conn->prepare("INSERT INTO payment_request (kode_transaksi, id_fee_by_student, id_student, id_organization_class, id_fee_component, request_datetime, request_expired)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi Kesalahan Pada Saat Menyimpan Data Permintaan Pembayaran (Error : '.$Conn->error.')',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }

    $stmt->bind_param("siiiiss", 
        $kode_transaksi, 
        $id_fee_by_student, 
        $id_student,
        $id_organization_class, 
        $id_fee_component, 
        $now, 
        $expired_date
    );

    if (!$stmt->execute()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi Kesalahan Pada Saat Menyimpan Data Permintaan Pembayaran (Error : '.$stmt->error.')',
            'id_student' => '',
            'id_organization_class' => ''
        ]);
        exit;
    }

    $stmt->close();

    echo json_encode([
        'status' => 'success',
        'message' => 'Menyimpan data permintaan pembayaran berhasil',
        'id_student' => $id_student,
        'id_organization_class' => $id_organization_class
    ]);
    exit;
    
?>