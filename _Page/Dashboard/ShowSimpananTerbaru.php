<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    $RowAnggota = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM anggota"));
    if(empty($RowAnggota)){
        echo '<div class="activity-item d-flex">';
        echo '  Data Anggota Belum Ada';
        echo '</div>';
    }else{
        //Arraykan Simpanan
        $RowSimpanan = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM simpanan"));
        if(empty($RowSimpanan)){
            echo '<div class="activity-item d-flex">';
            echo '  Data Simpanan Anggota Belum Ada';
            echo '</div>';
        }else{
            //Arraykan Simpanan
            $QrySimpanan = mysqli_query($Conn, "SELECT*FROM simpanan ORDER BY id_simpanan DESC LIMIT 5");
            while ($DataSimpanan = mysqli_fetch_array($QrySimpanan)) {
                $id_simpanan= $DataSimpanan['id_simpanan'];
                $tanggal= $DataSimpanan['tanggal'];
                $nip= $DataSimpanan['nip'];
                $nama= $DataSimpanan['nama'];
                $nama= $DataSimpanan['nama'];
                $jumlah_simpanan= $DataSimpanan['jumlah'];
                $jumlah_simpanan_format = "Rp " . number_format($jumlah_simpanan,0,',','.');
                $strtotime_simpanan= strtotime($tanggal);
                $tanggal_simpanan_format=date('d/m/y', $strtotime_simpanan);
                echo '<div class="activity-item d-flex">';
                echo '  <div class="activite-label"><code class="text-info">'.$tanggal_simpanan_format.'</code></div>';
                echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                echo '  <div class="activity-content">';
                echo '      <small class="credit">'.$nama.'</small><br><small class="credit"><code class="text text-grayish">'.$jumlah_simpanan_format.'</code></small>';
                echo '  </div>';
                echo '</div>';
            }
        }
    }
?>