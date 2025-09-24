<?php
    //Connection File
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');

    //Validasi Session tidak boleh kosong
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }

    //Validasi Data wajib
    if(empty($_POST['env_name'])){
        echo '
            <div class="alert alert-danger">
                <small>Nama environment tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }

    if(empty($_POST['url_status'])){
        echo '
            <div class="alert alert-danger">
                <small>URl untuk Cek Status Transaksi tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }

    if(empty($_POST['id_marchant'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Marchant tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['client_key'])){
        echo '
            <div class="alert alert-danger">
                <small>Clinet KEY tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['server_key'])){
        echo '
            <div class="alert alert-danger">
                <small>Server KEY tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['snap_url'])){
        echo '
            <div class="alert alert-danger">
                <small>Snap URL tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['production'])){
        echo '
            <div class="alert alert-danger">
                <small>Keterangan Environment tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['status'])){
        echo '
            <div class="alert alert-danger">
                <small>Status Profil tidak boleh kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $env_name=validateAndSanitizeInput($_POST['env_name']);
    $url_status=validateAndSanitizeInput($_POST['url_status']);
    $id_marchant=validateAndSanitizeInput($_POST['id_marchant']);
    $client_key=validateAndSanitizeInput($_POST['client_key']);
    $server_key=validateAndSanitizeInput($_POST['server_key']);
    $snap_url=validateAndSanitizeInput($_POST['snap_url']);
    $production=validateAndSanitizeInput($_POST['production']);
    $status_profil=validateAndSanitizeInput($_POST['status']);
    if(empty($_POST['urll_call_back'])){
       $urll_call_back="";
    }else{
        $urll_call_back=validateAndSanitizeInput($_POST['urll_call_back']);
    }

    //Buka pengaturan payment gateway
    $id_setting_payment = 1;
    try {
        // Gunakan prepared statement
        $Qry = $Conn->prepare("SELECT api_payment_url, USER_KEY, SECRET_KEY 
                            FROM setting_payment 
                            WHERE id_setting_payment = ?");
        if (!$Qry) {
            throw new Exception("Gagal mempersiapkan query: " . $Conn->error);
        }

        $Qry->bind_param("i", $id_setting_payment);

        if (!$Qry->execute()) {
            throw new Exception("Gagal mengeksekusi query: " . $Qry->error);
        }

        $Result = $Qry->get_result();
        if (!$Result) {
            throw new Exception("Gagal mengambil hasil query: " . $Qry->error);
        }

        $Data = $Result->fetch_assoc() ?? [];

        // Buat Variabel dengan fallback default "-"
        $api_payment_url = $Data['api_payment_url'] ?? "-";
        $USER_KEY        = $Data['USER_KEY'] ?? "-";
        $SECRET_KEY      = $Data['SECRET_KEY'] ?? "-";

        $Qry->close();

    } catch (Exception $e) {
        echo '
            <div class="alert alert-danger">
                <small> Keterangan: ' . htmlspecialchars($e->getMessage()) . '</small>
            </div>
        ';
        exit;
    }

    //Generate x-token
    $payload        = json_encode([ "USER_KEY"   => $USER_KEY, "SECRET_KEY" => $SECRET_KEY]);
    $url            = rtrim($api_payment_url, "/") . "/_API/get_token.php";
    $curl_request   = CurlPost($payload,$url);
    $response_array = json_decode($curl_request, true);
    
    //jika x-token gagal
    if($response_array['code']!==200){
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat generate x-token!<br>
                    Keterangan: '.$curl_request.'
                </small>
            </div>
        ';
        exit;
    }
    $x_token=$response_array['metadata']['x-token'];

    // Persiapkan data request
    $payload = [
        "env_name"      => $env_name,
        "urll_call_back"=> $urll_call_back,
        "url_status"    => $url_status,
        "id_marchant"   => $id_marchant,
        "client_key"    => $client_key,
        "server_key"    => $server_key,
        "snap_url"      => $snap_url,
        "production"    => $production,
        "status"        => $status_profil
    ];

    $url = rtrim($api_payment_url, "/") . "/_API/creat_setting.php";

    // Inisialisasi cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => "POST",
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            "x-token: ".$x_token,
            "Content-Type: application/json"
        ],
        CURLOPT_SSL_VERIFYHOST => 0,  // ⚠️ Disable SSL check (testing only)
        CURLOPT_SSL_VERIFYPEER => 0   // ⚠️ Disable SSL check (testing only)
    ]);

    $response = curl_exec($curl);

    // Cek error cURL
    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        echo '
            <div class="alert alert-danger">
                <small>
                    Gagal menghubungi server API.<br>
                    Error: '.$error.'
                </small>
            </div>
        ';
        exit;
    }

    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Decode response JSON
    $response_array = json_decode($response, true);

    // Debug jika response tidak bisa di-decode
    if ($response_array === null) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Response tidak valid dari server API.<br>
                    HTTP Code: '.$http_code.'<br>
                    Response Mentah: <pre>'.htmlspecialchars($response).'</pre>
                    Payload Terkirim: <pre>'.htmlspecialchars(json_encode($payload, JSON_PRETTY_PRINT)).'</pre>
                </small>
            </div>
        ';
        exit;
    }

    // Cek apakah response sukses
    if (!isset($response_array['code']) || $response_array['code'] !== 200) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat menyimpan pengaturan!<br>
                    HTTP Code: '.$http_code.'<br>
                    Keterangan: '.($response_array['status'] ?? 'Tidak ada keterangan').'<br>
                    Response: <pre>'.htmlspecialchars(json_encode($response_array, JSON_PRETTY_PRINT)).'</pre>
                </small>
            </div>
        ';
        exit;
    }

    // Jika berhasil
    echo '
        <div class="alert alert-success">
            <small id="NotifikasiTambahProfilPaymentGatewayBerhasil">Success</small>
        </div>
    ';
   
?>