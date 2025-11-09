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
        echo 'Sesi Akses Sudah Berakhir! Silahkan Login Ulang!';
        exit;
    }

    //Validasi Data tidak boleh kosong
    if(empty($_GET['id_organization_class'])){
       echo 'ID Kelas Tidak Boleh Kosong';
    }

    //Buat variabel
    $id_organization_class = validateAndSanitizeInput($_GET['id_organization_class']);

    //Buka class_name
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');

    //Buka id_academic_period
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');

    //Nama Periode
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    
    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Rekapitulasi Tagihan Siswa</title>
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
                    <span class="title_report">REKAPITULASI TAGIHAN & PEMBAYARAN SISWA</span>
                </td>
            </tr>
        </table>
        <table class="identitas">
            <tr>
                <td><small>Periode Akademik</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$academic_period"; ?></small></td>
            </tr>
            <tr>
                <td><small>Jenjang / Level</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$class_level"; ?></small></td>
            </tr>
            <tr>
                <td><small>Kelas / Rombel</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$class_name"; ?></small></td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center"><b>No</b></td>
                    <td><b>Siswa</b></td>
                    <td><b>NIS</b></td>
                    <td align="right"><b>Nominal</b></td>
                    <td align="right"><b>Diskon</b></td>
                    <td align="right"><b>Tagihan</b></td>
                    <td align="right"><b>Pembayaran</b></td>
                    <td align="right"><b>Sisa/Tunggakan</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    //jika data tidak ada
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="8" align="center">Tidak Ada Data Tagihan & Pembayaran Untuk Kelas Ini</td>
                            </tr>
                        ';
                    }else{
                        //Inisialisasi Total
                        $total_nomiinal     = 0;
                        $total_diskon       = 0;
                        $total_tagihan      = 0;
                        $total_pembayaran   = 0;
                        $total_tunggakan    = 0;

                        //Inisiaslisasi Nomor
                        $no = 1;

                        //Tampilkan Data Siswa (student) Dari Tabel fee_by_student
                        $query = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'");

                        //Looping Tampiilkan Data
                        while ($data = mysqli_fetch_array($query)) {
                            $id_student      = $data['id_student'];

                            //Buka 'student_name' dan 'student_nis' dari tabel 'student_name'
                            $student_name   = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
                            $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

                            //Inisialiasai fee_nominal dan fee_discount
                            $jumlah_fee_nominal     = 0;
                            $jumlah_fee_discount    = 0;
                            $jumlah_tagihan         = 0;

                            //Looping Data dari tabel 'fee_by_student' 
                            $query_fee_by_student = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'");
                            while ($data_fee_by_student = mysqli_fetch_array($query_fee_by_student)) {
                                $fee_nominal    = $data_fee_by_student['fee_nominal'];
                                $fee_discount   = $data_fee_by_student['fee_discount'];
                                $tagihan        = $fee_nominal - $fee_discount;

                                //Akumulasikan fee_nominal dan fee_discount
                                $jumlah_fee_nominal     = $jumlah_fee_nominal + $fee_nominal;
                                $jumlah_fee_discount    = $jumlah_fee_discount + $fee_discount;
                                $jumlah_tagihan         = $jumlah_tagihan + $tagihan;
                            }

                            //Hitung Jumlah Pembayaran
                            $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
                            $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];

                            //Sisa tunggakan
                            $sisa_tunggakan = $jumlah_tagihan - $jumlah_pembayaran;

                            //Akumulasi Total
                            $total_nomiinal     = $total_nomiinal + $jumlah_fee_nominal;
                            $total_diskon       = $total_diskon + $fee_discount;
                            $total_tagihan      = $total_tagihan + $jumlah_tagihan;
                            $total_pembayaran   = $total_pembayaran + $jumlah_pembayaran;
                            $total_tunggakan    = $total_tunggakan + $sisa_tunggakan;

                            //Format Rupiah
                            $jumlah_fee_nominal_format  = "Rp " . number_format($jumlah_fee_nominal,0,',','.');
                            $jumlah_fee_discount_format = "Rp " . number_format($jumlah_fee_discount,0,',','.');
                            $jumlah_tagihan_format      = "Rp " . number_format($jumlah_tagihan,0,',','.');
                            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
                            $sisa_tunggakan_format      = "Rp " . number_format($sisa_tunggakan,0,',','.');

                            //Routing style sisa tunggakan
                            if(empty($sisa_tunggakan)){
                                $style_sisa_tunggakan = "text-success";
                            }else{
                                $style_sisa_tunggakan = "text-danger";
                            }
                        
                                
                            echo '
                                <tr>
                                    <td align="center"><small>'.$no.'</small></td>
                                    <td><small>'.$student_name.'</small></td>
                                    <td><small>'.$student_nis.'</small></td>
                                    <td align="right"><small>'.$jumlah_fee_nominal_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_fee_discount_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_tagihan_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                                    <td align="right"><small>'.$sisa_tunggakan_format.'</small></td>
                                </tr>
                            ';
                            $no++;
                        }

                        //Format Total
                        $total_nomiinal_format      = "Rp " . number_format($total_nomiinal,0,',','.');
                        $total_diskon_format        = "Rp " . number_format($total_diskon,0,',','.');
                        $total_tagihan_format       = "Rp " . number_format($total_tagihan,0,',','.');
                        $total_pembayaran_format    = "Rp " . number_format($total_pembayaran,0,',','.');
                        $total_tunggakan_format     = "Rp " . number_format($total_tunggakan,0,',','.');

                        //Tampilkan Total
                        echo '
                            <tr>
                                <td></td>
                                <td colspan="2" class="text-end">
                                    <small><b>JUMLAH / TOTAL</b></small>
                                </td>
                                <td align="right"><small><b>'.$total_nomiinal_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_diskon_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_tagihan_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_pembayaran_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_tunggakan_format.'</b></small></td>
                            </tr>
                        ';
                        //Buat Variabel  'title_rekapitulasi_tagihan_siswa'
                        $title_rekapitulasi_tagihan_siswa = '
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-4"><small>Level/Jenjang</small></div>
                                        <div class="col-1"><small>:</small></div>
                                        <div class="col-7"><small class="text text-grayish">'.$class_level.'</small></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4"><small>Kelas</small></div>
                                        <div class="col-1"><small>:</small></div>
                                        <div class="col-7"><small class="text text-grayish">'.$class_name.'</small></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-4"><small>Periode Akademik</small></div>
                                        <div class="col-1"><small>:</small></div>
                                        <div class="col-7"><small class="text text-grayish">'.$academic_period.'</small></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4"><small>Jumlah Siswa</small></div>
                                        <div class="col-1"><small>:</small></div>
                                        <div class="col-7"><small class="text text-grayish">'.$jml_data.' Orang</small></div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>