<?php
    // Konfigurasi awal
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Validasi sesi login
    if (empty($SessionIdAccess)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
        exit;
    }

    // Validasi form input
    if (empty($_POST['password1'])) {
        echo '<small class="text-danger">Password tidak boleh kosong</small>';
    } elseif ($_POST['password1'] !== $_POST['password2']) {
        echo '<small class="text-danger">Password tidak sama</small>';
    } else {
        $password1 = $_POST['password1'];
        $JumlahKarakterPassword = strlen($password1);

        // Validasi panjang dan karakter
        if (
            $JumlahKarakterPassword < 6 ||
            $JumlahKarakterPassword > 20 ||
            !preg_match("/^[a-zA-Z0-9]*$/", $password1)
        ) {
            echo '<small class="text-danger">Password hanya boleh terdiri dari 6-20 karakter huruf dan angka</small>';
        } else {
            $password1 = validateAndSanitizeInput($password1);

            // Enkripsi password (bisa upgrade ke password_hash())
            $passwordHash = password_hash($password1, PASSWORD_DEFAULT);

            // Update password
            $UpdatePassword = mysqli_query($Conn,"UPDATE access SET 
                access_password='$passwordHash'
            WHERE id_access='$SessionIdAccess'") or die(mysqli_error($Conn)); 
            if ($UpdatePassword) {
                $_SESSION["NotifikasiSwal"] = "Ubah Password Berhasil";
                echo '<small class="text-success" id="NotifikasiUbahPasswordProfilBerhasil">Success</small>';
            } else {
                echo '<small class="text-danger">Terjadi kesalahan saat menyimpan data</small>';
            }
        }
    }
?>
