<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'Dnd2UZLzazCqJ9WfuzQKlIOpYueb2fXxNHXA');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small class="mobile-text">';
                    echo '      Berikut ini adalah halaman untuk mengelola informasi dokumentasi aplikasi.';
                    echo '      Halaman ini membantu pengembang dalam menyampaikan petunjuk penggunaan dan berbagai kendala yang mungkin saja terjadi.';
                    echo '      Buat berbagai dokumentasi yang berkaitan dengan cara penggunaan aplikasi sehingga membantu pengguna dalam memahami aplikasi lebih cepat.';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-12 text-end">
                                <a class="btn btn-md btn-outline-grayish btn-floating" href="index.php?Page=Help&Sub=HelpHome">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-md btn-outline-dark btn-floating" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalFilter">
                                    <i class="bi bi-filter"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-md btn-info btn-floating" data-bs-toggle="modal" data-bs-target="#ModalListKategori">
                                    <i class="bi bi-tag"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahBantuan">
                                    <i class="bi bi-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><b>No</b></th>
                                        <th><b>Judul Bantuan</b></th>
                                        <th><b>Kategori</b></th>
                                        <th><b>Author</b></th>
                                        <th><b>Tanggal</b></th>
                                        <th><b>status</b></th>
                                        <th><b>Opsi</b></th>
                                    </tr>
                                </thead>
                                <tbody id="MenampilkanTabelHelp">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <small class="text-danger">Belum ada dokumentasi yang ditampilkan</small>
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
            </div>
        </div>
    </section>
<?php } ?>