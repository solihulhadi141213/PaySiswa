<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingGeneral.php";
    require_once '../../vendor/autoload.php';
    date_default_timezone_set("Asia/Jakarta");

    //Validasi Session
    if(empty($SessionIdAccess)){
        echo '
           <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
        ';
        exit;
    }
    if(empty($_POST['id_academic_period'])){
         echo '
            <small class="text-danger">Silahkan pilih <b>Periode Tahun Akademik</b> terlebih dulu untuk menampilkan data tagihan siswa</small>
        ';
        exit;
    }
    if(empty($_POST['id_organization_class'])){
        echo '
           <small class="text-danger">Silahkan pilih <b>group kelas</b> terlebih dulu untuk menampilkan data tagihan siswa</small>
        ';
        exit;
    }

    //Validasi tipe_file
    if(empty($_POST['tipe_file'])){
        echo 'Tipe File Tidak Boleh Kosong!';
        exit;
    }
    //status_siswa
    if(!empty($_POST['status_siswa'])){
        $status_siswa=$_POST['status_siswa'];
    }else{
        $status_siswa="";
    }

    //Buat Variabel
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $tipe_file              = validateAndSanitizeInput($_POST['tipe_file']);
    $kelompok_status_siswa  = validateAndSanitizeInput($status_siswa);

    //Buka Infomasi Kelas
    $Qry = $Conn->prepare("SELECT * FROM organization_class WHERE id_organization_class = ?");
    $Qry->bind_param("i", $id_organization_class);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '<small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $id_organization_class  = $Data['id_organization_class'];
    $id_academic_period     = $Data['id_academic_period'];
    $class_level            = $Data['class_level'];
    $class_name             = $Data['class_name'];

    // buka periode pendidikan
    $academic_period=GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Mulai buffer output
    ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Data Tagihan Siswa | <?php echo "Kelas : $class_name"; ?></title>
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
        <table width="100%">
            <tr>
                <td align="center">
                    <b>TAGIHAN & PEMBAYARAN SISWA <br>Kelas <?php echo $class_name; ?></b><br>
                    <b>TAHUN AKADEMIK <?php echo $academic_period; ?></b>
                </td>
            </tr>
        </table>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Status</th>
                    <th>Biaya Pendidikan</th>
                    <th>Pembayaran</th>
                    <th>Sisa Tunggakan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Hitung jumlah
                    if(empty($kelompok_status_siswa)){
                        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE id_organization_class='$id_organization_class'"));
                    }else{
                        $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE student_status='$kelompok_status_siswa' AND id_organization_class='$id_organization_class'"));
                    }
                    
                    if(empty($jml_data)){
                        echo '
                            <tr>
                                <td colspan="7" align="center">
                                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                                </td>
                            </tr>
                        ';
                    }else{
                        //Inisiasi Jumlah
                        $jumlah_biaya_pendidikan    = 0;
                        $jumlah_pembayaran          = 0;
                        $jumlah_sisa_tinggakan      = 0;
                        //Inisiasi Nomor
                        $no = 1;
                        //KONDISI PENGATURAN MASING FILTER
                        if(empty($kelompok_status_siswa)){
                            $query = mysqli_query($Conn, "SELECT*FROM student WHERE id_organization_class='$id_organization_class' ORDER BY student_name ASC");
                        }else{
                            $query = mysqli_query($Conn, "SELECT*FROM student WHERE student_status='$kelompok_status_siswa' AND id_organization_class='$id_organization_class' ORDER BY student_name ASC");
                        }
                        while ($data = mysqli_fetch_array($query)) {
                            $id_student = $data['id_student'];
                            $id_organization_class= $data['id_organization_class'];
                            $student_name= $data['student_name'];
                            $student_gender= $data['student_gender'];
                            $student_registered= $data['student_registered'];
                            $student_status= $data['student_status'];

                            //NIS
                            if(empty($data['student_nis'])){
                                $student_nis='-';
                            }else{
                                $student_nis=$data['student_nis'];
                            }

                            //Routing Gender
                            if($student_gender=="Male"){
                                $gender_label='<i class="bi bi-gender-male"></i> Male';
                            }else{
                                $gender_label='<i class="bi bi-gender-female"></i> Female';
                            }

                            //Buka Kelas
                            if(empty($data['id_organization_class'])){
                                $label_kelas='-';
                                $id_academic_period="";
                                $academic_period="-";
                            }else{
                                $id_academic_period=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
                                $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                                $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                                $label_kelas="$level-$kelas";

                                $academic_period=GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
                            }
                            

                            //Format Tanggal Daftar
                            $tanggal_daftar=date('d/m/Y', strtotime($student_registered));

                            //Status
                            if($student_status=="Terdaftar"){
                                $label_status='<span class="badge badge-success">Terdaftar</span>';
                            }else{
                                if($student_status=="Lulus"){
                                    $label_status='<span class="badge badge-warning">Lulus</span>';
                                }else{
                                    $label_status='<span class="badge badge-danger">Keluar</span>';
                                }
                            }

                            //Buka Data Tagihan Siswa
                            $jumlah_tagihan=0;
                            $query_tagihan = mysqli_query($Conn, "SELECT fee_nominal, fee_discount FROM  fee_by_student WHERE id_student='$id_student'");
                            while ($data_tagihan = mysqli_fetch_array($query_tagihan)) {
                                $fee_nominal = $data_tagihan['fee_nominal'];
                                $fee_discount = $data_tagihan['fee_discount'];

                                //Hitung Subtotal
                                $subtotal = $fee_nominal-$fee_discount;

                                //Totalkan
                                $jumlah_tagihan=$jumlah_tagihan+$subtotal;
                            }
                            
                            //Format Uang Tagihan
                            $jumlah_tagihan_format="Rp " . number_format($jumlah_tagihan,0,',','.');

                            //Buka Data Pembayaran Siswa
                            $jumlah_payment=0;
                            $query_payment = mysqli_query($Conn, "SELECT payment_nominal FROM payment WHERE id_student='$id_student'");
                            while ($data_payment = mysqli_fetch_array($query_payment)) {
                                if(empty($data_payment['payment_nominal'])){
                                    $payment_nominal =0;
                                }else{
                                    $payment_nominal = $data_payment['payment_nominal'];
                                }
                                

                                //Totalkan
                                $jumlah_payment=$jumlah_payment+$payment_nominal;
                            }
                            
                            //Format Uang Tagihan
                            $jumlah_payment_format="Rp " . number_format($jumlah_payment,0,',','.');

                            //Menghitung Sisa Pembayaran
                            $sisa=$jumlah_tagihan-$jumlah_payment;
                            $sisa_format="Rp " . number_format($sisa,0,',','.');

                            //Tampilkan Data
                            echo '
                                <tr>
                                    <td align="center"><small>'.$no.'</small></td>
                                    <td><small>'.$student_name.'</small></td>
                                    <td><small>'.$student_nis.'</small></td>
                                    <td align="center"><small>'.$label_status.'</small></td>
                                    <td align="right"><small>'.$jumlah_tagihan_format.'</small></td>
                                    <td align="right"><small>'.$jumlah_payment_format.'</small></td>
                                    <td align="right"><small>'.$sisa_format.'</small></td>
                                </tr>
                            ';
                            //Akumulasi
                            $jumlah_biaya_pendidikan    = $jumlah_biaya_pendidikan+$jumlah_tagihan;
                            $jumlah_pembayaran          = $jumlah_pembayaran+$jumlah_payment;
                            $jumlah_sisa_tinggakan      = $jumlah_sisa_tinggakan+$sisa;
                            $no++;
                        }

                        //Format
                        $jumlah_biaya_pendidikan_format="Rp " . number_format($jumlah_biaya_pendidikan,0,',','.');
                        $jumlah_pembayaran_format="Rp " . number_format($jumlah_pembayaran,0,',','.');
                        $jumlah_sisa_tinggakan_format="Rp " . number_format($jumlah_sisa_tinggakan,0,',','.');
                        echo '
                            <tr>
                                <td align="center"><b></b></td>
                                <td colspan="3"><b>Jumlah</b></td>
                                <td align="right"><b>'.$jumlah_biaya_pendidikan_format.'</b></td>
                                <td align="right"><b>'.$jumlah_pembayaran_format.'</b></td>
                                <td align="right"><b>'.$jumlah_sisa_tinggakan_format.'</b></td>
                            </tr>
                        ';
                    }
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
        $mpdf->Output("Tagihan_Siswa_$id_organization_class.pdf", 'I');
        exit;
    } else {
        //Jika bukan PDF, tampilkan HTML biasa
        echo $html;
    }
?>
