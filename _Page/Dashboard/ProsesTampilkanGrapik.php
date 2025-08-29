<?php
    include "../../_Config/Connection.php";
    date_default_timezone_set('Asia/Jakarta');
    $a=1;
    $b=12;
    for ( $i =$a; $i<=$b; $i++ ){
        //Zero pading
        $i=sprintf("%02d", $i);
        $WaktuPencarian="$tahun-$i";
        $WaktuFormating="$tahun-$i-01";
        $Waktu = strtotime($WaktuFormating);
        $Waktu = date('F Y', $Waktu);
        //Jumlah Simpanan
        $SumSimpanan = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS total FROM simpanan WHERE tanggal like '%$WaktuPencarian%'"));
        $DataSimpanan = $SumSimpanan['total'];
        $JumlahSimpanan = "" . number_format($DataSimpanan,0,',','.');
        // Jumlah Pinjaman
        $SumPinjaman = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah_pinjaman) AS total FROM pinjaman WHERE tanggal LIKE '%$WaktuPencarian%'"));
        $DataPinjaman = $SumPinjaman['total'];
        $JumlahPinjaman = number_format($DataPinjaman, 0, ',', '.');
        $data[] = array(
            'x' => $Waktu,
            'y' => $JumlahSimpanan
        );
    }
    $json =json_encode($data, JSON_PRETTY_PRINT);
    if (file_put_contents("GrafikTransaksi.json", $json)){
        echo '<small class="text-success" id="NotifikasiCreatJson">Success</small>';
    }else{
        echo '<small class="text-danger" id="">Gagal Membuat File JSON</small>';
    }
?>