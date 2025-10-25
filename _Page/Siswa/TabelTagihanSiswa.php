<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Inisiasi Variabel
    $JmlHalaman = 0;
    $jml_data   = 0;
    $page       = 0;

    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="10" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Validasi id_student
    if(empty($_POST['id_student'])){
        echo '
            <tr>
                <td colspan="10" class="text-center">
                    <small class="text-danger">ID Siiswa Tidak Boleh Kosong!</small>
                </td>
            </tr>
        ';
        exit;
    }

    //id_student
    $id_student=$_POST['id_student'];

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_student='$id_student'"));

    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="10" class="text-center">
                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Atur Nomor
    $no = 1;

    //Inisialisasi Jumlah Total
    $total_nominal      = 0;
    $total_diskon       = 0;
    $total_jumlah       = 0;
    $total_pembayaran   = 0;
    //Looping Query
    $query = mysqli_query($Conn, "SELECT*FROM fee_by_student WHERE id_student='$id_student'");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_by_student      = $data['id_fee_by_student'];
        $id_organization_class  = $data['id_organization_class'];
        $id_student             = $data['id_student'];
        $id_fee_component       = $data['id_fee_component'];
        $fee_nominal            = $data['fee_nominal'];
        $fee_discount           = $data['fee_discount'];
        $jumlah_tagihan         = $fee_nominal - $fee_discount;

        //Buka Informasi Kelas dari tabel 'organization_class'
        $id_academic_period     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
        $level                  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
        $kelas                  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
        $label_kelas            = "$level-$kelas";
        $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

        //Buka Biaya Pendidikan dari tabel 'fee_component'
        $component_name = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
        $periode_month  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
        $periode_year   = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
        $nama_bulan     = getNamaBulan($periode_month);

        //Buka Data Pembayaran dari tabel 'payment'
        $subtotal_payment=0;
        $query_payment = mysqli_query($Conn, "SELECT payment_nominal FROM payment WHERE id_fee_by_student='$id_fee_by_student'");
        while ($data_payment = mysqli_fetch_array($query_payment)) {
            $payment_nominal=$data_payment['payment_nominal'];
            $subtotal_payment=$subtotal_payment + $payment_nominal;
        }

        //Menghitung Jumlah Total
        $total_nominal      = $total_nominal + $fee_nominal;
        $total_diskon       = $total_diskon + $fee_discount;
        $total_jumlah       = $total_jumlah + $jumlah_tagihan;
        $total_pembayaran   = $total_pembayaran + $subtotal_payment;

        //Format Rupiah
        $fee_nominal_format     = "Rp " . number_format($fee_nominal,0,',','.');
        $fee_discount_format    = "Rp " . number_format($fee_discount,0,',','.');
        $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');
        $subtotal_payment_format  = "Rp " . number_format($subtotal_payment,0,',','.');

        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$label_kelas.'</small></td>
                <td><small>'.$academic_period.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$nama_bulan.'</small></td>
                <td><small>'.$periode_year.'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
                <td><small>'.$fee_discount_format.'</small></td>
                <td><small>'.$jumlah_tagihan_format.'</small></td>
                <td><small>'.$subtotal_payment_format.'</small></td>
            </tr>
        ';
        $no++;
    }
    //Format Rupiah
    $total_nominal_format       = "Rp " . number_format($total_nominal,0,',','.');
    $total_diskon_format        = "Rp " . number_format($total_diskon,0,',','.');
    $total_jumlah_format        = "Rp " . number_format($total_jumlah,0,',','.');
    $total_pembayaran_format    = "Rp " . number_format($total_pembayaran,0,',','.');

    //Menampilkan Total
    echo '
        <tr>
            <td></td>
            <td colspan="5"><b>JUMLAH</b></td>
            <td><b>'.$total_nominal_format.'</b></td>
            <td><b>'.$total_diskon_format.'</b></td>
            <td><b>'.$total_jumlah_format.'</b></td>
            <td><b>'.$total_pembayaran_format.'</b></td>
        </tr>
    ';
?>
<script>
    var data_count = <?php echo $jml_data; ?>;
    $('#data_count_tagihan_siiswa').html('Data Count : '+data_count+' Record');
</script>