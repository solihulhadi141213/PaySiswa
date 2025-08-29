<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
    }else{
        //Validasi nama tidak boleh kosong
        if(empty($_POST['nama_akses'])){
            echo '<small class="text-danger">Nama tidak boleh kosong</small>';
        }else{
            //Validasi kontak tidak boleh kosong
            if(empty($_POST['kontak_akses'])){
                echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
            }else{
                //Validasi kontak tidak boleh lebih dari 20 karakter
                $JumlahKarakterKontak=strlen($_POST['kontak_akses']);
                if($JumlahKarakterKontak>20||$JumlahKarakterKontak<6||!preg_match("/^[0-9]*$/", $_POST['kontak_akses'])){
                    echo '<small class="text-danger">Kontak hanya boleh terdiri dari 6-20 karakter numerik</small>';
                }else{
                    //Validasi kontak tidak boleh duplikat
                    $id_akses=$SessionIdAkses;
                    $id_akses=validateAndSanitizeInput($id_akses);
                    $kontak_akses=$_POST['kontak_akses'];
                    $kontak_akses_lama=GetDetailData($Conn,'akses','id_akses',$id_akses,'kontak_akses');
                    if($kontak_akses_lama==$kontak_akses){
                        $ValidasiKontakDuplikat=0;
                    }else{
                        $ValidasiKontakDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses WHERE kontak_akses='$kontak_akses'"));
                    }
                    if(!empty($ValidasiKontakDuplikat)){
                        echo '<small class="text-danger">Nomor kontak sudah terdaftar</small>';
                    }else{
                        //Validasi email tidak boleh kosong
                        if(empty($_POST['email_akses'])){
                            echo '<small class="text-danger">Email tidak boleh kosong</small>';
                        }else{
                            //Validasi email duplikat
                            $email_akses=$_POST['email_akses'];
                            $email_akses=validateAndSanitizeInput($email_akses);
                            $email_akses_lama=GetDetailData($Conn,'akses','id_akses',$id_akses,'email_akses');
                            if($email_akses_lama==$email_akses){
                                $ValidasiEmailDuplikat=0;
                            }else{
                                $ValidasiEmailDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses WHERE email_akses='$email_akses'"));
                            }
                            if(!empty($ValidasiEmailDuplikat)){
                                echo '<small class="text-danger">Email yang anda gunakan sudah terdaftar</small>';
                            }else{
                                //Variabel Lainnya
                                $id_akses=$SessionIdAkses;
                                $nama_akses=$_POST['nama_akses'];
                                $kontak_akses=$_POST['kontak_akses'];
                                $email_akses=$_POST['email_akses'];
                                //Membersihkan Variabel
                                $id_akses=validateAndSanitizeInput($id_akses);
                                $nama_akses=validateAndSanitizeInput($nama_akses);
                                $kontak_akses=validateAndSanitizeInput($kontak_akses);
                                $email_akses=validateAndSanitizeInput($email_akses);
                                $UpdateAkses = mysqli_query($Conn,"UPDATE akses SET 
                                    nama_akses='$nama_akses',
                                    kontak_akses='$kontak_akses',
                                    email_akses='$email_akses',
                                    datetime_update='$now'
                                WHERE id_akses='$id_akses'") or die(mysqli_error($Conn)); 
                                if($UpdateAkses){
                                    $_SESSION ["NotifikasiSwal"]="Edit Akses Berhasil";
                                    echo '<small class="text-success" id="NotifikasiUbahIdentitasProfilBerhasil">Success</small>';
                                }else{
                                    echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>