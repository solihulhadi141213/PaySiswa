<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Validasi 'id_transaction' tidak boleh kosong
    if(empty($_POST['id_transaction'])){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>ID Transaksi Tidak Boleh Kosong!</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }
    $id_transaction = validateAndSanitizeInput($_POST['id_transaction']);

    // Buka 'setting_payment'
    $api_payment_url    = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'api_payment_url');
    $USER_KEY           = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'USER_KEY');
    $SECRET_KEY         = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'SECRET_KEY');

    //Panggil Fungsai 'GetXToken'
    $get_x_token        = GetXToken($api_payment_url,$USER_KEY,$SECRET_KEY);
    $response_array     = json_decode($get_x_token, true);
    if($response_array['code']!==200){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>Terjadi kesalahan pada saat membuat token '.$response_array['status'].'</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    // Buat Variabel x-token
    $x_token = $response_array['metadata']['x-token'];

    // Membuat payload
    $payload = json_encode([
        "id_transaction"    => $id_transaction
    ], JSON_UNESCAPED_SLASHES);

    // Mulai CURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $api_payment_url.'/_API/transaction_detail.php',
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


    //Eksekusi CURL
    $raw_response = curl_exec($curl);
    $curl_error   = curl_error($curl);
    $info         = curl_getinfo($curl);
    curl_close($curl);

    //Jika Terjadi Kesalahan
    if ($raw_response === false) {
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>CURL Error: ' . curl_error($curl).'</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    // Buat Response Dalam Bentuk Arry
    $response_array = json_decode($raw_response, true);

    // Jika Tida ada Response Code
    if(empty($response_array['code'])){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>'.$raw_response.'</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    //jika response code tidak 200
    if($response_array['code']!==200){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>'.$response_array['status'].'</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    // Buka Metadata
    $id_account             = $response_array['metadata']['id_account'];
    $id_setting_payment     = $response_array['metadata']['id_setting_payment'];
    $kode_transaksi         = $response_array['metadata']['kode_transaksi'];
    $order_id               = $response_array['metadata']['order_id'];
    $datetime               = $response_array['metadata']['datetime'];
    $ServerKey              = $response_array['metadata']['ServerKey'];
    $Production             = $response_array['metadata']['Production'];
    $gross_amount           = $response_array['metadata']['gross_amount'];
    $name                   = $response_array['metadata']['name'];
    $email                  = $response_array['metadata']['email'];
    $phone                  = $response_array['metadata']['phone'];
    $snapToken              = $response_array['metadata']['snapToken'];

    //Payment log
    $payment_log            = $response_array['metadata']['payment_log'];

    //Tampilkan
    echo '
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4"><small>ID Transaction</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$id_transaction.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>ID Account</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$id_account.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>ID Setting</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$id_setting_payment.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Kode Transaksi</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$kode_transaksi.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Order ID</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$order_id.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Datetime</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$datetime.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Server Key</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$ServerKey.'</small>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4"><small>Production</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$Production.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Gross Amount</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$gross_amount.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Nama</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$name.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Email</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$email.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Phone</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$phone.'</small>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Snap Token</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7">
                        <small class="text text-grayish">
                            <small>'.$snapToken.'</small>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    ';
?>
<div class="row mt-3">
    <div class="col-12">
        <div class="table table-responsive">
            <table class="table table-striped table-hover border-1 border-top">
                <thead>
                    <tr>
                        <th><small><b>No</b></small></th>
                        <th><small><b>Datetime</b></small></th>
                        <th><small><b>Status Code</b></small></th>
                        <th><small><b>Payment Type</b></small></th>
                        <th><small><b>Amount</b></small></th>
                        <th><small><b>Fraud</b></small></th>
                        <th><small><b>Status</b></small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(empty(count($payment_log))){
                            echo '
                                <tr>
                                    <td class="text-center" colspan="7">
                                        <small>Tidak Ada Riwayat Pembayaran</small>
                                    </td>
                                </tr>
                            ';
                        }else{
                            $no = 1;
                            foreach ($payment_log as $payment_log_list) {
                                # code...
                                echo '
                                    <tr>
                                        <td align="center"><small>'.$no.'</small></td>
                                        <td><small>'.$payment_log_list['transaction_time'].'</small></td>
                                        <td><small>'.$payment_log_list['status_code'].'</small></td>
                                        <td><small>'.$payment_log_list['payment_type'].'</small></td>
                                        <td><small>'.$payment_log_list['gross_amount'].'</small></td>
                                        <td><small>'.$payment_log_list['fraud_status'].'</small></td>
                                        <td><small>'.$payment_log_list['transaction_status'].'</small></td>
                                    </tr>
                                ';
                                $no++;
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>