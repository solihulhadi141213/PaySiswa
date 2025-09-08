<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingGeneral.php";
    require_once '../..//vendor/autoload.php';

    date_default_timezone_set("Asia/Jakarta");

    //Validasi Akses
    if(empty($SessionIdAccess)){
        die("Sesi Akses Sudah Berakhir! Silahkan Login Ulang!");
    }

    //Validasi periode
    if(empty($_GET['periode_1']) || empty($_GET['periode_2'])){
        die("Periode tidak valid!");
    }
    $periode_1 = validateAndSanitizeInput($_GET['periode_1']);
    $periode_2 = validateAndSanitizeInput($_GET['periode_2']);

    //Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment WHERE payment_datetime>='$periode_1' AND payment_datetime<='$periode_2'"));

    //Siapkan HTML
    $html = '
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table.header_page { width: 100%; border-bottom: 2px solid #000; margin-bottom: 20px; }
        table.data_table { width: 100%; border-collapse: collapse; margin-top:10px; }
        table.data_table th, table.data_table td {
            border: 1px solid #000;
            padding: 5px;
        }
        table.data_table th { background-color: #eee; }
        .text-center { text-align: center; }
    </style>

    <div style="overflow: hidden; border-bottom: 2px solid #000; padding-bottom:10px; margin-bottom:20px;">
        <div style="float:left; width:120px;">
            <img src="../../assets/img/'.$app_logo.'" width="80">
        </div>
        <div style="text-align:left;">
            <h2>'.$company_name.'</h2>
            '.$company_address.'<br>
            Telp : '.$company_contact.' | Email : '.$company_email.'
        </div>
    </div>

    <h3 style="text-align:center;">Laporan Pembayaran</h3>
    <p style="text-align:center;">Periode: '.date('d/m/Y',strtotime($periode_1)).' - '.date('d/m/Y',strtotime($periode_2)).'</p>

    <table class="data_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl.Bayar</th>
                <th>NIS</th>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Komponen/Uraian</th>
                <th>Pembayaran</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
    ';

    //Isi Data
    if(empty($jml_data)){
        $html .= '
            <tr>
                <td colspan="8" class="text-center">Tidak Ada Data Yang Ditampilkan!</td>
            </tr>';
    }else{
        $no=1;
        $query = mysqli_query($Conn, "SELECT*FROM payment WHERE payment_datetime>='$periode_1' AND payment_datetime<='$periode_2' ORDER BY id_payment ASC");
        while ($data = mysqli_fetch_array($query)) {
            $id_student = $data['id_student'];
            $id_organization_class= $data['id_organization_class'];
            $id_fee_component= $data['id_fee_component'];

            $student_nis=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
            $student_name=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
            $component_name=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
            $component_category=GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

            if(empty($id_organization_class)){
                $label_kelas='-';
            }else{
                $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
                $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
                $label_kelas="$level-$kelas";
            }

            $payment_datetime_format=date('d/m/Y', strtotime($data['payment_datetime']));
            $payment_nominal_format="Rp" . number_format($data['payment_nominal'],0,',','.');

            $html .= '
                <tr>
                    <td>'.$no.'</td>
                    <td>'.$payment_datetime_format.'</td>
                    <td>'.$student_nis.'</td>
                    <td>'.$student_name.'</td>
                    <td>'.$label_kelas.'</td>
                    <td>'.$component_name.' ('.$component_category.')</td>
                    <td>'.$payment_nominal_format.'</td>
                    <td>'.$data['payment_method'].'</td>
                </tr>
            ';
            $no++;
        }
    }
    $html .= '</tbody></table>';

    //Buat PDF
    $mpdf = new \Mpdf\Mpdf([
        'format'        => 'A4-L',   // A4 Landscape
        'margin_left'   => 10,
        'margin_right'  => 10,
        'margin_top'    => 10,
        'margin_bottom' => 10,
        'margin_header' => 0,
        'margin_footer' => 0
    ]);
    $mpdf->SetTitle("Laporan Pembayaran");
    $mpdf->WriteHTML($html);
    $mpdf->Output("Laporan_Pembayaran.pdf","I"); // I = inline (langsung buka di browser), D = download

?>