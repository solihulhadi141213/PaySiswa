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
                    <form action="javascript:void(0);" id="ProsesFilterTagihan">
                        <input type="hidden" name="page" id="page" value="1">
                        <input type="hidden" name="batas" id="batas" value="10">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="kelompok_status_siswa">
                                    <small>Status Siswa</small>
                                </label>
                                <select name="kelompok_status_siswa" id="kelompok_status_siswa" class="form-control">
                                    <option value="">Semua</option>
                                    <option selected value="Terdaftar">Terdaftar</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Keluar">Keluar</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="keyword_by">
                                    <small>Dasar Pencarian</small>
                                </label>
                                <select name="keyword_by" id="keyword_by" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="id_organization_class">Kelas</option>
                                    <option value="student_nis">NIS</option>
                                    <option value="student_name">Nama Siswa</option>
                                    
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="keyword">
                                    <small>Pencarian</small>
                                </label>
                                <div id="form_filter">
                                    <input type="text" class="form-control" name="keyword" id="keyword">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <br>
                                <button type="submit" class="btn btn-md btn-block btn-primary">
                                    <i class="bi bi-search"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><b>No</b></th>
                                    <th><b>NIS</b></th>
                                    <th><b>Siswa</b></th>
                                    <th><b>Kelas</b></th>
                                    <th><b>Tagihan (Rp)</b></th>
                                    <th><b>Pembayaran (Rp)</b></th>
                                    <th><b>Sisa (Rp)</b></th>
                                    <th><b>Opsi</b></th>
                                </tr>
                            </thead>
                            <tbody id="TabelTagihan">
                                <tr>
                                    <td colspan="8" class="text-center">Belum Ada Data Yang Ditampilkan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Tabel Tagihan Akan Ditampilkan Disini -->
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