<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/FungsiAkses.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo 'Sesi Akses Sudah Berakhir! Silahkan Login Ulang!';
        exit;
    }

    //Validasi Data tidak boleh kosong
    if(empty($_GET['id_academic_period'])){
       echo 'ID Periode Akademik Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel
    $id_academic_period = validateAndSanitizeInput($_GET['id_academic_period']);

    //Buka Detail periode akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Rekapitulasi Tagihan Kelas</title>
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
                        REKAPITULASI TAGIHAN & PEMBAYARAN BERDASARKAN KELAS<br>
                        <span class="title_report">PERIODE AKADEMIK <?php echo $academic_period ?></span>
                    </b>
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
                <td><small>Periode Mulai</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$academic_period_start"; ?></small></td>
            </tr>
            <tr>
                <td><small>Periode Berakhir</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$academic_period_end"; ?></small></td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center" valign="middle"><b><small>No</small></b></td>
                    <td align="center" valign="middle" colspan="2"><b><small>Jenjang / Kelas</small></b></td>
                    <td align="center" valign="middle"><b><small>Siswa</small></b></td>
                    <td align="center" valign="middle"><b><small>K.B.P</small></b></td>
                    <td align="center" valign="middle"><b><small>Nominal Tagihan</small></b></td>
                    <td align="center" valign="middle"><b><small>Diskon</small></b></td>
                    <td align="center" valign="middle"><b><small>Jumlah Tagihan</small></b></td>
                    <td align="center" valign="middle"><b><small>Pembayaran</small></b></td>
                    <td align="center" valign="middle"><b><small>Sisa/Tunggakan</small></b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="10" class="text-center">
                                    <small class="text-danger">Tidak ada data <b>Kelas</b> pada periode akademik tersebut</small>
                                </td>
                            </tr>
                        ';
                    }else{
                        //Tampilkan Data Level
                        $no_level=1;
                        $jumlah_level=0;
                        $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
                        while ($data_level = mysqli_fetch_array($query_level)) {
                            $class_level = $data_level['class_level'];

                            //Hitung Jumlah Level
                            $jumlah_level=$jumlah_level+1;
                            echo '
                                <tr>
                                    <td align="center" class="bg bg-body-secondary"><b><small>'.$no_level.'</small></b></td>
                                    <td colspan="2" class="bg bg-body-secondary"><b><small>'.$class_level.'</small></b></td>
                                    <td colspan="7"></td>
                                </tr>
                            ';
                            //Menampilkan List Kelas
                            $no_kelas=1;
                            $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
                            while ($data_kelas = mysqli_fetch_array($query_kelas)) {
                                $id_organization_class = $data_kelas['id_organization_class'];
                                $class_name = $data_kelas['class_name'];

                                //Hitung Jumlah Siswa Pada Tabel 'fee_by_student'
                                $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));

                                //Hitung Jumlah Siswa Pada Tabel 'student'
                                $jumlah_siswa_aktual=mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM student WHERE id_organization_class='$id_organization_class'"));

                                //Hitung Komponen Biaya
                                $jumlah_komponen=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));

                                //Hitung Jumlah Nomiinal Tagihan
                                $SumNominalTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS nominal_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                                $jumlah_nominal_tagihan = $SumNominalTagihan['nominal_tagihan'];
                                $jumlah_nominal_tagihan_format  = "Rp " . number_format($jumlah_nominal_tagihan,0,',','.');

                                //Hitung Jumlah Diskon
                                $SumDiskon = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                                $jumlah_diskon = $SumDiskon['jumlah_diskon'];
                                $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');

                                //Hitung Jumlah Tagihan
                                $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal - fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                                $jumlah_tagihan = $SumTagihan['total_tagihan'];
                                $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');
                                $jumlah_tagihan_format2 = "" . number_format($jumlah_tagihan,0,',','.');

                                //Hitung Jumlah Pembayaran
                                $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class'"));
                                $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
                                $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
                                
                                //Sisa Tagihan
                                $sisa_tagihan=$jumlah_tagihan-$jumlah_pembayaran;
                                $sisa_tagihan_format = "Rp " . number_format($sisa_tagihan,0,',','.');
                                
                                //Tampilkan Data
                                echo '
                                <tr>
                                    <td align="left"></td>
                                    <td><small>'.$no_level.'.'.$no_kelas.'</small></td>
                                    <td><small>'.$class_level.' ('.$class_name.')</small></td>
                                    <td><small>'.$jumlah_siswa.' / '.$jumlah_siswa_aktual.'</small></td>
                                    <td><small>'.$jumlah_komponen.'</small></td>
                                    <td><small>'.$jumlah_nominal_tagihan_format.'</small></td>
                                    <td><small>'.$jumlah_diskon_format.'</small></td>
                                    <td><small>'.$jumlah_tagihan_format.'</small></td>
                                    <td><small>'.$jumlah_pembayaran_format.'</small></td>
                                    <td><small>'.$sisa_tagihan_format.'</small></td>
                                </tr>
                            ';
                            }

                            $no_level++;
                        }
                    }
                ?>
            </tbody>
        </table>
        <?php
            // Membuat QR Code Dokumen
            $text = "$app_base_url/_Page/Exporter/ExportKelas.php?id_academic_period=$id_academic_period";
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