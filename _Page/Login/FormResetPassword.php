<?php
    //Tangkap Token Ada Atau Tidak
    if(empty($_GET['token'])){
        $token = "";
    }else{
        $token = $_GET['token'];
    }
?>
<form action="javascript:void(0);" class="row g-3" id="ProsesResetPassword">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <div class="card-body" id="FormResetPasword">
        <div class="row">
            <div class="col-md-12">
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <a href="" class="text-secondary">
                            <h1 class="judul_aplikasi">Buat Password Baru</h1>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <label for="password_baru1" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">
                                <i class="bi bi-key"></i>
                            </span>
                            <input type="password" name="password1" id="password_baru1" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <label for="password_baru2" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">
                                <i class="bi bi-key"></i>
                            </span>
                            <input type="password" name="password2" id="password_baru2" class="form-control" required>
                        </div>
                        <small class="credit">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Tampilkan" id="TampilkanPassword3" name="TampilkanPassword3">
                                <label class="form-check-label" for="TampilkanPassword3">
                                    Tampilkan Password
                                </label>
                            </div>
                        </small>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <small>Selanjutnya anda akan mendapatkan tautan untuk membuat pasword baru.</small>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12" id="NotifikasiresetPasword">
                        <!-- Notifikasi Reset Password Akan Muncul Disini -->
                    </div>
                    <div class="col-12">
                        <button class="btn btn-lg btn-primary w-100" type="submit" id="button_reset_password">
                            <i class="bi bi-save"></i> Simpan
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