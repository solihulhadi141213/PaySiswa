<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');

    // Validasi Sesi Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
    }else{
    
        // Validasi nama tidak boleh kosong dan tidak lebih dari 100 karakter
        if(empty($_POST['nama']) || strlen($_POST['nama']) > 100){
            echo '
                <div class="alert alert-danger">
                    <small>Nama fitur tidak boleh kosong dan harus kurang dari 100 karakter</small>
                </div>
            ';
        }else{

            // Validasi kategori tidak boleh kosong dan tidak lebih dari 50 karakter
            if(empty($_POST['kategori']) || strlen($_POST['kategori']) > 50){
                echo '
                    <div class="alert alert-danger">
                        <small>Kategori tidak boleh kosong dan harus kurang dari 50 karakter</small>
                    </div>
                ';
            }else{
                
                // Validasi keterangan tidak boleh kosong dan tidak lebih dari 500 karakter
                if(empty($_POST['keterangan']) || strlen($_POST['keterangan']) > 500){
                    echo '
                        <div class="alert alert-danger">
                            <small>Keterangan tidak boleh kosong dan harus kurang dari 500 karakter</small>
                        </div>
                    ';
                }else{
                    // Validasi kode tidak boleh duplikat
                    $nama=validateAndSanitizeInput($_POST['nama']);
                    $kategori=validateAndSanitizeInput($_POST['kategori']);
                    $keterangan=validateAndSanitizeInput($_POST['keterangan']);
                    
                    $ValidasiNamaDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM access_feature WHERE feature_name='$nama'"));
                    
                    //Validasi Nama Duplikat
                    if(!empty($ValidasiNamaDuplikat)){
                        echo '
                            <div class="alert alert-danger">
                                <small>Nama Fitur tersebut sudah terdaftar</small>
                            </div>
                        ';
                    }else{

                        //Membuat id_access_feature 
                        $id_access_feature =generateRandomString(36);

                        // Menggunakan Prepared Statement
                        $stmt = $Conn->prepare("INSERT INTO access_feature (id_access_feature, feature_name, feature_category, feature_description, datetime_creat) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssss", $id_access_feature, $nama, $kategori, $keterangan, $now);
                        $Input = $stmt->execute();
                        $stmt->close();

                        if($Input){
                            $kategori_log="Akses";
                            $deskripsi_log="Input Fitur Akses";
                            $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
                            if($InputLog=="Success"){
                                echo '<code class="text-success" id="NotifikasiTambahAksesFiturBerhasil">Success</code>';
                            }else{
                                echo '<code class="text-danger">Terjadi kesalahan pada saat menyimpan Log</code>';
                            }
                        }else{
                            echo '<code class="text-danger">Terjadi kesalahan pada saat menyimpan data</code>';
                        }
                    }
                }
            }
        }
    }
?>
