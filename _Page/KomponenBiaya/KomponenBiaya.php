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
                        Berikut ini adalah halaman pengelolaan komponen biaya pendidikan. 
                        Anda bisa mengelola data tarif pada komponen biaya berdasarkan periode akademik. 
                        Komponen biaya pendidikan adalah standar tarif biaya pendidikan yang akan berlaku pada setiap siswa.
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
                                <button type="button" class="btn btn-md btn-outline-primary btn-floating button_copy_komponen_biaya" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tambahkan komponen biaya dari periode akademik lainnya">
                                    <i class="bi bi-copy"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-outline-primary btn-floating button_export_komponen_biaya" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download/Export Komponen Biaya">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-primary btn-floating button_tambah_komponen"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tambah Komponen Biaya">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="javascript:void(0);" id="ProsesMultipleKomponenBiaya">
                            <div class="table table-responsive mb-2">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" name="check_all" class="form-check-input" value="check_all">
                                            </th>
                                            <th><b>No</b></th>
                                            <th><b>Komponen Biaya</b></th>
                                            <th><b>Kategori</b></th>
                                            <th><b>Bulan</b></th>
                                            <th><b>Tahun</b></th>
                                            <th><b>Tempo</b></th>
                                            <th>
                                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tarif standar biaya pendidikan per orang">
                                                    <b class="text-dark"><i class="bi bi-info-circle"></i> Tarif/Biaya</b>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jumlah/Total tagihan per komponen biaya (setelah potongan/diskon)">
                                                    <b class="text-dark"><i class="bi bi-info-circle"></i> Tagihan</b>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jumlah/Total Pembayaran Atas Tagihan Yang Dibuat">
                                                    <b class="text-dark"><i class="bi bi-info-circle"></i> Pembayaran</b>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jumlah Sisa Tagihan Setelah Dikurangi Pembayaran">
                                                    <b class="text-dark"><i class="bi bi-info-circle"></i> Sisa/Tunggakan</b>
                                                </a>
                                            </th>
                                            <th><b>Opsi</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="TabelKomponenBiaya">
                                        <tr>
                                            <td class="text-center" colspan="12">
                                                <small>Loading...</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary"  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i> Option
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditKategoriMultiple">
                                        <i class="bi bi-tag"></i> Ubah Kategori
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditTahunMultiple">
                                        <i class="bi bi-calendar"></i> Ubah Tahun
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditTarifMultiple">
                                        <i class="bi bi-cash-coin"></i> Ubah Tarif/Biaya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusMultiple">
                                        <i class="bi bi-x"></i> Hapus Komponen
                                    </a>
                                </li>
                            </ul>
                        </form>
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