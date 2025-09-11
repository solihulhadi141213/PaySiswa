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
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="IdPeriodeAkademik">
                                    <small>Periode Akademik</small>
                                </label>
                                <select name="id_academic_period" id="IdPeriodeAkademik" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                        //Menampilkan periode akademik
                                        $query = mysqli_query($Conn, "SELECT id_academic_period, academic_period, academic_period_start FROM academic_period ORDER BY academic_period_start ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_academic_period = $data['id_academic_period'];
                                            $academic_period= $data['academic_period'];
                                            $academic_period_start= $data['academic_period_start'];
                                            echo '<option value="'.$id_academic_period.'">'.$academic_period.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="SelectOrganizationClass">
                                    <small>Kelas</small>
                                </label>
                                <select name="id_organization_class" id="SelectOrganizationClass" class="form-control">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
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
                                    <th><b>Siswa</b></th>
                                    <th><b>NIS</b></th>
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