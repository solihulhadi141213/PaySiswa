<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($_POST['id_akses'])){
        echo '<code class="text-danger">ID Akses Tidak Boleh Kosong!</code>';
    }else{
        if(empty($_POST['rules'])){
            echo '<code class="text-danger">Ijin Akses Tidak Boleh Kosong! Setidaknya yang bersangkutan memiliki 1 fitur yang bisa diakses.</code>';
        }else{
            $id_akses=$_POST['id_akses'];
            $rules=$_POST['rules'];
            //Bersihkan Variabel
            $id_akses=validateAndSanitizeInput($id_akses);
            $JumlahFitur=count($rules);
            if(empty($JumlahFitur)){
                echo '<code class="text-danger">Ijin Akses Tidak Boleh Kosong! Setidaknya yang bersangkutan memiliki 1 fitur yang bisa diakses.</code>';
            }else{
                //Hapus Akses lama
                $HapusIjinAksesLama = mysqli_query($Conn, "DELETE FROM akses_ijin WHERE id_akses='$id_akses'") or die(mysqli_error($Conn));
                if($HapusIjinAksesLama){
                    //Melakukan Looping Berdasarkan Rules Yang Di Pilih
                    $JumlahRoleValid =0;
                    foreach($rules as $id_akses_fitur) {
                        $KodeFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kode');
                        $NamaFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'nama');
                        $KategoriFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kategori');
                        if(!empty($KodeFitur)){
                            $EntryIjinAkses="INSERT INTO akses_ijin (
                                id_akses,
                                id_akses_fitur,
                                kode,
                                nama,
                                kategori
                            ) VALUES (
                                '$id_akses',
                                '$id_akses_fitur',
                                '$KodeFitur',
                                '$NamaFitur',
                                '$KategoriFitur'
                            )";
                            $InputIjinAkses=mysqli_query($Conn, $EntryIjinAkses);
                            if($InputIjinAkses){
                                $JumlahRoleValid=$JumlahRoleValid+1;
                            }else{
                                $JumlahRoleValid=$JumlahRoleValid+0;
                            }
                        }
                    }
                    if($JumlahRoleValid==$JumlahFitur){
                        echo '<small class="text-success" id="NotifikasiUbahIzinAksesBerhasil">Success</small>';
                    }else{
                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan ijin akses</small>';
                    }
                }else{
                    echo '<small class="text-danger">Terjadi kesalahan pada saat menghapus ijin akses lama</small>';
                }
            }
        }
    }
?>