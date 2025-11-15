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
       exit;
    }
    if(empty($_GET['id_student'])){
       echo 'ID Siswa Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel
    $id_organization_class = validateAndSanitizeInput($_GET['id_organization_class']);
    $id_student = validateAndSanitizeInput($_GET['id_student']);

    //Buka class_name
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');

    //Buka id_academic_period
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');

    //Nama Periode
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka 'student_name' dan 'student_nis' dari tabel 'student_name'
    $student_name   = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_gender = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');

    //Routing $student_gender
    if($student_gender=="Male"){
        $student_gender = "Laki-laki";
    }else{
        if($student_gender=="Female"){
            $student_gender = "Perempuan";
        }else{
            $student_gender = "-";
        }
    }
    
    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student  FROM fee_by_student  WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Rincian Tagihan Siswa</title>
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
                    <span class="title_report">RINCIAN TAGIHAN & PEMBAYARAN SISWA</span>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="50%">
                    <table>
                        <tr>
                            <td><small>Nama Siswa</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$student_name"; ?></small></td>
                        </tr>
                        <tr>
                            <td><small>NIS</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$student_nis"; ?></small></td>
                        </tr>
                        <tr>
                            <td><small>Gender</small></td>
                            <td><small>:</small></td>
                            <td><small><?php echo "$student_gender"; ?></small></td>
                        </tr>
                    </table>
                </td>
                <td width="50%" align="right">
                    <table>
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
                </td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td><b><small>No</small></b></td>
                    <td><b><small>Komponen Biaya Pendidikan</small></b></td>
                    <td><b><small>Nominal Tagihan</small></b></td>
                    <td><b><small>Diskon</small></b></td>
                    <td><b><small>Jumlah Tagihan</small></b></td>
                    <td><b><small>Pembayaran</small></b></td>
                    <td><b><small>Sisa/Tagihan</small></b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    //jika data tidak ada
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="7" align="center">Tidak Ada Rincian Tagihan & Pembayaran Untuk Siswa Ini</td>
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

                        //Tampilkan Data tagihan siswa pada tabel fee_by_student
                        $query = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'");

                        //Looping Tampiilkan Data
                        while ($data = mysqli_fetch_array($query)) {
                            $id_fee_by_student  = $data['id_fee_by_student'];
                            $id_fee_component   = $data['id_fee_component'];
                            $fee_nominal        = $data['fee_nominal'];
                            $fee_discount       = $data['fee_discount'];

                            //Hitung Tgaihan
                            $tagihan            = $fee_nominal - $fee_discount;

                            //Buka Nama Komponen Biaya
                            $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');

                            //Hitung Jumlah Pembayaran
                            $SumPembayaran      = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_by_student='$id_fee_by_student'"));
                            $jumlah_pembayaran  = $SumPembayaran['jumlah_pembayaran'];

                            //Sisa tunggakan
                            $sisa_tunggakan     = $tagihan - $jumlah_pembayaran;

                            //Akumulasi Total
                            $total_nomiinal     = $total_nomiinal + $fee_nominal;
                            $total_diskon       = $total_diskon + $fee_discount;
                            $total_tagihan      = $total_tagihan + $tagihan;
                            $total_pembayaran   = $total_pembayaran + $jumlah_pembayaran;
                            $total_tunggakan    = $total_tunggakan + $sisa_tunggakan;

                            //Format Rupiah
                            $fee_nominal_format         = "Rp " . number_format($fee_nominal,0,',','.');
                            $fee_discount_format        = "Rp " . number_format($fee_discount,0,',','.');
                            $tagihan_format             = "Rp " . number_format($tagihan,0,',','.');
                            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
                            $sisa_tunggakan_format      = "Rp " . number_format($sisa_tunggakan,0,',','.');

                            //Routiing tooltip san style
                            if(empty($sisa_tunggakan)){
                                $tooltip_sisa="Lunas";
                                $style_sisa_tagihan = "text-success";
                            }else{
                                $tooltip_sisa="Perlu Pembayaran";
                                $style_sisa_tagihan = "text-danger";
                            }
                                
                            echo '
                                <tr>
                                    <td><small>'.$no.'</small></td>
                                    <td><small>'.$component_name.'</small></td>
                                    <td align="right"><small>'.$fee_nominal_format.'</small></td>
                                    <td align="right"><small>'.$fee_discount_format.'</small></td>
                                    <td align="right"><small>'.$tagihan_format.'</small></td>
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
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>