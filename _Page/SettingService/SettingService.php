<?php
    $Page=$_GET['Page'];
    if($Page=="SettingWhatsapp"){
        include "_Page/SettingService/SettingWhatsapp.php";
    }else{
        if($Page=="SettingPayment"){
            include "_Page/SettingService/SettingPayment.php";
        }else{
            if($Page=="SettingEmail"){
                include "_Page/SettingService/SettingEmail.php";
            }else{
                
            }
        }
    }
?>