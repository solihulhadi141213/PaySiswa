<?php
    include "_Page/Logout/ModalLogout.php";
    if(!empty($_GET['Page'])){
        $Page=$_GET['Page'];
        
        // Daftar halaman dan modal yang terkait
        $modals = [
            "MyProfile"             => "_Page/MyProfile/ModalMyProfile.php",
            "AksesFitur"            => "_Page/AksesFitur/ModalAksesFitur.php",
            "AksesEntitas"          => "_Page/AksesEntitas/ModalAksesEntitas.php",
            "Akses"                 => "_Page/Akses/ModalAkses.php",
            "TahunAjaran"           => "_Page/TahunAjaran/ModalTahunAjaran.php",
            "Kelas"                 => "_Page/Kelas/ModalKelas.php",
            "Siswa"                 => "_Page/Siswa/ModalSiswa.php",
            "KomponenBiaya"         => "_Page/KomponenBiaya/ModalKomponenBiaya.php",
            "Tagihan"               => "_Page/Tagihan/ModalTagihan.php",
            "Pembayaran"            => "_Page/Pembayaran/ModalPembayaran.php",
            "PaymentGateway"        => "_Page/SettingPayment/ModalSettingPayment.php",
            "SettingEmail"          => "_Page/SettingEmail/ModalSettingEmail.php",
            "Aktivitas"             => "_Page/Aktivitas/ModalAktivitas.php",
            "Help"                  => "_Page/Help/ModalHelp.php"
        ];

        // Cek apakah halaman memiliki modal terkait dan sertakan file modalnya
        if (!empty($_GET['Page']) && isset($modals[$_GET['Page']])) {
            include $modals[$_GET['Page']];
        }
    }
?>