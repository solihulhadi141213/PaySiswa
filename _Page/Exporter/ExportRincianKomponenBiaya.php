<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";

    //Validasi 'id_fee_component'
    if(empty($_GET['id_fee_component'])){
       echo 'ID Komponen Biaya Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel 'id_fee_component'
    $id_fee_component       = validateAndSanitizeInput($_GET['id_fee_component']);

    // Buka 
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
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Rncian Komponen Biaya</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #ffffffff;
                margin: 10px;
                padding: 0;
            }
            table.custom-table {
                width: 100%;
                border-collapse: collapse;
                color: #000;
                background-color: #fff;
            }
            table.custom-table thead td {
                border: 1px solid #000;
                padding: 4px; 
                text-align: center;
                font-family: Arial, sans-serif;
            }
            table.custom-table tbody td {
                border: 1px solid #000;
                padding: 4px;
                font-family: Arial, sans-serif;
            }
            table.header_logo{
                margin-bottom : 20px;
                border-bottom: 3px double #000;
                width: 100%;
            }
            .logo{
                padding-right : 15px;
                width: 70px;
            }
            table.identitas tr td{
                font-family: Arial, sans-serif;
            }
            b{
                font-family: Arial, sans-serif !important;
            }
            .title_report{
                text-decoration: underline;
            }

        </style>
    </head>
    <body>
        <table class="header_logo">
            <tr>
                <td rowspan="2" class="logo" valign="top"><img src="../../assets/img/<?php echo "$app_logo"; ?>" alt="Logo" width="70px"></td>
                <td valign="top">
                    <b><?php echo "$company_name"; ?></b>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <small><?php echo "$company_address"; ?></small><br>
                    <small>
                        Telepon : <?php echo "$company_contact"; ?><br>
                        Email : <?php echo "$company_email"; ?>
                    </small>
                </td>
            </tr>
        </table>
        <table width="100%" class="identitas">
            <tr>
                <td align="center">
                     <b>
                        RINCIAN TAGIHAN BERDASARKAN KOMPONEN BIAYA<br>
                        PERIODE AKADEMIK <span class="title_report"><?php echo $academic_period; ?></span>
                    </b>
                </td>
            </tr>
        </table>
        <table width="100%" class="identitas">
            <tr>
                <td width="50%">
                    <table>
                        <tr>
                            <td><small>Periode Akademik</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$academic_period"; ?></small></td>
                        </tr>
                        <tr>
                            <td><small>Periode Awal</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$academic_period_start"; ?></small></td>
                        </tr>
                        <tr>
                            <td><small>Periode Akhir</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$academic_period_end"; ?></small></td>
                        </tr>
                    </table>
                </td>
                <td width="50%" align="right">
                    <table>
                        <tr>
                            <td><small>Komponen Biaya</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$component_name"; ?></small></td>
                        </tr>
                        <tr>
                            <td><small>Kategori</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$component_category"; ?></small></td>
                        </tr>
                        <tr>
                            <td><small>Periode Tagihan</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo ''.$nama_bulan.' '.$periode_year.''; ?></small></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center"><b><small>No</small></b></td>
                    <td><small><b>Siswa</b></small></td>
                    <td><small><b>NIS</b></small></td>
                    <td><small><b>Jenjang / Level</b></small></td>
                    <td><small><b>Kelas / Rombel</b></small></td>
                    <td align="right"><small><b>Biaya Pendidikan</b></small></td>
                    <td align="right"><small><b>Diskon</b></small></td>
                    <td align="right"><small><b>Tagihan</b></small></td>
                    <td align="right"><small><b>Pembayaran</b></small></td>
                    <td align="right"><small><b>Sisa/Tunggakan</b></small></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Jumlah Data
                    $jumlah_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
                    if(empty($jumlah_data)){
                        echo '
                            <tr>
                                <td colspan="10" align="center">
                                    <small>Tidak Ada Data Yang Ditampilkan</small>
                                </td>
                            </tr>
                        ';
                    }else{
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
                                    <td align="right"><small>'.$fee_nominal_list_format.'</small></td>
                                    <td align="right"><small>'.$fee_discount_format.'</small></td>
                                    <td align="right"><small>'.$tagihan_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_tunggakan_format.'</small></td>
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
                                <td align="right"><small><b>'.$subtotal_biaya_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_diskon_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_tagihan_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_pembayaran_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_tunggakan_format.'</b></small></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
        <?php
            // Membuat QR Code Dokumen
            $text = "$app_base_url/_Page/Exporter/ExportRincianKomponenBiaya.php?id_fee_component='.$id_fee_component.'";
            echo '
                <table class="qr_table">
                    <tr>
                        <td align="center">
                            <img src="../../qr.php?text='.$text.'" alt="QR Code"><br>
                        </td>
                    </tr>
                     <tr><td align="center">Scan Me</td></tr>
                </table> 
            ';
        ?>
    </body>
</html>