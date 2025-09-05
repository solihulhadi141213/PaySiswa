<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    //Buka Pengaturan
    $api_payment_url=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','api_payment_url');
    $urll_call_back=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','urll_call_back');
    $api_key=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','api_key');
    $id_marchant=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','id_marchant');
    $client_key=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','client_key');
    $server_key=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','server_key');
    $snap_url=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','snap_url');
    $production=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','production');
    $aktif_payment_gateway=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','aktif_payment_gateway');
    if(empty($_POST['snap_token'])){
        echo '<div class="alert alert-warning border-1 alert-dismissible fade show" role="alert">';
        echo '  <small class="credit">';
        echo '      <code class="text-dark">';
        echo '          Snap Token Tidak Boleh Kosong!';
        echo '      </code>';
        echo '  </small>';
        echo '</div>';
    }else{
        $snap_token=$_POST['snap_token'];
        if(empty($_POST['gross_amount'])){
            $gross_amount="";
        }else{
            $gross_amount=$_POST['gross_amount'];
        }
        if(empty($_POST['first_name'])){
            $first_name="";
        }else{
            $first_name=$_POST['first_name'];
        }
        if(empty($_POST['last_name'])){
            $last_name="";
        }else{
            $last_name=$_POST['last_name'];
        }
        if(empty($_POST['email'])){
            $email="";
        }else{
            $email=$_POST['email'];
        }
        if(empty($_POST['phone'])){
            $phone="";
        }else{
            $phone=$_POST['phone'];
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
                    <small>Nama Depan</small>
                </div>
                <div class="col-md-8">
                    <small>
                        <code class="text text-grayish">
                            <?php echo "$first_name"; ?>
                        </code>
                    </small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <small>Nama Belakang</small>
                </div>
                <div class="col-md-8">
                    <small>
                        <code class="text text-grayish">
                            <?php echo "$last_name"; ?>
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
                    <button id="pay-button" class="btn btn-md btn-block btn-success btn-rounded">
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
                            window.location.href = 'index.php?page=Transaksi';
                            $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Lanjutkan');
                        },
                        onPending: function(result){
                            /* You may add your own implementation here */
                            window.location.href = 'index.php?page=Transaksi';
                            $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Lanjutkan');
                        },
                        onError: function(result){
                            /* You may add your own implementation here */
                            swal("Pembayaran Gagal", "Terjadi Kesalahan Pada Saat Melakukan Pembayaran", "error"); console.log(result);
                            $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Lanjutkan');
                        },
                        onClose: function(){
                            /* You may add your own implementation here */
                            swal("Pembayaran Batal", "Anda tidak jadi meneruskan proses pembayaran", "error");
                            $('#pay-button').html('<i class="bi bi-arrow-right-circle"></i> Lanjutkan');
                        }
                    })
                });
            </script>
        </body>
    </html>
<?php
    }
?>