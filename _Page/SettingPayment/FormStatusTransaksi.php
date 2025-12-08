<?php
    //Koneksi
    //Koneksi dan session
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
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

    //Validasi order_id
    if(empty($_POST['order_id'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    Order ID Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Variabel Order ID
    $order_id = validateAndSanitizeInput($_POST['order_id']);
    
    //Buka pengaturan payment gateway
    $api_payment_url    = GetDetailData($Conn,'setting_payment','id_setting_payment ','1','api_payment_url');
    $USER_KEY           = GetDetailData($Conn,'setting_payment','id_setting_payment ','1','USER_KEY');
    $SECRET_KEY         = GetDetailData($Conn,'setting_payment','id_setting_payment ','1','SECRET_KEY');

    //Generate x-token
    $payload        = json_encode([ "USER_KEY"   => $USER_KEY, "SECRET_KEY" => $SECRET_KEY]);
    $url            =rtrim($api_payment_url, "/") . "/_API/get_token.php";
    $curl_request   =CurlPost($payload,$url);
    $response_array = json_decode($curl_request, true);
    
    //jika x-token gagal
    if($response_array['code']!==200){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small>
                        Terjadi kesalahan pada saat generate x-token!<br>
                        Keterangan: '.$curl_request.'
                    </small>
                </td>
            </tr>
        ';
        exit;
    }
    $x_token=$response_array['metadata']['x-token'];

    // Persiapkan data request
    $payload = [
        "order_id"      => $order_id
    ];

    $url = rtrim($api_payment_url, "/") . "/_API/transaction_status.php";

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
                    Terjadi kesalahan pada saat meminta data dari server!<br>
                    HTTP Code: '.$http_code.'<br>
                    Keterangan: '.($response_array['status'] ?? 'Tidak ada keterangan').'<br>
                    Response: <pre>'.htmlspecialchars(json_encode($response_array, JSON_PRETTY_PRINT)).'</pre>
                </small>
            </div>
        ';
        exit;
    }

    $data_response = $response_array['response'];
    if(empty($data_response)){
        echo '
            <div class="alert alert-danger">
                <small>
                   Tidak Ada Response Dari Midtrans
                </small>
            </div>
        ';
        exit;
    }

    if(empty($response_array['response']['status_code'])){
        echo '
            <div class="alert alert-danger">
                <small>
                   '.$response_array['response'].'
                </small>
            </div>
        ';
        exit;
    }

    if($response_array['response']['status_code']=="404"){
        $isi = json_encode($response_array['response']);
        $pesan = json_encode($response_array['response']['status_message']);
         echo '
            <div class="alert alert-danger">
                <small>
                   '.$pesan.'
                </small>
            </div>
        ';
        exit;
    }

    $status_code            = $response_array['response']['status_code'];
    $transaction_id         = $response_array['response']['transaction_id'];
    $gross_amount           = $response_array['response']['gross_amount'];
    $currency               = $response_array['response']['currency'];
    $order_id               = $response_array['response']['order_id'];
    $payment_type           = $response_array['response']['payment_type'];
    $signature_key          = $response_array['response']['signature_key'];
    $transaction_status     = $response_array['response']['transaction_status'];
    $fraud_status           = $response_array['response']['fraud_status'];
    $status_message         = $response_array['response']['status_message'];
    $merchant_id            = $response_array['response']['merchant_id'];

    $gross_amount           = "Rp " . number_format($gross_amount,0,',','.');

    //Tampilkan Data
    echo '
        <div class="row mb-2">
            <div class="col-4"><small>Status Code</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$status_code.'</small></small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>ID Transaction</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$transaction_id.'</small></small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Gross Amount</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$gross_amount.'</small></small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Currency</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$currency.'</small></small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Payment Type</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$payment_type.'</small></small></div>
        </div>
      
        <div class="row mb-2">
            <div class="col-4"><small>Transaction Status</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$transaction_status.'</small></small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Status Message</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$status_message.'</small></small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Marchant ID</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small><small>'.$merchant_id.'</small></small></div>
        </div>
        <div class="row mb-2">
            <div class="col-4"><small>Signature Key</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><textarea readonly class="form-control">'.$signature_key.'</textarea></div>
        </div>
    ';
?>