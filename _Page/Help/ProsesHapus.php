<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAkses)){
        echo '<small class="credit text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
    }else{
        if(empty($_POST['id_help'])){
            echo '<small class="credit text-danger">ID bantuan Tidak Boleh Kosong!</small>';
        }else{
            $id_help=$_POST['id_help'];
            //Bersihkan Variabel
            $id_help=validateAndSanitizeInput($id_help);
            //Validasi Keberadaan Data
            $id_help=GetDetailData($Conn,'help','id_help',$id_help,'id_help');
            if(empty($id_help)){
                echo '<small class="credit text-danger">ID Bantuan Yang Anda Gunakan Tidak Valid!</small>';
            }else{
                //Proses hapus data
                $query = mysqli_query($Conn, "DELETE FROM help WHERE id_help='$id_help'") or die(mysqli_error($Conn));
                if ($query) {
                    //Apabila Berhasil, Simpan Log
                    $kategori_log="Bantuan";
                    $deskripsi_log="Hapus Konten Bantuan";
                    $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
                    if($InputLog=="Success"){
                        echo '<small class="credit text-success" id="NotifikasiHapusBerhasil">Success</small>';
                    }else{
                        echo '<small class="credit text-danger">Terjadi kesalahan pada saat menyimpan Log</small>';
                    }
                    
                }else{
                    echo '<small class="credit text-danger">Clear Data Fail</small>';
                }
            }
        }
    }
?>