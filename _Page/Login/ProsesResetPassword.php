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

    //Validasi token tidak boleh kosong
    if(empty($_POST['token'])){
        $response = [
            'status' => 'error',
            'message' => 'Token tidak boleh kosong!'
        ];
        echo json_encode($response);
        exit;
    }

    //Validasi password1 tidak boleh kosong
    if(empty($_POST['password1'])){
        $response = [
            'status' => 'error',
            'message' => 'Password tidak boleh kosong!'
        ];
        echo json_encode($response);
        exit;
    }

    //Validasi password2 tidak boleh kosong
    if(empty($_POST['password2'])){
        $response = [
            'status' => 'error',
            'message' => 'Password tidak Sama!'
        ];
        echo json_encode($response);
        exit;
    }

    //Buat Variabel
    $token      = validateAndSanitizeInput($_POST["token"]);
    $password1  = validateAndSanitizeInput($_POST["password1"]);
    $password2  = validateAndSanitizeInput($_POST["password2"]);

    //Validasi Password Harus Sama
    if($password1!==$password2){
        $response = [
            'status' => 'error',
            'message' => 'Password tidak Sama!'
        ];
        echo json_encode($response);
        exit;
    }

    // Validasi token
    $QueryReset = $Conn->prepare("SELECT id_access_reset, id_access FROM access_reset WHERE token  = ?");
    $QueryReset->bind_param("s", $token);
    $QueryReset->execute();
    $DataReset = $QueryReset->get_result()->fetch_assoc();

    if (!$DataReset) {
        $response = [
            'status' => 'error',
            'message' => 'Token yang anda masukan tidak valid'
        ];
        echo json_encode($response);
        exit;
    }
    
    if(empty($DataReset['id_access_reset'])){
        $response = [
            'status' => 'error',
            'message' => 'Token yang anda masukan tidak valid'
        ];
        echo json_encode($response);
        exit;
    }
    
    $id_access = $DataReset['id_access'];

    //Validasi id_access
    $stmt = $Conn->prepare("SELECT id_access, access_name FROM access WHERE id_access = ?");
    $stmt->bind_param("i", $id_access);
    $stmt->execute();
    $DataAkses = $stmt->get_result()->fetch_assoc();

    if(empty($DataAkses['id_access'])){
        $response = [
            'status' => 'error',
            'message' => 'Token yang anda masukan tidak terdaftar'
        ];
        echo json_encode($response);
        exit;
    }

    //Validasi Karakter password
    if(strlen($password1) < 6 || strlen($password1) > 20 || !preg_match("/^[a-zA-Z0-9]*$/", $password1)){
        $response = [
            'status' => 'error',
            'message' => 'Password terdiri dari 6-20 karakter huruf/angka!'
        ];
        echo json_encode($response);
        exit;
    }

    //Buat $id_access
    $id_access      = $DataAkses['id_access'];
    $access_name    = $DataAkses['access_name'];

    //Hasing Password
    $password = password_hash($password1, PASSWORD_DEFAULT);

    //Update Password ke Tabel 'access'
    $UpdateAkses = mysqli_query($Conn,"UPDATE access SET 
        access_password='$password'
    WHERE id_access='$id_access'") or die(mysqli_error($Conn)); 
    if($UpdateAkses){

        //Jika Berhasil Hapus token
        $HapusToken = mysqli_query($Conn, "DELETE FROM access_reset WHERE token='$token'") or die(mysqli_error($Conn));
        if($HapusToken){
            $response = [
                'status' => 'success',
                'message' => 'Password berhasil di update!'
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada saat hapus token lama!'
            ]; 
        }
       
    }else{
        $response = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat update password!'
        ];
    }
    echo json_encode($response);

?>