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
    //Jumlah Record Pembelian
    $JumlahRecordPembelian = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi_jual_beli FROM transaksi_jual_beli WHERE kategori='Pembelian'"));
    $JumlahRecordPembelianFormat = "" . number_format($JumlahRecordPembelian,0,',','.');
    
    //Jumlah Nomiinal Pembelian
    $SumNominalPembeliian = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Pembelian'"));
    $JumlahNomiinalPembelian = $SumNominalPembeliian['total'];

    //Jumlah Nomiinal Retur Pembelian
    $SumReturPembelian = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(total) AS total FROM transaksi_jual_beli WHERE kategori='Retur Pembelian'"));
    $JumlahNomiinalReturPembelian = $SumReturPembelian['total'];

    //Jumlah Pembelian Bersih
    $JumlahNomnalPembelianBersh=$JumlahNomiinalPembelian-$JumlahNomiinalReturPembelian;
    $JumlahNomnalPembelianBershFormat = "" . number_format($JumlahNomnalPembelianBersh,0,',','.');

    $response = [
        "status" => "Success",
        "message" => "Data Count Pembelian Berhasil",
        "put_nominal_pembelian" => $JumlahNomnalPembelianBershFormat,
        "put_record_pembelian" => "$JumlahRecordPembelianFormat",
    ];

    // Output response
    echo json_encode($response);
?>