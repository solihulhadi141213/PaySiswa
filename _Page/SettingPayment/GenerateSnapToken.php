<?php
    // Koneksi Dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');

    // Kirim response dalam format JSON
    header('Content-Type: application/json');

    // Time Now Tmp
    $now = date('Y-m-d H:i:s');

    //Validasi Session
    if (empty($SessionIdAccess)) {
        $response['status'] = 'error';
        $response['message'] = 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang';
        echo json_encode($response);
        exit;
    }

    // Validasi 'kode_transaksi'
    if(empty($_POST['kode_transaksi'])){
        $response['status'] = 'error';
        $response['message'] = 'Kode Transaksi Tidak Boleh Kosong';
        echo json_encode($response);
        exit;
    }

    // Validasi 'gross_amount'
    if (empty($_POST['gross_amount'])) {
        $response['status'] = 'error';
        $response['message'] = 'Jumlah Tagihan Tidak Boleh Kosong';
        echo json_encode($response);
        exit;
    }

    // Validasi 'name'
    if (empty($_POST['name'])) {
        $response['status'] = 'error';
        $response['message'] = 'Nama Pelanggan Tidak Boleh Kosong';
        echo json_encode($response);
        exit;
    }

    // Validasi 'email'
    if (empty($_POST['email'])) {
        $response['status'] = 'error';
        $response['message'] = 'Email Pelanggan Tidak Boleh Kosong';
        echo json_encode($response);
        exit;
    }

    // Validasi 'phone'
    if (empty($_POST['phone'])) {
        $response['status'] = 'error';
        $response['message'] = 'Kontak Pelanggan Tidak Boleh Kosong';
        echo json_encode($response);
        exit;
    }
    // Mengambil data dari POST dan sanitasi
    $kode_transaksi = validateAndSanitizeInput($_POST['kode_transaksi']);
    $gross_amount   = validateAndSanitizeInput($_POST['gross_amount']);
    $name           = validateAndSanitizeInput($_POST['name']);
    $email          = validateAndSanitizeInput($_POST['email']);
    $phone          = validateAndSanitizeInput($_POST['phone']);

    //Clean Format Uang
    $gross_amount   = str_replace('.', '', $gross_amount);

    // Buka tabel 'setting_payment'
    $api_payment_url    = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'api_payment_url');
    $USER_KEY           = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'USER_KEY');
    $SECRET_KEY         = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'SECRET_KEY');

    //Panggil Fungsai 'GetXToken'
    $get_x_token        = GetXToken($api_payment_url,$USER_KEY,$SECRET_KEY);
    $response_array     = json_decode($get_x_token, true);
    if($response_array['code']!==200){
        $response['status'] = 'error';
        $response['message'] = 'Terjadi kesalahan pada saat membuat token '.$response_array['status'].'';
        echo json_encode($response);
        exit;
    }

    // ==============================
    // 1. Ambil x-token
    // ==============================
    $x_token = $response_array['metadata']['x-token'];

    // ==============================
    // 2. Buat RAW JSON persis seperti postman
    // ==============================
    $payload = json_encode([
        "kode_transaksi"    => $kode_transaksi,
        "gross_amount"      => $gross_amount,
        "name"              => $name,
        "email"             => $email,
        "phone"             => $phone
    ], JSON_UNESCAPED_SLASHES);

    // ==============================
    // 3. Inisialisasi CURL
    // ==============================
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $api_payment_url.'/_API/snap_token.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'x-token: '.$x_token,
            'Content-Type: text/plain'
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    // ==============================
    // 4. Eksekusi
    // ==============================
    $raw_response = curl_exec($curl);
    $curl_error   = curl_error($curl);
    $info         = curl_getinfo($curl);
    curl_close($curl);


    //Jika Terjadi Kesalahan
    if ($raw_response === false) {
        $response['status'] = 'error';
        $response['message'] = 'CURL Error: ' . curl_error($curl).'';
        echo json_encode($response);
        exit;
    }

    //Menampilkan Data
    $response_array = json_decode($raw_response, true);
    if(empty($response_array['code'])){
        $response['status'] = 'error';
        $response['message'] = $raw_response;
        echo json_encode($response);
        exit;
    }

    //jika response code tidak 200
    if($response_array['code']!==200){
        $response['status'] = 'error';
        $response['message'] = $response_array['status'];
        echo json_encode($response);
        exit;
    }

    //Jika Response Snpa Token Kosong
    if(empty($response_array['metadata']['snap-token'])){
        $response['status'] = 'error';
        $response['message'] = "Snap Token Kosong";
        echo json_encode($response);
        exit;
    }

    // Variabel metadata
    $token          = $response_array['metadata']['snap-token'];
    $order_id       = $response_array['metadata']['order_id'];
    $datetime       = $response_array['metadata']['datetime'];
    $server_key     = $response_array['metadata']['server_key'];
    $production     = $response_array['metadata']['production'];
    if(empty($token)){
        $token = "Token Tidak Ada";
    }

    $response['status']         = 'success';
    $response['message']        = 'Snap Token Berhasil Dibuat';
    $response['token']          = $token;
    $response['order_id']       = $order_id;
    $response['kode_transaksi'] = $kode_transaksi;
    $response['name']           = $name;
    $response['email']          = $email;
    $response['phone']          = $phone;
    $response['gross_amount']   = $gross_amount;
    $response['datetime']       = $datetime;
    $response['server_key']     = $server_key;
    $response['production']     = $production;
    echo json_encode($response);
?>
