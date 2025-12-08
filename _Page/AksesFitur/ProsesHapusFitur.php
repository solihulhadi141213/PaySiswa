<?php
    //Connection
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Tanggal/Waktu Sekarang
    $now=date('Y-m-d H:i:s');

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
    }else{
        //Validasi 'id_access_feature'
        if(empty($_POST['id_access_feature'])){
            echo '
                <div class="alert alert-danger">
                    <small>ID Feature tidak dapat ditangkap oleh sistem!</small>
                </div>
            ';
        }else{

            //Buat Variabel
            $id_access_feature=$_POST['id_access_feature'];
            
            //Proses hapus data akses_fitur
            $HapusAksesFitur = mysqli_query($Conn, "DELETE FROM access_feature WHERE id_access_feature='$id_access_feature'") or die(mysqli_error($Conn));
            if ($HapusAksesFitur) {

                //Jika Berhasil Simpan Log
                $kategori_log="Fitur Akses";
                $deskripsi_log="Hapus Fitur Akses";
                $InputLog=addLog($Conn,$SessionIdAccess,$now,$kategori_log,$deskripsi_log);
                if($InputLog=="Success"){
                    echo '
                        <div class="alert alert-success">
                            <code class="text-success" id="NotifikasiHapusFiturBerhasil">Success</code>
                        </div>
                    ';
                }else{
                    echo '
                        <div class="alert alert-danger">
                            <small>Terjadi kesalahan pada saat menyimpan Log!</small>
                        </div>
                    ';
                }
            }else{
                echo '
                    <div class="alert alert-danger">
                        <small>Proses Hapus Data Feature Gagal!</small>
                    </div>
                ';
            }
        }
    }
?>