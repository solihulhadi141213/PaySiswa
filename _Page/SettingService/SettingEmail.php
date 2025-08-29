<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'rfLn8WEkAqzC1gu5z45');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        include "_Config/SettingEmail.php";
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-gear"></i> Email Gateway</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Email Gateway</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row mb-3">
            <div class="col-md-12">
                <?php
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '  <small>';
                    echo '      Berikut ini adalah halaman pengaturan email gateway.';
                    echo '      Apabila pengaturan ini berfungsi dengan baik maka sistem akan menggunakan parameter ini untuk melakukan pengirian data dalam beberapa fitur.';
                    echo '      Lakukan pengujian (Test) untuk memastikan bahwa parameter yang digunakan sudah sesuai.';
                    echo '      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '  </small>';
                    echo '</div>';
                ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <form action="javascript:void(0);" id="ProsesSettingEmail">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-gear"></i> Form Pengaturan Email Gateway
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="url_service">URL Service Gateway</i></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="url_service" id="url_service" class="form-control" required value="<?php echo "$url_service"; ?>">
                                    <small class="credit">
                                        <code class="text text-grayish">
                                            URL yang mengarah pada service gateway.
                                        </code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="url_provider">URL Provider</i></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="url_provider" id="url_provider" class="form-control" required value="<?php echo "$url_provider"; ?>">
                                    <small class="credit">
                                        <code class="text text-grayish">
                                            URL yang mengarah pada provider SMTP. Beberapa provider memilikki standar penggunaan URL yang berbeda. 
                                        </code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="email_gateway">Email Account</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" name="email_gateway" id="email_gateway" class="form-control" required value="<?php echo "$email_gateway"; ?>">
                                    <small class="credit">
                                        <code class="text text-grayish">
                                            Akun email dari web mail pada layanan hosting.
                                        </code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="password_gateway">Password Email</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="password_gateway" id="password_gateway" class="form-control" required value="<?php echo "$password_gateway"; ?>">
                                    <small class="credit">
                                        <code class="text text-grayish">
                                            Password akun email yang telah di buat pada web mail pada layanan hosting.
                                        </code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="nama_pengirim">Nama Pengirim</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="nama_pengirim" id="nama_pengirim" class="form-control" required value="<?php echo "$nama_pengirim"; ?>">
                                    <small class="credit">
                                        <code class="text text-grayish">
                                            Nama pengirim yang disematkan pada saat mengirim email.
                                        </code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="port_gateway">Port SMTP</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="port_gateway" id="port_gateway" class="form-control" required value="<?php echo "$port_gateway"; ?>">
                                    <small class="credit">
                                        <code class="text text-grayish">
                                            Port SMTP yang terbuka untuk proses pengiriman email.
                                        </code>
                                    </small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"></div>
                                <div class="col-md-8 text-right" id="">
                                    <small class="text-dark">Pastikan bahwa pengaturan email gateway yang anda gunakan sudah benar.</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"></div>
                                <div class="col-md-8 text-right" id="NotifikasiSimpanSettingEmail">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="bi bi-save"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <form action="javascript:void(0);" id="ProsesTestKirimEmail">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                <i class="bi bi-airplane"></i> Uji Coba Kirim Email
                            </b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="nama_tujuan">Dikirim Kepada</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="nama_tujuan" id="nama_tujuan" class="form-control" placeholder="Contoh : Bapak Syamsul Maarif">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="email_tujuan">Email Tujuan</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" name="email_tujuan" id="email_tujuan" class="form-control" placeholder="email@domain.com">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="subjek">Subjek Pesan</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="subjek" id="subjek" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="pesan">Isi Pesan</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea name="pesan" id="pesan" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">Log Proses</div>
                                <div class="col-md-8 scroll_div_200 border-1" >
                                    <small class="credit" id="NotifikasiTestKirimEmail">
                                        <code class="text text-grayish">Belum Ada Proses Pengiriman</code>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="bi bi-send"></i> Kirim Pesan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php } ?>