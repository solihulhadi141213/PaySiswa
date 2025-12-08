<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Validasi Snap 'token' tidak boleh kosong
    if(empty($_POST['token'])){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>Snap Token Tidak Boleh Kosong!</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    //Validasi 'kode_transaksi' tidak boleh kosong
    if(empty($_POST['kode_transaksi'])){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>Kode Transaksi Tidak Boleh Kosong!</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    //Validasi 'order_id' tidak boleh kosong
    if(empty($_POST['order_id'])){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>Order ID Tidak Boleh Kosong!</small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    // Buat Variabel Dan Sanitasi
    $snap_token     = validateAndSanitizeInput($_POST['token']);
    $kode_transaksi = validateAndSanitizeInput($_POST['kode_transaksi']);
    $order_id       = validateAndSanitizeInput($_POST['order_id']);

    //Buat Variabel yang tidak wajib
    $name           = validateAndSanitizeInput($_POST['name'] ?? '');
    $email          = validateAndSanitizeInput($_POST['email'] ?? '');
    $phone          = validateAndSanitizeInput($_POST['phone'] ?? '');
    $gross_amount   = validateAndSanitizeInput($_POST['gross_amount'] ?? 0);
    $datetime       = validateAndSanitizeInput($_POST['datetime'] ?? '');
    $server_key     = validateAndSanitizeInput($_POST['server_key'] ?? '');
    $production     = validateAndSanitizeInput($_POST['production'] ?? '');

    //format rupiah
    $gross_amount="" . number_format($gross_amount,0,',','.');

    //Buka Pengaturan
    $api_payment_url    = GetDetailData($Conn,'setting_payment','id_setting_payment ','1','api_payment_url');
    $USER_KEY           = GetDetailData($Conn,'setting_payment','id_setting_payment ','1','USER_KEY');
    $SECRET_KEY         = GetDetailData($Conn,'setting_payment','id_setting_payment ','1','SECRET_KEY');

    //Generate x-token
    $payload        = json_encode([ "USER_KEY"   => $USER_KEY, "SECRET_KEY" => $SECRET_KEY]);
    $url            = rtrim($api_payment_url, "/") . "/_API/get_token.php";
    $curl_request   = CurlPost($payload,$url);
    $response_array = json_decode($curl_request, true);
    
    //jika x-token gagal
    if($response_array['code']!==200){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>
                             Terjadi kesalahan pada saat generate x-token!<br>
                            Keterangan: '.$curl_request.'
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }
    $x_token=$response_array['metadata']['x-token'];

    //Panggil Curl list setting
    $list_setting=list_setting($x_token,$api_payment_url);
    $list_setting_array = json_decode($list_setting, true);

    if($list_setting_array['code']!==200){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>
                            Terjadi kesalahan pada saat menampilkan list setting!<br>
                            Keterangan: '.$list_setting_array.'
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    //Menampilkan Pengaturan
    $metadata=$list_setting_array['metadata'];
    if(empty(count($metadata))){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small>
                           Tidak ada profile pengaturan yang tersimpan! Silahkan buat profil pengaturan terlebih dulu.
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit;
    }

    //Pengaturan yang dipilih
    foreach ($metadata as $metadata_list) {
        if($metadata_list['status']=='active'){
            $client_key = $metadata_list['client_key'];
            $snap_url = $metadata_list['snap_url'];
        }
    }
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
        <script type="text/javascript" src="<?php echo "$snap_url";?>" data-client-key="<?php echo "$client_key";?>"></script>
        <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
    </head>
    <body>
        <div class="row mb-3">
            <div class="col-md-4">
                <small>Snap Token</small>
            </div>
            <div class="col-md-8">
                <small>
                    <code class="text text-grayish">
                        <?php echo "$snap_token"; ?>
                    </code>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <small>Nama Pelanggan</small>
            </div>
            <div class="col-md-8">
                <small>
                    <code class="text text-grayish">
                        <?php echo "$name"; ?>
                    </code>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <small>Email</small>
            </div>
            <div class="col-md-8">
                <small>
                    <code class="text text-grayish">
                        <?php echo "$email"; ?>
                    </code>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <small>Kontak</small>
            </div>
            <div class="col-md-8">
                <small>
                    <code class="text text-grayish">
                        <?php echo "$phone"; ?>
                    </code>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <small>Gross Amount</small>
            </div>
            <div class="col-md-8">
                <small>
                    <code class="text text-grayish">
                        <?php echo "$gross_amount"; ?>
                    </code>
                </small>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <button id="pay-button" class="btn btn-md btn-block btn-primary btn-rounded">
                    <i class="bi bi-arrow-right-circle"></i> Bayar Sekarang
                </button>
            </div>
        </div>
        
        <script type="text/javascript">
            // For example trigger on button clicked, or any time you need
            var payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function () {
                $('#pay-button').html('Loading..');
                // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                window.snap.pay('<?php echo $snap_token;?>', {
                    onSuccess: function(result){
                        /* You may add your own implementation here */
                        location.reload();
                        $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Bayar Sekarang');
                    },
                    onPending: function(result){
                        /* You may add your own implementation here */
                        location.reload();
                        $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Bayar Sekarang');
                    },
                    onError: function(result){
                        /* You may add your own implementation here */
                        swal("Pembayaran Gagal", "Terjadi Kesalahan Pada Saat Melakukan Pembayaran", "error"); console.log(result);
                        $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Bayar Sekarang');
                    },
                    onClose: function(){
                        /* You may add your own implementation here */
                        swal("Pembayaran Batal", "Anda tidak jadi meneruskan proses pembayaran", "error");
                        $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Bayar Sekarang');
                    }
                })
            });
        </script>
    </body>
</html>