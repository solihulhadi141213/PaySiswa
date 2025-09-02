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

    //Buat variabel
    $id_access=validateAndSanitizeInput($_POST['id_access']);

    //Buka data foto
    $access_foto=GetDetailData($Conn,'access','id_access',$id_access,'access_foto');
    
    //Proses hapus data
    $HapusAkses = mysqli_query($Conn, "DELETE FROM access WHERE id_access='$id_access'") or die(mysqli_error($Conn));
    if ($HapusAkses) {

        //Jika Ada File Foto
        if(!empty($access_foto)){

            //Tentukan Path Foto
            $file = '../../assets/img/User/'.$access_foto.'';

            //Jika File ADa
            if (file_exists($file)) {
                if (unlink($file)) {
                    echo '<span class="text-success" id="NotifikasiHapusAksesBerhasil">Success</span>';
                } else {
                    echo '
                        <div class="alert alert-danger">
                            <small>
                                Terjadi kesalahan pada saat menghapus file!
                            </small>
                        </div>
                    ';
                }
            }else{
                echo '<span class="text-success" id="NotifikasiHapusAksesBerhasil">Success</span>';
            }
        }else{
            echo '<span class="text-success" id="NotifikasiHapusAksesBerhasil">Success</span>';
        }       
    }else{

        //Jika menghapus gagal
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat menghapus data!
                </small>
            </div>
        ';
    }
?>