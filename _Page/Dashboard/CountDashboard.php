<?php
    // Koneksi
    include "../../_Config/Connection.php";

    // Set header JSON
    header('Content-Type: application/json');

    // Siapkan variabel default
    $response = [
        "user" => 0,
        "siswa" => 0,
        "periode" => 0,
        "pembayaran" => 0
    ];

    // Hitung jumlah user
    $qUser = $Conn->query("SELECT COUNT(*) AS total FROM access");
    if ($qUser) {
        $dUser = $qUser->fetch_assoc();
        $response['user'] = (int)$dUser['total'];
    }

    // Hitung jumlah siswa aktif
    $qSiswa = $Conn->query("SELECT COUNT(*) AS total FROM student WHERE student_status='Terdaftar'");
    if ($qSiswa) {
        $dSiswa = $qSiswa->fetch_assoc();
        $response['siswa'] = (int)$dSiswa['total'];
    }

    // Hitung jumlah periode akademik aktif
    $qPeriode = $Conn->query("SELECT COUNT(*) AS total FROM academic_period");
    if ($qPeriode) {
        $dPeriode = $qPeriode->fetch_assoc();
        $response['periode'] = (int)$dPeriode['total'];
    }

    // Hitung total nominal pembayaran tahun berjalan
    $tahun = date("Y");
    $qPembayaran = $Conn->prepare("
        SELECT COALESCE(SUM(payment_nominal),0) AS total 
        FROM payment 
        WHERE YEAR(payment_datetime)=?
    ");
    $qPembayaran->bind_param("i", $tahun);
    $qPembayaran->execute();
    $resPembayaran = $qPembayaran->get_result();
    if ($resPembayaran) {
        $dPembayaran = $resPembayaran->fetch_assoc();
        // Format rupiah
        $response['pembayaran'] = "Rp " . number_format((float)$dPembayaran['total'], 0, ',', '.');
    }
    $qPembayaran->close();

    // Output JSON
    echo json_encode($response);

?>