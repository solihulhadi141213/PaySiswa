<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        if(empty($_GET['Sub'])){
            include "_Page/Siswa/SiswaHome.php";
        }else{
            $Sub=$_GET['Sub'];
            if($Sub=="Detail"){
                include "_Page/Siswa/DetailSiswa.php";
            }
        }
    }
?>