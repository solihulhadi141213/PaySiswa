<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'G2LxhMdVkih0ZJ4xPz8YGxVVCMLNmH0OnQvF');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-tags"></i> Komponen Biaya</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Komponen Biaya</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <small>
                        Berikut ini adalah halaman pengelolaan komponen biaya. 
                        Anda bisa mengelola data tarif dan pola pembayaran berdasarkan kelompok.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-9 col-sx-8 col-8">
                                <small>
                                    <b data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Periode Akademik (Tahun Ajaran)">P.A :</b> 
                                </small>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalPilihPeriodeAkademik">
                                    <span class="badge badge-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click Untuk Mengubah Periode Akademik (Tahun Ajaran)">
                                        <span id="id_academic_period_terpilih">None</span> <i class="bi bi-arrow-up-right"></i>
                                    </span>
                                </a>
                                <input type="hidden" id="id_academic_period">
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-3 col-sx-4 col-4 text-end">
                                <button type="button" class="btn btn-md btn-outline-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalCopy" title="Copy dari periode lain">
                                    <i class="bi bi-copy"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambah" title="Tambah Data Komponen Biaya">
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
                                        <th><b>Biaya Pendidikan</b></th>
                                        <th><b>Kategori</b></th>
                                        <th><b>Bulan</b></th>
                                        <th><b>Tahun</b></th>
                                        <th><b>Tempo</b></th>
                                        <th><b>Nominal</b></th>
                                        <th><b>Opsi</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelKomponenBiaya">
                                    <tr>
                                        <td class="text-center" colspan="8">
                                            <small>Tidak ada data yang ditampilkan</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <small>
                                    Jumlah Data : <span id="page_info">0 Record</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>