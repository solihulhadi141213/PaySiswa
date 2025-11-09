<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-building"></i> Group Kelas</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Group Kelas</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <small>
                        Berikut ini adalah halaman pengelolaan data kelas. 
                        Silahkan tambahkan daftar kelas yang tersedia sesuai dengan periode akademik.
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
                            <div class="col-xl-3 col-lg-4 col-md-8 col-sx-8 col-8">
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
                            <div class="col-xl-9 col-lg-8 col-md-4 col-sx-4 col-4 text-end">
                                <button type="button" class="btn btn-md btn-outline-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalCopy" title="Copy dari periode lain">
                                    <i class="bi bi-copy"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-outline-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalExportKelas" title="Export Data Kelas">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambah" title="Tambah Data">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th valign="middle"><b>No</b></th>
                                        <th valign="middle"><b>Jenjang</b></th>
                                        <th valign="middle"><b>Kelas</b></th>
                                        <th valign="middle">
                                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jumlah Siswa Berdasarkan Data Tagihan / Jumlah Siswa Aktual">
                                                <b><i class="bi bi-info-circle"></i> Siswa</b>
                                            </a>
                                        </th>
                                        <th valign="middle">
                                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Komponen Biaya Pendidikan">
                                                <b><i class="bi bi-info-circle"></i> K.B.P</b>
                                            </a>
                                        </th>
                                        <th valign="middle"><b>Nominal<br>Tagihan</b></th>
                                        <th valign="middle"><b>Diskon</b></th>
                                        <th valign="middle"><b>Jumlah <br>Tagihan</b></th>
                                        <th valign="middle"><b>Pembayaran</b></th>
                                        <th valign="middle"><b>Sisa/Tunggakan</b></th>
                                        <th valign="middle"><b>Opsi</b></th>
                                    </tr>
                                </thead>
                                <tbody id="TabelKelas">
                                    <tr>
                                        <td class="text-center" colspan="9">
                                            <small>Tidak ada data kelas yang ditampilkan</small>
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
                                    Level/Kelas : <span id="put_jumlah_data">0/0</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>