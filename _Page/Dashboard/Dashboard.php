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
                    <div id="tanggal_menarik">Hari, 01 Januari 1900</div><br>
                    <div id="jam_menarik">00:00:00</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="ps-3">
                            <h3>20</h3>
                            <small>Akses/Pengguna</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card info-card blue-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <div class="ps-3">
                            <h3>14</h3>
                            <small>Tahun Ajaran</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3">
                            <h3>20.000</h3>
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
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="ps-3">
                            <h3>12</h3>
                            <small>Kelas</small>
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
                            <i class="bi bi-wallet"></i>
                        </div>
                        <div class="ps-3">
                            <h3>5</h3>
                            <small>Komponen Biaya</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            
            <div class="row">
                <!-- Reports -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Grafik Pendapatan Thn <?php echo date ('Y'); ?>
                            </b>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" id="NamaTitleData"></h5>
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Anggota / <small class="text text-muted">5 Record terbaru</small>
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="activity" id="ShowAnggotaTerbaru">
                                <!-- Menampilkan "ShowAnggotaTerbaru" -->
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="index.php?Page=Anggota" 
                                class="btn btn-secondary btn-sm btn-floating" 
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                data-bs-custom-class="custom-tooltip" 
                                data-bs-title="Lihat Selengkapnya Di Halaman Anggota" >
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Simpanan / <small class="text text-muted">5 Record terbaru</small>
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="activity" id="ShowSimpananTerbaru">
                                <!-- Menampilkan "ShowSimpananTerbaru"  -->
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="index.php?Page=Tabungan" 
                                class="btn btn-secondary btn-sm btn-floating" 
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                data-bs-custom-class="custom-tooltip" 
                                data-bs-title="Lihat Selengkapnya Di Halaman Simpanan" >
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Pinjaman / <small class="text text-muted">5 Record terbaru</small>
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="activity" id="ShowPinjamanTerbaru">
                                <!-- Menampilkan Pinjaman Terbaru -->
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="index.php?Page=Pinjaman" 
                                class="btn btn-secondary btn-sm btn-floating" 
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                data-bs-custom-class="custom-tooltip" 
                                data-bs-title="Lihat Selengkapnya Di Halaman Pinjaman" >
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
