<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'qLECdqLVgBMjV0BXHUC');
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
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small>';
                    echo '      Berikut ini adalah halaman pengaturan umum aplikasi.';
                    echo '      Pada halaman ini anda bisa mengatur properti aplikasi sesuai yang anda inginkan dari mulai judul, deskripsi, informasi kontak dan logo.';
                    echo '      Periksa kembali pengaturan yang anda gunakan agar aplikasi berjalan dengan baik.';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="javascript:void(0);" id="ProsesSettingGeneral">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">Form Pengaturan Umum</b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="title_page">Judul/Nama Perusahaan</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="title_page" id="title_page" class="form-control" placeholder="Koperasi Andalan Jaya" value="<?php echo "$title_page"; ?>">
                                    <small>Maksimal 20 karakter</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="kata_kunci">Kata Kunci</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="kata_kunci" id="kata_kunci" class="form-control" value="<?php echo "$kata_kunci"; ?>">
                                    <small>(Contoh: keyword1, keyword2, keyword3)</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="deskripsi">Deskripsi</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" class="form-control"><?php echo "$deskripsi"; ?></textarea>
                                    <small>Menjelaskan gambaran umum aplikasi ini</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="alamat_bisnis">Alamat/Kantor</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="alamat_bisnis" id="alamat_bisnis" cols="30" rows="3" class="form-control"><?php echo "$alamat_bisnis"; ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="email">Email Perusahaan</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="email" name="email_bisnis" id="email_bisnis" class="form-control" placeholder="email@domain.com" value="<?php echo "$email_bisnis"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="telepon_bisnis">Nomor Telepon</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="telepon_bisnis" id="telepon_bisnis" class="form-control" placeholder="+62" value="<?php echo "$telepon_bisnis"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="favicon">File Favicon</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" name="favicon" id="favicon" class="form-control">
                                    <small>
                                        Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                        <?php
                                            if(!empty($favicon)){
                                                echo '<a href="assets/img/'.$favicon.'" target="_blank">View Image Here</a>';
                                            }
                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="logo">Logo Image</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" name="logo" id="logo" class="form-control">
                                    <small>
                                        Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                        <?php
                                            if(!empty($logo)){
                                                echo '<a href="assets/img/'.$logo.'" target="_blank">View Image Here</a>';
                                            }
                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="base_url">Base URL</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="base_url" id="base_url" class="form-control" placeholder="https://" value="<?php echo "$base_url"; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="author">Author Aplikasi</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="author" id="author" class="form-control" value="<?php echo "$AuthorAplikasi"; ?>">
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
    </section>
<?php } ?>