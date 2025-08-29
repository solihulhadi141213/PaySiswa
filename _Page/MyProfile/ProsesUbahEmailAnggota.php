<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($SessionIdAkses)){
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
    }else{
        //Validasi nama tidak boleh kosong
        if(empty($_POST['email'])){
            echo '<small class="text-danger">Email tidak boleh kosong</small>';
        }else{
            $email=$_POST['email'];
            $email=validateAndSanitizeInput($email);
            $EmailLama=GetDetailData($Conn,'anggota','id_anggota',$SessionIdAkses,'email');
            if($EmailLama==$email){
                $ValidasiEmailDuplikat=0;
            }else{
                $ValidasiEmailDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM anggota WHERE email='$email'"));
            }
            if(!empty($ValidasiEmailDuplikat)){
                echo '<small class="text-danger">Email yang anda gunakan sudah terdaftar</small>';
            }else{
                $UpdateAnggota = mysqli_query($Conn,"UPDATE anggota SET 
                    email='$email'
                WHERE id_anggota='$SessionIdAkses'") or die(mysqli_error($Conn)); 
                if($UpdateAnggota){
                    $_SESSION ["NotifikasiSwal"]="Edit Email Anggota Berhasil";
                    echo '<small class="text-success" id="NotifikasiUbahEmailAnggotaBerhasil">Success</small>';
                }else{
                    echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                }
            }
        }
    }
?>