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
    if(empty($_GET['id'])){
       echo 'ID Pembayaran Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel
    $id_payment = validateAndSanitizeInput($_GET['id']);

    //Buka Data Pembayaran
    $id_fee_by_student      = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'id_fee_by_student');
    $payment_datetime       = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'payment_datetime');
    $payment_nominal        = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'payment_nominal');
    $payment_method         = GetDetailData($Conn, 'payment', 'id_payment', $id_payment, 'payment_method');
    $tanggal_bayar          = date('d F Y',strtotime($payment_datetime));
    $jam_bayar              = date('H:i T',strtotime($payment_datetime));
    $payment_nominal_format = "Rp " . number_format($payment_nominal,0,',','.');
    //Buka 'id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount' dari tabel 'fee_by_student' 
    $id_organization_class  = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_organization_class');
    $id_student             = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');
    $id_fee_component       = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_fee_component');
    $fee_nominal            = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'fee_nominal');
    $fee_discount           = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'fee_discount');
    $jumlah_tagihan         = $fee_nominal-$fee_discount;
    $fee_nominal_format     = "Rp " . number_format($fee_nominal,0,',','.');
    $fee_discount_format    = "Rp " . number_format($fee_discount,0,',','.');
    $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');

    //Membuka 'student_nis, student_name, student_gender, student_status dari table' 'student'
    $student_nis            = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name           = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_gender         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');
    $student_status         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_status');
    $id_organization_class2 = GetDetailData($Conn, 'student', 'id_student', $id_student, 'id_organization_class');
    $class_level1           = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class2, 'class_level');
    $class_name1            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class2, 'class_name');

    //Buka Periode Akademik
    $id_academic_period     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name             = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka Data Komponen
    $component_name         = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
    $periode_month          = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year           = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
    $nama_bulan             = getNamaBulan($periode_month);

    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment  WHERE id_fee_by_student='$id_fee_by_student'"));

    //Susun text
    $text = "$app_base_url/_Page/Exporter/ExporterPembayaran.php?id=$id_payment";
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Tanda Bukti Pembayaran Siswa</title>
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
            table.tabel_uraiian{
                margin-bottom : 20px;
                border-bottom: 3px double #000;
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
        <table width="100%" class="tabel_uraiian">
            <tr>
                <td align="center">
                    <b>TANDA BUKTI PEMBAYARAN</b><br><br>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" class="tabel_uraiian">
             <tr>
                <td><small>Petugas</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$access_name"; ?></small></td>
                <td></td>
                <td><small>Tanggal Bayar</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$tanggal_bayar"; ?></small></td>
            </tr>
            <tr>
                <td><small>Nama Siswa</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$student_name"; ?></small></td>
                <td></td>
                <td><small>Jam Bayar</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$jam_bayar"; ?></small></td>
            </tr>
            <tr>
                <td><small>NIS</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$student_nis"; ?></small></td>
                <td></td>
                <td><small>Komponen Biaya</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$component_name"; ?></small></td>
            </tr>
            <tr>
                <td><small>Gender</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$student_gender"; ?></small></td>
                <td></td>
                <td><small>Kategori</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$component_category"; ?></small></td>
            </tr>
            <tr>
                <td><small>Periode Akademik</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$academic_period"; ?></small></td>
                <td></td>
                <td><small>Periode Bulan</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$nama_bulan"; ?></small></td>
            </tr>
            <tr>
                <td><small>Level/Jenjang</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$class_level"; ?></small></td>
                <td></td>
                <td><small>Periode Tahun</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$periode_year"; ?></small></td>
            </tr>
            <tr>
                <td><small>Kelas / Rombel</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$class_name "; ?></small></td>
                <td></td>
                <td><small>Nominal Pembayaran</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$payment_nominal_format"; ?></small></td>
            </tr>
            <tr>
                <td colspan="7"><br></td>
            </tr>
        </table>
        <table width="100%" class="tabel_uraiian">
            <tr>
                <td align="center">
                    <small>ID Pembayaran</small><br>
                    <img src="../../qr.php?text=<?php echo "$text" ?>" alt="QR Code"><br>
                </td>
            </tr>
        </table>
    </body>
</html>