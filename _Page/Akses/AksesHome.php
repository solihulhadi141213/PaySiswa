<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-person"></i> Akses Pengurus</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Akses Pengurus</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <?php
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo '  <small>';
                echo '      Berikut ini adalah halaman pengelolaan data akses pengurus koperasi. Anda bisa menambahkan data akses baru, melihat detail informasi user akses, ';
                echo '      Dan melihat riwayat aktivitas user tersebut. Ijin akses sesuai dengan entitas pada saat pertama kali dibuat, namun anda masih bisa melakukan perubahan ijin fitur pada masing-masing data akses tersebut.';
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
                    <div class="row">
                        <div class="col-md-8 mb-3"></div>
                        <div class="col-md-2 mb-3">
                            <button type="button" class="btn btn-md btn-outline-dark btn-block btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalFilterAkses" title="Filter Data Akses">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="button" class="btn btn-md btn-primary btn-block btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalTambahAkses" title="Tambah Data Akses Baru">
                                <i class="bi bi-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="MenampilkanTabelAkses">

                </div>
            </div>
        </div>
    </div>
</section>