<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingEmail.php";
    if(empty($SessionIdAkses)){
        echo '<code class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</code>';
    }else{
        //Tangkap Data
        if(empty($_POST['nama_tujuan'])){
            echo '<code class="text-danger">Name cannot be empty!</code>';
        }else{
            if(empty($_POST['email_tujuan'])){
                echo '<code class="text-danger">Email address cannot be empty!</code>';
            }else{
                if(empty($_POST['subjek'])){
                    echo '<code class="text-danger">Subject cannot be empty!</code>';
                }else{
                    if(empty($_POST['pesan'])){
                        echo '<code class="text-danger">Message cannot be empty!</code>';
                    }else{
                        $nama_tujuan=$_POST['nama_tujuan'];
                        $email_tujuan=$_POST['email_tujuan'];
                        $subjek=$_POST['subjek'];
                        $pesan=$_POST['pesan'];
                        //Bersihkan Variabel
                        $nama_tujuan=validateAndSanitizeInput($nama_tujuan);
                        $email_tujuan=validateAndSanitizeInput($email_tujuan);
                        $subjek=validateAndSanitizeInput($subjek);
                        $pesan=validateAndSanitizeInput($pesan);
                        //Kirim email
                        $ch = curl_init();
                        $headers = array(
                            'Content-Type: Application/JSON',          
                            'Accept: Application/JSON'     
                        );
                        $arr = array(
                            "subjek" => "$subjek",
                            "email_asal" => "$email_gateway",
                            "password_email_asal" => "$password_gateway",
                            "url_provider" => "$url_provider",
                            "nama_pengirim" => "$nama_pengirim",
                            "email_tujuan" => "$email_tujuan",
                            "nama_tujuan" => "$nama_tujuan",
                            "pesan" => "$pesan",
                            "port" => "$port_gateway"
                        );
                        $json = json_encode($arr);
                        curl_setopt($ch, CURLOPT_URL, "$url_service");
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 1000); 
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $content = curl_exec($ch);
                        $err = curl_error($ch);
                        curl_close($ch);
                        $get =json_decode($content, true);
                        if(empty($get['code'])){
                            $code="";
                        }else{
                            $code=$get['code'];
                        }
                        if(empty($get['pesan'])){
                            $pesan="";
                        }else{
                            $pesan=$get['code'];
                        }
                        echo '<code class="text text-grayish">'.$content.'</code>';
                    }
                }
            }
        }
    }
?>