<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'zjp4kL7qiPgGvrKaWwkZe4ODEnvFuvNVxrTJ');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <input type="hidden" id="put_id_organization_class">
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-cash-coin"></i> Tagihan</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Tagihan</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <small>
                        Berikut ini adalah halaman daftar tagihan biaya sekolah siswa. 
                        Pada halaman ini menampilkan daftar siswa dan komponen biaya yang harus di bayar.
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
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-md btn-outline-primary btn-floating modal_export_tagihan" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Export Data Tagihan Siswa">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-secondary btn-floating modal_filter_tagihan" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ubah Filter Periode Akademik Dan Mode Tampilan Data">
                                    <i class="bi bi-filter"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-primary btn-floating modal_pilih_siswa" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tambah Data Tagihan Siswa Secara Parsial">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12" id="title_table">
                                <!-- Title Table -->
                            </div>
                        </div>
                        <div class="table table-responsive border-1 border-top">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <td valign="middle" align="center"><small><b>No</b></small></td>
                                        <td valign="middle"><small><b>Nama Siswa</b></small></td>
                                        <td valign="middle"><small><b>NIS</b></small></td>
                                        <td valign="middle" align="right"><small><b>Biaya Pendidikan</b></small></td>
                                        <td valign="middle" align="right"><small><b>Diskon/Potongan</b></small></td>
                                        <td valign="middle" align="right"><small><b>Jumlah Tagihan</b></small></td>
                                        <td valign="middle" align="right"><small><b>Pembayaran</b></small></td>
                                        <td valign="middle" align="right"><small><b>Sisa/Tunggakan</b></small></td>
                                        <td valign="middle" align="right"><small><b>Opsi</b></small></td>
                                    </tr>
                                </thead>
                                <tbody id="TabelTagihan">
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <small>Loading..</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Tabel Tagihan Akan Ditampilkan Disini -->
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <small id="page_info">
                                    Jumlah Siswa : -
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>