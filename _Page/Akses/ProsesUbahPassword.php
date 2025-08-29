<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Harus Login Terlebih Dulu
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
    }else{
        if(empty($_POST['id_akses'])){
            echo '<small class="text-danger">ID Akses tidak boleh kosong</small>';
        }else{
            $id_akses=$_POST['id_akses'];
            $id_akses=validateAndSanitizeInput($id_akses);
            //Buka data askes
            $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
            $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
            $kontak_akses_lama= $DataDetailAkses['kontak_akses'];
            $email_akses_lama = $DataDetailAkses['email_akses'];
            //Validasi Password tidak boleh kosong
            if(empty($_POST['password1'])){
                echo '<small class="text-danger">Password tidak boleh kosong</small>';
            }else{
                if($_POST['password1']!==$_POST['password2']){
                    echo '<small class="text-danger">Password tidak sama</small>';
                }else{
                    //Validasi jumlah dan jenis karakter password
                    $JumlahKarakterPassword=strlen($_POST['password1']);
                    if($JumlahKarakterPassword>20||$JumlahKarakterPassword<6||!preg_match("/^[a-zA-Z0-9]*$/", $_POST['password1'])){
                        echo '<small class="text-danger">Password hanya boleh terdiri dari 6-20 karakter numerik dan huruf</small>';
                    }else{
                        $password1=$_POST['password1'];
                        $password1=validateAndSanitizeInput($password1);
                        //md5
                        $password1=MD5($password1);                           
                        $UpdateAkses = mysqli_query($Conn,"UPDATE akses SET 
                            password='$password1'
                        WHERE id_akses='$id_akses'") or die(mysqli_error($Conn)); 
                        if($UpdateAkses){
                            echo '<small class="text-success" id="NotifikasiUbahPasswordBerhasil">Success</small>';
                        }else{
                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                        }
                    }
                }
            }
        }
    }
?>