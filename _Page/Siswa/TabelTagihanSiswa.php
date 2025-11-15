<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Inisiasi Variabel
    $jml_data   = 0;

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
    $id_student = $_POST['id_student'];

    //Buka Kelas Sekarang
    $id_organization_class_current = GetDetailData($Conn, 'student', 'id_student', $id_student, 'id_organization_class');

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_organization_class FROM fee_by_student WHERE id_student='$id_student'"));

    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="10" class="text-center">
                    <small class="text-danger">Tidak Ada Data Tagihan Siswa Yang Ditampilkan</small>
                </td>
            </tr>
        ';
        exit;
    }

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

        //Routing $id_organization_class_current
        if($id_organization_class_current==$id_organization_class){
            $class_row = 'table-warning';
        }else{
            $class_row = '';
        }

        //menampilkan data pada baris tabel
        echo '
            <tr class="'.$class_row.'">
                <td><small>'.$no.'</small></td>
                <td><small>'.$academic_period.'</small></td>
                <td><small>'.$level_student.'</small></td>
                <td><small>'.$kelas_student.'</small></td>
                <td><small>'.$jumlah_komponen.'</small></td>
                <td><small>'.$jumlah_tagihan_format.'</small></td>
                <td><small>'.$jumlah_diskon_format.'</small></td>
                <td><small>'.$jumlah_tagihan_netto_format.'</small></td>
                <td><small>'.$jumlah_pembayaran_format.'</small></td>
                <td><small>'.$jumlah_sisa_tagihan_format.'</small></td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating" data-bs-toggle="modal" data-bs-target="#ModalRincianTagihanSiswa" data-id_organization_class="'.$id_organization_class.'" data-id_student="'.$id_student.'">
                        <i class="bi bi-arrow-up-right"></i>
                    </button>
                </td>
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
            <td colspan="3"><b>JUMLAH</b></td>
            <td><b>'.$subtotal_komonen.'</b></td>
            <td><b>'.$subtotal_tagihan_format.'</b></td>
            <td><b>'.$subtotal_diskon_format.'</b></td>
            <td><b>'.$subtotal_tagihan_netto_format.'</b></td>
            <td><b>'.$subtotal_pembayaran_format.'</b></td>
            <td><b>'.$subtotal_tunggakan_format.'</b></td>
            <td></td>
        </tr>
    ';
?>
<script>
    var data_count = <?php echo $jml_data; ?>;
    $('#data_count_tagihan_siiswa').html('Data Count : '+data_count+' Record');
</script>
