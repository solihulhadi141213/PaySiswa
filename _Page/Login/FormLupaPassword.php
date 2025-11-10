<form action="javascript:void(0);" class="row g-3" id="ProsesLupaPasword">
    <div class="card-body" id="FormLupaPasword">
        <div class="row">
            <div class="col-md-12">
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <a href="" class="text-secondary">
                            <h1 class="judul_aplikasi">Reset Password</h1>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <img src="_Page/Login/Captcha.php" class="mb-2" id="captchaImage" alt="No Image" width="100%" style="border: 1px solid #ddd; margin-right: 10px;"/>
                        <a href="javascript:void(0);" onclick="reloadCaptcha()" title="Buat kode captcha baru">
                            <small>
                                <i class="bi bi-repeat"></i> Muat ulang kode captcha
                            </small>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <small>
                            Masukan karakter <i>Captcha</i>
                        </small>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">
                                <i class="bi bi-shield-exclamation"></i>
                            </span>
                            <input type="text" name="captcha" class="form-control" id="captcha" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <small>Selanjutnya anda akan mendapatkan tautan untuk membuat pasword baru.</small>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12" id="NotifikasiLupaPasword">
                        <!-- Notifikasi Lupa Password Akan Muncul Disini -->
                    </div>
                    <div class="col-12">
                        <button class="btn btn-lg btn-primary w-100" type="submit" id="button_lupa_password">
                            <i class="bi bi-arrow-clockwise"></i> Reset Password  
                        </button>
                    </div>
                    <div class="col-12 text-center">
                        <p class="small mb-0"><a href="Login.php">Kembali Ke Halaman Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>