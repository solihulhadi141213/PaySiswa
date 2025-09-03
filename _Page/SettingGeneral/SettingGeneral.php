<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'Mt24BYzC76RJBEuHdY95bmMKrulttEQzblzH');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-gear"></i> Pengaturan Umum</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Pengaturan Umum</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <small>
                        Berikut ini adalah halaman pengaturan umum aplikasi.
                        Pada halaman ini anda bisa mengatur properti aplikasi sesuai yang anda inginkan. 
                        Periksa kembali pengaturan yang anda gunakan agar aplikasi berjalan dengan baik. 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="javascript:void(0);" id="ProsesSettingAplikasi">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title"><i class="bi bi-app"></i> Pengaturan Aplikasi</b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="app_title">Nama Aplikasi</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="app_title" id="app_title" class="form-control" placeholder="Koperasi Andalan Jaya" value="<?php echo "$app_title"; ?>">
                                    <small>Maksimal 20 karakter</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="app_keyword">Kata Kunci</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="app_keyword" id="app_keyword" class="form-control" value="<?php echo "$app_keyword_show"; ?>">
                                    <small>(Contoh: keyword1, keyword2, keyword3)</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="app_description">Deskripsi</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="app_description" id="app_description" cols="30" rows="3" class="form-control"><?php echo "$app_description"; ?></textarea>
                                    <small>Menjelaskan gambaran umum aplikasi ini</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="app_base_url">Base URL</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="app_base_url" id="app_base_url" class="form-control" placeholder="https://" value="<?php echo "$app_base_url"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="app_author">Author Aplikasi</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="app_author" id="app_author" class="form-control" value="<?php echo "$app_author"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="app_year">Tahun Release </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="app_year" id="app_year" class="form-control" value="<?php echo "$app_year"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-9 text-right" id="NotifikasiSimpanSettingGeneral">
                                    <small class="text-dark">Pastikan pengaturan yang anda gunakan sudah sesuai.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary btn-rounded">
                                <i class="bi bi-save"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <form action="javascript:void(0);" id="ProsesUpdateFavicon">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-image"></i> Favicon
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4 text-center">
                                    <img src="assets\img\<?php echo "$app_favicon"; ?>" alt="" width="70%">
                                </div>
                                <div class="col-md-8">
                                    <label for="app_favicon"><small>Upload Favicon</small></label>
                                    <input type="file" class="form-control" name="app_favicon" id="app_favicon">
                                    <small>
                                        <small class="text text-muted">
                                            File maksimal 2 mb (Type: JPG, JPEG, PNG, GIF)
                                        </small>
                                    </small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12" id="NotifikasiUpdateFavicon">
                                    <!-- Notifikasi Update Favicon -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-success btn-rounded">
                                <i class="bi bi-upload"></i> Upload & Update Setting
                            </button>
                        </div>
                    </div>
                </form>
                <form action="javascript:void(0);" id="ProsesUpdateLogo">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-image"></i> Logo
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4 text-center">
                                    <img src="assets\img\<?php echo "$app_logo"; ?>" alt="" width="70%">
                                </div>
                                <div class="col-md-8">
                                    <label for="app_logo"><small>Upload Logo</small></label>
                                    <input type="file" class="form-control" name="app_logo" id="app_logo">
                                    <small>
                                        <small class="text text-muted">
                                            File maksimal 2 mb (Type: JPG, JPEG, PNG, GIF)
                                        </small>
                                    </small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12" id="NotifikasiUpdateLogo">
                                    <!-- Notifikasi Update Favicon -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-success btn-rounded">
                                <i class="bi bi-upload"></i> Upload & Update Setting
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form action="javascript:void(0);" id="ProsesUpdateCompany">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-building"></i> Profil Sekolah
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="company_name">
                                        <small>Company Name</small>
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="company_name" id="company_name" value="<?php echo "$company_name"; ?>">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="company_contact">
                                        <small>Contact</small>
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="company_contact" id="company_contact" value="<?php echo "$company_contact"; ?>">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="company_email">
                                        <small>Email</small>
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" class="form-control" name="company_email" id="company_email" value="<?php echo "$company_email"; ?>">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="company_address">
                                        <small>Alamat</small>
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <textarea name="company_address" id="company_address" class="form-control"><?php echo "$company_address"; ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12" id="NotifikasiUpdateCompany">
                                    <!-- Notifikasi Update Company -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-success btn-rounded">
                                <i class="bi bi-upload"></i> Upload & Update Setting
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php } ?>