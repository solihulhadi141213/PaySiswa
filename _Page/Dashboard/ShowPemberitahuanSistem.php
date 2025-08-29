<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Hitung Jumlah Barang Yang Hampir Habis
    $sqlStokMinimum = "SELECT COUNT(*) AS total_minimum FROM barang WHERE stok_barang < stok_minimum";
    $resultMinimum = $Conn->query($sqlStokMinimum);
    $totalMinimum = $resultMinimum->fetch_assoc()['total_minimum'] ?? 0;
    if(!empty($totalMinimum)){
        echo '
            <div class="alert alert-warning mb-3 alert-dismissible fade show">
                <small>
                    Terdapat '.$totalMinimum.' item barang yang hampir habis.
                </small>
            </div>
        ';
    }else{
        echo '
            <div class="alert alert-success mb-3 alert-dismissible fade show">
                <small>
                    <i class="bi bi-check"></i> Stok barang dalam keadaan baik.
                </small>
            </div>
        ';
    }
    // Tanggal saat ini dalam format Y-m-d
    $currentDate = date('Y-m-d');

    // Query untuk menghitung jumlah item barang yang sudah expired
    $sqlExpired = "SELECT COUNT(*) AS total_expired FROM barang_bacth WHERE reminder_date <= ?";
    $stmt = $Conn->prepare($sqlExpired);
    $stmt->bind_param("s", $currentDate);
    $stmt->execute();
    $resultExpired = $stmt->get_result();

    if ($resultExpired) {
        $totalExpired = intval($resultExpired->fetch_assoc()['total_expired'] ?? 0);
        
        if ($totalExpired > 0) {
            echo '
                <div class="alert alert-danger mb-3 alert-dismissible fade show">
                    <small>
                        <i class="bi bi-exclamation-octagon"></i> Terdapat '.$totalExpired.' item barang expire.
                    </small>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-success mb-3 alert-dismissible fade show">
                    <small>
                        <i class="bi bi-check-circle"></i> Tidak ada barang yang expire.
                    </small>
                </div>
            ';
        }
    } else {
        echo '
            <div class="alert alert-danger mb-3 alert-dismissible fade show">
                <small>
                    <i class="bi bi-x-circle"></i> Gagal mengambil data barang expired.
                </small>
            </div>
        ';
    }
    // Tanggal dan waktu saat ini dalam format Y-m-d H:i:s
    $currentDateTime = date('Y-m-d H:i:s');

    // Query untuk menghitung jumlah data yang sudah expired
    $sqlExpiredAccess = "SELECT COUNT(*) AS total_expired FROM akses_login WHERE date_expired > ?";
    $stmt = $Conn->prepare($sqlExpiredAccess);
    $stmt->bind_param("s", $currentDateTime);
    $stmt->execute();
    $resultExpiredAccess = $stmt->get_result();

    if ($resultExpiredAccess) {
        $totalExpiredAccess = intval($resultExpiredAccess->fetch_assoc()['total_expired'] ?? 0);
        
        if ($totalExpiredAccess > 0) {
            echo '
                <div class="alert alert-info mb-3 alert-dismissible fade show">
                    <small>
                        <i class="bi bi-person-circle"></i> Terdapat '.$totalExpiredAccess.' pengguna sedang login.
                    </small>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-success mb-3 alert-dismissible fade show">
                    <small>
                        <i class="bi bi-unlock"></i> Tidak ada pengguna yang login.
                    </small>
                </div>
            ';
        }
    } else {
        echo '
            <div class="alert alert-danger mb-3 alert-dismissible fade show">
                <small>
                    <i class="bi bi-x-circle"></i> Gagal mengambil data akses login yang expired.
                </small>
            </div>
        ';
    }
    $stmt->close();
?>