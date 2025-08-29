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
        $QryAnggota = mysqli_query($Conn, "SELECT*FROM anggota ORDER BY tanggal_masuk DESC LIMIT 5");
        while ($DataAnggota = mysqli_fetch_array($QryAnggota)) {
            $id_anggota= $DataAnggota['id_anggota'];
            $tanggal= $DataAnggota['tanggal_masuk'];
            $nip= $DataAnggota['nip'];
            $nama= $DataAnggota['nama'];
            $strtotime_anggota= strtotime($tanggal);
            $tanggal_anggota_format=date('d/m/y', $strtotime_anggota);
            echo '<div class="activity-item d-flex">';
            echo '  <div class="activite-label"><code class="text-info">'.$tanggal_anggota_format.'</code></div>';
            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
            echo '  <div class="activity-content">';
            echo '      <small class="credit">'.$nama.'</small><br><small class="credit"><code class="text text-grayish">'.$nip.'</code></small>';
            echo '  </div>';
            echo '</div>';
        }
    }
?>