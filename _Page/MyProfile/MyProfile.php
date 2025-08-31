<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-person-circle"></i> Profil Saya</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active"> Profil Saya</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row mb-3">
        <div class="col-md-12">
            <?php
                echo '
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <small>
                            Berikut ini adalah halaman profil yang digunakan untuk mengelola informasi akses anda. 
                            Pada halaman ini anda bisa melakukan perubahan data akses (Nama, Email, Password dan Foto Profile).
                        </small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <b class="card-title">
                                <i class="bi bi-info-circle"></i> Informasi Pengguna
                            </b>
                        </div>
                        <div class="col-4 text-end">
                            <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                                <li class="dropdown-header text-start">
                                    <h6>Option</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUbahIdentitasProfil">
                                        <i class="bi bi-pencil"></i> Edit Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUbahFotoProfil">
                                        <i class="bi bi-image-alt"></i> Ubah Foto Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalUbahPasswordProfil">
                                        <i class="bi bi-key"></i> Ubah Password
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3 text-center">
                            <img src="<?php echo 'image_proxy.php?dir=User&filename='.$access_foto.''; ?>" alt="" width="70%" class="rounded-circle">
                        </div>
                        <div class="col-md-9 mb-3">
                            <div class="row mb-2">
                                <div class="col-5 mb-2">
                                    <small class="credit">Nama Pengguna</small>
                                </div>
                                <div class="col-1 mb-2">
                                    <small class="credit">:</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-grayish">
                                        <?php echo "$access_name"; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 mb-2">
                                    <small class="credit">Nomor Kontak</small>
                                </div>
                                <div class="col-1 mb-2">
                                    <small class="credit">:</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-grayish">
                                        <?php echo "$access_contact"; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 mb-2">
                                    <small class="credit">Alamat Email</small>
                                </div>
                                <div class="col-1 mb-2">
                                    <small class="credit">:</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-grayish">
                                        <?php echo "$access_email"; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 mb-2">
                                    <small class="credit">Group Akses</small>
                                </div>
                                <div class="col-1 mb-2">
                                    <small class="credit">:</small>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-grayish">
                                        <?php echo "$access_group"; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
