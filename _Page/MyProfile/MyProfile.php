<?php
    if($SessionModeAkses=="Anggota"){
        $Sub="";
        include "_Page/MyProfile/DetailProfileAnggota.php";
    }else{
        $Sub="";
        include "_Page/MyProfile/DetailProfile.php";
    }
?>