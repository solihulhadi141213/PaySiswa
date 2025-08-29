<?php 
    $date_version=date('YmdHis');
    if(empty($_GET['Page'])){
        //Dafault Javascript Diarahkan Ke Dashboard
        echo '<script type="text/javascript" src="_Page/Dashboard/Dashboard.js?V='.$date_version.'"></script>';
    }else{
        $Page=$_GET['Page'];
        // Routing Javascript Berdasarkan Halaman
        $scripts = [
            "MyProfile"                 => "_Page/MyProfile/MyProfile.js",
            "AksesFitur"                => "_Page/AksesFitur/AksesFitur.js",
            "AksesEntitas"              => "_Page/AksesEntitas/AksesEntitas.js",
            "Akses"                     => "_Page/Akses/Akses.js",
            "Anggota"                   => "_Page/Anggota/Anggota.js",
            "AnggotaKeluarMasuk"        => "_Page/AnggotaKeluarMasuk/AnggotaKeluarMasuk.js",
            "RekapAnggota"              => "_Page/RekapAnggota/RekapAnggota.js",
            "JenisSimpanan"             => "_Page/JenisSimpanan/JenisSimpanan.js",
            "SimpananWajib"             => "_Page/SimpananWajib/SimpananWajib.js",
            "Tabungan"                  => "_Page/Tabungan/Tabungan.js",
            "RekapSimpanan"             => "_Page/RekapSimpanan/RekapSimpanan.js",
            "JenisPinjaman"             => "_Page/JenisPinjaman/JenisPinjaman.js",
            "Pinjaman"                  => "_Page/Pinjaman/Pinjaman.js",
            "Tagihan"                   => "_Page/Tagihan/Tagihan.js",
            "RekapPinjaman"             => "_Page/RekapPinjaman/RekapPinjaman.js",
            "PotonganAnggota"           => "_Page/PotonganAnggota/PotonganAnggota.js",
            "Barang"                    => "_Page/Barang/Barang.js",
            "BarangExpired"             => "_Page/BarangExpired/BarangExpired.js",
            "StockOpename"              => "_Page/StockOpename/StockOpename.js",
            "Supplier"                  => "_Page/Supplier/Supplier.js",
            "JenisTransaksi"            => "_Page/JenisTransaksi/JenisTransaksi.js",
            "Transaksi"                 => "_Page/Transaksi/Transaksi.js",
            "RekapTransaksi"            => "_Page/RekapTransaksi/RekapTransaksi.js",
            "Penjualan"                 => "_Page/Penjualan/Penjualan.js",
            "Pembelian"                 => "_Page/Pembelian/Pembelian.js",
            "RekapJualBeli"             => "_Page/RekapJualBeli/RekapJualBeli.js",
            "UtangPiutang"              => "_Page/UtangPiutang/UtangPiutang.js",
            "TransaksiJualBeli"         => "_Page/TransaksiJualBeli/Transaksi.js",
            "AkunPerkiraan"             => "_Page/AkunPerkiraan/AkunPerkiraan.js",
            "Jurnal"                    => "_Page/Jurnal/Jurnal.js",
            "BagiHasil"                 => "_Page/BagiHasil/BagiHasil.js",
            "SettingGeneral"            => "_Page/SettingGeneral/SettingGeneral.js",
            "EntitasAkses"              => "_Page/EntitasAkses/EntitasAkses.js",
            "ApiDoc"                    => "_Page/ApiDoc/ApiDoc.js",
            "AutoJurnal"                => "_Page/AutoJurnal/AutoJurnal.js",
            "Help"                      => "_Page/Help/Help.js",
            "SettingEmail"              => "_Page/SettingService/SettingService.js",
            "Pendaftaran"               => "_Page/Pendaftaran/Pendaftaran.js",
            "Pembayaran"                => "_Page/Pembayaran/Pembayaran.js",
            "Aktivitas"                 => "_Page/Aktivitas/Aktivitas.js",
            "SimpanPinjam"              => "_Page/SimpanPinjam/SimpanPinjam.js",
            "BukuBesar"                 => "_Page/BukuBesar/BukuBesar.js",
            "NeracaSaldo"               => "_Page/NeracaSaldo/NeracaSaldo.js",
            "LabaRugi"                  => "_Page/LabaRugi/LabaRugi.js",
            "RiwayatSimpanPinjam"       => "_Page/RiwayatSimpanPinjam/RiwayatSimpanPinjam.js",
            "RekapitulasiTransaksi"     => "_Page/RekapitulasiTransaksi/RekapitulasiTransaksi.js",
            "RiwayatAnggota"            => "_Page/RiwayatAnggota/RiwayatAnggota.js"
        ];

        // Cek apakah halaman ada dalam daftar dan sertakan file JS yang sesuai
        if (!empty($_GET['Page']) && isset($scripts[$_GET['Page']])) {
            echo '<script type="text/javascript" src="' . $scripts[$_GET['Page']] . '?V='.$date_version.'"></script>';
        }
    }
    echo '<script type="text/javascript" src="_Partial/Universal.js?V='.$date_version.'"></script>';
?>