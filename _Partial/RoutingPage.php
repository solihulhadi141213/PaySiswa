<?php
    if(empty($_GET['Page'])){
        include "_Page/Dashboard/Dashboard.php";
    }else{
        $Page=$_GET['Page'];
        //Index Halaman
        $page_arry=[
            "MyProfile"         =>  "_Page/MyProfile/MyProfile.php",
            "AksesFitur"        =>  "_Page/AksesFitur/AksesFitur.php",
            "AksesEntitas"      =>  "_Page/AksesEntitas/AksesEntitas.php",
            "Akses"             =>  "_Page/Akses/Akses.php",
            "TahunAjaran"       =>  "_Page/TahunAjaran/TahunAjaran.php",
            "Kelas"             =>  "_Page/Kelas/Kelas.php",
            "Siswa"             =>  "_Page/Siswa/Siswa.php",
            "KomponenBiaya"     =>  "_Page/KomponenBiaya/KomponenBiaya.php",
            "Tagihan"           =>  "_Page/Tagihan/Tagihan.php",
            "Pembayaran"        =>  "_Page/Pembayaran/Pembayaran.php",
            "SettingGeneral"    =>  "_Page/SettingGeneral/SettingGeneral.php",
            "PaymentGateway"    =>  "_Page/SettingPayment/SettingPayment.php",
            "SettingEmail"      =>  "_Page/SettingEmail/SettingEmail.php",
            "Aktivitas"         =>  "_Page/Aktivitas/Aktivitas.php",
            "Help"              =>  "_Page/Help/Help.php",
        ];

        //Tangkap 'Page'
        $Page = !empty($_GET['Page']) ? $_GET['Page'] : "";

        //Kondisi Pada masing-masing Page
        if (array_key_exists($Page, $page_arry)) { 
            include $page_arry[$Page]; 
        } else { 
            include "_Page/Error/PageNotFound.php";
        }
    }
?>