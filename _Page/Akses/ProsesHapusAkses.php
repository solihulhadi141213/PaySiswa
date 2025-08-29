<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
    }else{
        //Validasi id_akses tidak boleh kosong
        if(empty($_POST['id_akses'])){
            echo '<small class="text-danger">ID Akses tidak boleh kosong</small>';
        }else{
            //Variabel Lainnya
            $id_akses=$_POST['id_akses'];
            //Bersihkan Variabel
            $id_akses=validateAndSanitizeInput($id_akses);
            $image_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'image_akses');
            //Proses hapus data
            $HapusAkses = mysqli_query($Conn, "DELETE FROM akses WHERE id_akses='$id_akses'") or die(mysqli_error($Conn));
            if ($HapusAkses) {
                $HhapusIzinAkses = mysqli_query($Conn, "DELETE FROM akses_ijin WHERE id_akses='$id_akses'") or die(mysqli_error($Conn));
                if ($HhapusIzinAkses) {
                    if(!empty($image_akses)){
                        $file = '../../assets/img/User/'.$image_akses.'';
                        if (file_exists($file)) {
                            if (unlink($file)) {
                                echo '<span class="text-success" id="NotifikasiHapusAksesBerhasil">Success</span>';
                            } else {
                                echo '<span class="text-danger">Terjadi kesalahan pada saat menghapus file</span>';
                            }
                        }else{
                            echo '<span class="text-success" id="NotifikasiHapusAksesBerhasil">Success</span>';
                        }
                    }else{
                        echo '<span class="text-success" id="NotifikasiHapusAksesBerhasil">Success</span>';
                    }
                    
                }else{
                    echo '<span class="text-danger">Hapus Data Gagal</span>';
                }
            }else{
                echo '<span class="text-danger">Hapus Data Gagal</span>';
            }
        }
    }
?>