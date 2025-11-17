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
    if(empty($_GET['id_fee_component'])){
       echo 'ID Periode Akademik Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel
    $id_fee_component = validateAndSanitizeInput($_GET['id_fee_component']);

    //Buyka Komponen
    $id_academic_period     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'id_academic_period');
    $periode_month          = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year           = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
    $component_name         = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

    //Nama Bulan 
    $nama_bulan=getNamaBulan($periode_month);

    //Buka Detail periode akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period'"));
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Komponen Biaya (Kelas)</title>
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
                    <span class="title_report">REKAPITULASI TAGIHAN & PEMBAYARAN BERDASARKAN KOMPONEN BIAYA PER KELAS</span>
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
                <td><small><?php echo "$nama_bulan $periode_year"; ?></small></td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center" valign="middle"><b><small>No</small></b></td>
                    <td align="center" valign="middle" colspan="2"><b><small>Jenjang / Kelas</small></b></td>
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
                                <td colspan="7" class="text-center">
                                    <small class="text-danger">Tidak ada data <b>Kelas</b> pada periode akademik tersebut</small>
                                </td>
                            </tr>
                        ';
                    }else{
                        //Inisiasi Akumulasi
                        $total_tagihan          = 0;
                        $total_diskon           = 0;
                        $total_tagihan_bersih   = 0;
                        $total_pembayaran       = 0;
                        $total_sisa             = 0;
                        //Menampilkan data kelas
                        $no_level=1;
                        $jumlah_level=0;
                        $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
                        while ($data_level = mysqli_fetch_array($query_level)) {
                            $class_level = $data_level['class_level'];

                            //Hitung Jumlah Level
                            $jumlah_level=$jumlah_level+1;
                            echo '
                                <tr>
                                    <td align="center"><b>'.$no_level.'</b></td>
                                    <td colspan="7"><b>'.$class_level.'</b></td>
                                </tr>
                            ';
                            //Menampilkan List Kelas
                            $no_kelas=1;
                            $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
                            while ($data_kelas = mysqli_fetch_array($query_kelas)) {
                                $id_organization_class = $data_kelas['id_organization_class'];
                                $class_name = $data_kelas['class_name'];

                                //menghitung jumlah tagihan
                                $SumNominalTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS nominal_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                                $jumlah_nominal_tagihan = $SumNominalTagihan['nominal_tagihan'];
                                $jumlah_nominal_tagihan_format  = "Rp " . number_format($jumlah_nominal_tagihan,0,',','.');

                                //Hitung Jumlah Diskon
                                $SumDiskon = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                                $jumlah_diskon = $SumDiskon['jumlah_diskon'];
                                $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');

                                //Hitung Jumlah Tagihan
                                $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal - fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                                $jumlah_tagihan = $SumTagihan['total_tagihan'];
                                $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');

                                //Hitung Jumlah Pembayaran
                                $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                                $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
                                $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');

                                //Menghitung Sisa Tagihan
                                $sisa_tagihan = $jumlah_tagihan-$jumlah_pembayaran;
                                $sisa_tagihan_format   = "Rp " . number_format($sisa_tagihan,0,',','.');

                                //Hitung akumulasi
                                $total_tagihan          = $total_tagihan + $jumlah_nominal_tagihan;
                                $total_diskon           = $total_diskon + $jumlah_diskon;
                                $total_tagihan_bersih   = $total_tagihan_bersih + $jumlah_tagihan;
                                $total_pembayaran       = $total_pembayaran + $jumlah_pembayaran;
                                $total_sisa             = $total_sisa + $sisa_tagihan;

                                echo '
                                    <tr>
                                        <td align="left"></td>
                                        <td align="right"><small>'.$no_level.'.'.$no_kelas.'</small></td>
                                        <td><small>'.$class_name.'</small></td>
                                        <td align="right"><small>'.$jumlah_nominal_tagihan_format.'</small></td>
                                        <td align="right"><small>'.$jumlah_diskon_format.'</small></td>
                                        <td align="right"><small>'.$jumlah_tagihan_format.'</small></td>
                                        <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                                        <td align="right"><small>'.$sisa_tagihan_format.'</small></td>
                                    </tr>
                                ';

                                $no_kelas++;
                            }
                            $no_level++;
                        }

                        //Format akumulasi
                        $total_tagihan_format          = "Rp " . number_format($total_tagihan,0,',','.');
                        $total_diskon_format           = "Rp " . number_format($total_diskon,0,',','.');
                        $total_tagihan_bersih_format   = "Rp " . number_format($total_tagihan_bersih,0,',','.');
                        $total_pembayaran_format       = "Rp " . number_format($total_pembayaran,0,',','.');
                        $total_sisa_format             = "Rp " . number_format($total_sisa,0,',','.');

                        //Tampilkan Akumulasi
                        echo '
                            <tr>
                                <td colspan="3"><small><b>JUMLAH/TOTAL</b></small></td>
                                <td align="right"><small><b>'.$total_tagihan_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_diskon_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_tagihan_bersih_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_pembayaran_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_sisa_format.'</b></small></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>