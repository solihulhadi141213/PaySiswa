<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <tr><td colspan="11" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>$("#title_komponen_biaya").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="11" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_komponen_biaya").html("");</script>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Informasi Periode Akdemik
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <tr><td colspan="11" class="text-center"><small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small></td></tr>
            <script>$("#title_komponen_biaya").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Inisiasi $title_komponen_biaya
    $title_komponen_biaya = '
        <input type="hidden" name="id_academic_period" value="'.$id_academic_period.'">
        <b>
            REKAPITULASI TAGIHAN BERDASARKAN KOMPONEN BIAYA<br>
            PERIODE AKADEMIK <span class="text-primary underscore_doted">'.$academic_period.'</span>
        </b>
    ';
    //Inisialisasi Jumlah total
    $subtotal_biaya         = 0 ;
    $subtotal_diskon        = 0 ;
    $subtotal_tagihan       = 0 ;
    $subtotal_pembayaran    = 0 ;
    $subtotal_tunggakan     = 0 ;
    
    //Menampilkan Data Siswa
    $no=1;
    $QeryKomponenBiaya = mysqli_query($Conn, "SELECT * FROM fee_component WHERE id_academic_period='$id_academic_period' ORDER BY periode_month ASC");
    while ($DataKomponenBiaya = mysqli_fetch_array($QeryKomponenBiaya)) {
        $id_fee_component   = $DataKomponenBiaya['id_fee_component'];
        $component_name     = $DataKomponenBiaya['component_name'];
        $component_category = $DataKomponenBiaya['component_category'];
        $periode_month      = $DataKomponenBiaya['periode_month'];
        $periode_year       = $DataKomponenBiaya['periode_year'];
        $fee_nominal        = $DataKomponenBiaya['fee_nominal'];

        //Nama Bulan
        $nama_bulan = getNamaBulan($periode_month);

        //Menghitung Biaya Pendidikan, Diskon, Tagihan, Pembayaran, dan Sisa tunggakan
        # Jumlah Biaya Pendidikan
        $SumFeeNominal      = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS jumlah_fee_nominal FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
        if(!empty($SumFeeNominal['jumlah_fee_nominal'])){
            $jumlah_fee_nominal = $SumFeeNominal['jumlah_fee_nominal'];
        }else{
            $jumlah_fee_nominal = 0;
        }
        

        # Jumlah Diskon/potongan
        $SumFeeDiscount      = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_fee_discount FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
        if(!empty($SumFeeDiscount['jumlah_fee_discount'])){
            $jumlah_fee_discount = $SumFeeDiscount['jumlah_fee_discount'];
        }else{
            $jumlah_fee_discount = 0;
        }
        

        # Jumlah Tagihan
        $SumTagihan         = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS jumlah_tagihan FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
        if(!empty($SumTagihan['jumlah_tagihan'])){
            $jumlah_tagihan     = $SumTagihan['jumlah_tagihan'];
        }else{
            $jumlah_tagihan     = 0;
        }
        

        # Jumlah Pembayaran
        $SumPayment         = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_component='$id_fee_component'"));
        if(!empty($SumPayment['jumlah_pembayaran'])){
            $jumlah_pembayaran  = $SumPayment['jumlah_pembayaran'];
        }else{
            $jumlah_pembayaran  = 0;
        }
        
          # Sisa Tunggakan
        $jumlah_tunggakan = $jumlah_tagihan - $jumlah_pembayaran;

          # akumulasi subtotal
        $subtotal_biaya      = $subtotal_biaya + $jumlah_fee_nominal ;
        $subtotal_diskon     = $subtotal_diskon + $jumlah_fee_discount ;
        $subtotal_tagihan    = $subtotal_tagihan + $jumlah_tagihan ;
        $subtotal_pembayaran = $subtotal_pembayaran + $jumlah_pembayaran ;
        $subtotal_tunggakan  = $subtotal_tunggakan + $jumlah_tunggakan ;

        # Format Rupiah
        $fee_nominal_format             = "Rp " . number_format($fee_nominal,0,',','.');
        $jumlah_fee_nominal_format      = "Rp " . number_format($jumlah_fee_nominal,0,',','.');
        $jumlah_fee_discount_format     = "Rp " . number_format($jumlah_fee_discount,0,',','.');
        $jumlah_tagihan_format          = "Rp " . number_format($jumlah_tagihan,0,',','.');
        $jumlah_pembayaran_format       = "Rp " . number_format($jumlah_pembayaran,0,',','.');
        $jumlah_tunggakan_format        = "Rp " . number_format($jumlah_tunggakan,0,',','.');

        

        //Buka List Sswa
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$component_category.'</small></td>
                <td><small>'.$nama_bulan.' '.$periode_year.'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
                <td><small>'.$jumlah_fee_nominal_format.'</small></td>
                <td><small>'.$jumlah_fee_discount_format.'</small></td>
                <td><small>'.$jumlah_tagihan_format.'</small></td>
                <td><small>'.$jumlah_pembayaran_format.'</small></td>
                <td><small>'.$jumlah_tunggakan_format.'</small></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary btn-floating modal_rincian_komponen_biaya" data-id="'.$id_fee_component.'">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </td>
            </tr>
        ';
        $no++;
    }

    // Format Akumulasi Subtotal
    $subtotal_biaya_format      = "Rp " . number_format($subtotal_biaya,0,',','.');
    $subtotal_diskon_format     = "Rp " . number_format($subtotal_diskon,0,',','.');
    $subtotal_tagihan_format    = "Rp " . number_format($subtotal_tagihan,0,',','.');
    $subtotal_pembayaran_format = "Rp " . number_format($subtotal_pembayaran,0,',','.');
    $subtotal_tunggakan_format  = "Rp " . number_format($subtotal_tunggakan,0,',','.');

    // Tampilkan Baris Subtotal
    echo '
        <tr>
            <td><small><b></b></small></td>
            <td><small><b>JUMLAH / TOTAL</b></small></td>
            <td><small><b></b></small></td>
            <td><small><b></b></small></td>
            <td><small><b></b></small></td>
            <td><small><b>'.$subtotal_biaya_format.'</b></small></td>
            <td><small><b>'.$subtotal_diskon_format.'</b></small></td>
            <td><small><b>'.$subtotal_tagihan_format.'</b></small></td>
            <td><small><b>'.$subtotal_pembayaran_format.'</b></small></td>
            <td><small><b>'.$subtotal_tunggakan_format.'</b></small></td>
            <td><small><b></b></small></td>
        </tr>
    ';
   
    echo '
        <script>
            $("#export_komponen_biaya").attr("href", "_Page/Exporter/ExportKomponenBiaya.php?id_academic_period='.$id_academic_period.'");
            $("#title_komponen_biaya").html(' . json_encode($title_komponen_biaya) . ');
        </script>
    ';

?>