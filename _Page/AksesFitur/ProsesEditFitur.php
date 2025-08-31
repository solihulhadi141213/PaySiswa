<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
    }else{

        //Validasi nama tidak boleh kosong
        if(empty($_POST['nama'])){
            echo '
                <div class="alert alert-danger">
                    <small>Nama Feature tidak boleh kosong!</small>
                </div>
            ';
        }else{
        
            //Validasi kategori tidak boleh kosong
            if(empty($_POST['kategori'])){
                 echo '
                    <div class="alert alert-danger">
                        <small>Kategori Feature tidak boleh kosong!</small>
                    </div>
                ';
            }else{
                
                //Validasi id_access_feature tidak boleh kosong
                if(empty($_POST['id_access_feature'])){
                    echo '
                        <div class="alert alert-danger">
                            <small>Id Feature tidak boleh kosong!</small>
                        </div>
                    ';
                }else{
                    
                    //Validasi keterangan tidak boleh kosong
                    if(empty($_POST['keterangan'])){
                        echo '
                            <div class="alert alert-danger">
                                <small>Keterangan Feature tidak boleh kosong!</small>
                            </div>
                        ';
                    }else{
                        
                        // Validasi nama tidak lebih dari 100 karakter
                        if (strlen($_POST['nama']) > 100) {
                            echo '
                                <div class="alert alert-danger">
                                    <small>Nama fitur tidak boleh lebih dari 100 karakter</small>
                                </div>
                            ';
                        }else{

                            // Validasi kategori tidak lebih dari 50 karakter
                            if(strlen($_POST['kategori']) > 50){
                                echo '
                                    <div class="alert alert-danger">
                                        <small>Kategori fitur tidak boleh lebih dari 50 karakter</small>
                                    </div>
                                ';
                            }else{
                                
                                // Validasi keterangan tidak lebih dari 500 karakter
                                if(strlen($_POST['keterangan']) > 500){
                                    echo '
                                        <div class="alert alert-danger">
                                            <small>Keterangan fitur tidak boleh lebih dari 500 karakter</small>
                                        </div>
                                    ';
                                }else{
                                    //Validasi kode tidak boleh duplikat
                                    $id_access_feature = validateAndSanitizeInput($_POST['id_access_feature']);
                                    $nama = validateAndSanitizeInput($_POST['nama']);
                                    $kategori = validateAndSanitizeInput($_POST['kategori']);
                                    $keterangan = validateAndSanitizeInput($_POST['keterangan']);

                                    //Buka Data Lama
                                    $NamaFitur=GetDetailData($Conn,'access_feature','id_access_feature',$id_access_feature,'feature_name');

                                    //Validasi Duplikat feature_name
                                    if($nama==$NamaFitur){
                                        $ValidasiNamaDuplikat=0;
                                    }else{
                                        $ValidasiNamaDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT id_access_feature FROM access_feature WHERE feature_name='$nama'"));
                                    }
                                    if(!empty($ValidasiNamaDuplikat)){
                                       echo '
                                            <div class="alert alert-danger">
                                                <small>Nama Feature Tersebut Sudah Terdaftar!</small>
                                            </div>
                                        ';
                                    }else{
                                        $UpdateFiturAkses = mysqli_query($Conn,"UPDATE access_feature SET 
                                            feature_name='$nama',
                                            feature_category='$kategori',
                                            feature_description='$keterangan'
                                        WHERE id_access_feature='$id_access_feature'") or die(mysqli_error($Conn)); 
                                        if($UpdateFiturAkses){
                                            echo '<code class="text-success" id="NotifikasiEditFiturBerhasil">Success</code>';
                                        }else{
                                            echo '<code class="text-danger">Terjadi kesalahan pada saat update fitur</code>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>