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

    //Sanitasi Variabel
    $id_access=$_POST['id_access'];
    $id_access=validateAndSanitizeInput($id_access);

    //Buka File Lama
    $ImageLama=GetDetailData($Conn,'access','id_access',$id_access,'access_foto');

    //Validasi File Tidak Boleh Kosong
    if(empty($_FILES['image_akses']['name'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    File Foto tidak boleh kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $nama_gambar=$_FILES['image_akses']['name'];
    $ukuran_gambar = $_FILES['image_akses']['size']; 
    $tipe_gambar = $_FILES['image_akses']['type']; 
    $tmp_gambar = $_FILES['image_akses']['tmp_name'];

    //Buat Nama Baru
    $timestamp = strval(time()-strtotime('1970-01-01 00:00:00'));
    $key=implode('', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
    $FileNameRand=$key;
    $Pecah = explode("." , $nama_gambar);
    $BiasanyaNama=$Pecah[0];
    $Ext=$Pecah[1];
    $namabaru = "$FileNameRand.$Ext";

    //Path File
    $path = "../../assets/img/User/".$namabaru;

    //Validasi Tipe File
    if($tipe_gambar == "image/jpeg"||$tipe_gambar == "image/jpg"||$tipe_gambar == "image/gif"||$tipe_gambar == "image/png"){
        if($ukuran_gambar<2000000){
            if(move_uploaded_file($tmp_gambar, $path)){
                $UpdateAkses = mysqli_query($Conn,"UPDATE access SET 
                    access_foto='$namabaru'
                WHERE id_access='$id_access'") or die(mysqli_error($Conn)); 
                if($UpdateAkses){
                    if(!empty($ImageLama)){
                        $file = '../../assets/img/User/'.$ImageLama.'';
                        if (file_exists($file)) {
                            if (unlink($file)) {
                                echo '<small class="text-success" id="NotifikasiUbahFotoAksesBerhasil">Success</small>';
                            } else {
                                echo '
                                    <div class="alert alert-danger">
                                        <small>Terjadi kesalahan pada saat menghapus foto lama</small>
                                    </div>
                                ';
                            }
                        }else{
                            echo '<small class="text-success" id="NotifikasiUbahFotoAksesBerhasil">Success</small>';
                        }
                    }else{
                        echo '
                            <div class="alert alert-success">
                                <small class="text-success" id="NotifikasiUbahFotoAksesBerhasil">Success</small>
                            </div>
                        ';
                    }
                }else{
                    echo '
                        <div class="alert alert-danger">
                            <small class="text-danger">Terjadi kesalahan pada saat menyimpan data akses</small>
                        </div>
                    ';
                }
            }else{
                echo '
                    <div class="alert alert-danger">
                        <small class="text-danger">Upload file gambar gagal</small>
                    </div>
                ';
            }
        }else{
            echo '
                <div class="alert alert-danger">
                    <small class="text-danger">File gambar tidak boleh lebih dari 2 mb</small>
                </div>
            ';
        }
    }else{
        echo '
            <div class="alert alert-danger">
                <small class="text-danger">Tipe file hanya boleh JPG, JPEG, PNG and GIF</small>
            </div>
        ';
    }
?>