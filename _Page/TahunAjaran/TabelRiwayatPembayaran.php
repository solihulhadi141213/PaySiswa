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
        echo '
            <tr><td colspan="8" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>$("#title_riwayat_pembayaran").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="8" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_riwayat_pembayaran").html("");</script>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Informasi Periode Akdemik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_academic_period FROM academic_period WHERE id_academic_period='$id_academic_period'"));

    if(empty($jml_data)){
        echo '
            <tr><td colspan="8" class="text-center"><small class="text-danger">Tidak Ada Riwayat Pembayaran Untuk Periode Ini!</small></td></tr>
            <script>$("#title_riwayat_pembayaran").html("");</script>
        ';
        exit;
    }

    //Inisiasi Nomor
    $no                 = 1;
    $totel_pembayaran   = 0;
    //Looping data tabel 'organization_class'
    $query_kelas = mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY id_organization_class ASC");
    while ($data_kelas = mysqli_fetch_array($query_kelas)) {
        $id_organization_class      = $data_kelas['id_organization_class'];

        //Buka pembayaran dari tabel 'payment'
        $query_payment = mysqli_query($Conn, "SELECT * FROM payment WHERE id_organization_class='$id_organization_class' ORDER BY id_payment ASC");
        while ($data_payment = mysqli_fetch_array($query_payment)) {
            $id_payment             = $data_payment['id_payment'];
            $id_fee_by_student      = $data_payment['id_fee_by_student'];
            $id_student             = $data_payment['id_student'];
            $id_organization_class  = $data_payment['id_organization_class'];
            $id_fee_component       = $data_payment['id_fee_component'];
            $payment_datetime       = $data_payment['payment_datetime'];
            $payment_nominal        = $data_payment['payment_nominal'];
            $payment_method         = $data_payment['payment_method'];

            //Total Pemayaran
            $totel_pembayaran   = $totel_pembayaran + $payment_nominal;

            //Buka Nama Siswa dan NIS dari tabel 'student'
            $student_name           = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
            $student_nis            = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

            //Buka nama kelas dari tabel 'organization_class'
            $class_name             = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

            //Buka nama komponen dari tabel 'fee_component'
            $component_name         = GetDetailData($Conn, ' fee_component ', 'id_fee_component', $id_fee_component, 'component_name');

            //Format Nominal
            $payment_nominal_format = "" . number_format($payment_nominal,0,',','.');

            //Tampilkan Data
            echo '
                <tr>
                    <td align="center"><small>'.$no.'</small></td>
                    <td align="left"><small>'.$student_name.'</small></td>
                    <td align="left"><small>'.$student_nis.'</small></td>
                    <td align="left"><small>'.$class_name.'</small></td>
                    <td align="left"><small>'.$component_name.'</small></td>
                    <td align="right"><small>'.date('d/m/Y',strtotime($payment_datetime)).'</small></td>
                    <td align="right"><small>'.$payment_nominal_format.'</small></td>
                    <td align="center"><small>'.$payment_method.'</small></td>
                </tr>
            ';
            $no++;
        }
    }
    $totel_pembayaran_format = "" . number_format($totel_pembayaran,0,',','.');
     echo '
        <tr>
            <td align="right" colspan="6"><b>JUMLAH</b></td>
            <td align="right"><b>'.$totel_pembayaran_format.'</b></td>
            <td></td>
        </tr>
    ';


    //Tampilkan title
    echo '<script>$("#title_riwayat_pembayaran").html("PERIODE AKADEMIK '.$academic_period.'");</script>';
?>