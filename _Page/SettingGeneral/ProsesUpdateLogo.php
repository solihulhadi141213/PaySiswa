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
    if(empty($_FILES['app_logo']['name'])){
        echo '<div class="alert alert-danger"><small>Belum ada file yang dipilih!</small></div>';
        exit;
    }

    //Tangkap Data
    $nama_gambar_logo=$_FILES['app_logo']['name'];
    $ukuran_gambar_logo = $_FILES['app_logo']['size']; 
    $tipe_gambar_logo = $_FILES['app_logo']['type']; 
    $tmp_gambar_logo = $_FILES['app_logo']['tmp_name'];

    //Timestamp Dan Nama Baru
    $timestamp_logo = strval(time()-strtotime('1970-01-01 00:00:00'));
    $key=implode('', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
    $FileNameRand=$key;
    $Pecah = explode("." , $nama_gambar_logo);
    $BiasanyaNama=$Pecah[0];
    $Ext=$Pecah[1];
    $namabarulogo = "$FileNameRand.$Ext";

    //Tentukan Path Penyimpanan
    $path_logo = "../../assets/img/".$namabarulogo;

    //Validasi tipe File
    if($tipe_gambar_logo=="image/jpeg"||$tipe_gambar_logo=="image/jpg"||$tipe_gambar_logo=="image/gif"||$tipe_gambar_logo=="image/png"){

        //Validasi File Size
        if($ukuran_gambar_logo<2000000){

            //Upload File
            if(move_uploaded_file($tmp_gambar_logo, $path_logo)){

                //Update Setting
                $UpdateSettingGeneral = mysqli_query($Conn,"UPDATE  app_configuration  SET 
                    app_logo='$namabarulogo'
                WHERE id_configuration='1'") or die(mysqli_error($Conn)); 
                if($UpdateSettingGeneral){
                     $_SESSION ["NotifikasiSwal"]="Simpan Setting General Berhasil";
                    echo '<small class="text-success" id="NotifikasiUpdateLogoBerhasil">Success</small>';
                }else{
                    echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat update nama file Logo</small></div>';
                }
            }else{
                echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat upload file Logo</small></div>';
            }
        }else{
            echo '<div class="alert alert-danger"><small>Ukuran Logo maksimal 2 Mb</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Tipe file Logo hanya boleh JPG, JPEG, PNG and GIF</small></div>';
    }
?>