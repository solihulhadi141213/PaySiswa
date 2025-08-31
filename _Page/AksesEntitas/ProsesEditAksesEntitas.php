<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $now=date('Y-m-d H:i:s');
    //Validasi uuid_akses_entitas tidak boleh kosong
    if(empty($_POST['uuid_akses_entitas'])){
        echo '<code class="text-danger">ID Akses Entitias Tidak Boleh Kosong</code>';
    }else{
        //Validasi akses tidak boleh kosong
        if(empty($_POST['akses'])){
            echo '<code class="text-danger">Nama Entitas Akses Tidak Boleh Kosong</code>';
        }else{
            //Validasi keterangan tidak boleh kosong
            if(empty($_POST['keterangan'])){
                echo '<code class="text-danger">Setidaknya anda harus menulis keterangan mengenai entitias yang anda buat</code>';
            }else{
                //Validasi class_label tidak boleh kosong
                if(empty($_POST['rules'])){
                    echo '<code class="text-danger">Fitur Entitas Tidak Boleh Kosong</code>';
                }else{
                    //Validasi nama akses tidak boleh lebih dari 20 karakter
                    $JumlahKarakterTitle=strlen($_POST['akses']);
                    if($JumlahKarakterTitle>20){
                        echo '<code class="text-danger">Nama Entitas Akses Tidak Boleh Lebih Dari 20 Karakter</code>';
                    }else{
                        $uuid_akses_entitas=$_POST['uuid_akses_entitas'];
                        $akses=$_POST['akses'];
                        $keterangan=$_POST['keterangan'];
                        $standar_fitur=$_POST['rules'];
                        //Bersihkan Variabel
                        $uuid_akses_entitas=validateAndSanitizeInput($uuid_akses_entitas);
                        $akses=validateAndSanitizeInput($akses);
                        $keterangan=validateAndSanitizeInput($keterangan);
                        $jumlah_standar_fitur=count($standar_fitur);
                        if(empty($jumlah_standar_fitur)){
                            echo '<code class="text-danger">Ijin Akses Entitas Tidak Boleh Kosong</code>';
                        }else{
                            //Simpan Ke database akses_entitias
                            $UpdateEntitias = mysqli_query($Conn,"UPDATE akses_entitas SET 
                                akses='$akses',
                                keterangan='$keterangan'
                            WHERE uuid_akses_entitas='$uuid_akses_entitas'") or die(mysqli_error($Conn)); 
                            if($UpdateEntitias){
                                //Hapus Semua referensi
                                $HapusReferensi = mysqli_query($Conn, "DELETE FROM akses_referensi WHERE uuid_akses_entitas='$uuid_akses_entitas'") or die(mysqli_error($Conn));
                                if($HapusReferensi){
                                    //Input ke akses_referensi
                                    $JumlahYangBerhasil =0;
                                    foreach($standar_fitur as $id_akses_fitur) {
                                        //Buka Kode Fitur
                                        $kode=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kode');
                                        $nama=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'nama');
                                        $kategori=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kategori');
                                        //Simpan Ke Database
                                        $entry2="INSERT INTO akses_referensi (
                                            uuid_akses_entitas,
                                            id_akses_fitur
                                        ) VALUES (
                                            '$uuid_akses_entitas',
                                            '$id_akses_fitur'
                                        )";
                                        $Input2=mysqli_query($Conn, $entry2);
                                        if($Input2){
                                            $JumlahYangBerhasil=$JumlahYangBerhasil+1;
                                        }else{
                                            $JumlahYangBerhasil=$JumlahYangBerhasil+0;
                                        }
                                    }
                                    if($JumlahYangBerhasil==$jumlah_standar_fitur){
                                        $kategori_log="Entitas Akses";
                                        $deskripsi_log="Edit Entitas Akses";
                                        $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
                                        if($InputLog=="Success"){
                                            echo '<small class="text-success" id="NotifikasiEditAksesEntitasBerhasil">Success</small>';
                                        }else{
                                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan Log</small>';
                                        }
                                    }else{
                                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data referensi</small>';
                                    }
                                }else{
                                    echo '<small class="text-danger">Terjadi kesalahan pada saat menghapus data referensi entitias</small>';
                                }
                            }else{
                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data entitias</small>';
                            }
                        }
                    }
                }
            }
        }
    }
?>