<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-people"></i> Daftar Siswa</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Daftar Siswa</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <small>
                    Berikut ini adalah halaman pengelolaan siswa. 
                    Anda bisa mengelola data identitas siswa pada halaman ini. Buka detail siswa untuk mengelola atribut lengkap dan riwayat pembayaran.
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
                                <button type="button" class="btn btn-md btn-outline-primary btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-database"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                    <li class="dropdown-header text-start">
                                        <h6>Option</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalImport" title="Import Data Siswa">
                                            <i class="bi bi-upload"></i> Import
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalExport" title="Export Data Siswa">
                                            <i class="bi bi-download"></i> Export
                                        </a>
                                    </li>
                                </ul>
                                <button type="button" class="btn btn-md btn-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalFilter" title="Filter Data Siswa">
                                    <i class="bi bi-filter"></i>
                                </button>
                                <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambah" title="Tambah Data Siswa Baru">
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
                                        <td valign="middle">
                                            <input type="checkbox" name="check_all" class="form-check-input" value="check_all">
                                        </td>
                                        <td valign="middle"><b>Nama</b></td>
                                        <td valign="middle"><b>NIS</b></td>
                                        <td valign="middle"><b>Jenjang</b></td>
                                        <td valign="middle"><b>Kelas</b></td>
                                        <td valign="middle">
                                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Tahun Akademik / Periode Akademik">
                                                <b class="text-dark"><i class="bi bi-info-circle"></i> T.A</b>
                                            </a>
                                        </td>
                                        <td valign="middle">
                                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jenis Kelamin (Laki-laki/Perempuan)">
                                                <b class="text-dark"><i class="bi bi-info-circle"></i> L/P</b>
                                            </a>
                                        </td>
                                        <td valign="middle"><b>Tgl.Daftar</b></td>
                                        <td valign="middle"><b>Status</b></td>
                                        <td valign="middle"><b>Tunggakan</b></td>
                                        <td valign="middle"><b>Opsi</b></td>
                                    </tr>
                                </thead>
                                <tbody id="TabelSiswa">
                                    <tr>
                                        <td class="text-center" colspan="11">
                                            <small>No Data</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-secondary"  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i> Option
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUpdateStatus">
                                        <i class="bi bi-tags"></i> Update Status Siswa
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUpdateKelas">
                                        <i class="bi bi-building"></i> Update Kelas
                                    </a>
                                </li>
                            </ul>
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