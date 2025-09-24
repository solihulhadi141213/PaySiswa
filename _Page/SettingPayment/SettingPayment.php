<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
       
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-plug"></i> Setting Payment Gateway</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Payment Gateway</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <small class="mobile-text">
                        Berikut ini adalah halaman pengaturan payment gateway menggunakan provider <a href="https://midtrans.com/"><b>Midtrans.com</b></a>. 
                        Pada halaman ini anda bisa mengatur parameter payment gateway yang dibutuhkan. 
                        Periksa kembali pengaturan yang anda gunakan agar aplikasi berjalan dengan baik. 
                         Baca panduan integrasi dengan provider pada <a href="https://docs.midtrans.com/docs/snap-snap-integration-guide"><b>Link Berikut</b></a> ini. 
                         Koneksi dengan midtrans sepenuhnya menggunakan <a href="https://github.com/solihulhadi141213/SnapProxy">SnapProxy</a>
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingProxyConnection">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProxyConnection" aria-expanded="true" aria-controls="collapseProxyConnection">
                                        <b>A. Koneksi Payment Proxy</b>
                                    </button>
                                </h2>
                                <div id="collapseProxyConnection" class="accordion-collapse collapse show" aria-labelledby="headingProxyConnection" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body mb-4 mt-3" id="ConnectionSetting">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <b>B. Profil Pengaturan</b>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body mb-4">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahProfilPaymentGateway">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-12">
                                                <div class="table table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th><b>No</b></th>
                                                                <th><b>Nama Profil</b></th>
                                                                <th><b>ID Marchant</b></th>
                                                                <th><b>Client Key</b></th>
                                                                <th><b>Server Key</b></th>
                                                                <th><b>Environment</b></th>
                                                                <th><b>Status</b></th>
                                                                <th><b>Option</b></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="TabelProfileSetting">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <small>Tidak Ada Profile Pengaturan</small>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <b>C. Uji Coba Snap Button </b>
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <form action="javascript:void(0);" id="ProsesGenerateSnapButton">
                                            <div class="row mb-3 mt-4">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="kode_transaksi">Kode Transaksi</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" required>
                                                        <span class="input-group-text" id="inputGroupPrepend">
                                                            <a href="javascript:void(0);" id="GenerateKodeTransaksi">
                                                                <code class="text text-success">Generate</code>
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            Kode transaksi adalah kode unik yang merepresentasikan transaksi yang sedang berlangsung. (Maksimal 36 karakter)
                                                        </code>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3 mt-4">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="order_id">Order ID (Kode Pembayaran)</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" name="order_id" id="order_id" class="form-control" required>
                                                        <span class="input-group-text" id="inputGroupPrepend">
                                                            <a href="javascript:void(0);" id="GenerateOrderId">
                                                                <code class="text text-success">Generate</code>
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            ID transaksi unik yang hanya dapat digunakan satu kali pada saat meminta kode token snap. 
                                                            Karakter yang diperbolehkan adalah Alfanumerik, tanda hubung(-), garis bawah(_), tanda gelombang (~), dan titik (.) String, maksimal 50.
                                                        </code>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="gross_amount">Jumlah Tagihan (Rp)</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" name="gross_amount" id="gross_amount" class="form-control" required>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            Diisi dengan nilai uang yang akan dibayarkan.
                                                        </code>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="first_name">Nama Pelanggan</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            Nama Depan
                                                        </code>
                                                    </small>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            Nama Belakang
                                                        </code>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="email">Email Pelanggan</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="email" name="email" id="email" class="form-control" required>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            Provider akan mengirimkan email notifikasi dari status pembayaran yang telah dilakukan.
                                                        </code>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="phone">Kontak/HP Pelanggan</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" name="phone" id="phone" class="form-control" required>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            Informasi nomor kontak pelanggan.
                                                        </code>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="snap_token">Snap Token</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" name="snap_token" id="snap_token" class="form-control" required>
                                                        <span class="input-group-text" id="inputGroupPrepend">
                                                            <a href="javascript:void(0);" id="GenerateSnapToken">
                                                                <code class="text text-success">Generate</code>
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <small>
                                                        <code class="text text-grayish">
                                                            Token unik yang dihasilkan oleh provider <i>Payment Gateway</i> untuk digunakan dalam pembuatan <i>Snap Button</i>.
                                                        </code>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-3 mt-4">
                                                <div class="col-md-112 text-center">
                                                    <button type="submit" class="btn btn-md btn-success btn-rounded" id="NotifikasiSimpanSettingPayment">
                                                        <i class="bi bi-arrow-right-circle"></i> Generate Button
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <b>D. Transaksi </b>
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <?php
                                            $curl = curl_init();
                                            curl_setopt_array($curl, array(
                                                CURLOPT_URL => ''.$api_payment_url.'/InfoSetting.php',
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => '',
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 0,
                                                CURLOPT_FOLLOWLOCATION => true,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => 'POST',
                                                CURLOPT_POSTFIELDS =>'{
                                                    "api_key":"'.$api_key.'"
                                                }',
                                                CURLOPT_HTTPHEADER => array(
                                                    'Content-Type: application/json'
                                                ),
                                                CURLOPT_SSL_VERIFYPEER => false,
                                                CURLOPT_SSL_VERIFYHOST => 0,
                                            ));
                                            $response = curl_exec($curl);
                                            curl_close($curl);
                                            $arry_response=json_decode($response, true);
                                            if($arry_response['status']!=="Success"){
                                                echo '<div class="row mt-4 mb-3">';
                                                echo '  <div class="col col-md-12">';
                                                echo '      <div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                                echo '          Tidak ada status koneksi dari server!';
                                                echo '      </div>';
                                                echo '  </div>';
                                                echo '</div>';
                                            }else{
                                                if(empty($arry_response['setting']['urll_call_back'])){
                                                    $urll_call_back="Tidak Ada";
                                                }else{
                                                    $urll_call_back=$arry_response['setting']['urll_call_back'];
                                                }
                                                echo '<div class="row mt-4 mb-3">';
                                                echo '  <div class="col col-md-12">';
                                                echo '      Koneksi Ke Server Berhasil.<br>';
                                                echo '      Berikut ini adalah informasi parameter koneksi dari server.<br>';
                                                echo '      <ol>';
                                                echo '          <li>URL Payment Gateway : <small class="text text-grayish">'.$api_payment_url.'</small></li>';
                                                echo '          <li>URL Call Back : <small class="text text-grayish">'.$urll_call_back.'</small></li>';
                                                echo '          <li>ID Marchant : <small class="text text-grayish">'.$arry_response['setting']['id_marchant'].'</small></li>';
                                                echo '          <li>Client Key : <small class="text text-grayish">'.$arry_response['setting']['client_key'].'</small></li>';
                                                echo '          <li>Server Key : <small class="text text-grayish">'.$arry_response['setting']['server_key'].'</small></li>';
                                                echo '          <li>Snap URL : <small class="text text-grayish">'.$arry_response['setting']['snap_url'].'</small></li>';
                                                echo '          <li>Production : <small class="text text-grayish">'.$arry_response['setting']['production'].'</small></li>';
                                                echo '      </ol>';
                                                echo '  </div>';
                                                echo '</div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <b>D. Log Order ID </b>
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-8 mb-3"></div>
                                            <div class="col col-md-2 mb-3">
                                                <button type="button" class="btn btn-md btn-block btn-rounded btn-outline-grayish" data-bs-toggle="modal" data-bs-target="#ModalFilter">
                                                    <i class="bi bi-filter"></i> Filter
                                                </button>
                                            </div>
                                            <div class="col col-md-2 mb-3">
                                                <button type="button" class="btn btn-md btn-block btn-rounded btn-outline-primary" id="ReloadPaymentLog">
                                                    <i class="bi bi-filter"></i> Reload
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-12" id="MenampilkanTabelPaymentLog">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>