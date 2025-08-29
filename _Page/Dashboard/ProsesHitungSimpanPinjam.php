<?php
    date_default_timezone_set('Asia/Jakarta');
    $a = 1;
    $b = 12;
    $data = [];
    $tahun = date('Y'); // Ambil tahun saat ini
    for ($i = $a; $i <= $b; $i++) {
        // Zero padding
        $i = sprintf("%02d", $i);
        $WaktuPencarian = "$tahun-$i";
        $WaktuFormating = "$tahun-$i-01";
        $Waktu = strtotime($WaktuFormating);
        $Waktu = date('F', $Waktu);

        // Jumlah Simpanan
        $SumSimpanan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS total FROM simpanan WHERE tanggal LIKE '%$WaktuPencarian%'"));
        $DataSimpanan = $SumSimpanan['total'];
        $JumlahSimpanan = $DataSimpanan ? $DataSimpanan : 0;

        // Jumlah Pinjaman
        $SumPinjaman = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah_pinjaman) AS total FROM pinjaman WHERE tanggal LIKE '%$WaktuPencarian%'"));
        $DataPinjaman = $SumPinjaman['total'];
        $JumlahPinjaman = $DataPinjaman ? $DataPinjaman : 0;

        $data[] = array(
            'x' => $Waktu,
            'ySimpanan' => $JumlahSimpanan,
            'yPinjaman' => $JumlahPinjaman
        );
    }

    $json = json_encode($data, JSON_PRETTY_PRINT);
    if (file_put_contents("_Page/Dashboard/GrafikTransaksi.json", $json)) {
        
    } else {
        echo '<small class="text-danger">Gagal Membuat File JSON</small>';
    }
?>
