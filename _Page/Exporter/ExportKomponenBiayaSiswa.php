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
    if(empty($_GET['id_fee_component'])){
       echo 'ID Komponen Biaya Tidak Boleh Kosong';
       exit;
    }

    if(empty($_GET['id_organization_class'])){
       echo 'ID Kelas Tidak Boleh Kosong';
       exit;
    }

    //Buat variabel
    $id_fee_component       = validateAndSanitizeInput($_GET['id_fee_component']);
    $id_organization_class  = validateAndSanitizeInput($_GET['id_organization_class']);

    //Buka Data fee_component
    $Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
    $Qry->bind_param("i", $id_fee_component);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo 'Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Jika Data Tidak Ditemukan
    if(empty($Data['id_fee_component'])){
        echo '<small class="text-danger">ID Komponen Biaya Pendidikan Tidak Valid</small>';
        exit;
    }

    //Buat Variabel
    $id_academic_period     = $Data['id_academic_period'];
    $periode_month          = $Data['periode_month'];
    $periode_year           = $Data['periode_year'];
    $component_name         = $Data['component_name'];
    $component_category     = $Data['component_category'];
    
    //Nama Bulan 
    $nama_bulan=getNamaBulan($periode_month);

    //Buka Informasi Periode Akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    //Detail Informasi Kelas
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    
    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Komponen Biaya Siswa</title>
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
                    <span class="title_report">REKAPITULASI BIAYA PENDIDIKAN SISWA</span>
                </td>
            </tr>
        </table>
        <table width="100%" class="identitas">
            <tr>
                <td width="50%">
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
                <td width="50%" align="right">
                    <table>
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
                            <td><small><?php echo ''.$nama_bulan.' '.$periode_year.''; ?></small></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table class="custom-table">
            <thead>
                <tr>
                    <td align="center"><b>No</b></td>
                    <td><b>Siswa</b></td>
                    <td><b>NIS</b></td>
                    <td align="right"><b>Nominal</b></td>
                    <td align="right"><b>Diskon</b></td>
                    <td align="right"><b>Tagihan</b></td>
                    <td align="right"><b>Pembayaran</b></td>
                    <td align="right"><b>Sisa/Tunggakan</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    //jika data tidak ada
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="8" align="center">Tidak Ada Data Tagihan & Pembayaran Untuk Kelas Ini</td>
                            </tr>
                        ';
                    }else{
                        //Inisiasi Akumulasi
                        $total_nominal      = 0;
                        $total_diskon       = 0;
                        $total_tagihan      = 0;
                        $total_pembayaran   = 0;
                        $total_sisa         = 0;
                        //Menampilkan 'fee_by_student' secara distinct
                        $no = 1;
                        $qry_fee_by_student = mysqli_query($Conn, "SELECT id_fee_by_student, id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' ORDER BY id_student ASC");
                        while ($data_fee_by_student = mysqli_fetch_array($qry_fee_by_student)) {
                            $id_fee_by_student  = $data_fee_by_student['id_fee_by_student'];
                            $id_student         = $data_fee_by_student['id_student'];

                            //Buka Data Siswa
                            $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
                            $student_name    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

                            //menghitung jumlah tagihan
                            $SumNominalTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS nominal_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
                            $jumlah_nominal_tagihan = $SumNominalTagihan['nominal_tagihan'];
                            $jumlah_nominal_tagihan_format  = "Rp " . number_format($jumlah_nominal_tagihan,0,',','.');
                            

                            //Hitung Jumlah Diskon
                            $SumDiskon = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
                            $jumlah_diskon = $SumDiskon['jumlah_diskon'];
                            $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');
                           

                            //Hitung Jumlah Tagihan
                            $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal - fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
                            $jumlah_tagihan = $SumTagihan['total_tagihan'];
                            $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');
                            

                            //Hitung Jumlah Pembayaran
                            $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component' AND id_student='$id_student'"));
                            $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
                            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
                            

                            //Menghitung Sisa Tagihan
                            $sisa_tagihan = $jumlah_tagihan-$jumlah_pembayaran;
                            $sisa_tagihan_format   = "Rp " . number_format($sisa_tagihan,0,',','.');
                            

                            //Akumulasi
                            $total_nominal      = $total_nominal + $jumlah_nominal_tagihan;
                            $total_diskon       = $total_diskon + $jumlah_diskon;
                            $total_tagihan      = $total_tagihan + $jumlah_tagihan;
                            $total_pembayaran   = $total_pembayaran + $jumlah_pembayaran;
                            $total_sisa         = $total_sisa + $sisa_tagihan;
                            //Menampilkan Tabel
                            echo '
                                <tr>
                                    <td align="center"><small>'.$no.'</small></td>
                                    <td><small>'.$student_name.'</small></td>
                                    <td><small>'.$student_nis.'</small></td>
                                    <td align="right"><small>'.$jumlah_nominal_tagihan_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_diskon_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_tagihan_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                                    <td align="right"><small>'.$sisa_tagihan_format.'</small></td>
                                </tr>
                            ';
                            $no++;
                        }

                        //Format Akumulasi
                        $total_nominal_format       = "Rp " . number_format($total_nominal,0,',','.');
                        $total_diskon_format        = "Rp " . number_format($total_diskon,0,',','.');
                        $total_tagihan_format       = "Rp " . number_format($total_tagihan,0,',','.');
                        $total_pembayaran_format    = "Rp " . number_format($total_pembayaran,0,',','.');
                        $total_sisa_format          = "Rp " . number_format($total_sisa,0,',','.');
                        echo '
                            <tr>
                                <td><small></small></td>
                                <td colspan="2"><small><b>JUMLAH/TOTAL</b></small></td>
                                <td align="right"><small><b>'.$total_nominal_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_diskon_format.'</b></small></td>
                                <td align="right"><small><b>'.$total_tagihan_format.'</b></small></td>
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