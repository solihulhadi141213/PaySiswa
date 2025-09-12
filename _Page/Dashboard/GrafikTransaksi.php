<?php
    // Koneksi
    include "../../_Config/Connection.php";

    // Set timezone
    date_default_timezone_set("Asia/Jakarta");

    // Tahun sekarang
    $tahun = date("Y");

    // Nama bulan
    $bulanNama = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

    // Siapkan array hasil awal
    $data = [];
    foreach($bulanNama as $bln){
        $data[] = ["x" => $bln, "y" => 0];
    }

    // Query total nominal pembayaran per bulan
    $sql = "SELECT MONTH(payment_datetime) AS bulan, SUM(payment_nominal) AS total
            FROM payment
            WHERE YEAR(payment_datetime) = ?
            GROUP BY MONTH(payment_datetime)";
    $stmt = $Conn->prepare($sql);
    $stmt->bind_param("i", $tahun);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $index = (int)$row['bulan'] - 1;
        $data[$index]['y'] = (float)$row['total'];
    }

    $stmt->close();

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($data);

?>