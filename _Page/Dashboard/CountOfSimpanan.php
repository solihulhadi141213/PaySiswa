<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');

    // Time Now Tmp
    $now = date('Y-m-d H:i:s');

    // Inisialisasi respons default
    $response = [
        "status" => "Error",
        "message" => "Belum ada proses yang dilakukan pada sistem."
    ];
    //Jumlah Simpanan Bersih
    $SumSimpananKotor = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE kategori!='Penarikan'"));
    $JumlahSimpananKotor = $SumSimpananKotor['jumlah'];
    
    //Penarikan Simpanan
    $SumPenarikan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM simpanan WHERE kategori='Penarikan'"));
    $JumlahPenarikan = $SumPenarikan['jumlah'];
    
    //Jumlah Simpanan Bersih
    $JumlahSimpananBersih=$JumlahSimpananKotor-$JumlahPenarikan;
    $JumlahSimpananBersih = "" . number_format($JumlahSimpananBersih,0,',','.');

    //JumlahPenarikan Format
    $JumlahPenarikanFormat = "" . number_format($JumlahPenarikan,0,',','.');

    $response = [
        "status" => "Success",
        "message" => "Data Count Barang Berhasil",
        "put_simpanan_anggota" => "$JumlahSimpananBersih",
        "put_penarikan_dana" => "($JumlahPenarikanFormat)",
    ];

    // Output response
    echo json_encode($response);
?>