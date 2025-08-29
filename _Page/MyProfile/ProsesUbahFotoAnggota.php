<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    //Validasi nama tidak boleh kosong
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Login Sudah Berakhir, Silahkan Login Ulang</small>';
    }else{
        $ImageLama=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'foto');
        if(empty($_FILES['foto']['name'])){
            echo '<small class="text-danger">File Foto tidak boleh kosong</small>';
        }else{
            //nama gambar
            $nama_gambar=$_FILES['foto']['name'];
            $ukuran_gambar = $_FILES['foto']['size']; 
            $tipe_gambar = $_FILES['foto']['type']; 
            $tmp_gambar = $_FILES['foto']['tmp_name'];
            $timestamp = strval(time()-strtotime('1970-01-01 00:00:00'));
            $key=implode('', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
            $FileNameRand=$key;
            $Pecah = explode("." , $nama_gambar);
            $BiasanyaNama=$Pecah[0];
            $Ext=$Pecah[1];
            $namabaru = "$FileNameRand.$Ext";
            $path = "../../assets/img/Anggota/".$namabaru;
            if($tipe_gambar == "image/jpeg"||$tipe_gambar == "image/jpg"||$tipe_gambar == "image/gif"||$tipe_gambar == "image/png"){
                if($ukuran_gambar<2000000){
                    if(move_uploaded_file($tmp_gambar, $path)){
                        $UpdateAnggota = mysqli_query($Conn,"UPDATE anggota SET 
                            foto='$namabaru'
                        WHERE id_anggota='$SessionIdAkses'") or die(mysqli_error($Conn)); 
                        if($UpdateAnggota){
                            if(!empty($ImageLama)){
                                $file = '../../assets/img/Anggota/'.$ImageLama.'';
                                if (file_exists($file)) {
                                    if (unlink($file)) {
                                        $_SESSION ["NotifikasiSwal"]="Ubah Foto Profil Berhasil";
                                        echo '<small class="text-success" id="NotifikasiUbahFotoAnggotaBerhasil">Success</small>';
                                    } else {
                                        echo '<span class="text-danger">Terjadi kesalahan pada saat menghapus foto lama</span>';
                                    }
                                }else{
                                    $_SESSION ["NotifikasiSwal"]="Ubah Foto Profil Berhasil";
                                    echo '<small class="text-success" id="NotifikasiUbahFotoAnggotaBerhasil">Success</small>';
                                }
                            }else{
                                $_SESSION ["NotifikasiSwal"]="Ubah Foto Profil Berhasil";
                                echo '<small class="text-success" id="NotifikasiUbahFotoAnggotaBerhasil">Success</small>';
                            }
                        }else{
                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data akses</small>';
                        }
                    }else{
                        echo '<small class="text-danger">Upload file gambar gagal</small>';
                    }
                }else{
                    echo '<small class="text-danger">File gambar tidak boleh lebih dari 2 mb</small>';
                }
            }else{
                echo '<small class="text-danger">Tipe file hanya boleh JPG, JPEG, PNG and GIF</small>';
            }
        }
    }
?>