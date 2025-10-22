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
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <b>C. Transaction </b>
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
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
                                        <div class="row mb-3 mt-3">
                                            <div class="col-12">
                                                <div class="table table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th><b>No</b></th>
                                                                <th><b>ID</b></th>
                                                                <th><b>Datetime</b></th>
                                                                <th><b>Name</b></th>
                                                                <th><b>Email</b></th>
                                                                <th><b>Phone</b></th>
                                                                <th><b>Amount</b></th>
                                                                <th><b>Status</b></th>
                                                                <th><b>Option</b></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="TabelTransaksi">
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <small>Tidak Ada Transaksi Yang Ditampilkan</small>
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