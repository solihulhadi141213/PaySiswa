<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Time Now Tmp
    $now=date('Y-m-d H:i:s');

    // --- Validasi singkat ---
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }
    if(empty($_FILES['app_favicon']['name'])){
        echo '<div class="alert alert-danger"><small>Belum ada file yang dipilih!</small></div>';
        exit;
    }

    //Tangkap Data
    $nama_gambar_favicon=$_FILES['app_favicon']['name'];
    $ukuran_gambar_favicon = $_FILES['app_favicon']['size']; 
    $tipe_gambar_favicon = $_FILES['app_favicon']['type']; 
    $tmp_gambar_favicon = $_FILES['app_favicon']['tmp_name'];

    //Timestamp Dan Nama Baru
    $timestamp_favicon = strval(time()-strtotime('1970-01-01 00:00:00'));
    $key=implode('', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
    $FileNameRand=$key;
    $Pecah = explode("." , $nama_gambar_favicon);
    $BiasanyaNama=$Pecah[0];
    $Ext=$Pecah[1];
    $namabarufavicon = "$FileNameRand.$Ext";

    //Tentukan Path Penyimpanan
    $path_favicon = "../../assets/img/".$namabarufavicon;

    //Validasi tipe File
    if($tipe_gambar_favicon=="image/jpeg"||$tipe_gambar_favicon=="image/jpg"||$tipe_gambar_favicon=="image/gif"||$tipe_gambar_favicon=="image/png"){

        //Validasi File Size
        if($ukuran_gambar_favicon<2000000){

            //Upload File
            if(move_uploaded_file($tmp_gambar_favicon, $path_favicon)){

                //Update Setting
                $UpdateSettingGeneral = mysqli_query($Conn,"UPDATE  app_configuration  SET 
                    app_favicon='$namabarufavicon'
                WHERE id_configuration='1'") or die(mysqli_error($Conn)); 
                if($UpdateSettingGeneral){
                     $_SESSION ["NotifikasiSwal"]="Simpan Setting General Berhasil";
                    echo '<small class="text-success" id="NotifikasiUpdateFaviconBerhasil">Success</small>';
                }else{
                    echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat update nama file Favicon</small></div>';
                }
            }else{
                echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat upload file Favicon</small></div>';
            }
        }else{
            echo '<div class="alert alert-danger"><small>Ukuran Favicon maksimal 2 Mb</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Tipe file Favicon hanya boleh JPG, JPEG, PNG and GIF</small></div>';
    }
?>