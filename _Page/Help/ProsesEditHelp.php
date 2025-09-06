<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    //Validasi Akses Login
    if(empty($SessionIdAkses)){
        echo '<span class="text-danger">Sesi Akses Sudah Berakhir, Silahkkan Login Ulang</span>';
    }else{
        $now=date('Y-m-d H:i:s');
        //Tangkap data
        if(empty($_POST['judul'])){
            echo '<span class="text-danger">Judul Konten Bantuan Tidak Boleh Kosong!</span>';
        }else{
            if(empty($_POST['kategori'])){
                echo '<span class="text-danger">Kategori Bantuan Tidak Boleh Kosong</span>';
            }else{
                if(empty($_POST['status'])){
                    echo '<span class="text-danger">Status Bantuan Tidak Boleh Kosong</span>';
                }else{
                    if(empty($_POST['deskripsi'])){
                        echo '<span class="text-danger">Isi Konten Bantuan Tidak Boleh Kosong</span>';
                    }else{
                        if(empty($_POST['id_help'])){
                            echo '<span class="text-danger">Isi Konten Bantuan Tidak Boleh Kosong</span>';
                        }else{
                            //Buat Variabel
                            $judul=$_POST['judul'];
                            $kategori=$_POST['kategori'];
                            $status=$_POST['status'];
                            $deskripsi=$_POST['deskripsi'];
                            $id_help=$_POST['id_help'];
                            //Bersihkan Variabel
                            $judul=validateAndSanitizeInput($judul);
                            $kategori=validateAndSanitizeInput($kategori);
                            $status=validateAndSanitizeInput($status);
                            $deskripsi=validateAndSanitizeInput($deskripsi);
                            $id_help=validateAndSanitizeInput($id_help);
                            $author=GetDetailData($Conn,'akses','id_akses',$SessionIdAkses,'nama_akses');
                            $datetime_creat=date('Y-m-d H:i:s');
                            $datetime_update=date('Y-m-d H:i:s');
                            //Simpan data
                            $Update = mysqli_query($Conn,"UPDATE help SET 
                                judul='$judul',
                                kategori='$kategori',
                                deskripsi='$deskripsi',
                                datetime_update='$datetime_update',
                                status='$status'
                            WHERE id_help='$id_help'") or die(mysqli_error($Conn)); 
                            if($Update){
                                //Apabila Berhasil, Simpan Log
                                $kategori_log="Bantuan";
                                $deskripsi_log="Edit Konten Bantuan";
                                $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
                                if($InputLog=="Success"){
                                    $_SESSION ["NotifikasiSwal"]="Simpan Help Berhasil";
                                    echo '<small class="text-success" id="NotifikasiEditHelpBerhasil">Success</small>';
                                }else{
                                    echo '<code class="text-danger">Terjadi kesalahan pada saat menyimpan Log</code>';
                                }
                            }else{
                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data pada database</small>';
                            }
                        }
                    }
                }
            }
        }
    }
?>