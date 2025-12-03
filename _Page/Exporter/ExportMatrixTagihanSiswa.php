<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";

    //Tangkap id_organization_class
    if(empty($_GET['id_organization_class'])){
        echo 'ID Kelas Tidak Boleh Kosong!';
        exit;
    }
    

    //Buat variabel
    $id_organization_class=validateAndSanitizeInput($_GET['id_organization_class']);

    //Validasi 'id_organization_class'
    $validasi_id_organization_class = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_organization_class');

    if(empty($validasi_id_organization_class)){
        echo 'ID Kelas Tidak Valid';
        exit;
    }

    //Buka 'id_academic_period', 'class_level' dan 'class_name' dari tabel 'organization_class'
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka academic_period dari tabel 'academic_period'
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Hitung Jumlah Data pada tabel 'fee_by_student'
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));

    //Menghitung Jumlah Komponen Biaya (id_fee_component) dari tabel 'fee_by_student'
    $jumlah_komponen = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Matrix Rincian Tagihan Siswa</title>
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
                        DAFTAR TAGIHAN BIAYA PENDIDIKAN SISWA<br>
                        <span class="title_report">PERIODE <?php echo $academic_period; ?></span>
                    </b>
                    <br>
                    <?php
                        echo '
                            <b>Jenjang/Level :</b> <span class="">'.$class_level.'</span> | <b>Kelas Rombel :</b> <span class="">'.$class_name.'</span>
                        ';
                    ?>
                    <br><br>
                </td>
            </tr>
        </table>
        
        <!-- MENAMPILKAN TABEL DATA -->
        <table class="custom-table">
            <?php
                echo '      <thead>';
                echo '          <tr>';
                echo '              <td align="center" rowspan="2" valign="middle"><b><small>No</small></b></td>';
                echo '              <td align="center" rowspan="2" valign="middle"><b><small>Nama Siswa</small></b></td>';
                echo '              <td align="center" colspan="'.$jumlah_komponen.'" valign="middle"><b><small>KOMPONEN BIAYA PENDIDIKAN</small></b></td>';
                echo '              <td align="center" valign="middle"><b><small>JUMLAH TAGIHAN</small></b></td>';
                echo '              <td align="center" rowspan="2" valign="middle"><b><small>Sisa</small></b></td>';
                echo '          </tr>';
                echo '          <tr>';

                                    //Looping Komponen Biaya Untuk Kolom Dinamiis
                                    $qry_komponen = mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
                                    while ($data_komponen = mysqli_fetch_array($qry_komponen)) {
                                        $id_fee_component = $data_komponen['id_fee_component'];

                                        //Buka Nama Komponen
                                        $component_name     = GetDetailData($Conn, 'fee_component ', 'id_fee_component', $id_fee_component, 'component_name');
                                        $component_category = GetDetailData($Conn, 'fee_component ', 'id_fee_component', $id_fee_component, 'component_category');

                                        //Tampilkan Header Komponen Biaya
                                        echo '<td align="center" valign="middle"><small><b>'. $component_name.' <br>('. $component_category.')</b></small></td>';
                                    }
                echo '              <td align="center" valign="middle"><b><small>PEMBAYARAN</small></b></td>';
                echo '          </tr>';
                echo '      </thead>';
            ?>
            <tbody>
                <?php
                    //Inisialisasi Jumlah Total Tagihan, Pembayaran dan Sisa
                    $total_tagihan       = 0;
                    $total_pembayaran    = 0;
                    $total_sisa          = 0;
                    
                    //Inisialisasi Nomor Ururt
                    $no = 1;
                    //Loopiing Data Siswa pada tabel 'fee_by_student'
                    $qry_siswa = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_student ASC");
                    while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
                        $id_student = $data_siswa['id_student'];

                        //Buka nama siswa
                        $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
                        $student_nis = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

                        //Tampilkan Data
                        echo '<tr>';
                        echo '  <td align="center"><small>'.$no.'</small></td>';
                        echo '  <td><small>'.$student_name.'<br><small>('.$student_nis.')</small></small></td>';

                        //Inisialisasi Jumlah
                        $jumlah_tagihan     = 0;
                        $jumlah_pembayaran  = 0;
                        
                        //Looping Komponen Biaya
                        $qry_komponen = mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
                        while ($data_komponen = mysqli_fetch_array($qry_komponen)) {
                            
                            # Inisiasi variabel 'id_fee_component'
                            $id_fee_component = $data_komponen['id_fee_component'];

                            # Menghitung Subtotal Tagihan
                            $SumTagihan                 = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS subtotal_tagihan FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                            $subtotal_tagihan           = $SumTagihan['subtotal_tagihan'];
                            $subtotal_tagihan_format    = "" . number_format($subtotal_tagihan,0,',','.');

                            # Hitung Subtotal Pembayaran
                            $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS subtotal_payment FROM payment WHERE id_student='$id_student' AND id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                            $subtotal_payment           = $SumPembayaran['subtotal_payment'];
                            $subtotal_payment_format    = "" . number_format($subtotal_payment,0,',','.');

                            # Routing '$_label_jumlah_pembayaran'
                            if($subtotal_payment){
                                $label_subtotal_pembayaran = '<small class="text text-success">('.$subtotal_payment_format.')</small>';
                            }else{
                                $label_subtotal_pembayaran = '<small class="text text-grayish">('.$subtotal_payment_format.')</small>';
                            }

                            // Tampilkan Kolom
                            echo '
                                <td align="right">
                                    <small>
                                        '. $subtotal_tagihan_format.'<br>'.$label_subtotal_pembayaran.'
                                    </small>
                                </td>
                            ';

                            # Akumulasikan Per Baris -->
                            $jumlah_tagihan     = $jumlah_tagihan + $subtotal_tagihan;
                            $jumlah_pembayaran  = $jumlah_pembayaran + $subtotal_payment;
                        }

                        //Format 'jumlah_tagihan' dan 'jumlah_pembayaran'
                        $jumlah_tagihan_format      = "" . number_format($jumlah_tagihan,0,',','.');
                        $jumlah_pembayaran_format   = "" . number_format($jumlah_pembayaran,0,',','.');

                        //Menghiitung Sisa
                        $jumlah_sisa        = $jumlah_tagihan - $jumlah_pembayaran;
                        $jumlah_sisa_format = "" . number_format($jumlah_sisa,0,',','.');

                        //Routing Warna text untuk 'jumlah_pembayaran'
                        if(!empty($jumlah_pembayaran)){
                            $label_jumlah_pembayaran = '<small class="text text-success">('.$jumlah_pembayaran_format.')</small>';
                        }else{
                            $label_jumlah_pembayaran = '<small class="text text-grayish">('.$jumlah_pembayaran_format.')</small>';
                        }

                        //Routing Warna text untuk 'jumlah_sisa'
                        if(!empty($jumlah_sisa)){
                            $label_jumlah_sisa = '<span class="text text-danger">'.$jumlah_sisa_format.'</span>';
                        }else{
                            $label_jumlah_sisa = '<span class="text text-success">'.$jumlah_sisa_format.'</span>';
                        }

                        //Tampilkan jumlah_tagihan_format, 
                        echo '  
                                <td align="right">
                                    <small>
                                        '. $jumlah_tagihan_format.'<br>
                                        '. $label_jumlah_pembayaran.'
                                    </small>
                                </td>
                                <td align="right">
                                    <small>
                                        '. $label_jumlah_sisa.'
                                    </small>
                                </td>
                        ';
                        echo '</tr>';

                        # Akumali total tagihan, pembayaran dan sisa
                        $total_tagihan       = $total_tagihan + $jumlah_tagihan;
                        $total_pembayaran    = $total_pembayaran + $jumlah_pembayaran;
                        $total_sisa          = $total_sisa + $jumlah_sisa;

                        //Number Plus-plus
                        $no++;
                    }

                    //Format total tagihan, pembayaran dan sisa
                    $total_tagihan_format       = "" . number_format($total_tagihan,0,',','.');
                    $total_pembayaran_format    = "" . number_format($total_pembayaran,0,',','.');
                    $total_sisa_format          = "" . number_format($total_sisa,0,',','.');

                    //Menghitung Colspan
                    $colspan = $jumlah_komponen+2; // 2 adalah kolom nomor dan nama siswa

                    //Tampilkan baris akhir
                    echo '
                        <tr>
                            <td align="right" colspan="'.$colspan.'"><small><b>TOTAL TAGIHAN</b></small></td>
                            <td align="right"><small><b>'.$total_tagihan_format.'</b></small></td>
                            <td align="right"></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="'.$colspan.'"><small><b>TOTAL PEMBAYARAN</b></small></td>
                            <td align="right"><small><b>'.$total_pembayaran_format.'</b></small></td>
                            <td align="right"></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="'.$colspan.'"><small><b>TOTAL SISA TUNGGAKAN</b></small></td>
                            <td align="right"></td>
                            <td align="right"><small><b>'.$total_sisa_format.'</b></small></td>
                        </tr>
                    ';
                ?>
            </tbody>
        </table>
        <?php
            // Membuat QR Code Dokumen
            $text = "$app_base_url/_Page/Exporter/ExportMatrixTagihanSiswa.php?id_organization_class=$id_organization_class";
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
