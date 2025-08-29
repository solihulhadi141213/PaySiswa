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

    //Jumlah Angsuran
    $SumAngsuran = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM pinjaman_angsuran"));
    $JumlahAngsuran = $SumAngsuran['jumlah'];
    $JumlahAngsuran = "" . number_format($JumlahAngsuran,0,',','.');
    
    //Jumlah Record Angsuran
    $JumlahRecordAngsuran = mysqli_num_rows(mysqli_query($Conn, "SELECT id_pinjaman_angsuran FROM pinjaman_angsuran"));
    $JumlahRecordAngsuranFormat = "" . number_format($JumlahRecordAngsuran,0,',','.');
    $response = [
        "status" => "Success",
        "message" => "Data Count Barang Berhasil",
        "put_nominal_angsuran" => "$JumlahAngsuran",
        "put_record_angsuran" => "($JumlahRecordAngsuranFormat)",
    ];

    // Output response
    echo json_encode($response);
?>