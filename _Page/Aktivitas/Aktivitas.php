<?php
    //Menangkap mode data
    if(empty($_GET['mode'])){
        $mode="Tabel";
    }else{
        $mode=$_GET['mode'];
    }
?>
<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-circle"></i> Log Aktivitas</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active"> Log Aktivitas</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <?php
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo '  <small>';
                echo '      Berikut ini adalah halaman data log aktivitas.';
                echo '      Fitur ini digunakan untuk mempermudah anda dalam melakukan monitoring aktivitas user.';
                echo '      Tampilkan data menggunakan mode grafik atau tabel sesuai keinginan anda.';
                echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '  </small>';
                echo '</div>';
            ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body" id="GrafikAktivitas">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form action="javascript:void(0);" id="ProsesMulti">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8 mb-2">
                                <b class="card-title"># Tabel Riwayat Aktivitas</b>
                            </div>
                            <div class="col-4 mb-2 text-end">
                                <button type="button" class="btn btn-md btn-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalFilter">
                                    <i class="bi bi-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" name="check_all" class="form-check-input" value="check_all">
                                                </th>
                                                <th><b>No</b></th>
                                                <th><b>Tanggal/Waktu</b></th>
                                                <th><b>Kategori</b></th>
                                                <th><b>Deskripsi</b></th>
                                                <th><b>Akses/User</b></th>
                                                <th><b>Opsi</b></th>
                                            </tr>
                                        </thead>
                                        <tbody id="MenampilkanTabel">
                                            <!-- Menampilkan Tabel -->
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <small class="text-danger">Belum Ada Data Yang Ditampilkan!</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#ModalHapusMultiple">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#ModalHapusSemua">
                                    <i class="bi bi-eraser"></i> Clear
                                </button>
                            </div>
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