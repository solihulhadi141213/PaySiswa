<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            //Koneksi
            session_start();
            include "_Config/Connection.php";
            include "_Config/GlobalFunction.php";
            include "_Config/SettingGeneral.php";
            include "_Partial/Head.php";
        ?>
    </head>
    <body>
        <main class="landing_background">
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12">
                            <div class="card mb-3 p-3">
                                <?php
                                    if(empty($_GET['Page'])){
                                        include "_Page/Login/Login.php";
                                    }else{
                                        $Page=$_GET['Page'];
                                        if($Page=="LupaPassword"){
                                            include "_Page/Login/FormLupaPassword.php";
                                        }else{
                                            if($Page=="ResetPassword"){
                                                include "_Page/Login/FormResetPassword.php";
                                            }
                                        }
                                    }
                                ?>
                            </div>
                            <div class="credits text-center">
                                <small>
                                    <div class="copyright text-white">
                                        &copy; Copyright <strong><span><?php echo "$app_title"; ?></span></strong>. All Rights Reserved <?php echo "$app_year"; ?>
                                    </div>
                                    <div class="credits text-white">
                                        Designed by <span class="text text-decoration-underline"><?php echo "$app_author"; ?></span>
                                    </div>
                                </small>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    </main>
        <?php
            include "_Partial/FooterJs.php";
        ?>
        <script>

            //Fungsi Reload Captcha
            function reloadCaptcha() {
                document.getElementById('captchaImage').src = '_Page/Login/Captcha.php?' + new Date().getTime();
            }
            //Kondisi saat tampilkan password
            $('#TampilkanPassword2').click(function(){
                if($(this).is(':checked')){
                    $('#password').attr('type','text');
                }else{
                    $('#password').attr('type','password');
                }
            });

            //Submit Login
            $('#ProsesLogin').submit(function(){
                var ProsesLogin = $('#ProsesLogin').serialize();
                var Loading='<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>';
                $('#TombolLogin').html(Loading);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Login/ProsesLogin.php',
                    data 	    :  ProsesLogin,
                    dataType    : 'json',
                    success     : function(response){
                        $('#TombolLogin').html('Login');
                        if (response.status === 'success') {
                            // Redirect jika login berhasil
                            window.location.href = 'index.php';
                        } else {
                            // Tampilkan notifikasi error jika gagal
                            $('#NotifikasiLogin').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    }
                });
            });

            //Kondisi saat tampilkan password
            $('.form-check-input').click(function(){
                if($(this).is(':checked')){
                    $('#password_baru1').attr('type','text');
                    $('#password_baru2').attr('type','text');
                }else{
                    $('#password_baru1').attr('type','password');
                    $('#password_baru2').attr('type','password');
                }
            });

            //Proses Lupa Password
            $('#ProsesLupaPasword').submit(function(){

                //Tangkap Data Dari Form
                var ProsesLupaPasword = $('#ProsesLupaPasword').serialize();
                
                //Loading Tombol
                $('#button_lupa_password').html('<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Login/ProsesLupaPasword.php',
                    data 	    :  ProsesLupaPasword,
                    dataType    : 'json',
                    success     : function(response){
                        $('#button_lupa_password').html('Login');

                        //Kondisi Jik Berhasil
                        if (response.status === 'success') {
                            $('#FormLupaPasword').load('_Page/Login/NotifikasiResetPasswordBerhasil.php');
                        } else {
                            // Kondisi Jika gagal
                            $('#NotifikasiLupaPasword').html('<div class="alert alert-danger"><small>' + response.message + '</small></div>');
                        }

                        //Kembalikan Kndisi Tombol
                        $('#button_lupa_password').html('<i class="bi bi-arrow-clockwise"></i> Reset Password');
                    }
                });
            });

             //Proses Proses Reset Password
            $('#ProsesResetPassword').submit(function(){

                //Tangkap Data Dari Form
                var ProsesResetPassword = $('#ProsesResetPassword').serialize();
                
                //Loading Tombol
                $('#button_reset_password').html('<div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Login/ProsesResetPassword.php',
                    data 	    :  ProsesResetPassword,
                    dataType    : 'json',
                    success     : function(response){
                        $('#button_reset_password').html('Login');

                        //Kondisi Jik Berhasil
                        if (response.status === 'success') {
                            $('#FormResetPasword').load('_Page/Login/NotifikasiUbahPasswordBerhasil.php');
                        } else {
                            // Kondisi Jika gagal
                            $('#NotifikasiresetPasword').html('<div class="alert alert-danger"><small>' + response.message + '</small></div>');
                        }

                        //Kembalikan Kndisi Tombol
                        $('#button_reset_password').html('<i class="bi bi-arrow-clockwise"></i> Reset Password');
                    }
                });
            });

            // Jalankan reloadCaptcha setiap 1 menit (60.000 ms)
            setInterval(reloadCaptcha, 60000); // 60000 ms = 1 menit
        </script>
    </body>
</html>