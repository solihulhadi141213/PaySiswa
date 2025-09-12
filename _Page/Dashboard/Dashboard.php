<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-grid"></i> Dashboard
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12" id="notifikasi_proses">
            <!-- Kejadian Kegagalan Menampilkan Data Akan Ditampilkan Disini -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="card_jam_menarik">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2 text-center" id="image_menarik">
                            <img src="assets/img/<?php echo $app_logo; ?>" width="150px" class="image_menarik">
                        </div>
                        <div class="col-md-9 mb-2 text-end">
                            <h1 class="text text-white"><?php echo $company_name; ?></h1>
                            <div id="tanggal_menarik">Hari, 01 Januari 1900</div><br>
                            <div id="jam_menarik">00:00:00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-12">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="ps-3">
                            <b id="put_pengguna">20.000</b><br>
                            <small>Akses/User</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3">
                            <b id="put_siswa_aktif">20.000</b><br>
                            <small>Siswa Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <div class="ps-3">
                            <b id="put_periode_akademik">5</b><br>
                            <small>Periode Akademik</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-coin"></i>
                        </div>
                        <div class="ps-3">
                            <b id="put_pembayaran">Rp 15.000.000</b><br>
                            <small>Pembayaran Masuk</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body" id="chart">
                           <!-- Menampilkan Grafik Disini -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Biaya Pendidikan /  <small class="text text-muted">Periode Akademik</small>
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="activity">
                                <div class="table table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Periode Akademik</th>
                                                <th class="text-end">Biaya Pendidikan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ShowRiwayatTagihan">
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <small class="text-danger">Belum Ada Data Yang Ditampilkan</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Pembayaran /  <small class="text text-muted">Periode Akademik</small>
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="activity" id="">
                                <div class="table table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Periode Akademik</th>
                                                <th class="text-end">Pembayaran</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ShowRiwayatPembayaran">
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <small class="text-danger">Belum Ada Data Yang Ditampilkan</small>
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
        </div>
    </div>
</section>
