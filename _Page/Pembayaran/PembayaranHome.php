<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-cash-coin"></i> Pembayaran</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Pembayaran</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <small>
                    Berikut ini adalah halaman pengelolaan riwayat pembayaran siswa. 
                    Anda bisa menambahkan/mencatat pembayaran siswa dan mencetak bukti pembayaran pada halaman ini.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form action="javascript:void(0);" id="ProsesMultipleSiswa">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-md btn-info btn-floating" data-bs-toggle="modal" data-bs-target="#ModalExport" title="Export Data Pembayaran">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalFilter" title="Filter Data">
                                    <i class="bi bi-filter"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalSiswa" title="Tambah Pembayaran Baru">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Tgl.Bayar</b></th>
                                        <th><b>NIS</b></th>
                                        <th><b>Siswa</b></th>
                                        <th><b>Kelas</b></th>
                                        <th><b>Komponen/Uraian</b></th>
                                        <th><b>Pembayaran</b></th>
                                        <th><b>Metode</b></th>
                                        <th><b>Opsi</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelPembayaran">
                                    <tr>
                                        <td class="text-center" colspan="9">
                                            <small>Tidak ada data pembayaran yang ditampilkan</small>
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
            </form>
        </div>
    </div>
</section>