<?php
    // Koneksi dan konfigurasi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan waktu dan zona waktu
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    if (empty($SessionIdAccess)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
    } else {
        if (empty($_POST['nama'])) {
            echo '<small class="text-danger">Nama tidak boleh kosong</small>';
        } elseif (empty($_POST['kontak'])) {
            echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
        } elseif (empty($_POST['email'])) {
            echo '<small class="text-danger">Email tidak boleh kosong</small>';
        } else {
            $nama = $_POST['nama'];
            $kontak = $_POST['kontak'];
            $email = $_POST['email'];
            $JumlahKarakterKontak = strlen($kontak);

            if ($JumlahKarakterKontak > 20 || $JumlahKarakterKontak < 6 || !preg_match("/^[0-9]*$/", $kontak)) {
                echo '<small class="text-danger">Kontak hanya boleh terdiri dari 6-20 karakter numerik</small>';
            } else {
                $nama = validateAndSanitizeInput($nama);
                $kontak = validateAndSanitizeInput($kontak);
                $email = validateAndSanitizeInput($email);

                // Ambil kontak lama
                $kontak_lama = GetDetailData($Conn, 'access', 'id_access', $SessionIdAccess, 'access_contact');
                
                // Cek duplikasi kontak
                $ValidasiKontakDuplikat = 0;
                if ($kontak !== $kontak_lama) {
                    $ValidasiKontakDuplikat = mysqli_num_rows(mysqli_query($Conn, "SELECT id_access FROM access WHERE access_contact='$kontak'"));
                }

                if (!empty($ValidasiKontakDuplikat)) {
                    echo '<small class="text-danger">Nomor kontak sudah terdaftar</small>';
                } else {
                    
                    //Ambil Email Lama
                    $email_lama = GetDetailData($Conn, 'access', 'id_access', $SessionIdAccess, 'access_email');
                    

                    // Cek duplikasi email
                    $ValidasiEmailDuplikat = 0;
                    if ($email !== $email_lama) {
                        $ValidasiEmailDuplikat = mysqli_num_rows(mysqli_query($Conn, "SELECT id_access FROM access WHERE access_email='$email'"));
                    }
                   

                    if (!empty($ValidasiEmailDuplikat)) {
                        echo '<small class="text-danger">Email yang anda gunakan sudah terdaftar</small>';
                    } else {

                        //Update Data Ke Database
                        try {
                            $UpdateProfil = mysqli_query($Conn,"UPDATE access SET 
                                access_name='$nama',
                                access_contact='$kontak',
                                access_email='$email'
                            WHERE id_access='$SessionIdAccess'") or die(mysqli_error($Conn)); 
                        
                            if ($UpdateProfil) {
                                $_SESSION["NotifikasiSwal"] = "Edit Akses Berhasil";
                                echo '<small class="text-success" id="NotifikasiUbahIdentitasProfilBerhasil">Success</small>';
                            } else {
                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                            }
                        } catch (PDOException $e) {
                            echo '<small class="text-danger">Error: ' . $e->getMessage() . '</small>';
                        }
                    }
                }
            }
        }
    }
?>
