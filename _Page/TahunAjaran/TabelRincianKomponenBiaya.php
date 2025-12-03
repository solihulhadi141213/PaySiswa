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
    //Tangkap id_fee_component
    if(empty($_POST['id_fee_component'])){
        echo '
            <tr><td colspan="11" class="text-center"><small class="text-danger">ID Komponen Biaya Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_komponen_biaya").html("");</script>
        ';
        exit;
    }

    //Buat variabel
    $id_fee_component = validateAndSanitizeInput($_POST['id_fee_component']);

    //Buka data 'fee_component'
    $id_academic_period = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'id_academic_period');
    $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
    $periode_month      = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year       = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
    $fee_nominal        = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'fee_nominal');

    //Format fee_nominal
    $fee_nominal_format = "Rp " . number_format($fee_nominal,0,',','.');

    //Buka data 'academic_period'
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');
    $academic_period_status = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_status');

    //Inisiasi Nama bulan
    $nama_bulan = getNamaBulan($periode_month);

    //Routing Status
    if ($academic_period_status == 1) {
        $label_status = 'Unlock';
    } else {
        $label_status = 'Locked';
    }

    //Inisiasi $title_rincian_komponen_biaya
    $title_rincian_komponen_biaya = '
        <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">

        <div class="row mb-3 border-1 border-bottom">
            <div class="col-12 mb-3 text-center">
                <b>
                    RINCIAN TAGIHAN BERDASARKAN KOMPONEN BIAYA<br>
                    PERIODE AKADEMIK <span class="text-primary underscore_doted">'.$academic_period.'</span>
                </b>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-5">
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Mulai</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period_start.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Berakhir</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period_end.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Status</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$label_status.'</small></div>
                </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5">
                <div class="row mb-2">
                    <div class="col-5"><small>Komponen Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_name.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Kategori Komponen</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_category.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Nominal Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$fee_nominal_format.'</small></div>
                </div>
            </div>
        </div>
    ';
    //Inisialisasi Jumlah total
    $subtotal_biaya         = 0 ;
    $subtotal_diskon        = 0 ;
    $subtotal_tagihan       = 0 ;
    $subtotal_pembayaran    = 0 ;
    $subtotal_tunggakan     = 0 ;
    
    //Menampilkan Data Siswa
    $no=1;
    $QryFeeByStudent = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_fee_component='$id_fee_component' ORDER BY id_student ASC");
    while ($DataFeeByStudent = mysqli_fetch_array($QryFeeByStudent)) {
        $id_fee_by_student      = $DataFeeByStudent['id_fee_by_student'];
        $id_organization_class  = $DataFeeByStudent['id_organization_class'];
        $id_student             = $DataFeeByStudent['id_student'];
        $fee_nominal_list       = $DataFeeByStudent['fee_nominal'];
        $fee_discount           = $DataFeeByStudent['fee_discount'];

        //Buka 'organization_class'
        $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
        $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

        // Buka 'student'
        $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
        $student_name    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

        //Nama Bulan
        $nama_bulan = getNamaBulan($periode_month);

        //Jumlah Tagihan
        $tagihan = $fee_nominal_list-$fee_discount;

        # Jumlah Pembayaran
        $SumPayment         = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_by_student='$id_fee_by_student'"));
        $jumlah_pembayaran  = $SumPayment['jumlah_pembayaran'];

        # Sisa Tunggakan
        $jumlah_tunggakan       = $tagihan - $jumlah_pembayaran;

        # akumulasi subtotal
        $subtotal_biaya         = $subtotal_biaya + $fee_nominal_list ;
        $subtotal_diskon        = $subtotal_diskon + $fee_discount ;
        $subtotal_tagihan       = $subtotal_tagihan + $tagihan ;
        $subtotal_pembayaran    = $subtotal_pembayaran + $jumlah_pembayaran ;
        $subtotal_tunggakan     = $subtotal_tunggakan + $jumlah_tunggakan ;

        # Format Rupiah
        $fee_nominal_list_format    = "Rp " . number_format($fee_nominal_list,0,',','.');
        $fee_discount_format        = "Rp " . number_format($fee_discount,0,',','.');
        $tagihan_format             = "Rp " . number_format($tagihan,0,',','.');
        $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
        $jumlah_tunggakan_format    = "Rp " . number_format($jumlah_tunggakan,0,',','.');
       
        //Buka List Sswa
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$student_name.'</small></td>
                <td><small>'.$student_nis.'</small></td>
                <td><small>'.$class_level.'</small></td>
                <td><small>'.$class_name.'</small></td>
                <td><small>'.$fee_nominal_list_format.'</small></td>
                <td><small>'.$fee_discount_format.'</small></td>
                <td><small>'.$tagihan_format.'</small></td>
                <td><small>'.$jumlah_pembayaran_format.'</small></td>
                <td><small>'.$jumlah_tunggakan_format.'</small></td>
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
        </tr>
    ';
   
    echo '
        <script>
            $("#title_rincian_komponen_biaya").html(' . json_encode($title_rincian_komponen_biaya) . ');
            $("#export_rincian_komponen_biaya").attr("href","_Page/Exporter/ExportRincianKomponenBiaya.php?id_fee_component='.$id_fee_component.'");
        </script>
    ';

?>