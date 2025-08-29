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
    //Jumlah Barang
    $JumlahBarang = mysqli_num_rows(mysqli_query($Conn, "SELECT id_barang FROM barang"));
    $JumlahBarangFormat = "" . number_format($JumlahBarang,0,',','.');
    //Menghitung jumlah Rupiah barang
    $sqlBarang = "SELECT SUM(harga_beli * stok_barang) AS total_rupiah FROM barang";
    $resultBarang = $Conn->query($sqlBarang);

    if ($resultBarang) {
        $rowBarang= $resultBarang->fetch_assoc();
        $totalRupiahBarang = $rowBarang['total_rupiah'] ?? 0;
        $rp_barang = "Rp " . number_format($totalRupiahBarang,0,',','.');
        $response = [
            "status" => "Success",
            "message" => "Data Count Barang Berhasil",
            "rp_barang" => $rp_barang,
            "item_barang" => "$JumlahBarangFormat Item",
        ];
    } else {
        $response = [
            "status" => "Error",
            "message" => $Conn->error
        ];
    }

    // Output response
    echo json_encode($response);
?>