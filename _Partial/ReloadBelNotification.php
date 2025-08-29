<?php
    //Karena Ini Di running Dengan JS maka Panggil Ulang Koneksi
    include "../_Config/Connection.php";
    include "../_Config/GlobalFunction.php";
    date_default_timezone_set("Asia/Jakarta");
    //Menghitung Jumlah Pinjaman Yang Menunggak
    $JumlahNotifikasi=0;
    $query_pinjaman_berjalan = mysqli_query($Conn, "SELECT id_pinjaman, tanggal, periode_angsuran FROM pinjaman WHERE status='Berjalan'");
    while ($data = mysqli_fetch_array($query_pinjaman_berjalan)) {
        $id_pinjaman= $data['id_pinjaman'];
        $tanggal= $data['tanggal'];
        $periode_angsuran= $data['periode_angsuran'];
        
        //Tanggal Sekarang
        $TanggalSekarang=date('Y-m-d');
        $JumlahPeriodeTagihan=0;
        for ( $i=1; $i<=$periode_angsuran; $i++ ){
            $GetPeriodePinjaman=date('d/m/Y', strtotime('+'.$i.' month', strtotime($tanggal))); 
            //Ubah Format Tangga
            $GetPeriodePinjaman2=date('Y-m-d', strtotime('+'.$i.' month', strtotime($tanggal))); 
            if($TanggalSekarang>$GetPeriodePinjaman2){
                //Cek Apakah Sudah Ada Angsuran
                $QryAngsuran = mysqli_query($Conn,"SELECT id_pinjaman_angsuran FROM pinjaman_angsuran WHERE id_pinjaman='$id_pinjaman' AND tanggal_angsuran='$GetPeriodePinjaman2'")or die(mysqli_error($Conn));
                $DataAngsuran = mysqli_fetch_array($QryAngsuran);
                if(empty($DataAngsuran['id_pinjaman_angsuran'])){
                    $JumlahPeriodeTagihan=$JumlahPeriodeTagihan+1;
                }else{
                    $JumlahPeriodeTagihan=$JumlahPeriodeTagihan+0;
                }
            }else{
                $JumlahPeriodeTagihan=$JumlahPeriodeTagihan+0;
            }
        }
        if(!empty($JumlahPeriodeTagihan)){
            $JumlahNotifikasi=$JumlahNotifikasi+1;
        }
    }
    //Apabila ada notifgikasi
    if(!empty($JumlahNotifikasi)){
        echo '<i class="bi bi-bell"></i>';
        echo '<span class="badge bg-danger rounded-pill badge-number">'.$JumlahNotifikasi.'</span>';
    }else{
        //Apabila Tidak Ada
        echo '<i class="bi bi-bell"></i>';
    }
?>