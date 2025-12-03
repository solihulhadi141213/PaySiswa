<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'JbBByqDggzgIC8y6IH4JnbyynMUvHd0iFx5G');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-calendar-check"></i> Tahun Akademik</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Tahun Akademik</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <small>
                        Berikut ini adalah halaman untuk mengelola periode tahun akademik. 
                        Setiap data transaksi dan kelompok kelas siswa akan dikelompokan berdasarkan tahun akademik ini. 
                        Setelah tahun akademik berakhir, silahkan lakukan penguncian agar data tidak bisa diubah.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <form action="javascript:void(0);" id="ProsesBatas">
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-md btn-outline-primary btn-floating modal_export_periode_akademik" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cetak/Export Data Periode Akademik">
                                        <i class="bi bi-download"></i>
                                    </button>
                                    <button type="button" class="btn btn-md btn-secondary btn-floating modal_filter" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Filter Data">
                                        <i class="bi bi-filter"></i>
                                    </button>
                                    <button type="button" class="btn btn-md btn-primary btn-floating modal_tambah_periode_akademik" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tambah Periode Akademik">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><small><b>No</b></small></th>
                                        <th><small><b>Tahun Akademik</b></small></th>
                                        <th><small><b>Kelas/Rombel</b></small></th>
                                        <th><small><b>Siswa</b></small></th>
                                        <th>
                                            <small data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Komponen Biaya Pendidikan">
                                                <b><i class="bi bi-info-circle"></i> K.B.P</b>
                                            </small>
                                        </th>
                                        <th>
                                            <small data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jumlah Tagihan Sebelum Diskon">
                                                <b><i class="bi bi-info-circle"></i> Biaya/Tagihan</b>
                                            </small>
                                        </th>
                                        <th><small><b>Diskon/Potongan</b></small></th>
                                        <th>
                                            <small data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jumlah Tagihan Setelah Diskon/Potongan">
                                                <b><i class="bi bi-info-circle"></i> Tagihan</b>
                                            </small>
                                        </th>
                                        <th><small><b>Pembayaran</b></small></th>
                                        <th><small><b>Sisa/Tunggakan</b></small></th>
                                        <th><small><b>Status</b></small></th>
                                        <th><small><b>Opsi</b></small></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelTahunAjaran">
                                    <!-- Menampilkan Tabel Tahun Akademik -->
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <small id="page_info">
                                    Page 1 Of 100
                                </small>
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="prev_button">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="next_button">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>