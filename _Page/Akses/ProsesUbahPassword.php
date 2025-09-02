<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');

    //Time Now Tmp
    $now=date('Y-m-d H:i:s');

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }

    //Tangkap id_access
    if(empty($_POST['id_access'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Access Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Tangkap password1
    if(empty($_POST['password1'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    Password Baru Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Validasi password1 sama dengan password2
    if($_POST['password1']!==$_POST['password2']){
         echo '
            <div class="alert alert-danger">
                <small>
                    Password Baru Yang Anda Masukan Tidak Sama!
                </small>
            </div>
        ';
        exit;
    }

    //Sanitasi Variabel
    $id_access=validateAndSanitizeInput($_POST['id_access']);
    $password1=validateAndSanitizeInput($_POST['password1']);
    $password2=validateAndSanitizeInput($_POST['password2']);

    // Validasi panjang password
    if(strlen($password1) < 6 || strlen($password1) > 20 || !preg_match("/^[a-zA-Z0-9]*$/", $password1)){
        echo '<div class="alert alert-danger"><small>Password harus 6-20 karakter huruf/angka!</small></div>';
        exit;
    }

    // Hash password
    $password = password_hash($password1, PASSWORD_DEFAULT);
            
    $UpdateAkses = mysqli_query($Conn,"UPDATE access SET 
        access_password='$password'
    WHERE id_access='$id_access'") or die(mysqli_error($Conn)); 
    if($UpdateAkses){
        echo '<small class="text-success" id="NotifikasiUbahPasswordBerhasil">Success</small>';
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat update password!</small></div>';
    }
?>