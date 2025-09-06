<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAccess,'TIVPffUE3kqw288OB1R0CJ09daM9l2TLdGVv');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        if(empty($_GET['Sub'])){
            include "_Page/Pembayaran/PembayaranHome.php";
        }else{
            $Sub=$_GET['Sub'];
            if($Sub=="Detail"){
                include "_Page/Pembayaran/DetailPembayaran.php";
            }else{
                if($Sub=="Tambah"){
                    include "_Page/Pembayaran/TambahPembayaran.php";
                }
            }
        }
    }
?>