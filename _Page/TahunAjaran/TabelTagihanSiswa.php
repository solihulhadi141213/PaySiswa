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
            <tr><td colspan="11" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>$("#title_tagihan_siswa").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="11" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_tagihan_siswa").html("");</script>
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
            <tr><td colspan="11" class="text-center"><small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small></td></tr>
            <script>$("#title_tagihan_siswa").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Buat title_tagihan_siswa
    $title_tagihan_siswa = '
       <b>
            REKAPITULASI TAGIHAN & PEMBAYARAN SISWA BERDASARKAN KELAS<br>
            PERIODE <span class="text-primary underscore_doted">'.$academic_period.'</span>
       </b>
    ';

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    if(empty($jml_data)){
        echo '
            <tr><td colspan="11" class="text-center"><small class="text-danger">Tidak Ada Kelas Untuk Periode Ini </small></td></tr>
        ';
        exit;
    }

    //Menampilkan Data Kelas
    $total_siswa        = 0;
    $total_komponen     = 0;
    $total_biaya        = 0;
    $total_diskon       = 0;
    $total_tagihan      = 0;
    $total_pembayaran   = 0;
    $total_sisa         = 0;

    //Inisialisasi 'no_class_level'
    $no_class_level       = 1;
    
    //Looping tabel 'class_level'
    $QryClassLevel = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
    while ($DataClassLevel = mysqli_fetch_array($QryClassLevel)) {
        $class_level = $DataClassLevel['class_level'];

         //Tampilkan Data class_level
        echo '
            <tr>
                <td class="bg bg-body-secondary"><small><b>'.$no_class_level.'</b></small></td>
                <td class="bg bg-body-secondary"><small><b>'.$class_level.'</b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
            </tr>
        ';

        //Inisialisasi $no_class_name
        $no_class_name = 1;

        //Looping Kelas Name
        $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE id_academic_period='$id_academic_period' AND class_level='$class_level' ORDER BY class_name ASC");
        while ($data_kelas = mysqli_fetch_array($query_kelas)) {
            $id_organization_class  = $data_kelas['id_organization_class'];
            $class_name             = $data_kelas['class_name'];

            //Hitung Jumlah Siswa
            $jumlah_siswa = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
            if(empty($jumlah_siswa)){
                $label_jumlah_siswa = '<span class="text text-grayish">0 Orang</span>';
            }else{
                $label_jumlah_siswa = '<span class="text text-dark">'.$jumlah_siswa.' Orang</span>';
            }
            $total_siswa        = $total_siswa + $jumlah_siswa;


            //Hitung Komponen Biaya
            $jumlah_komponen    = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class'"));
            $total_komponen     = $total_komponen+$jumlah_komponen;
            //Routing Label Komponen
            if(empty($jumlah_komponen)){
                $label_jumlah_komponen = '<span class="text text-grayish">0 Komponen</span>';
            }else{
                $label_jumlah_komponen = '<span class="text text-dark">'.$jumlah_komponen.' Komponen</span>';
            }

            //Hitung Jumlah Biaya Pendidikan
            $SumBiaya             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS total_biaya FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
            $jumlah_biaya         = $SumBiaya['total_biaya'];
            $jumlah_biaya_format  = "" . number_format($jumlah_biaya,0,',','.');
            $total_biaya          = $total_biaya + $jumlah_biaya;
            //Routing Jumlah Tagihan
            if(empty($jumlah_biaya)){
                $label_jumlah_biaya   = '<span class="text text-grayish">'.$jumlah_biaya_format.'</span>';
            }else{
                $label_jumlah_biaya   = '<span class="text text-dark">'.$jumlah_biaya_format.'</span>';
            }

            //Hitung Jumlah Diskon
            $SumDiskon              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS total_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
            $jumlah_diskon          = $SumDiskon['total_diskon'];
            $jumlah_diskon_format   = "" . number_format($jumlah_diskon,0,',','.');
            $total_diskon           = $total_diskon + $jumlah_diskon;
            //Routing Jumlah Diskon
            if($jumlah_diskon==0.00){
                $label_diskon   = '<span class="text text-grayish">'.$jumlah_diskon_format.'</span>';
            }else{
                $label_diskon   = '<span class="text text-dark">'.$jumlah_diskon_format.'</span>';
            }

            //Hitung Jumlah Tagihan
            $SumTagihan             = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
            $jumlah_tagihan         = $SumTagihan['total_tagihan'];
            $jumlah_tagihan_format  = "" . number_format($jumlah_tagihan,0,',','.');
            $total_tagihan          = $total_tagihan + $jumlah_tagihan;
            //Routing Jumlah Tagihan
            if(empty($jumlah_tagihan)){
                $label_jumlah_tagihan   = '<span class="text text-grayish">'.$jumlah_tagihan_format.'</span>';
            }else{
                $label_jumlah_tagihan   = '<span class="text text-dark">'.$jumlah_tagihan_format.'</span>';
            }

            //Hitung Pembayaran
            $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_organization_class='$id_organization_class'"));
            $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];
            $jumlah_pembayaran_format   = "" . number_format($jumlah_pembayaran,0,',','.');
            $total_pembayaran           = $total_pembayaran + $jumlah_pembayaran;
            //Routing Pembayaran
            if(empty($jumlah_pembayaran)){
                $label_jumlah_pembayaran = '<span class="text text-grayish">'.$jumlah_pembayaran_format.'</span>';
            }else{
                $label_jumlah_pembayaran = '<span class="text text-dark">'.$jumlah_pembayaran_format.'</span>';
            }

            //Menghitung Sisa Tagihan
            $sisa_tagiihan          = $jumlah_biaya - $jumlah_diskon - $jumlah_pembayaran ;
            $total_sisa             = $total_sisa + $sisa_tagiihan;
            $sisa_tagiihan_format   = "" . number_format($sisa_tagiihan,0,',','.');
            //Routing Sisa Tagihan
            if(empty($sisa_tagiihan)){
                $label_sisa_tagihan   = '<span class="text text-grayish">'.$sisa_tagiihan_format.'</span>';
            }else{
                $label_sisa_tagihan   = '<span class="text text-dark">'.$sisa_tagiihan_format.'</span>';
            }

            //Tampilkan Data
            echo '
                <tr>
                    <td align="center"><small></small></td>
                    <td align="left"><small>'.$no_class_level.'.'.$no_class_name.'</small></td>
                    <td align="left"><small>'.$class_name.'</small></td>
                    <td align="left"><small>'.$label_jumlah_siswa.'</small></td>
                    <td align="right"><small>'.$label_jumlah_komponen.'</small></td>
                    <td align="right"><small>'.$label_jumlah_biaya.'</small></td>
                    <td align="right"><small>'.$label_diskon.'</small></td>
                    <td align="right"><small>'.$label_jumlah_tagihan.'</small></td>
                    <td align="right"><small>'.$label_jumlah_pembayaran.'</small></td>
                    <td align="right"><small>'.$label_sisa_tagihan.'</small></td>
                    <td align="center">
                        <a href="javascriipt:void(0);" class="btn btn-sm btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalRincianTagihanSiswa" data-id1="'.$id_academic_period .'" data-id2="'.$id_organization_class .'">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </td>
                </tr>
            ';
            $no_class_name++;
        }

        $no_class_level++;
    }

    $total_biaya_format         = "" . number_format($total_biaya,0,',','.');
    $total_diskon_format        = "" . number_format($total_diskon,0,',','.');
    $total_tagihan_format       = "" . number_format($total_tagihan,0,',','.');
    $total_pembayaran_format    = "" . number_format($total_pembayaran,0,',','.');
    $total_sisa_format          = "" . number_format($total_sisa,0,',','.');
    echo '
        <tr>
            <td align="right"></td>
            <td align="left" colspan="2"><b><small>JUMLAH TOTAL</small></b></td>
            <td align="right"><b><small>'.$total_siswa.' Orang</small></b></td>
            <td align="right"><b><small>'.$total_komponen.' Komponen</small></b></td>
            <td align="right"><b><small>'.$total_biaya_format.'</small></b></td>
            <td align="right"><b><small>'.$total_diskon_format.'</small></b></td>
            <td align="right"><b><small>'.$total_tagihan_format.'</small></b></td>
            <td align="right"><b><small>'.$total_pembayaran_format.'</small></b></td>
            <td align="right"><b><small>'.$total_sisa_format.'</small></b></td>
            <td align="right"></td>
        </tr>
    ';
    //Tampilkan Di Atas Tabel
    echo '
        <script>
            $("#title_tagihan_siswa").html(' . json_encode($title_tagihan_siswa) . ');
            $("#export_tagihan_siswa").attr("href", "_Page/Exporter/ExportKelas.php?id_academic_period='.$id_academic_period.'");
        </script>
    ';
?>