<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'FGPgsQeVDKPGoQPNGJH');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        if(empty($_GET['Sub'])){
            include "_Page/Akses/AksesHome.php";
        }else{
            $Sub=$_GET['Sub'];
            if($Sub=="AturIjinAkses"){
                include "_Page/Akses/AturIjinAkses.php";
            }else{
                if($Sub=="DetailAkses"){
                    include "_Page/Akses/DetailAkses.php";
                }else{
                    include "_Page/Akses/AksesHome.php";
                }
            }
        }
    }
?>