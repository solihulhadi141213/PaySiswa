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
    //Jumlah Penjualan
    $JumlahPenjualan = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi_jual_beli FROM transaksi_jual_beli WHERE kategori='Penjualan'"));
    $JumlahPenjualanFormat = "" . number_format($JumlahPenjualan,0,',','.');
    
    //Jumlah Penjualan
    $SumPenjualan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Penjualan'"));
    $JumlahNomiinalPenjualan = $SumPenjualan['total'];

    $SumReturPenjualan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Retur Penjualan'"));
    $JumlahNomiinalReturPenjualan = $SumReturPenjualan['total'];

    //Jumlah Penjualan Bersih
    $JumlahNomnalPenjualanBersh=$JumlahNomiinalPenjualan-$JumlahNomiinalReturPenjualan;
    $JumlahNomnalPenjualanBershFormat = "" . number_format($JumlahNomnalPenjualanBersh,0,',','.');

    $response = [
        "status" => "Success",
        "message" => "Data Count Penjualan Berhasil",
        "put_nominal_penjualan" => $JumlahNomnalPenjualanBershFormat,
        "put_record_penjualan" => "$JumlahPenjualanFormat",
    ];

    // Output response
    echo json_encode($response);
?>