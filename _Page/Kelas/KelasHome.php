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
                            <div class="col-xl-3 col-lg-4 col-md-9 col-sx-9 col-9">
                                <label for="id_academic_period">
                                    <small>Pilih Periode Akademik</small>
                                </label>
                                <select name="id_academic_period" id="id_academic_period" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                        //Menampilkan Tahun Akademik
                                        $query = mysqli_query($Conn, "SELECT id_academic_period, academic_period FROM academic_period  ORDER BY academic_period_start DESC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_academic_period = $data['id_academic_period'];
                                            $academic_period= $data['academic_period'];
                                            echo '<option value="'.$id_academic_period.'">'.$academic_period.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-3 col-sx-3 col-3 text-end">
                                <br>
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
                                        <th><b>No</b></th>
                                        <th><b>Kelas/Level</b></th>
                                        <th><b>Sub Kelas</b></th>
                                        <th><b>Jumlah Siswa</b></th>
                                        <th><b>Komponen Biaya</b></th>
                                        <th><b>Biaya Pendidikan</b></th>
                                        <th><b>Pembayaran</b></th>
                                        <th><b>Sisa/Tunggakan</b></th>
                                        <th><b>Opsi</b></th>
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