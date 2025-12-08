<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{

        // Menangkap Sub Halaman
        if(empty($_GET['sub'])){
            $sub = "";
        }else{
            $sub = $_GET['sub'];
        }
       
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
                         Uji coba pembayaran : https://docs.midtrans.com/docs/testing-payment-on-sandbox
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <b class="card-title">
                            <i class="bi bi-gear"></i> Pengaturan Payment Gateway
                        </b>
                    </div>
                    <div class="card-body">
                        <?php
                            
                            // Routing Konten (Koneksi)
                            if($sub=="koneksi"){
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b>A. Koneksi Payment Proxy</b>
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b><i class="bi bi-chevron-up"></i></b>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-12 mb-3">
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <small>
                                                        Berikut ini adalah variabel koneksi dari <b>PaySiswa</b> menuju aplikasi <b>SnapProxy</b> yang berhasil tersimpan. 
                                                        Silahkan lakukan test koneksi untuk memastikan bahwa kedua aplikasi terhubung dengan baik.
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-12" id="ConnectionSetting">
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }else{
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=koneksi">
                                                A. Koneksi Payment Proxy
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=koneksi">
                                                <i class="bi bi-chevron-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                ';
                            }

                            // Routing Konten (profil)
                            if($sub=="profil"){
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b>B. Profil Pengaturan</b>
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b><i class="bi bi-chevron-up"></i></b>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-12 mb-3">
                                            <div class="row mb-3 mt-3">
                                                <div class="col-md-10 mb-2">
                                                   <small>
                                                        Profil pengaturan menyimpan <b>environment</b> variabel akun payment gateway yang akan dihubungkan.
                                                        Silahkan buat profil pengaturan kredensial dari provider untuk dapat digunakan untuk terhubung dengan akun payment gateway anda.
                                                   </small>
                                                </div>
                                                <div class="col-md-2 mb-2 text-end">
                                                    <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahProfilPaymentGateway">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table table-responsive border-1 border-top">
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
                                ';
                            }else{
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=profil">
                                               B. Profil Pengaturan
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=profil">
                                                <i class="bi bi-chevron-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                ';
                            }

                            // Routing Konten (transaksi)
                            if($sub=="transaksi"){
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b>C. Transaksi</b>
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b><i class="bi bi-chevron-up"></i></b>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-12 mb-3">
                                            <div class="row mb-3 mt-3">
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn btn-md btn-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalFilterTransaksi">
                                                        <i class="bi bi-filter"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalCreatSnapToken">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="table table-responsive border-1 border-top">
                                                        <table class="table table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th><b>No</b></th>
                                                                    <th><b>Order ID</b></th>
                                                                    <th><b>Kode Transaksi</b></th>
                                                                    <th><b>Datetime</b></th>
                                                                    <th><b>Nama Pelanggan</b></th>
                                                                    <th><b>Email</b></th>
                                                                    <th><b>Telepon</b></th>
                                                                    <th><b>Nominal</b></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="TabelTransaksi">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small id="page_info_transaction">
                                                        Page 1 Of 100
                                                    </small>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="prev_button_transaction">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="next_button_transaction">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }else{
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=transaksi">
                                               C. Transaksi
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=transaksi">
                                                <i class="bi bi-chevron-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                ';
                            }

                            // Routing Konten (order)
                            if($sub=="order"){
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b>D. Log Payment</b>
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway" class="text-primary">
                                                <b><i class="bi bi-chevron-up"></i></b>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-12 mb-3">
                                            <div class="row mt-3 mb-3">
                                                <div class="col-12 mb-3 text-end">
                                                    <button type="button" class="btn btn-md btn-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalFilterLogOrder">
                                                        <i class="bi bi-filter"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-md btn-primary btn-floating" id="ReloadPaymentLog">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row mt-3 mb-3">
                                                <div class="col-md-12">
                                                    <div class="table table-responsive border-1 border-top">
                                                        <table class="table table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th><b>No</b></th>
                                                                    <th><b>Transaction Code</b></th>
                                                                    <th><b>Order ID</b></th>
                                                                    <th><b>Datetime</b></th>
                                                                    <th><b>Status Code</b></th>
                                                                    <th><b>Payment type</b></th>
                                                                    <th><b>Amount</b></th>
                                                                    <th><b>Fraud Status</b></th>
                                                                    <th><b>Transaction Status</b></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="TabelLogOrder">
                                                                <tr>
                                                                    <td colspan="10" class="text-center">
                                                                        <small>Tidak Ada Log Order ID Yang Ditampilkan</small>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small id="page_info_order">
                                                        Page 1 Of 100
                                                    </small>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="prev_button_order">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="next_button_order">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }else{
                                echo '
                                    <div class="row mb-3 border-1 border-bottom">
                                        <div class="col-11 mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=order">
                                               D. Log Payment
                                            </a>
                                        </div>
                                        <div class="col-1 text-end mb-2">
                                            <a href="index.php?Page=PaymentGateway&sub=order">
                                                <i class="bi bi-chevron-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                ';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>