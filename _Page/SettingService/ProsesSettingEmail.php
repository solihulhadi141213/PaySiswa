<?php
    //koneksi dan session
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    $now=date('Y-m-d H:i:s');
    if(empty($SessionIdAkses)){
        echo '<span class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</span>';
    }else{
        //Validasi Eksistensi variabel
        if(empty($_POST['url_provider'])){
            $url_provider="";
        }else{
            $url_provider=$_POST['url_provider'];
        }
        if(empty($_POST['port_gateway'])){
            $port_gateway="";
        }else{
            $port_gateway=$_POST['port_gateway'];
        }
        if(empty($_POST['email_gateway'])){
            $email_gateway="";
        }else{
            $email_gateway=$_POST['email_gateway'];
        }
        if(empty($_POST['password_gateway'])){
            $password_gateway="";
        }else{
            $password_gateway=$_POST['password_gateway'];
        }
        if(empty($_POST['nama_pengirim'])){
            $nama_pengirim="";
        }else{
            $nama_pengirim=$_POST['nama_pengirim'];
        }
        if(empty($_POST['url_service'])){
            $url_service="";
        }else{
            $url_service=$_POST['url_service'];
        }
        $Update= mysqli_query($Conn,"UPDATE setting_email_gateway SET 
            email_gateway='$email_gateway',
            password_gateway='$password_gateway',
            url_provider='$url_provider',
            port_gateway='$port_gateway',
            nama_pengirim='$nama_pengirim',
            url_service='$url_service'
        WHERE id_setting_email_gateway='1'") or die(mysqli_error($Conn)); 
        if($Update){
            $kategori_log="Setting";
            $deskripsi_log="Setting Email";
            $InputLog=addLog($Conn,$SessionIdAkses,$now,$kategori_log,$deskripsi_log);
            if($InputLog=="Success"){
                $_SESSION ["NotifikasiSwal"]="Simpan Setting Email Berhasil";
                echo '<span class="text-success" id="NotifikasiSimpanSettingEmailBerhasil">Success</span>';
            }else{
                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan Log</small>';
            }
        }else{
            echo '<span class="text-danger">Save Parallel Whatsapp integration settings Failed</span>';
        }
    }
?>