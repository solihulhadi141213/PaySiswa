<?php
    if(empty($_GET['Page'])){
        include "_Page/Dashboard/Dashboard.php";
    }else{
        $Page=$_GET['Page'];
        //Index Halaman
        $page_arry=[
            "AksesFitur"        =>  "_Page/AksesFitur/AksesFitur.php",
            "AksesEntitas"      =>  "_Page/AksesEntitas/AksesEntitas.php",
            "Akses"             =>  "_Page/Akses/Akses.php",
            "Anggota"           =>  "_Page/Anggota/Anggota.php",
            "AnggotaKeluarMasuk"=>  "_Page/AnggotaKeluarMasuk/AnggotaKeluarMasuk.php",
            "RekapAnggota"      =>  "_Page/RekapAnggota/RekapAnggota.php",
            "JenisSimpanan"     =>  "_Page/JenisSimpanan/JenisSimpanan.php",
            "SimpananWajib"     =>  "_Page/SimpananWajib/SimpananWajib.php",
            "RekapSimpanan"     =>  "_Page/RekapSimpanan/RekapSimpanan.php",
            "Tagihan"           =>  "_Page/Tagihan/Tagihan.php",
            "RekapPinjaman"     =>  "_Page/RekapPinjaman/RekapPinjaman.php",
            "PotonganAnggota"   =>  "_Page/PotonganAnggota/PotonganAnggota.php",
            "JenisTransaksi"    =>  "_Page/JenisTransaksi/JenisTransaksi.php",
            "Transaksi"         =>  "_Page/Transaksi/Transaksi.php",
            "RekapTransaksi"    =>  "_Page/RekapTransaksi/RekapTransaksi.php",
            "Penjualan"         =>  "_Page/Penjualan/Penjualan.php",
            "Pembelian"         =>  "_Page/Pembelian/Pembelian.php",
            "RekapJualBeli"     =>  "_Page/RekapJualBeli/RekapJualBeli.php",
            "UtangPiutang"      =>  "_Page/UtangPiutang/UtangPiutang.php",
            "Version"           =>  "_Page/Version/Version.php",
            "SettingGeneral"    =>  "_Page/SettingGeneral/SettingGeneral.php",
            "EntitasAkses"      =>  "_Page/EntitasAkses/EntitasAkses.php",
            "ApiDoc"            =>  "_Page/ApiDoc/ApiDoc.php",
            "Tabungan"          =>  "_Page/Tabungan/Tabungan.php",
            "Pinjaman"          =>  "_Page/Pinjaman/Pinjaman.php",
            "JenisPinjaman"     =>  "_Page/JenisPinjaman/JenisPinjaman.php",
            "Barang"            =>  "_Page/Barang/Barang.php",
            "BarangExpired"     =>  "_Page/BarangExpired/BarangExpired.php",
            "StockOpename"      =>  "_Page/StockOpename/StockOpename.php",
            "Supplier"          =>  "_Page/Supplier/Supplier.php",
            "AutoJurnal"        =>  "_Page/AutoJurnal/AutoJurnal.php",
            "MyProfile"         =>  "_Page/MyProfile/MyProfile.php",
            "Help"              =>  "_Page/Help/Help.php",
            "SettingEmail"      =>  "_Page/SettingService/SettingService.php",
            "RiwayatAnggota"    =>  "_Page/RiwayatAnggota/RiwayatAnggota.php",
            "Aktivitas"         =>  "_Page/Aktivitas/Aktivitas.php",
            "AkunPerkiraan"     =>  "_Page/AkunPerkiraan/AkunPerkiraan.php",
            "Jurnal"            =>  "_Page/Jurnal/Jurnal.php",
            "SimpanPinjam"      =>  "_Page/SimpanPinjam/SimpanPinjam.php",
            "BukuBesar"         =>  "_Page/BukuBesar/BukuBesar.php",
            "NeracaSaldo"       =>  "_Page/NeracaSaldo/NeracaSaldo.php",
            "LabaRugi"          =>  "_Page/LabaRugi/LabaRugi.php",
            "RiwayatSimpanPinjam"=>  "_Page/RiwayatSimpanPinjam/RiwayatSimpanPinjam.php",
            "RekapitulasiTransaksi"=>  "_Page/RekapitulasiTransaksi/RekapitulasiTransaksi.php",
            "BagiHasil"         =>  "_Page/BagiHasil/BagiHasil.php",
            "CetakInvoice"      =>  "_Page/CetakInvoice/CetakInvoice.php",
            "Error"             =>  "_Page/Error/Error.php"
        ];

        //Tangkap 'Page'
        $Page = !empty($_GET['Page']) ? $_GET['Page'] : "";

        //Kondisi Pada masing-masing Page
        if (array_key_exists($Page, $page_arry)) { 
            include $page_arry[$Page]; 
        } else { 
            include "_Page/Dashboard/Dashboard.php";
        }
    }
?>