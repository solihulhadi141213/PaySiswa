<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";

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
        <title>Rekapitulasi Komponen Biaya</title>
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
                        REKAPITULASI TAGIHAN & PEMBAYARAN SISWA <br>
                        BERDASARKAN KOMPONEN BIAYA PENDIDIKAN <br>
                        <span class="title_report">PERIODE AKADEMIK <?php echo $academic_period ?></span>
                    </b>
                </td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center" valign="middle"><b><small>No</small></b></td>
                    <td align="center" valign="middle"><b><small>Komponen Biaya</small></b></td>
                    <td align="center" valign="middle"><b><small>Kategori</small></b></td>
                    <td align="center" valign="middle"><b><small>Bulan</small></b></td>
                    <td align="center" valign="middle"><b><small>Tahun</small></b></td>
                    <td align="center" valign="middle"><b><small>Tempo</small></b></td>
                    <td align="center" valign="middle"><b><small>Tarif / Biaya</small></b></td>
                    <td align="center" valign="middle"><b><small>Diskon / Potongan</small></b></td>
                    <td align="center" valign="middle"><b><small>Tagihan</small></b></td>
                    <td align="center" valign="middle"><b><small>Pembayaran</small></b></td>
                    <td align="center" valign="middle"><b><small>Sisa / Tunggakan</small></b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="10" class="text-center">
                                    <small class="text-danger">Tidak ada data <b>Komponen Biaya</b> pada periode akademik tersebut</small>
                                </td>
                            </tr>
                        ';
                    }else{

                        //Menampilkan Data
                        $total_biaya_pendidikan = 0;
                        $total_diskon = 0;
                        $total_tagihan = 0;
                        $total_pembayaran = 0;
                        $total_tunggakan = 0;

                        //inisiasi nomor urut
                        $no = 1;

                        //Looping Dari Database
                        $query = mysqli_query($Conn, "SELECT*FROM fee_component WHERE id_academic_period='$id_academic_period' ORDER BY component_category DESC, periode_year ASC, periode_month ASC");
                        while ($data = mysqli_fetch_array($query)) {
                            $id_fee_component   = $data['id_fee_component'];
                            $component_name     = $data['component_name'];
                            $component_category = $data['component_category'];
                            $periode_month      = $data['periode_month'];
                            $periode_year       = $data['periode_year'];
                            $periode_start      = $data['periode_start'];
                            $periode_end        = $data['periode_end'];
                            $fee_nominal        = $data['fee_nominal'];
                            
                            //Format Rupiah
                            $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');

                            //Nama Bulan 
                            $nama_bulan=getNamaBulan($periode_month);

                            //Menghitung Jumlah Biaya
                            $SumBiaya               = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS jumlah_biaya FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
                            $jumlah_biaya           = $SumBiaya['jumlah_biaya'];
                            $jumlah_biaya_format    = "Rp " . number_format($jumlah_biaya,0,',','.');

                            //Menghitung Jumlah Diskon
                            $SumDiskon               = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
                            $jumlah_diskon           = $SumDiskon['jumlah_diskon'];
                            $jumlah_diskon_format    = "Rp " . number_format($jumlah_diskon,0,',','.');

                            //Menghitung Jumlah Nominal Tagihan
                            $SumTagihan                 = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS jumlah_tagihan FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
                            $jumlah_rp_tagihan          = $SumTagihan['jumlah_tagihan'];
                            $jumlah_rp_tagihan_format   = "Rp " . number_format($jumlah_rp_tagihan,0,',','.');

                            //Menghitung Jumlah Pembayaran
                            $SumPembayaran                  = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_component='$id_fee_component'"));
                            $jumlah_pembayaran              = $SumPembayaran['jumlah_pembayaran'];
                            $jumlah_pembayaran_format       = "Rp " . number_format($jumlah_pembayaran,0,',','.');

                            $sisa_tagihan = $jumlah_rp_tagihan - $jumlah_pembayaran;
                            $sisa_tagihan_format       = "Rp " . number_format($sisa_tagihan,0,',','.');

                            //Tampilkan Data
                            echo '
                                <tr>
                                    <td><small>'.$no.'</small></td>
                                    <td><small>'.$component_name.'</small></td>
                                    <td><small>'.$component_category.'</small></td>
                                    <td><small>'.$nama_bulan.'</small></td>
                                    <td><small>'.$periode_year.'</small></td>
                                    <td><small>'.date('d/m/y', strtotime($periode_start)).' - '.date('d/m/y', strtotime($periode_end)).'</small></td>
                                    <td align="right"><small>'.$jumlah_biaya_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_diskon_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_rp_tagihan_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                                    <td align="right"><small>'.$sisa_tagihan_format.'</small></td>
                                </tr>
                            ';
                            $no++;

                            //Akumulasi
                            $total_biaya_pendidikan = $total_biaya_pendidikan + $jumlah_biaya;
                            $total_diskon           = $total_diskon + $jumlah_diskon;
                            $total_tagihan          = $total_tagihan + $jumlah_rp_tagihan;
                            $total_pembayaran       = $total_pembayaran + $jumlah_pembayaran;
                            $total_tunggakan        = $total_tunggakan + $sisa_tagihan;
                        }
                        
                        //Format Akumulasi
                        $total_biaya_pendidikan_format  = "Rp " . number_format($total_biaya_pendidikan,0,',','.');
                        $total_diskon_format            = "Rp " . number_format($total_diskon,0,',','.');
                        $total_tagihan_format           = "Rp " . number_format($total_tagihan,0,',','.');
                        $total_pembayaran_format        = "Rp " . number_format($total_pembayaran,0,',','.');
                        $total_tunggakan_format         = "Rp " . number_format($total_tunggakan,0,',','.');

                        //Tampilkan Akumulasi
                        echo '
                            <tr>
                                <td></td>
                                <td align="right" colspan="5"><small><b>JUMLAH/TOTAL</b></small></td>
                                <td align="right"><small><b>'.$total_biaya_pendidikan_format.'</b></small></td>
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
        <?php
            // Membuat QR Code Dokumen
            $text = "$app_base_url/_Page/Exporter/ExportKomponenBiaya.php?id_academic_period=$id_academic_period";
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