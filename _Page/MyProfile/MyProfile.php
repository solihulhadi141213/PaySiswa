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
        <div class="col-md-3">
            <div class="card">
                <div class="card-header text-center">
                    <b class="card-title">
                        <i class="bi bi-info-circle"></i> Info Pengguna
                    </b>
                </div>
                <div class="card-body">
                    <div class="row mb-3 border-1 border-bottom">
                        <div class="col-md-12 mb-3 text-center">
                            <img src="<?php echo 'image_proxy.php?dir=User&filename='.$access_foto.''; ?>" alt="" width="70%" class="rounded-circle">
                        </div>
                    </div>
                    <div class="row mb-3 border-1 border-bottom">
                        <div class="col-md-12 mb-3">
                            <div class="row mb-2">
                                <div class="col-5 mb-2"><small class="credit">Nama Pengguna</small></div>
                                <div class="col-1 mb-2"><small class="credit">:</small></div>
                                <div class="col-6 mb-2"><small class="text-grayish"><?php echo "$access_name"; ?></small></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 mb-2"><small class="credit">Nomor Kontak</small></div>
                                <div class="col-1 mb-2"><small class="credit">:</small></div>
                                <div class="col-6 mb-2"><small class="text-grayish"><?php echo "$access_contact"; ?></small></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 mb-2"><small class="credit">Alamat Email</small></div>
                                <div class="col-1 mb-2"><small class="credit">:</small></div>
                                <div class="col-6 mb-2"><small class="text-grayish"><?php echo "$access_email"; ?></small></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 mb-2"><small class="credit">Group Akses</small></div>
                                <div class="col-1 mb-2"><small class="credit">:</small></div>
                                <div class="col-6 mb-2"><small class="text-grayish"><?php echo "$access_group"; ?></small></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-md btn-outline-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalUbahIdentitasProfil">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-md btn-outline-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalUbahFotoProfil">
                                <i class="bi bi-image-alt"></i>
                            </button>
                            <button type="button" class="btn btn-md btn-outline-secondary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalUbahPasswordProfil">
                                <i class="bi bi-key"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header text-center">
                    <b class="card-title"><i class="bi bi-app"></i> Izin Pengguna</b>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td class="text-center"><b><small>No</small></b></td>
                                    <td colspan="2"><b><small>Kategori / Fitur</small></b></td>
                                    <td><b><small>Deskripsi</small></b></td>
                                    <td><b><small>Status</small></b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_access_feature FROM access_feature "));
                                    if(empty($jml_data)){
                                        echo '
                                            <tr>
                                                <td colspan="5" class="text-center"><small class="text-danger">Tidak Ada Data Fitur Aplikasi Yang Ditampilkan</small></td>
                                            </tr>
                                        ';
                                        exit;
                                    }

                                    //Tampilkan Data Secara Distinct 'feature_category' dari tabel 'access_feature'
                                    $no = 1;
                                    $query_kategori = mysqli_query($Conn, "SELECT DISTINCT feature_category FROM access_feature ORDER BY feature_category ASC");
                                    while ($data_kategori = mysqli_fetch_array($query_kategori)) {
                                        $feature_category = $data_kategori['feature_category'];
                                        echo '
                                            <tr>
                                                <td class="text-center"><small><b>'.$no.'</b></small></td>
                                                <td colspan="4"><small><b>'.$feature_category.'</b></small></td>
                                            </tr>
                                        ';

                                        //Menampilkan 'access_feature' berdasarkan 'feature_category'
                                        $no_data = 1;
                                        $query = mysqli_query($Conn, "SELECT * FROM access_feature WHERE feature_category='$feature_category' ORDER BY feature_name ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_access_feature  = $data['id_access_feature'];
                                            $feature_name       = $data['feature_name'];
                                            $feature_description       = $data['feature_description'];

                                            //Memeriksa Apakah '$SessionIdAccess'memiliki 'id_access_feature' dari tabel 'access_permission' 
                                            $QryPermission = mysqli_query($Conn,"SELECT id_permission FROM access_permission WHERE id_access_feature='$id_access_feature' AND id_access='$SessionIdAccess'")or die(mysqli_error($Conn));
                                            $DataPermission = mysqli_fetch_array($QryPermission);
                                            if(empty($DataPermission['id_permission'])){
                                                $label_status_permission = '<span class="text-danger"><i class="bi bi-x-circle"></i></span>';
                                                $text_color = 'text-grayish';
                                            }else{
                                                $label_status_permission = '<span class="text-success"><i class="bi bi-check-circle"></i></span>';
                                                $text_color = 'text-dark';
                                            }

                                            //Tampilkan Baris Tabel
                                            echo '
                                                 <tr>
                                                    <td></td>
                                                    <td><small class="'.$text_color.'">'.$no.'.'.$no_data.'</small></td>
                                                    <td><small class="'.$text_color.'">'.$feature_name.'</small></td>
                                                    <td><small class="'.$text_color.'">'.$feature_description.'</small></td>
                                                    <td><small>'.$label_status_permission.'</small></td>
                                                </tr>
                                            ';
                                            $no_data++;
                                        }
                                        $no++;
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
