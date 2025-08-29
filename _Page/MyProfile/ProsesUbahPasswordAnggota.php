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
        $id_anggota=$SessionIdAkses;
        $id_anggota=validateAndSanitizeInput($id_anggota);
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
                    $UpdateAnggota = mysqli_query($Conn,"UPDATE anggota SET 
                        password='$password1'
                    WHERE id_anggota='$id_anggota'") or die(mysqli_error($Conn)); 
                    if($UpdateAnggota){
                        $_SESSION ["NotifikasiSwal"]="Ubah Password Berhasil";
                        echo '<small class="text-success" id="NotifikasiUbahPasswordAnggotaBerhasil">Success</small>';
                    }else{
                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                    }
                }
            }
        }
    }
?>