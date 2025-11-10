<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/SettingEmail.php";

    // Set header agar selalu mengembalikan JSON dan keamanan
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');

    // Tetapkan zona waktu
    date_default_timezone_set('Asia/Jakarta');

    // Timestamp sekarang
    $timestamp_now = date('Y-m-d H:i:s');

    //Validasi email tidak boleh kosong
    if(empty($_POST['email'])){
        $response = [
            'status' => 'error',
            'message' => 'Email tidak boleh kosong!'
        ];
        echo json_encode($response);
        exit;
    }

    //Validasi captcha tidak boleh kosong
    if(empty($_POST['captcha'])){
        $response = [
            'status' => 'error',
            'message' => 'Captcha tidak boleh kosong!'
        ];
        echo json_encode($response);
        exit;
    }

    //Buat Variabel
    $email      = validateAndSanitizeInput($_POST["email"]);
    $captcha    = validateAndSanitizeInput($_POST["captcha"]);

    // Validasi Captcha
    $QryCaptcha = $Conn->prepare("SELECT * FROM captcha WHERE captcha  = ?");
    $QryCaptcha->bind_param("s", $captcha);
    $QryCaptcha->execute();
    $DataCaptcha = $QryCaptcha->get_result()->fetch_assoc();

    if (!$DataCaptcha) {
        $response = [
            'status' => 'error',
            'message' => 'Captcha yang anda masukan tidak valid'
        ];
        echo json_encode($response);
        exit;
    }
    
    if($DataCaptcha['datetime_expired'] < $timestamp_now) {
        $response = [
            'status' => 'error',
            'message' => 'Captcha yang anda masukan tidak valid'
        ];
        echo json_encode($response);
        exit;
    }

    //Validasi Email
    $stmt = $Conn->prepare("SELECT id_access, access_name FROM access  WHERE access_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $DataAkses = $stmt->get_result()->fetch_assoc();

    if(empty($DataAkses['id_access'])){
        $response = [
            'status' => 'error',
            'message' => 'Email yang anda masukan tidak terdaftar'
        ];
        echo json_encode($response);
        exit;
    }

    //Buat $id_access
    $id_access      = $DataAkses['id_access'];
    $access_name    = $DataAkses['access_name'];

    // Buat token
    $token = GenerateToken(36);

    //Simpan token ke tabel 'access_reset' 
    $insertTokenStmt = $Conn->prepare("INSERT INTO access_reset (id_access, datetime_creat, token) VALUES (?, ?, ?)");
    $insertTokenStmt->bind_param("iss", $id_access, $timestamp_now, $token);

    if ($insertTokenStmt->execute()) {
        
        //Persiapkan Pesan Email
        $tautan_reset_password = "$app_base_url/Login.php?Page=ResetPassword&token=$token";
        $Pesan ='Berikut ini kami kirimkan tautan untuk reset password anda.<br> URL : '.$tautan_reset_password.'<br> Jika anda tidak merasa meminta tautan tersebut, silahkan abaikan email ini!';
        $Subjek = "Tautan Reset Password";
        
        //Proses Kirim Pesan
        SendEmail($access_name,$email,$Subjek,$Pesan,$email_gateway,$password_gateway,$url_provider,$nama_pengirim,$port_gateway,$url_service);

        //Abaikan Response Langsung Buat JSON Success
        $response = [
            'status' => 'success',
            'message' => 'Tautan Berhasil Dikirim'
        ];
        echo json_encode($response);
        exit;
    }else{
        //Jika Gagal Membuat Token
        $response = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat membuat token reset password'
        ];
        echo json_encode($response);
        exit;
    }

?>