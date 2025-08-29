<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    $RowPinjaman = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM pinjaman"));
    if(empty($RowPinjaman)){
        echo '<div class="activity-item d-flex">';
        echo '  Data Pinjaman Anggota Belum Ada';
        echo '</div>';
    }else{
        //Arraykan Pinjaman
        $QryPinjaman = mysqli_query($Conn, "SELECT*FROM pinjaman ORDER BY id_pinjaman DESC LIMIT 5");
        while ($DataPinjaman = mysqli_fetch_array($QryPinjaman)) {
            $id_pinjaman= $DataPinjaman['id_pinjaman'];
            $tanggal= $DataPinjaman['tanggal'];
            $nip= $DataPinjaman['nip'];
            $nama= $DataPinjaman['nama'];
            $jumlah_pinjaman= $DataPinjaman['jumlah_pinjaman'];
            $jumlah_pinjaman_format = "Rp " . number_format($jumlah_pinjaman,0,',','.');
            $strtotime_pinjaman= strtotime($tanggal);
            $tanggal_pinjaman_format=date('d/m/y', $strtotime_pinjaman);
            echo '<div class="activity-item d-flex">';
            echo '  <div class="activite-label"><code class="text-info">'.$tanggal_pinjaman_format.'</code></div>';
            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
            echo '  <div class="activity-content">';
            echo '      <small class="credit">'.$nama.'</small><br><small class="credit"><code class="text text-grayish">'.$jumlah_pinjaman_format.'</code></small>';
            echo '  </div>';
            echo '</div>';
        }
    }
?>