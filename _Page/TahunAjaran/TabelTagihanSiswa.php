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
            <script>$("#title_tagihan_biaya_pendidikan").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="7" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_tagihan_biaya_pendidikan").html("");</script>
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
            <script>$("#title_tagihan_biaya_pendidikan").html("");</script>
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
    $no_kelas           = 1;
    $total_komponen     = 0;
    $total_tagihan      = 0;
    $total_diskon      = 0;
    $total_pembayaran   = 0;
    $total_sisa         = 0;
    $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_level, class_name FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_name ASC");
    while ($data_kelas = mysqli_fetch_array($query_kelas)) {
        $id_organization_class = $data_kelas['id_organization_class'];
        $class_level = $data_kelas['class_level'];
        $class_name = $data_kelas['class_name'];

        //Hitung Komponen Biaya
        $jumlah_komponen    = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));
        $total_komponen     = $total_komponen+$jumlah_komponen;
        //Routing Label Komponen
        if(empty($jumlah_komponen)){
            $label_jumlah_komponen = '
                <span class="text text-grayish">0 Rcrd</span>
            ';
        }else{
            $label_jumlah_komponen = '
                <span class="text text-dark">'.$jumlah_komponen.' Rcrd</span>
            ';
        }

        //Hitung Jumlah Tagihan
        $SumTagihan             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        $jumlah_tagihan         = $SumTagihan['total_tagihan'];
        $jumlah_tagihan_format  = "" . number_format($jumlah_tagihan,0,',','.');
        $total_tagihan          = $total_tagihan + $jumlah_tagihan;
        //Routing Jumlah Tagihan
        if(empty($jumlah_tagihan)){
            $label_jumlah_tagihan   = '
                <span class="text text-grayish">'.$jumlah_tagihan_format.'</span>
            ';
        }else{
            $label_jumlah_tagihan   = '
                <span class="text text-dark">'.$jumlah_tagihan_format.'</span>
            ';
        }

        //Hitung Jumlah Diskon
        $SumDiskon              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS total_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        $jumlah_diskon          = $SumDiskon['total_diskon'];
        $jumlah_diskon_format   = "" . number_format($jumlah_diskon,0,',','.');
        $total_diskon           = $total_diskon + $jumlah_diskon;
        //Routing Jumlah Diskon
        if($jumlah_diskon==0.00){
            $label_diskon   = '
                <span class="text text-grayish">'.$jumlah_diskon_format.'</span>
            ';
        }else{
            $label_diskon   = '
                <span class="text text-dark">'.$jumlah_diskon_format.'</span>
            ';
        }

        //Hitung Pembayaran
        $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_organization_class='$id_organization_class'"));
        $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];
        $jumlah_pembayaran_format   = "" . number_format($jumlah_pembayaran,0,',','.');
        $total_pembayaran           = $total_pembayaran + $jumlah_pembayaran;
        //Routing Pembayaran
        if(empty($jumlah_pembayaran)){
            $label_jumlah_pembayaran = '
                <span class="text text-grayish">'.$jumlah_pembayaran_format.'</span>
            ';
        }else{
            $label_jumlah_pembayaran = '
                <span class="text text-dark">'.$jumlah_pembayaran_format.'</span>
            ';
        }

        //Menghitung Sisa Tagihan
        $sisa_tagiihan          = $jumlah_tagihan - $jumlah_diskon - $jumlah_pembayaran ;
        $total_sisa             = $total_sisa + $sisa_tagiihan;
        $sisa_tagiihan_format   = "" . number_format($sisa_tagiihan,0,',','.');
        //Routing Sisa Tagihan
        if(empty($sisa_tagiihan)){
            $label_sisa_tagihan   = '
                <span class="text text-grayish">'.$sisa_tagiihan_format.'</span>
            ';
        }else{
            $label_sisa_tagihan   = '
                <span class="text text-dark">'.$sisa_tagiihan_format.'</span>
            ';
        }

        //Tampilkan Data
        echo '
            <tr>
                <td align="center"><small>'.$no_kelas.'</small></td>
                <td align="left">
                    <a href="javascriipt:void(0);" data-bs-toggle="modal" data-bs-target="#ModalRincianTagihanSiswa" data-id1="'.$id_academic_period .'" data-id2="'.$id_organization_class .'">
                        <small class="text text-decoration-underline">'.$class_level.' - '.$class_name.'</small>
                    </a>
                </td>
                <td align="right"><small>'.$label_jumlah_komponen.'</small></td>
                <td align="right"><small>'.$label_jumlah_tagihan.'</small></td>
                <td align="right"><small>'.$label_diskon.'</small></td>
                <td align="right"><small>'.$label_jumlah_pembayaran.'</small></td>
                <td align="right"><small>'.$label_sisa_tagihan.'</small></td>
                <td align="center">
                    <a href="javascriipt:void(0);" class="btn btn-sm btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalRincianTagihanSiswa" data-id1="'.$id_academic_period .'" data-id2="'.$id_organization_class .'">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </td>
            </tr>
        ';
        $no_kelas++;
    }

    $total_tagihan_format       = "" . number_format($total_tagihan,0,',','.');
    $total_diskon_format        = "" . number_format($total_diskon,0,',','.');
    $total_pembayaran_format    = "" . number_format($total_pembayaran,0,',','.');
    $total_sisa_format          = "" . number_format($total_sisa,0,',','.');
    echo '
        <tr>
            <td align="right"></td>
            <td align="left"><b><small>JUMLAH TOTAL</small></b></td>
            <td align="right"><b><small>'.$total_komponen.' Rcrd</small></b></td>
            <td align="right"><b><small>'.$total_tagihan_format.'</small></b></td>
            <td align="right"><b><small>'.$total_diskon_format.'</small></b></td>
            <td align="right"><b><small>'.$total_pembayaran_format.'</small></b></td>
            <td align="right"><b><small>'.$total_sisa_format.'</small></b></td>
            <td align="right"></td>
        </tr>
    ';
    //Tampilkan Di Atas Tabel
    echo '
        <script>
            $("#title_tagihan_biaya_pendidikan").html("PERIODE '.$academic_period.'");
            $("#ExportTagihanSiswaPdf").attr("href", "_Page/TahunAjaran/ProsesExportTagihanSiswa.php?type=PDF&id='.$id_academic_period.'");
            $("#ExportTagihanSiswaHtml").attr("href", "_Page/TahunAjaran/ProsesExportTagihanSiswa.php?type=HTML&id='.$id_academic_period.'");
        </script>
    ';
?>