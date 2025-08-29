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
    //Jumlah Record Transaksi Operasional
    $JumlahRecordTransaksi = mysqli_num_rows(mysqli_query($Conn, "SELECT id_transaksi FROM transaksi"));
    $JumlahRecordTransaksiFormat = "" . number_format($JumlahRecordTransaksi,0,',','.');
    
    //Jumlah Nomiinal Transaksi Operasional
    $SumNominalTransaksi = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM transaksi"));
    $JumlahNominalTransaksi = $SumNominalTransaksi['jumlah'];

    //Jumlah Nomiinal Bagi Hasil Format
    $JumlahNominalTransaksiFormat = "" . number_format($JumlahNominalTransaksi,0,',','.');

    $response = [
        "status" => "Success",
        "message" => "Data Count Transaksi Operasional Berhasil",
        "put_nominal_transaksi" => $JumlahNominalTransaksiFormat,
        "put_record_transaksi" => "$JumlahRecordTransaksiFormat",
    ];

    // Output response
    echo json_encode($response);
?>