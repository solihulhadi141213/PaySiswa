<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    require_once '../../vendor/autoload.php';

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo 'Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!';
        exit;
    }
    //Tangkap type
    if(empty($_GET['type'])){
        echo 'Tipe Data Tiidak Boleh Kosong!';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_GET['id'])){
        echo 'ID Periode Akademik Tidak Boleh Kosong!';
        exit;
    }

    //Buat variabel
    $type=validateAndSanitizeInput($_GET['type']);
    $id_academic_period=validateAndSanitizeInput($_GET['id']);

    //Buka Informasi Periode Akdemik
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo 'Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    if(empty($jml_data)){
        echo 'Tidak Ada Kelas Untuk Periode Ini';
        exit;
    }

    //Mulai buffer output
    ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Data Tagihan Siswa | <?php echo "Periode : $academic_period"; ?></title>
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
                padding : 15px;
                width: 70px;
            }
            table.identitas tr td{
                font-family: Arial, sans-serif;
            }
            b{
                font-family: Arial, sans-serif !important;
            }

        </style>
    </head>
    <body>
        <table class="header_logo">
            <tr>
                <td class="logo"><img src="../../assets/img/<?php echo "$app_logo"; ?>" alt="Logo" width="70px"></td>
                <td>
                    <b><?php echo "$company_name"; ?></b><br>
                    <?php echo "$company_address"; ?><br>
                    Telepon : <?php echo "$company_contact"; ?> - Email : <?php echo "$company_email"; ?>
                </td>
            </tr>
        </table>
        <table width="100%" class="identitas">
            <tr>
                <td align="center">
                    <h3>TAGIHAN SISWA PER KELAS PERIODE <?php echo $academic_period; ?></h3>
                </td>
            </tr>
        </table>
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center"><b>No</b></th>
                    <td align="left"><b>Kelas</b></th>
                    <td align="left"><b>KBP</b></th>
                    <td align="right"><b>Tagihan</b></th>
                    <td align="right"><b>Diskon</b></th>
                    <td align="right"><b>Pembayaran</b></th>
                    <td align="right"><b>Sisa</b></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Menampilkan Data Kelas
                    $no_kelas           = 1;
                    $total_komponen     = 0;
                    $total_tagihan      = 0;
                    $total_diskon      = 0;
                    $total_pembayaran   = 0;
                    $total_sisa         = 0;
                    $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_level, class_name FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_name ASC");
                    while ($data_kelas = mysqli_fetch_array($query_kelas)) {
                        $id_organization_class = $data_kelas['id_organization_class'];
                        $class_level = $data_kelas['class_level'];
                        $class_name = $data_kelas['class_name'];

                        //Hitung Komponen Biaya
                        $jumlah_komponen    = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));
                        $total_komponen     = $total_komponen+$jumlah_komponen;

                        //Hitung Jumlah Tagihan
                        $SumTagihan             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                        $jumlah_tagihan         = $SumTagihan['total_tagihan'];
                        $jumlah_tagihan_format  = "" . number_format($jumlah_tagihan,0,',','.');
                        $total_tagihan          = $total_tagihan + $jumlah_tagihan;

                        //Hitung Jumlah Diskon
                        $SumDiskon              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS total_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
                        $jumlah_diskon          = $SumDiskon['total_diskon'];
                        $jumlah_diskon_format   = "" . number_format($jumlah_diskon,0,',','.');
                        $total_diskon           = $total_diskon + $jumlah_diskon;

                        //Hitung Pembayaran
                        $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_organization_class='$id_organization_class'"));
                        $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];
                        $jumlah_pembayaran_format   = "" . number_format($jumlah_pembayaran,0,',','.');
                        $total_pembayaran           = $total_pembayaran + $jumlah_pembayaran;

                        //Menghitung Sisa Tagihan
                        $sisa_tagiihan          = $jumlah_tagihan - $jumlah_diskon - $jumlah_pembayaran ;
                        $total_sisa             = $total_sisa + $sisa_tagiihan;
                        $sisa_tagiihan_format   = "" . number_format($sisa_tagiihan,0,',','.');

                        //Tampilkan Data
                        echo '
                            <tr>
                                <td align="center">'.$no_kelas.'</td>
                                <td align="left">'.$class_level.' - '.$class_name.'</td>
                                <td align="right">'.$jumlah_komponen.'</td>
                                <td align="right">'.$jumlah_tagihan_format.'</td>
                                <td align="right">'.$jumlah_diskon_format.'</td>
                                <td align="right">'.$jumlah_pembayaran_format.'</td>
                                <td align="right">'.$sisa_tagiihan_format.'</td>
                            </tr>
                        ';
                        $no_kelas++;
                    }

                    //Format Total
                    $total_tagihan_format       = "" . number_format($total_tagihan,0,',','.');
                    $total_diskon_format        = "" . number_format($total_diskon,0,',','.');
                    $total_pembayaran_format    = "" . number_format($total_pembayaran,0,',','.');
                    $total_sisa_format          = "" . number_format($total_sisa,0,',','.');
                    echo '
                        <tr>
                            <td align="right"></td>
                            <td align="left"><b>JUMLAH TOTAL</b></td>
                            <td align="right"><b>'.$total_komponen.' Rcrd</b></td>
                            <td align="right"><b>'.$total_tagihan_format.'</b></td>
                            <td align="right"><b>'.$total_diskon_format.'</b></td>
                            <td align="right"><b>'.$total_pembayaran_format.'</b></td>
                            <td align="right"><b>'.$total_sisa_format.'</b></td>
                        </tr>
                    ';
                ?>
            </tbody>
        </table>
    </body>
</html>
<?php
    //Akhiri buffer dan ambil konten HTML
    $html = ob_get_clean();

    //Jika tipe file PDF
    if (strtoupper($type) == "PDF") {
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 10,
            'margin_bottom' => 10,
            'margin_header' => 1,
            'margin_footer' => 1
        ]);

        $mpdf->SetTitle("Tagihan Siswa - ");
        $mpdf->WriteHTML($html);
        $mpdf->Output("Tagihan_Siswa_$id_academic_period.pdf", 'I');
        exit;
    } else {
        //Jika bukan PDF, tampilkan HTML biasa
        echo $html;
    }
?>

