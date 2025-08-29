<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
    }else{
        //Tangkap data
        if(empty($_POST['title_page'])){
            echo '<span class="text-danger">Judul/Nama Perusahaan Tidak Boleh Kosong</span>';
        }else{
            if(empty($_POST['kata_kunci'])){
                echo '<span class="text-danger">Kata kunci tidak boleh kosong!</span>';
            }else{
                if(empty($_POST['deskripsi'])){
                    echo '<span class="text-danger">Setidaknya anda harus mengisi deskripsi aplikasi ini!</span>';
                }else{
                    if(empty($_POST['alamat_bisnis'])){
                        echo '<span class="text-danger">Alamat perusahaan tidak boleh kosong!</span>';
                    }else{
                        if(empty($_POST['email_bisnis'])){
                            echo '<span class="text-danger">Alamat email perusahaan tidak boleh kosong!</span>';
                        }else{
                            if(empty($_POST['telepon_bisnis'])){
                                echo '<span class="text-danger">Nomor kontak perusahaan tidak boleh kosong!</span>';
                            }else{
                                if(empty($_POST['base_url'])){
                                    echo '<span class="text-danger">Base URL tidak boleh kosong!</span>';
                                }else{
                                    if(empty($_POST['author'])){
                                        echo '<span class="text-danger">Author Aplikasi tidak boleh kosong!</span>';
                                    }else{
                                        $title_page=$_POST['title_page'];
                                        $kata_kunci=$_POST['kata_kunci'];
                                        $deskripsi=$_POST['deskripsi'];
                                        $alamat_bisnis=$_POST['alamat_bisnis'];
                                        $email_bisnis=$_POST['email_bisnis'];
                                        $telepon_bisnis=$_POST['telepon_bisnis'];
                                        $base_url=$_POST['base_url'];
                                        $author=$_POST['author'];
                                        if(!empty($_FILES['favicon']['name'])){
                                            $nama_gambar_favicon=$_FILES['favicon']['name'];
                                            $ukuran_gambar_favicon = $_FILES['favicon']['size']; 
                                            $tipe_gambar_favicon = $_FILES['favicon']['type']; 
                                            $tmp_gambar_favicon = $_FILES['favicon']['tmp_name'];
                                            $timestamp_favicon = strval(time()-strtotime('1970-01-01 00:00:00'));
                                            $key=implode('', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
                                            $FileNameRand=$key;
                                            $Pecah = explode("." , $nama_gambar_favicon);
                                            $BiasanyaNama=$Pecah[0];
                                            $Ext=$Pecah[1];
                                            $namabarufavicon = "$FileNameRand.$Ext";
                                            $path_favicon = "../../assets/img/".$namabarufavicon;
                                            if($tipe_gambar_favicon=="image/jpeg"||$tipe_gambar_favicon=="image/jpg"||$tipe_gambar_favicon=="image/gif"||$tipe_gambar_favicon=="image/png"){
                                                if($ukuran_gambar_favicon<2000000){
                                                    if(move_uploaded_file($tmp_gambar_favicon, $path_favicon)){
                                                        $ValidasiGambarFavicon="Valid";
                                                        $UpdateSettingGeneral = mysqli_query($Conn,"UPDATE setting_general SET 
                                                            favicon='$namabarufavicon'
                                                        WHERE id_setting_general='1'") or die(mysqli_error($Conn)); 
                                                        if($UpdateSettingGeneral){
                                                            $ValidasiGambarFavicon="Valid";
                                                        }else{
                                                            $ValidasiGambarFavicon="Terjadi kesalahan pada saat update nama file Favicon";
                                                        }
                                                    }else{
                                                        $ValidasiGambarFavicon="Terjadi kesalahan pada saat upload file Favicon";
                                                    }
                                                }else{
                                                    $ValidasiGambarFavicon="Ukuran Favicon maksimal 2 Mb";
                                                }
                                            }else{
                                                $ValidasiGambarFavicon="Tipe file Favicon hanya boleh JPG, JPEG, PNG and GIF";
                                            }
                                        }else{
                                            $ValidasiGambarFavicon="Valid";
                                        }
                                        if(!empty($_FILES['logo']['name'])){
                                            $nama_gambar_logo=$_FILES['logo']['name'];
                                            $ukuran_gambar_logo = $_FILES['logo']['size']; 
                                            $tipe_gambar_logo = $_FILES['logo']['type']; 
                                            $tmp_gambar_logo = $_FILES['logo']['tmp_name'];
                                            $timestamp_logo = strval(time()-strtotime('1970-01-01 00:00:00'));
                                            $key=implode('', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
                                            $FileNameRand=$key;
                                            $Pecah = explode("." , $nama_gambar_logo);
                                            $BiasanyaNama=$Pecah[0];
                                            $Ext=$Pecah[1];
                                            $namabarulogo = "$FileNameRand.$Ext";
                                            $path_logo = "../../assets/img/".$namabarulogo;
                                            if($tipe_gambar_logo=="image/jpeg"||$tipe_gambar_logo=="image/jpg"||$tipe_gambar_logo=="image/gif"||$tipe_gambar_logo=="image/png"){
                                                if($ukuran_gambar_logo<2000000){
                                                    if(move_uploaded_file($tmp_gambar_logo, $path_logo)){
                                                        $ValidasiGambarLogo="Valid";
                                                        $UpdateSettingGeneral = mysqli_query($Conn,"UPDATE setting_general SET 
                                                            logo='$namabarulogo'
                                                        WHERE id_setting_general='1'") or die(mysqli_error($Conn)); 
                                                        if($UpdateSettingGeneral){
                                                            $ValidasiGambarLogo="Valid";
                                                        }else{
                                                            $ValidasiGambarLogo="Terjadi kesalahan pada saat update nama file logo";
                                                        }
                                                    }else{
                                                        $ValidasiGambarLogo="Terjadi kesalahan pada saat upload file logo";
                                                    }
                                                }else{
                                                    $ValidasiGambarLogo="Ukuran file logo tidak boleh lebih dari 3 mb";
                                                }
                                            }else{
                                                $ValidasiGambarLogo="Tipe file logo hanya boleh JPG, JPEG, PNG and GIF";
                                            }
                                        }else{
                                            $ValidasiGambarLogo="Valid";
                                        }

                                        if($ValidasiGambarFavicon!=="Valid"){
                                            echo '<span class="text-danger">'.$ValidasiGambarFavicon.'</span>';
                                        }else{
                                            if($ValidasiGambarLogo!=="Valid"){
                                                echo '<span class="text-danger">'.$ValidasiGambarLogo.'</span>';
                                            }else{
                                                $UpdateSetting = mysqli_query($Conn,"UPDATE setting_general SET 
                                                    title_page='$title_page',
                                                    kata_kunci='$kata_kunci',
                                                    deskripsi='$deskripsi',
                                                    alamat_bisnis='$alamat_bisnis',
                                                    email_bisnis='$email_bisnis',
                                                    telepon_bisnis='$telepon_bisnis',
                                                    base_url='$base_url',
                                                    author='$author'
                                                WHERE id_setting_general='1'") or die(mysqli_error($Conn)); 
                                                if($UpdateSetting){
                                                    $_SESSION ["NotifikasiSwal"]="Simpan Setting General Berhasil";
                                                    echo '<small class="text-success" id="NotifikasiSimpanSettingGeneralBerhasil">Success</small>';
                                                }else{
                                                    echo '<small class="text-danger">Terjadi kesalahan pada saat update data pengaturan</small>';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>