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
    if(empty($_GET['id_student'])){
       echo 'ID Siswa Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel
    $id_student = validateAndSanitizeInput($_GET['id_student']);

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
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_organization_class FROM fee_by_student WHERE id_student='$id_student'"));
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Tagihan Per Periode Siswa</title>
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
                    <span class="title_report">TAGIHAN PER PERIODE SISWA</span>
                </td>
            </tr>
        </table>
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
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td><b><small>No</small></b></td>
                    <td><b><small>Periode Akademik</small></b></td>
                    <td><b><small>Jenjang/Level</small></b></td>
                    <td><b><small>Kelas/Rombel</small></b></td>
                    <td><b><small>K.B.P</small></b></td>
                    <td><b><small>Tagihan</small></b></td>
                    <td><b><small>Diskon</small></b></td>
                    <td><b><small>Jumlah Tagihan</small></b></td>
                    <td><b><small>Pembayaran</small></b></td>
                    <td><b><small>Sisa Tunggakan</small></b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    //jika data tidak ada
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="10" align="center">Tidak Ada Tagihan & Pembayaran Untuk Siswa Ini</td>
                            </tr>
                        ';
                    }else{
                        //Atur Nomor
                        $no = 1;

                        //Inisialisasi Jumlah Total
                        $subtotal_komonen           = 0;
                        $subtotal_tagihan           = 0;
                        $subtotal_diskon            = 0;
                        $subtotal_tagihan_netto     = 0;
                        $subtotal_pembayaran        = 0;
                        $subtotal_tunggakan         = 0;
                        //Looping Query
                        $query = mysqli_query($Conn, "SELECT DISTINCT id_organization_class FROM fee_by_student WHERE id_student='$id_student'");
                        while ($data = mysqli_fetch_array($query)) {
                            $id_organization_class = $data['id_organization_class'];

                            //Buka Informasi Kelas pada tabel 'organization_class'
                            $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
                            $level_student      = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                            $kelas_student      = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

                            //Buka Periode Akademik
                            $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

                            //Menghitung Komponen Biaya Pendidikan
                            $jumlah_komponen    = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));

                            //Menghitung jumlah tagihan
                            $SumTagihan             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS jumlah_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
                            $jumlah_tagihan         = $SumTagihan['jumlah_tagihan'];
                            $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');

                            //Menghitung Jumlah Diskon
                            $SumDiskon             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
                            $jumlah_diskon         = $SumDiskon['jumlah_diskon'];
                            $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');

                            //Jumlah Tagihan Netto
                            $jumlah_tagihan_netto           = $jumlah_tagihan-$jumlah_diskon;
                            $jumlah_tagihan_netto_format    = "Rp " . number_format($jumlah_tagihan_netto,0,',','.');

                            //Hitung Jumlah Pembayaran
                            $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
                            $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];
                            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');

                            //Menghitung Sisa Tagihan
                            $jumlah_sisa_tagihan        = $jumlah_tagihan_netto-$jumlah_pembayaran;
                            $jumlah_sisa_tagihan_format = "Rp " . number_format($jumlah_sisa_tagihan,0,',','.');

                            //menampilkan data pada baris tabel
                            echo '
                                <tr>
                                    <td><small>'.$no.'</small></td>
                                    <td><small>'.$academic_period.'</small></td>
                                    <td><small>'.$level_student.'</small></td>
                                    <td><small>'.$kelas_student.'</small></td>
                                    <td align="center"><small>'.$jumlah_komponen.'</small></td>
                                    <td align="right"><small>'.$jumlah_tagihan_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_diskon_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_tagihan_netto_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_sisa_tagihan_format.'</small></td>
                                </tr>
                            ';
                            $no++;

                            //Menghitung Subtotal
                            $subtotal_komonen           = $subtotal_komonen + $jumlah_komponen;
                            $subtotal_tagihan           = $subtotal_tagihan + $jumlah_tagihan;
                            $subtotal_diskon            = $subtotal_diskon + $jumlah_diskon;
                            $subtotal_tagihan_netto     = $subtotal_tagihan_netto + $jumlah_tagihan_netto;
                            $subtotal_pembayaran        = $subtotal_pembayaran + $jumlah_pembayaran;
                            $subtotal_tunggakan         = $subtotal_tunggakan + $jumlah_sisa_tagihan;
                        }
                        //Format Rupiah
                        $subtotal_tagihan_format        = "Rp " . number_format($subtotal_tagihan,0,',','.');
                        $subtotal_diskon_format         = "Rp " . number_format($subtotal_diskon,0,',','.');
                        $subtotal_tagihan_netto_format  = "Rp " . number_format($subtotal_tagihan_netto,0,',','.');
                        $subtotal_pembayaran_format     = "Rp " . number_format($subtotal_pembayaran,0,',','.');
                        $subtotal_tunggakan_format      = "Rp " . number_format($subtotal_tunggakan,0,',','.');

                        //Menampilkan Total
                        echo '
                            <tr>
                                <td></td>
                                <td colspan="3"><small><b>JUMLAH</b></small></td>
                                <td align="center"><small><b>'.$subtotal_komonen.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_tagihan_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_diskon_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_tagihan_netto_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_pembayaran_format.'</b></small></td>
                                <td align="right"><small><b>'.$subtotal_tunggakan_format.'</b></small></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>