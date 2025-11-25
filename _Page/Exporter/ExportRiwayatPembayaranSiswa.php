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
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment WHERE id_student='$id_student'"));
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
                    <span class="title_report">RIWAYAT PEMBAYARAN SISWA</span>
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
                    <td><b><small>Tanggal</small></b></td>
                    <td><b><small>Jam</small></b></td>
                    <td><b><small>Komponen Biaya Pendidikan</small></b></td>
                    <td><b><small>Kategori</small></b></td>
                    <td><b><small>Bulan</small></b></td>
                    <td><b><small>Tahun</small></b></td>
                    <td><b><small>Metode</small></b></td>
                    <td><b><small>Pembayaran</small></b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    //jika data tidak ada
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="9" align="center">Tidak Ada Riwayat Pembayaran Untuk Siswa Ini</td>
                            </tr>
                        ';
                    }else{
                        //Inisialisasi Nomor Urut
                        $no=1;

                        //Inisialisasi Pembayaran
                        $subtotal_pembayaran = 0;

                        $query = mysqli_query($Conn, "SELECT*FROM payment WHERE id_student='$id_student' ORDER BY id_payment ASC");
                        while ($data = mysqli_fetch_array($query)) {
                            $id_payment = $data['id_payment'];
                            $id_fee_component= $data['id_fee_component'];
                            $payment_datetime = $data['payment_datetime'];
                            $payment_nominal= $data['payment_nominal'];
                            $payment_method= $data['payment_method'];

                            //Akumulasi Pembayaran
                            $subtotal_pembayaran = $subtotal_pembayaran + $payment_nominal;
                            
                            //Format Rupiah
                            $payment_nominal_format="Rp " . number_format($payment_nominal,0,',','.');

                            //Buka Detail Komponen
                            $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                            $component_category = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
                            $periode_month      = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
                            $periode_year       = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

                            //Nama Bulan
                            $bulan              = getNamaBulan($periode_month);

                            echo '
                                <tr>
                                    <td align="center"><small>'.$no.'</small></td>
                                    <td><small>'.date('d/m/Y', strtotime($payment_datetime)).'</small></td>
                                    <td><small>'.date('H:i T', strtotime($payment_datetime)).'</small></td>
                                    <td><small>'.$component_name.'</small></td>
                                    <td><small>'.$component_category.'</small></td>
                                    <td><small>'.$bulan.'</small></td>
                                    <td><small>'.$periode_year.'</small></td>
                                    <td><small>'.$payment_method.'</small></td>
                                    <td align="right"><small>'.$payment_nominal_format.'</small></td>
                                </tr>
                            ';
                            $no++;
                        }
                        //Format total pembayaran
                        $subtotal_pembayaran_format="Rp " . number_format($subtotal_pembayaran,0,',','.');
                        echo '
                            <tr>
                                <td colspan="8"><small><b>JUMLAH PEMBAYARAN</b></small></td>
                                <td align="right"><small><b>'.$subtotal_pembayaran_format.'</b></small></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>