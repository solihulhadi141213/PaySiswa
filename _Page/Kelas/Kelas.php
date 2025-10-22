<?php
    if(empty($_GET['Sub'])){
        include "_Page/Kelas/KelasHome.php";
    }else{
        $Sub=$_GET['Sub'];
        if($Sub=="Detail"){
            include "_Page/Kelas/KelasDetail.php";
        }
    }
?>
