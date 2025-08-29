<div class="card-body">
    <form action="javascript:void(0);" class="row g-3" id="ProsesLogin">
        <div class="row">
            <div class="col-md-8 text-center mb-3">
                <div class="row mt-3 mb-3">
                    <div class="col-12 text-center">
                        <a href="" class="text-secondary">
                            <h1 class="judul_aplikasi"><?php echo $app_title;?></h1>
                        </a>
                        <small class="text-muted"><?php echo $app_description;?></small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <img src="assets/img/online-payment.webp" alt="<?php echo $app_title;?>" width="100%">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row mt-3">
                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
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
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">
                                <i class="bi bi-key"></i>
                            </span>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <small class="credit">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Tampilkan" id="TampilkanPassword2" name="TampilkanPassword2">
                                <label class="form-check-label" for="TampilkanPassword2">
                                    Tampilkan Password
                                </label>
                            </div>
                        </small>
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
                        Pastikan email dan password sudah benar.
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12" id="NotifikasiLogin"></div>
                    <div class="col-12">
                        <button class="btn btn-lg btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                        <p class="small mb-0">Anda Lupa Password? <a href="Login.php?Page=LupaPassword">Reset password</a></p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>