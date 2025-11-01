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
            <tr><td colspan="7" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>$("#title_daftar_kelas").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="7" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_daftar_kelas").html("");</script>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Informasi Periode Akdemik
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <tr><td colspan="7" class="text-center"><small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small></td></tr>
            <script>$("#title_daftar_kelas").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    if(empty($jml_data)){
        echo '
            <tr><td colspan="7" class="text-center"><small class="text-danger">Tidak Ada Kelas Untuk Periode Ini </small></td></tr>
        ';
        exit;
    }

    //Menampilkan Data Kelas
    $no_kelas=1;
    $total_siswa=0;
    $total_komponen_biaya=0;
    $total_tagihan_siswa=0;
    $total_pembayaran=0;
    $total_sisa_tagihan=0;
    $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_name ASC");
    while ($data_kelas = mysqli_fetch_array($query_kelas)) {
        $id_organization_class = $data_kelas['id_organization_class'];
        $class_name = $data_kelas['class_name'];

        //Hitung Jumlah Siswa Dari Tabel fee_by_student
        $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        $total_siswa=$total_siswa+$jumlah_siswa;

        //Hitung Komponen Biaya
        $jumlah_komponen=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));
        $total_komponen_biaya=$total_komponen_biaya+$jumlah_komponen;

        //Routing $jumlah_komponen
        if(empty($jumlah_komponen)){
            $label_jumlah_komponen='<small class="text text-grayish">'.$jumlah_komponen.' Record</small>';
        }else{
            $label_jumlah_komponen='<small>'.$jumlah_komponen.' Record</small>';
        }

        //Routing $jumlah_siswa
        if(empty($jumlah_siswa)){
            $label_jumlah_siswa='<small class="text-grayish">'.$jumlah_siswa.'</small>';
        }else{
            $label_jumlah_siswa='<small>'.$jumlah_siswa.'</small>';
        }

        //Hitung Jumlah Tagihan
        $SumTagihan = mysqli_fetch_array(mysqli_query(
            $Conn,
            "SELECT SUM(fee_nominal - fee_discount) AS total_tagihan 
            FROM fee_by_student 
            WHERE id_organization_class='$id_organization_class'"
        ));
        $jumlah_tagihan = $SumTagihan['total_tagihan'];
        $jumlah_tagihan_format = "Rp " . number_format($jumlah_tagihan,0,',','.');
        if(empty($jumlah_tagihan)){
            $label_jumlah_tagihan='<small class="text-grayish">'.$jumlah_tagihan_format.'</small>';
        }else{
            $label_jumlah_tagihan='<small class="text">'.$jumlah_tagihan_format.'</small>';
        }
        $total_tagihan_siswa=$total_tagihan_siswa+$jumlah_tagihan;
        
        //Hitung Jumlah Pembayaran
        $SumPembayaran = mysqli_fetch_array(mysqli_query(
            $Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class'"
        ));
        $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
        $jumlah_pembayaran_format = "Rp " . number_format($jumlah_pembayaran,0,',','.');
        if(empty($jumlah_pembayaran)){
            $label_jumlah_pembayaran='<small class="text-grayish">'.$jumlah_pembayaran_format.'</small>';
        }else{
            $label_jumlah_pembayaran='<small>'.$jumlah_pembayaran_format.'</small>';
        }
        $total_pembayaran = $total_pembayaran + $jumlah_pembayaran;

        //Sisa Tagihan
        $sisa_tagihan=$jumlah_tagihan-$jumlah_pembayaran;
        $sisa_tagihan_format = "Rp " . number_format($sisa_tagihan,0,',','.');
        $total_sisa_tagihan=$total_sisa_tagihan + $sisa_tagihan;

        //Tampilkan Data
        echo '
            <tr>
                <td align="center"><small>'.$no_kelas.'</small></td>
                <td align="left"><small>'.$class_name.'</small></td>
                <td>'.$label_jumlah_siswa.'</td>
                <td>'.$label_jumlah_komponen.'</td>
                <td>'.$label_jumlah_tagihan.'</td>
                <td>'.$label_jumlah_pembayaran.'</td>
                <td><small>'.$sisa_tagihan_format.'</small></td>
            </tr>
        ';
        $no_kelas++;
    }

    $total_tagihan_siswa_format = "Rp " . number_format($total_tagihan_siswa,0,',','.');
    $total_pembayaran_format = "Rp " . number_format($total_pembayaran,0,',','.');
    $total_sisa_tagihan_format = "Rp " . number_format($total_sisa_tagihan,0,',','.');
    echo '
        <tr>
            <td colspan="2"><b><small>JUMLAH</small></b></td>
            <td><b><small>'.$total_siswa.'</small></b></td>
            <td><b><small>'.$total_komponen_biaya.' Record</small></b></td>
            <td><b><small>'.$total_tagihan_siswa_format.'</small></b></td>
            <td><b><small>'.$total_pembayaran_format.'</small></b></td>
            <td><b><small>'.$total_sisa_tagihan_format.'</small></b></td>
        </tr>
    ';
    //Tampilkan Di Atas Tabel
    echo '<script>$("#title_daftar_kelas").html("Daftar Kelas Periode Akademik '.$academic_period.'");</script>';
?>