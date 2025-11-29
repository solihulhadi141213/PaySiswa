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
    if(empty($_GET['id_fee_by_student'])){
       echo 'ID Tagihan Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel
    $id_fee_by_student = validateAndSanitizeInput($_GET['id_fee_by_student']);

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

    //Nama Bulan
    $nama_bulan         = getNamaBulan($periode_month);

    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment  WHERE id_fee_by_student='$id_fee_by_student'"));
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Detail Tagihan Siswa</title>
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
            table.nama_dokumen{
               border-bottom: 3px double #000;
            }
            table.nama_dokumen tr td{
                font-family: Arial, sans-serif;
                padding-bottom : 20px;
            }
            table.identitas{
                border-bottom: 3px double #000;
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
        <table width="100%" class="nama_dokumen">
            <tr>
                <td align="center">
                    <b>DETAIL INFORMASI TAGIHAN</b>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" class="identitas">
            <tr>
                <td><small>Nama Siswa</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$student_name"; ?></small></td>
                <td></td>
                <td><small>Komponen Biaya</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$component_name"; ?></small></td>
            </tr>
            <tr>
                <td><small>NIS</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$student_nis"; ?></small></td>
                <td></td>
                <td><small>Kategori</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$component_category"; ?></small></td>
            </tr>
            <tr>
                <td><small>Gender</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$student_gender"; ?></small></td>
                <td></td>
                <td><small>Periode</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$nama_bulan $periode_year"; ?></small></td>
            </tr>
            <tr>
                <td><small>Periode Akademik</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$academic_period"; ?></small></td>
                <td></td>
                <td><small>Nominal Tagihan</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$fee_nominal_format"; ?></small></td>
            </tr>
            <tr>
                <td><small>Level/Jenjang</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$class_level"; ?></small></td>
                <td></td>
                <td><small>Diskon/Potongan</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$fee_discount_format"; ?></small></td>
            </tr>
            <tr>
                <td><small>Kelas</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$class_name"; ?></small></td>
                <td></td>
                <td><small>Jumlah Tagihan</small></td>
                <td><small>:</small></td>
                <td><small><?php echo "$jumlah_tagihan_format"; ?></small></td>
            </tr>
            <tr>
                <td colspan="7"><br></td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td align="center">
                    <b>RIWAYAT PEMBAYARAN</b>
                </td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <tr>
                <td align="center"><small><b>No</b></small></td>
                <td align="left"><small><b>Tanggal Pembayaran</b></small></td>
                <td align="left"><small><b>Waktu/Jam</b></small></td>
                <td align="center"><small><b>Metode</b></small></td>
                <td align="right"><small><b>Nominal</b></small></td>
            </tr>
            <tbody>
                <?php
                    //jika data tidak ada
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="4" align="center">Tidak Ada Pembayaran Untuk Tagihan Ini</td>
                            </tr>
                        ';
                    }else{
                        //Menampilkan Riwayat Pembayaran
                        $no = 1;
                        $total_payment =0;
                        $query = mysqli_query($Conn, "SELECT * FROM payment WHERE id_fee_by_student='$id_fee_by_student'");
                        while ($data = mysqli_fetch_array($query)) {
                            $id_payment  = $data['id_payment'];
                            $payment_datetime  = $data['payment_datetime'];
                            $payment_nominal  = $data['payment_nominal'];
                            $payment_method  = $data['payment_method'];

                            //Akumulasii Payment
                            $total_payment = $total_payment + $payment_nominal;

                            //Format Tanggal
                            $payment_datetime_format    = date('d/m/Y', strtotime($payment_datetime));
                            $payment_time_format        = date('H:i T', strtotime($payment_datetime));

                            //Format Nominal
                            $payment_nominal_format     = "Rp " . number_format($payment_nominal,0,',','.');

                            //Tampilkan Data
                            echo '
                                <tr>
                                    <td align="center"><small>'.$no.'</small></td>
                                    <td align="left"><small>'.$payment_datetime_format.'</small></td>
                                    <td align="left"><small>'.$payment_time_format.'</small></td>
                                    <td align="center"><small>'.$payment_method.'</small></td>
                                    <td align="right"><small>'.$payment_nominal_format.'</small></td>
                                </tr>
                            ';
                            $no++;
                        }

                        //Format Total Payment
                        $total_payment_format     = "Rp " . number_format($total_payment,0,',','.');

                        //Routing Status Pembayaran
                        if($jumlah_tagihan<=$total_payment){
                            $status_pembayaran  = "LUNAS";
                        }else{
                            $status_pembayaran  = "-";
                        }
                        echo '
                            <tr>
                                <td align="center"></td>
                                <td align="right" colspan="3"><small><b>TOTAL BAYAR</b></small></td>
                                <td align="right"><small><b>'.$total_payment_format.'</b></small></td>
                            </tr>
                        ';
                        echo '
                            <tr>
                                <td align="center"></td>
                                <td align="right" colspan="3"><small><b>KETERANGAN</b></small></td>
                                <td align="right"><small><b>'.$status_pembayaran.'</b></small></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>