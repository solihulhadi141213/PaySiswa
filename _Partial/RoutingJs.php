<?php 
    $date_version=date('YmdHis');
    if(empty($_GET['Page'])){
        //Dafault Javascript Diarahkan Ke Dashboard
        echo '<script type="text/javascript" src="_Page/Dashboard/Dashboard.js?V='.$date_version.'"></script>';
    }else{
        $Page=$_GET['Page'];
        // Routing Javascript Berdasarkan Halaman
        $scripts = [
            "MyProfile"         => "_Page/MyProfile/MyProfile.js",
            "AksesFitur"        => "_Page/AksesFitur/AksesFitur.js",
            "AksesEntitas"      => "_Page/AksesEntitas/AksesEntitas.js",
            "Akses"             => "_Page/Akses/Akses.js",
            "Kelas"             => "_Page/Kelas/Kelas.js",
            "Siswa"             => "_Page/Siswa/Siswa.js",
            "SettingGeneral"    => "_Page/SettingGeneral/SettingGeneral.js"
        ];

        // Cek apakah halaman ada dalam daftar dan sertakan file JS yang sesuai
        if (!empty($_GET['Page']) && isset($scripts[$_GET['Page']])) {
            echo '<script type="text/javascript" src="' . $scripts[$_GET['Page']] . '?V='.$date_version.'"></script>';
        }
    }
    echo '<script type="text/javascript" src="_Partial/Universal.js?V='.$date_version.'"></script>';
?>