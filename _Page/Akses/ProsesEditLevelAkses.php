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
            //Validasi akses tidak boleh kosong
            if(empty($_POST['akses'])){
                echo '<small class="text-danger">Level Akses tidak boleh kosong</small>';
            }else{
                //Variabel Lainnya
                $id_akses=$_POST['id_akses'];
                $akses=$_POST['akses'];
                //Bersihkan Variabel
                $id_akses=validateAndSanitizeInput($id_akses);
                $akses=validateAndSanitizeInput($akses);
                //Update Level Akses
                $UpdateAkses = mysqli_query($Conn,"UPDATE akses SET 
                    akses='$akses'
                WHERE id_akses='$id_akses'") or die(mysqli_error($Conn)); 
                if($UpdateAkses){
                    //Menghapus Role Lama
                    $HapusRoleAkses = mysqli_query($Conn, "DELETE FROM akses_ijin WHERE id_akses='$id_akses'") or die(mysqli_error($Conn));
                    if($HapusRoleAkses){
                        //Uiid Entitias
                        $uuid_akses_entitas=GetDetailData($Conn,'akses_entitas','akses',$akses,'uuid_akses_entitas');
                        $JumlahRole =mysqli_num_rows(mysqli_query($Conn, "SELECT id_akses_referensi FROM akses_referensi WHERE uuid_akses_entitas='$uuid_akses_entitas'"));
                        //Loop Referensi Berdasarkan Uiid Entitias
                        $JumlahRoleValid=0;
                        $query = mysqli_query($Conn, "SELECT*FROM akses_referensi WHERE uuid_akses_entitas='$uuid_akses_entitas'");
                        while ($data = mysqli_fetch_array($query)) {
                            $id_akses_fitur= $data['id_akses_fitur'];
                            $KodeFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kode');
                            $NamaFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'nama');
                            $kategoriFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kategori');
                            if(empty($KodeFitur)){
                                $JumlahRoleValid=$JumlahRoleValid+0;
                            }else{
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
                                    '$kategoriFitur'
                                )";
                                $InputIjinAkses=mysqli_query($Conn, $EntryIjinAkses);
                                if($InputIjinAkses){
                                    $JumlahRoleValid=$JumlahRoleValid+1;
                                }else{
                                    $JumlahRoleValid=$JumlahRoleValid+0;
                                }
                            }
                        }
                        if($JumlahRoleValid==$JumlahRole){
                            echo '<small class="text-success" id="NotifikasiEditLevelAksesBerhasil">Success</small>';
                        }else{
                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data ijin akses</small>';
                        }
                    }else{
                        echo '<small class="text-danger">Terjadi kesalahan pada saat menghapus ijin akses</small>';
                    }
                }
            }
        }
    }
?>