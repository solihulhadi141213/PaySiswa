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
    //Jumlah Record Bagi Hasil
    $JumlahRecordShu = mysqli_num_rows(mysqli_query($Conn, "SELECT id_shu_session FROM shu_session WHERE status='Realisasi'"));
    $JumlahRecordShuFormat = "" . number_format($JumlahRecordShu,0,',','.');
    
    //Jumlah Nomiinal Bagi Hasil
    $SumNominalBagiHasil = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(shu) AS shu FROM shu_session WHERE status='Realisasi'"));
    $JumlahNominalShu = $SumNominalBagiHasil['shu'];

    //Jumlah Nomiinal Bagi Hasil Format
    $JumlahNominalShuFormat = "" . number_format($JumlahNominalShu,0,',','.');

    $response = [
        "status" => "Success",
        "message" => "Data Count Pembelian Berhasil",
        "put_nominal_bagi_hasil" => $JumlahNominalShuFormat,
        "put_record_bagii_hasil" => "$JumlahRecordShuFormat",
    ];

    // Output response
    echo json_encode($response);
?>