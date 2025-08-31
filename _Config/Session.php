<?php
    //Menangkap seasson kemudian menampilkannya
    session_start();

    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Jika Session id_access_login Tidak ADa
    if(empty($_SESSION["id_access"])){
        $SessionIdAccess="";
        $SessionLoginToken="";
    }else{

        //Jika login_token tidak ada
        if(empty($_SESSION["login_token"])){
            $SessionIdAccess="";
            $SessionLoginToken="";
        }else{

            //Membuat Variabel
            $SessionIdAccess=validateAndSanitizeInput($_SESSION ["id_access"]);
            $SessionLoginToken=validateAndSanitizeInput($_SESSION ["login_token"]);
            
            //Validasi Token Akses
            $QryAksesLogin = mysqli_query($Conn,"SELECT * FROM access_login WHERE id_access='$SessionIdAccess' AND token='$SessionLoginToken'")or die(mysqli_error($Conn));
            $DataAksesLogin = mysqli_fetch_array($QryAksesLogin);
            
            //Apabila id_access_login tidak ditemukan
            if(empty($DataAksesLogin['id_access_login'])){
                $SessionIdAccess="";
                $SessionLoginToken="";
            }else{
                
                //Apabila Ada Buka SessionDateCreat
                $SessionDateCreat=$DataAksesLogin['datetime_creat'];
                    
                //Validasi Apakah Token Masih Berlaku Atau Tidak
                $SessionDateExpired=$DataAksesLogin['datetime_expired'];
                $DateSekarang=date('Y-m-d H:i:s');

                //Jika Sudah Expired
                if($SessionDateExpired<$DateSekarang){
                    $SessionIdAccess="";
                    $SessionLoginToken="";
                }else{
                    $SessionIdAccess=$DataAksesLogin['id_access'];
                    $expired_milisecond=1000*60*60;
                    $now=date('Y-m-d H:i:s');
                    $date_expired_new=calculateExpirationTimeFromDateTime($now, $expired_milisecond);
                    
                    //Update Token Yang Ada
                    $UpdateToken = mysqli_query($Conn,"UPDATE access_login SET 
                        datetime_expired='$date_expired_new'
                    WHERE id_access='$SessionIdAccess'") or die(mysqli_error($Conn)); 
                    if($UpdateToken){
                        $SessionIdAccess=$DataAksesLogin['id_access'];
                        $SessionLoginToken=$DataAksesLogin['token'];
                    }else{
                        $SessionIdAccess="";
                        $SessionLoginToken="";
                    }
                }
            }
        }
    }
?>
