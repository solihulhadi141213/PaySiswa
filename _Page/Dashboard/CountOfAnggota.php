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
    //Jumlah Anggota Aktif
    $JumlahAnggotaAktif = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM anggota WHERE status='Aktif'"));
    $JumlahAnggotaAktif = "" . number_format($JumlahAnggotaAktif,0,',','.');

    //Jumlah Anggota Aktif Keluar
    $JumlahAnggotaKeluar = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM anggota WHERE status='Keluar'"));
    $JumlahAnggotaKeluar = "" . number_format($JumlahAnggotaKeluar,0,',','.');

    $response = [
        "status" => "Success",
        "message" => "Data Count Barang Berhasil",
        "anggota_aktif" => $JumlahAnggotaAktif,
        "anggota_keluar" => $JumlahAnggotaKeluar,
    ];

    // Output response
    echo json_encode($response);
?>