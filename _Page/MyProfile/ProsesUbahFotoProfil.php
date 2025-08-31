<?php
    // Koneksi dan konfigurasi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    if (empty($SessionIdAccess)) {
        echo '<small class="text-danger">Sesi Login Sudah Berakhir, Silahkan Login Ulang</small>';
    } else {
        $ImageLama = GetDetailData($Conn, 'access', 'id_access', $SessionIdAccess, 'access_foto');
        

        if (empty($_FILES['image_akses']['name'])) {
            echo '<small class="text-danger">File Foto tidak boleh kosong</small>';
        } else {
            $nama_gambar = $_FILES['image_akses']['name'];
            $ukuran_gambar = $_FILES['image_akses']['size'];
            $tipe_gambar = $_FILES['image_akses']['type'];
            $tmp_gambar = $_FILES['image_akses']['tmp_name'];

            // Generate nama file acak
            $key = implode('', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
            $ext = pathinfo($nama_gambar, PATHINFO_EXTENSION);
            $namabaru = $key . "." . $ext;
            $path = "../../assets/img/User/" . $namabaru;

            // Validasi tipe file
            $allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
            if (!in_array($tipe_gambar, $allowedTypes)) {
                echo '<small class="text-danger">Tipe file hanya boleh JPG, JPEG, PNG, dan GIF</small>';
                exit;
            }

            // Validasi ukuran file
            if ($ukuran_gambar > 2000000) {
                echo '<small class="text-danger">File gambar tidak boleh lebih dari 2 MB</small>';
                exit;
            }

            // Proses upload file
            if (!move_uploaded_file($tmp_gambar, $path)) {
                echo '<small class="text-danger">Upload file gambar gagal</small>';
                exit;
            }

            // Simpan ke database dengan PDO
            $UpdateFoto = mysqli_query($Conn,"UPDATE access SET access_foto='$namabaru' WHERE id_access='$SessionIdAccess'") or die(mysqli_error($Conn)); 
                
            if($UpdateFoto){
                // Hapus gambar lama jika ada
                if (!empty($ImageLama)) {
                    $fileLama = "../../assets/img/User/" . $ImageLama;
                    if (file_exists($fileLama)) {
                        if (!unlink($fileLama)) {
                            echo '<span class="text-danger">Gagal menghapus foto lama</span>';
                            exit;
                        }
                    }
                }

                $_SESSION["NotifikasiSwal"] = "Ubah Foto Profil Berhasil";
                echo '<small class="text-success" id="NotifikasiUbahFotoProfilBerhasil">Success</small>';
            } else {
                echo '<small class="text-danger">Terjadi kesalahan saat menyimpan ke database</small>';
            }
        }
    }
?>
