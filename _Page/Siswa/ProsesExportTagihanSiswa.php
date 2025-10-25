<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingGeneral.php";
    require_once '../../vendor/autoload.php'; // pastikan path ke autoload mPDF benar

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo 'Sesi Akses Sudah Berakhir! Silahkan Login Ulang!';
        exit;
    }

    //Validasi id
    if(empty($_GET['id'])){
        echo 'ID Siswa Tidak Boleh Kosong!';
        exit;
    }

    //Validasi tipe_file
    if(empty($_GET['tipe_file'])){
        echo 'Tipe File Tidak Boleh Kosong!';
        exit;
    }

    //Buat Variabel
    $id_student = validateAndSanitizeInput($_GET['id']);
    $tipe_file  = validateAndSanitizeInput($_GET['tipe_file']);

    //Mulai buffer output
    ob_start();
    ?>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Data Tagihan Siswa | <?php echo $id_student; ?></title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 10pt;
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
                table.custom-table thead th {
                    border: 1px solid #000;
                    padding: 4px; 
                    text-align: center;
                    font-family: Arial, sans-serif;
                    font-size: 10pt;
                }
                table.custom-table tbody td {
                    border: 1px solid #000;
                    padding: 4px;
                    font-family: Arial, sans-serif;
                    font-size: 10pt;
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
                    font-size: 10pt;
                }
                b{
                    font-family: Arial, sans-serif !important;
                    font-size: 9pt !important;
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
                        <small>Telepon : <?php echo "$company_contact"; ?> - Email : <?php echo "$company_email"; ?></small>
                    </td>
                </tr>
            </table>
            <?php
                //Buka Data Dari Tabel 'student'
                $Qry = $Conn->prepare("SELECT * FROM student WHERE id_student = ?");
                $Qry->bind_param("i", $id_student);
                if (!$Qry->execute()) {
                    $error=$Conn->error;
                    echo '
                        <div class="alert alert-danger">
                            <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                        </div>
                    ';
                }else{
                    $Result = $Qry->get_result();
                    $Data = $Result->fetch_assoc();
                    $Qry->close();

                    //Buat Variabel
                    $id_organization_class  = $Data['id_organization_class'];
                    $student_nis            = $Data['student_nis'] ?? '-';
                    $student_nisn           = $Data['student_nisn'] ?? '-';
                    $student_name           = $Data['student_name'];
                    $student_gender         = $Data['student_gender'];
                    $student_parent         = $Data['student_parent'];
                    $student_registered     = $Data['student_registered'];
                    $student_status         = $Data['student_status'];
                    echo '
                        <table class="identitas">
                            <tr>
                                <td>Nama Siswa</td>
                                <td>:</td>
                                <td>'.$student_name.'</td>
                            </tr>
                            <tr>
                                <td>NIS</td>
                                <td>:</td>
                                <td>'.$student_nis.'</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td>'.$student_gender.'</td>
                            </tr>
                        </table>
                    ';
                }
            ?>
            <table width="100%">
                <tr>
                    <td align="center">
                        <b>TAGIHAN & PEMBAYARAN SISWA</b>
                    </td>
                </tr>
            </table>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Periode Akademik</th>
                        <th>Komponen</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Nominal</th>
                        <th>Diskon</th>
                        <th>Jumlah</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        $total_nominal = $total_diskon = $total_jumlah = $total_pembayaran = 0;

                        $query = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_student='$id_student'");
                        while ($data = mysqli_fetch_array($query)) {
                            $id_fee_by_student      = $data['id_fee_by_student'];
                            $id_organization_class  = $data['id_organization_class'];
                            $id_fee_component       = $data['id_fee_component'];
                            $fee_nominal            = $data['fee_nominal'];
                            $fee_discount           = $data['fee_discount'];
                            $jumlah_tagihan         = $fee_nominal - $fee_discount;

                            //Informasi kelas
                            $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
                            $level              = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                            $kelas              = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                            $label_kelas        = "$level-$kelas";
                            $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

                            //Komponen biaya
                            $component_name = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
                            $periode_month  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
                            $periode_year   = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
                            $nama_bulan     = getNamaBulan($periode_month);

                            //Pembayaran
                            $subtotal_payment = 0;
                            $query_payment = mysqli_query($Conn, "SELECT payment_nominal FROM payment WHERE id_fee_by_student='$id_fee_by_student'");
                            while ($data_payment = mysqli_fetch_array($query_payment)) {
                                $subtotal_payment += $data_payment['payment_nominal'];
                            }

                            //Total keseluruhan
                            $total_nominal    += $fee_nominal;
                            $total_diskon     += $fee_discount;
                            $total_jumlah     += $jumlah_tagihan;
                            $total_pembayaran += $subtotal_payment;

                            echo '
                            <tr>
                                <td><small>'.$no.'</small></td>
                                <td><small>'.$label_kelas.'</small></td>
                                <td><small>'.$academic_period.'</small></td>
                                <td><small>'.$component_name.'</small></td>
                                <td><small>'.$nama_bulan.'</small></td>
                                <td><small>'.$periode_year.'</small></td>
                                <td><small>Rp '.number_format($fee_nominal,0,',','.').'</small></td>
                                <td><small>Rp '.number_format($fee_discount,0,',','.').'</small></td>
                                <td><small>Rp '.number_format($jumlah_tagihan,0,',','.').'</small></td>
                                <td><small>Rp '.number_format($subtotal_payment,0,',','.').'</small></td>
                            </tr>';
                            $no++;
                        }

                        echo '
                            <tr>
                                <td></td>
                                <td colspan="5"><b>JUMLAH</b></td>
                                <td><b>Rp '.number_format($total_nominal,0,',','.').'</b></td>
                                <td><b>Rp '.number_format($total_diskon,0,',','.').'</b></td>
                                <td><b>Rp '.number_format($total_jumlah,0,',','.').'</b></td>
                                <td><b>Rp '.number_format($total_pembayaran,0,',','.').'</b></td>
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
    if (strtoupper($tipe_file) == "PDF") {
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

        $mpdf->SetTitle("Tagihan Siswa - $id_student");
        $mpdf->WriteHTML($html);
        $mpdf->Output("Tagihan_Siswa_$id_student.pdf", 'I');
        exit;
    } else {
        //Jika bukan PDF, tampilkan HTML biasa
        echo $html;
    }
?>
