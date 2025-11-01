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
    if(empty($_GET['id_academic_period'])){
        echo 'ID Periode Akademik Tidak Boleh Kosong!';
        exit;
    }
    //Tangkap id_organization_class
    if(empty($_GET['id_organization_class'])){
        echo 'ID Kelas Tidak Boleh Kosong!';
        exit;
    }

    //Buat variabel
    $type=validateAndSanitizeInput($_GET['type']);
    $id_academic_period=validateAndSanitizeInput($_GET['id_academic_period']);
    $id_organization_class=validateAndSanitizeInput($_GET['id_organization_class']);

   //Buka academic_period dari tabel 'academic_period'
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka nama kelas
    $class_level            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name             = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Hitung Jumlah Data pada tabel 'fee_by_student'
    $jml_data               = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    if(empty($jml_data)){
        echo 'Tidak Ada Kelas Untuk Periode Ini';
        exit;
    }
    
    //Menghitung Jumlah Komponen Biaya (id_fee_component) dari tabel 'fee_by_student'
    $jumlah_komponen        = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
    //Mulai buffer output
    ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Rincian Tagihan Siswa | <?php echo "Periode : $academic_period"; ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 8pt;
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
                font-size: 8pt;
            }
            table.custom-table tbody td {
                border: 1px solid #000;
                padding: 4px;
                font-family: Arial, sans-serif;
                font-size: 8pt;
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
                font-size: 8pt;
            }
            b{
                font-family: Arial, sans-serif !important;
                font-size: 8pt !important;
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
                    <h3>RINCIAN TAGIHAN SISWA PERIODE <?php echo $academic_period; ?></h3>
                </td>
            </tr>
        </table>
        <table class="custom-table">
            <?php
                echo '      <thead>';
                echo '          <tr>';
                echo '              <td align="center" rowspan="2" valign="middle"><b>No</b></td>';
                echo '              <td align="center" rowspan="2" valign="middle"><b>Nama Siswa</b></td>';
                echo '              <td align="center" colspan="'.$jumlah_komponen.'" valign="middle"><b>Komponen Biaya</b></td>';
                echo '              <td align="center" rowspan="2" valign="middle"><b>Tagiihan</b></td>';
                echo '              <td align="center" rowspan="2" valign="middle"><b>Pembayaran</b></td>';
                echo '              <td align="center" rowspan="2" valign="middle"><b>Sisa</b></td>';
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
                                    echo '<td align="center" valign="middle"><b>'. $component_name.' <br>('. $component_category.')</b></td>';
                                }
                echo '          </tr>';
                echo '      </thead>';
            ?>
            <tbody>
                <?php
                    //Loopiing Data Siswa pada tabel 'fee_by_student'
                    $no                 = 1;
                    $jumlah_total       = 0;
                    $total_pembayaran   = 0;
                    $total_sisa         = 0;
                    $qry_siswa = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_student ASC");
                    while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
                        $id_student = $data_siswa['id_student'];

                        //Buka nama siswa
                        $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
                        $student_nis = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

                        //Tampilkan Data
                        echo '<tr>';
                        echo '  <td>'.$no.'</td>';
                        echo '  <td>'.$student_name.'<br>('.$student_nis.')</td>';
                        
                        //Looping Komponen Biaya
                        $jumlah_fee             = 0;
                        $subtotal_pembayaran    = 0;
                        $qry_komponen = mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
                        while ($data_komponen = mysqli_fetch_array($qry_komponen)) {
                            $id_fee_component = $data_komponen['id_fee_component'];

                            //Buka 'fee_nominal' dan 'fee_discount' dari tabel 'fee_by_student'
                            $QryComponent = $Conn->prepare("SELECT id_fee_by_student, fee_nominal, fee_discount FROM fee_by_student WHERE id_organization_class = ? AND id_student = ? AND id_fee_component = ?");
                            $QryComponent->bind_param("iii", $id_organization_class, $id_student, $id_fee_component);

                            //Jika Terjadi kesalahan Pada Saat Membuka Data
                            if (!$QryComponent->execute()) {
                                $id_fee_by_student  = $Conn->error;
                                $fee_nominal        = $Conn->error;
                                $fee_discount       = $Conn->error;
                                $fee_total          = $Conn->error;
                                $jumlah_fee         = $jumlah_fee + 0;
                                $jumlah_total       = $jumlah_total + 0;
                            }else{
                                $ResultComponent    = $QryComponent->get_result();
                                $DataComponent      = $ResultComponent->fetch_assoc();
                                $QryComponent->close();
                                //Buat Variabel
                                $id_fee_by_student  = $DataComponent['id_fee_by_student'];
                                $fee_nominal        = $DataComponent['fee_nominal'];
                                $fee_discount       = $DataComponent['fee_discount'];
                                $fee_total          = $fee_nominal - $fee_discount;
                                $jumlah_fee         = $jumlah_fee + $fee_total;
                                $jumlah_total       = $jumlah_total + $fee_total;
                                $fee_total          = "" . number_format($fee_total,0,',','.');
                            }

                            //Buka Data Pembayaran dari tabel 'payment'
                            $jumlah_pembayaran = 0; 
                            $QryPayment = mysqli_query($Conn, "SELECT payment_nominal FROM payment WHERE id_fee_by_student='$id_fee_by_student' ORDER BY id_payment ASC");
                            while ($DataPayment = mysqli_fetch_array($QryPayment)) {
                                $payment_nominal        = $DataPayment['payment_nominal'];
                                $jumlah_pembayaran      = $jumlah_pembayaran + $payment_nominal; 
                                $subtotal_pembayaran    = $subtotal_pembayaran + $jumlah_pembayaran;
                            }
                            
                            //Format 'jumlah_pembayaran'
                            $jumlah_pembayaran_format   = "" . number_format($jumlah_pembayaran,0,',','.');

                            //Routing '$_label_jumlah_pembayaran'
                            if($jumlah_pembayaran){
                                $_label_jumlah_pembayaran = '<span class="text text-success">('.$jumlah_pembayaran_format.')</span>';
                            }else{
                                $_label_jumlah_pembayaran = '<span class="text text-grayish">('.$jumlah_pembayaran_format.')</span>';
                            }

                            //Tampilkan Nilai 'fee_total' pada masing-masing komponen biaya
                            echo '<td align="right">'. $fee_total.'<br>'.$_label_jumlah_pembayaran.'</td>';
                        }

                        //Format jumlah_fee
                        $jumlah_fee_format          = "" . number_format($jumlah_fee,0,',','.');
                        $subtotal_pembayaran_format = "" . number_format($subtotal_pembayaran,0,',','.');

                        //Menghiitung Sisa
                        $sisa           = $jumlah_fee - $subtotal_pembayaran;
                        $sisa_format    = "" . number_format($sisa,0,',','.');
                        $total_sisa     = $total_sisa + $sisa;

                        //Tampilkan jumlah_fee_format
                        echo '  <td align="right">'. $jumlah_fee_format.'</td>';
                        echo '  <td align="right">'. $subtotal_pembayaran_format.'</td>';
                        echo '  <td align="right">'. $sisa_format.'</td>';
                        echo '</tr>';
                        $no++;
                    }

                    //Format Jumlah Total
                    $jumlah_total_format        = "" . number_format($jumlah_total,0,',','.');
                    $total_pembayaran_format    = "" . number_format($total_pembayaran,0,',','.');
                    $total_sisa_format          = "" . number_format($total_sisa,0,',','.');
                    echo '
                        <tr>
                            <td colspan="2"><b>JUMLAH-TOTAL</b></td>
                            <td colspan="'.$jumlah_komponen.'"></td>
                            <td align="right"><b>'.$jumlah_total_format.'</b></td>
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
            'orientation' => 'L',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 10,
            'margin_bottom' => 10,
            'margin_header' => 1,
            'margin_footer' => 1
        ]);

        $mpdf->SetTitle("Tagihan Siswa - ");
        $mpdf->WriteHTML($html);
        $mpdf->Output("Tagihan_Siswa_$id_organization_class.pdf", 'I');
        exit;
    } else {
        //Jika bukan PDF, tampilkan HTML biasa
        echo $html;
    }
?>

