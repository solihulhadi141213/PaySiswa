<?php
    // Koneksi
    include "../../_Config/Connection.php";

    // Set timezone
    date_default_timezone_set("Asia/Jakarta");

    // Ambil tahun sekarang
    $tahun = date("Y");

    // Siapkan array bulan
    $bulanNama = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
    $bulanAngka = range(1, 12);

    // Siapkan data awal dengan 0
    $data = array_fill(0, 12, 0);

    // Query jumlah aktivitas per bulan
    $sql = "SELECT MONTH(log_datetime) AS bulan, COUNT(*) AS jumlah
            FROM access_log
            WHERE YEAR(log_datetime) = ?
            GROUP BY MONTH(log_datetime)";
    $stmt = $Conn->prepare($sql);
    $stmt->bind_param("i", $tahun);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $index = (int)$row['bulan'] - 1;
        $data[$index] = (int)$row['jumlah'];
    }

    $stmt->close();

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode([
        "categories" => $bulanNama,
        "series" => $data
    ]);
?>
