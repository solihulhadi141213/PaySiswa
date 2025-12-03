<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";

    // Menghitung Jumlah Data Tahun Ajaran
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period FROM academic_period"));
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tahun Ajaran</title>
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
                margin-bottom : 20px;
                padding-bottom : 5px;
            }
            b{
                font-family: Arial, sans-serif !important;
            }
            .title_report{
                text-decoration: underline;
            }
            table.tabel_uraiian{
                margin-bottom : 20px;
                border-bottom: 3px double #000;
            }
            table.qr_table{
                margin-top: 5px;
            }
            table.qr_table tr td{
                padding: 1px;
            }

        </style>
    </head>
    <body>
        <!-- MENAMPILKAN KOP SURAT YANG TERDIRI DARI LOGO, IDENTITAS SEKOLAH -->
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

        <!-- MENAMPILKAN JUDUL DOKUMEN -->
        <table width="100%" class="identitas">
            <tr>
                <td align="center">
                    <b>
                        REKAPITULASI JUMLAH TAGIHAN & PEMBAYARAN <br>BERDASARKAN TAHUN AJARAN
                    </b>
                    <br><br>
                </td>
            </tr>
        </table>
        
        <!-- MENAMPILKAN TABEL DATA -->
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center"><small><b>No</b></small></td>
                    <td><small><b>Tahun Akademik</b></small></td>
                    <td><small><b>Kelas / Rombel</b></small></td>
                    <td><small><b>Siswa</b></small></td>
                    <td><small><b>Komponen Biaya</b></small></td>
                    <td align="right"><small><b>Biaya / Tagihan</b></small></td>
                    <td align="right"><small><b>Diskon / Potongan</b></small></td>
                    <td align="right"><small><b>Tagihan</b></small></td>
                    <td align="right"><small><b>Pembayaran</b></small></td>
                    <td align="right"><small><b>Sisa / Tunggakan</b></small></td>
                    <td align="right"><small><b>Status</b></small></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (empty($jml_data)) {
                        echo '
                            <tr>
                                <td colspan="11" class="text-center">
                                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                                </td>
                            </tr>
                        ';
                    } else {
                        $no = 1 ;

                        // Subtotal
                        $subtotal_rombel        = 0;
                        $subtotal_siswa         = 0;
                        $subtotal_komponen      = 0;
                        $subtotal_biaya         = 0;
                        $subtotal_diskon        = 0;
                        $subtotal_tagihan       = 0;
                        $subtotal_pembayaran    = 0;
                        $subtotal_sisa          = 0;

                        // Query Data Periode Akademik
                        $query = "SELECT * FROM academic_period ORDER BY id_academic_period ASC";
                        $result_period = mysqli_query($Conn, $query);

                        while ($data_period = mysqli_fetch_assoc($result_period)) {
                            $id_academic_period     = $data_period['id_academic_period'];
                            $academic_period        = $data_period['academic_period'];
                            $academic_period_start  = $data_period['academic_period_start'];
                            $academic_period_end    = $data_period['academic_period_end'];
                            $academic_period_status = $data_period['academic_period_status'];

                            // --- Bagian Perhitungan Total Menggunakan Query Lebih Efisien ---
                            // *PERHATIAN*: Menggunakan subquery untuk menggabungkan perhitungan dari organization_class, fee_by_student, dan payment.
                            // Ini akan menggantikan banyak loop dan query di dalam loop PHP, menjadikannya lebih efisien.
                            
                            // Query untuk mendapatkan total biaya, diskon, tagihan, pembayaran, dan jumlah siswa dalam satu langkah
                            $query_total = "
                                SELECT
                                    SUM(t1.fee_nominal) AS total_biaya,
                                    SUM(t1.fee_discount) AS total_diskon,
                                    SUM(t1.fee_nominal - t1.fee_discount) AS total_tagihan,
                                    SUM(t2.payment_nominal) AS total_pembayaran,
                                    COUNT(DISTINCT t1.id_student) AS total_siswa
                                FROM fee_by_student t1
                                LEFT JOIN payment t2 ON t1.id_organization_class = t2.id_organization_class
                                WHERE t1.id_organization_class IN (
                                    SELECT id_organization_class
                                    FROM organization_class
                                    WHERE id_academic_period = '$id_academic_period'
                                )
                            ";
                            
                            // *CATATAN OPTIMALISASI*: Query di atas mungkin menghasilkan total pembayaran yang tidak akurat karena JOIN.
                            // Solusi yang lebih akurat adalah menghitung total tagihan dan total pembayaran secara terpisah berdasarkan ID Kelas dalam periode ini,
                            // lalu menjumlahkannya di PHP.

                            $total_biaya = 0;
                            $total_diskon = 0;
                            $total_tagihan = 0;
                            $total_pembayaran = 0;
                            $total_siswa = 0;
                            $jumlah_kelas = 0;
                            $QryKelas = mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'");
                            $jumlah_kelas = mysqli_num_rows($QryKelas);

                            while ($DataKelas = mysqli_fetch_assoc($QryKelas)) {
                                $id_organization_class = $DataKelas['id_organization_class'];

                                // Total Biaya, Diskon, dan Tagihan untuk Kelas ini
                                $SumFee = mysqli_fetch_assoc(mysqli_query($Conn, "SELECT SUM(fee_nominal) AS total_biaya, SUM(fee_discount) AS total_diskon, SUM(fee_nominal-fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                                $total_biaya += (float)$SumFee['total_biaya'];
                                $total_diskon += (float)$SumFee['total_diskon'];
                                $total_tagihan += (float)$SumFee['total_tagihan'];

                                // Total Pembayaran untuk Kelas ini
                                $SumPayment = mysqli_fetch_assoc(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS total_pembayaran FROM payment WHERE id_organization_class='$id_organization_class'"));
                                $total_pembayaran += (float)$SumPayment['total_pembayaran'];

                                // Hitung jumlah siswa unik per kelas dan tambahkan ke total
                                $jumlah_siswa_kelas = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                                $total_siswa += $jumlah_siswa_kelas;
                            }
                            
                            $total_sisa = $total_tagihan - $total_pembayaran;

                            // --- Routing dan Formatting Data ---

                            // Routing 'label_status' dan 'tombol_lanjutan'
                            if ($academic_period_status == 1) { // Menggunakan integer 1/0 lebih baik untuk status boolean
                                $label_status = 'Unlock';
                            } else {
                                $label_status = 'Locked';
                            }

                            // Menghitung Komponen Biaya (K.B.P)
                            $jumlah_fee_component = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));

                            // Format Mata Uang
                            $total_biaya_format         = "Rp " . number_format($total_biaya, 0, ',', '.');
                            $total_diskon_format        = "Rp " . number_format($total_diskon, 0, ',', '.');
                            $total_tagihan_format       = "Rp " . number_format($total_tagihan, 0, ',', '.');
                            $total_pembayaran_format    = "Rp " . number_format($total_pembayaran, 0, ',', '.');
                            $total_sisa_format          = "Rp " . number_format($total_sisa, 0, ',', '.');

                            // Routing 'LabelSisaTagihan'
                            if (empty($total_sisa)) {
                                $LabelSisaTagihan = '<span class="text text-success">'.$total_sisa_format.'</span>';
                            } else {
                                $LabelSisaTagihan = '<span class="text text-danger">'.$total_sisa_format.'</span>';
                            }

                            // Tampilkan Data
                            echo '
                                <tr>
                                    <td><small>'.$no.'</small></td>
                                    <td><small>'.$academic_period.'</small></td>
                                    <td><small>'.$jumlah_kelas.' Kelas</small></td>
                                    <td><small>'.$total_siswa.' Orang</small></td>
                                    <td><small>'.$jumlah_fee_component.' Komponen</small></td>
                                    <td align="right"><small>'.$total_biaya_format.'</small></td>
                                    <td align="right"><small>'.$total_diskon_format.'</small></td>
                                    <td align="right"><small>'.$total_tagihan_format.'</small></td>
                                    <td align="right"><small>'.$total_pembayaran_format.'</small></td>
                                    <td align="right"><small>'.$total_sisa_format.'</small></td>
                                    <td><small>'.$label_status.'</small></td>
                                </tr>
                            ';
                            $no++;

                            // Akumulasi
                            $subtotal_biaya         = $subtotal_biaya + $total_biaya;
                            $subtotal_diskon        = $subtotal_diskon + $total_diskon;
                            $subtotal_tagihan       = $subtotal_tagihan + $total_tagihan;
                            $subtotal_pembayaran    = $subtotal_pembayaran + $total_pembayaran;
                            $subtotal_sisa          = $subtotal_sisa + $total_sisa;
                        }

                        // Format subtotal
                        $subtotal_biaya_format      = "Rp " . number_format($subtotal_biaya, 0, ',', '.');
                        $subtotal_diskon_format     = "Rp " . number_format($subtotal_diskon, 0, ',', '.');
                        $subtotal_tagihan_format    = "Rp " . number_format($subtotal_tagihan, 0, ',', '.');
                        $subtotal_pembayaran_format = "Rp " . number_format($subtotal_pembayaran, 0, ',', '.');
                        $subtotal_sisa_format       = "Rp " . number_format($subtotal_sisa, 0, ',', '.');

                        // Tampilkan Baris Akhir
                        echo '
                            <tr>
                                <td align="right" colspan="5"><b>JUMLAH TOTAL</b></td>
                                <td align="right"><b><small>'.$subtotal_biaya_format.'</small></b></td>
                                <td align="right"><b><small>'.$subtotal_diskon_format.'</small></b></td>
                                <td align="right"><b><small>'.$subtotal_tagihan_format.'</small></b></td>
                                <td align="right"><b><small>'.$subtotal_pembayaran_format.'</small></b></td>
                                <td align="right"><b><small>'.$subtotal_sisa_format.'</small></b></td>
                                <td align="right"><b></b></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
        <?php
            // Membuat QR Code Dokumen
            $text = "$app_base_url/_Page/Exporter/ExportTahunAjaran.php";
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
