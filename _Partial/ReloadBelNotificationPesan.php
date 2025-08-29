<?php
    //Karena Ini Di running Dengan JS maka Panggil Ulang Koneksi
    include "../_Config/Connection.php";
    include "../_Config/GlobalFunction.php";
    //Menghitung Testimoni Pending
    $JumlahNotifikasi=0;
    //Apabila ada notifgikasi
    if(!empty($JumlahNotifikasi)){
        echo '<i class="bi bi-chat-left-text text-light"></i>';
        echo '<span class="badge bg-success badge-number">'.$JumlahNotifikasi.'</span>';
    }else{
        echo '<i class="bi bi-chat-left-text text-light"></i>';
    }
?>