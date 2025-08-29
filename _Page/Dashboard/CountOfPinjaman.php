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
    //Jumlah Pinjaman
    $SumPinjaman = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah_pinjaman) AS jumlah_pinjaman FROM pinjaman"));
    $JumlahPinjaman = $SumPinjaman['jumlah_pinjaman'];

    //Jumlah Angsuran
    $SumAngsuran = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(pokok) AS pokok FROM pinjaman_angsuran"));
    $JumlahAngsuran = $SumAngsuran['pokok'];

    //Pinjaman Bersih
    $pinjaman=$JumlahPinjaman-$JumlahAngsuran;
    $JumlahPinjamanBersihFormat = "" . number_format($pinjaman,0,',','.');
    
    //Jumlah Sesi Pinjaman
    $JumlahSesiPinjamanBelumLunas = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM pinjaman WHERE status!='Lunas'"));
    $JumlahSesiPinjamanBelumLunasFormat = "" . number_format($JumlahSesiPinjamanBelumLunas,0,',','.');

    $response = [
        "status" => "Success",
        "message" => "Data Count Barang Berhasil",
        "put_pinjaman_anggota" => "$JumlahPinjamanBersihFormat",
        "put_sesi_pinjaman" => "($JumlahSesiPinjamanBelumLunasFormat)",
    ];

    // Output response
    echo json_encode($response);
?>