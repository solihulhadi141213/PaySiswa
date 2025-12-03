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
            <div class="alert alert-danger"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></div>
            <script>$("#title_riincian_tagihan_biaya_pendidikan").html("");</script>
        ';
        exit;
    }
    
    //Validasi 'id_academic_period'
    if(empty($_POST['id_academic_period'])){
        echo '
            <div class="alert alert-danger"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></div>
            <script>$("#title_riincian_tagihan_biaya_pendidikan").html("");</script>
        ';
        exit;
    }

    //Validasi 'id_organization_class'
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger"><small class="text-danger">ID Kelas Tidak Boleh Kosong!</small></div>
            <script>$("#title_riincian_tagihan_biaya_pendidikan").html("");</script>
        ';
        exit;
    }

    //Buat variabel dan Sanitasi untuk 'id_academic_period' dan 'id_organization_class'
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka 'academic_period' dari tabel 'academic_period'
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka nama kelas
    $class_level            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name             = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buat Variabel 'title_riincian_tagihan_biaya_pendidikan'
    $title_riincian_tagihan_biaya_pendidikan = '
        <div class="row mb-2">
            <div class="col-md-12 mb-3 text-center">
                <b>
                    DAFTAR TAGIHAN BIAYA PENDIDIKAN<br>
                    PERIODE <span class="text-primary underscore_doted">'.$academic_period.'</span>
                </b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <small>
                    JENJANG LEVEL : <span class="text-grayish">'.$class_level.'</span> | ROMBEL KELAS : <span class="text-grayish">'.$class_name.'</span>
                </small>
            </div>
        </div>
    ';

    //Hitung Jumlah Data pada tabel 'fee_by_student'
    $jml_data               = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));

    //Jika Data Tidak Ditemukan
    if(empty($jml_data)){
        echo '
            <div class="alert alert-danger"><small class="text-danger">Tidak ada data tagihan pada periode & kelas ini</small></div>
            <script>$("#title_riincian_tagihan_biaya_pendidikan").html("");</script>
        ';
        exit;
    }

    //Menghitung Jumlah Komponen Biaya (id_fee_component) dari tabel 'fee_by_student'
    $jumlah_komponen        = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));

    //Menampilkan Tabel
    echo '<div class="table table-responsive">';
    echo ' <table class="table table-bordered table-striped table-hover">';

    //Setup Header Tabel
    echo '      <thead>';
    echo '          <tr>';
    echo '              <td class="text-center" rowspan="2" valign="middle"><b><small>No</small></b></td>';
    echo '              <td class="text-center" rowspan="2" valign="middle"><b><small>Nama Siswa</small></b></td>';
    echo '              <td class="text-center" colspan="'.$jumlah_komponen.'" valign="middle"><b><small>KOMPONEN BIAYA PENDIDIKAN</small></b></td>';
    echo '              <td class="text-center" valign="middle"><b><small>JUMLAH TAGIHAN</small></b></td>';
    echo '              <td class="text-center" rowspan="2" valign="middle"><b><small>Sisa</small></b></td>';
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
                        echo '<td class="text-center" valign="middle"><b><small><small>'. $component_name.' <br>('. $component_category.')</small></small></b></td>';
                    }
    ECHO '              <td class="text-center" valign="middle"><b><small>PEMBAYARAN</small></b></td>';
    echo '          </tr>';
    echo '      </thead>';
    echo '      <tbody>';
                
                

                //Inisialisasi Jumlah Total Tagihan, Pembayaran dan Sisa
                $total_tagihan       = 0;
                $total_pembayaran    = 0;
                $total_sisa          = 0;
                
                //Inisialisasi Nomor Ururt
                $no = 1;
                //Loopiing Data Siswa pada tabel 'fee_by_student'
                $qry_siswa = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_student ASC");
                while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
                    $id_student = $data_siswa['id_student'];

                    //Buka nama siswa
                    $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
                    $student_nis = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

                    //Tampilkan Data
                    echo '<tr>';
                    echo '  <td  class="text-center"><small>'.$no.'</small></td>';
                    echo '  <td><small>'.$student_name.'<br><small>('.$student_nis.')</small></small></td>';

                    //Inisialisasi Jumlah
                    $jumlah_tagihan     = 0;
                    $jumlah_pembayaran  = 0;
                    
                    //Looping Komponen Biaya
                    $qry_komponen = mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
                    while ($data_komponen = mysqli_fetch_array($qry_komponen)) {
                        
                        # Inisiasi variabel 'id_fee_component'
                        $id_fee_component = $data_komponen['id_fee_component'];

                        # Menghitung Subtotal Tagihan
                        $SumTagihan                 = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS subtotal_tagihan FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                        $subtotal_tagihan           = $SumTagihan['subtotal_tagihan'];
                        $subtotal_tagihan_format    = "" . number_format($subtotal_tagihan,0,',','.');

                        # Hitung Subtotal Pembayaran
                        $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS subtotal_payment FROM payment WHERE id_student='$id_student' AND id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
                        $subtotal_payment           = $SumPembayaran['subtotal_payment'];
                        $subtotal_payment_format    = "" . number_format($subtotal_payment,0,',','.');

                        # Routing '$_label_jumlah_pembayaran'
                        if($subtotal_payment){
                            $label_subtotal_pembayaran = '<small class="text text-success">('.$subtotal_payment_format.')</small>';
                        }else{
                            $label_subtotal_pembayaran = '<small class="text text-grayish">('.$subtotal_payment_format.')</small>';
                        }

                        // Tampilkan Kolom
                        echo '
                            <td align="right">
                                <small>
                                    '. $subtotal_tagihan_format.'<br>'.$label_subtotal_pembayaran.'
                                </small>
                            </td>
                        ';

                        # Akumulasikan Per Baris -->
                        $jumlah_tagihan     = $jumlah_tagihan + $subtotal_tagihan;
                        $jumlah_pembayaran  = $jumlah_pembayaran + $subtotal_payment;
                    }

                    //Format 'jumlah_tagihan' dan 'jumlah_pembayaran'
                    $jumlah_tagihan_format      = "" . number_format($jumlah_tagihan,0,',','.');
                    $jumlah_pembayaran_format   = "" . number_format($jumlah_pembayaran,0,',','.');

                    //Menghiitung Sisa
                    $jumlah_sisa        = $jumlah_tagihan - $jumlah_pembayaran;
                    $jumlah_sisa_format = "" . number_format($jumlah_sisa,0,',','.');

                    //Routing Warna text untuk 'jumlah_pembayaran'
                    if(!empty($jumlah_pembayaran)){
                        $label_jumlah_pembayaran = '<small class="text text-success">('.$jumlah_pembayaran_format.')</small>';
                    }else{
                        $label_jumlah_pembayaran = '<small class="text text-grayish">('.$jumlah_pembayaran_format.')</small>';
                    }

                    //Routing Warna text untuk 'jumlah_sisa'
                    if(!empty($jumlah_sisa)){
                        $label_jumlah_sisa = '<span class="text text-danger">'.$jumlah_sisa_format.'</span>';
                    }else{
                        $label_jumlah_sisa = '<span class="text text-success">'.$jumlah_sisa_format.'</span>';
                    }

                    //Tampilkan jumlah_tagihan_format, 
                    echo '  
                            <td align="right">
                                <small>
                                    '. $jumlah_tagihan_format.'<br>
                                    '. $label_jumlah_pembayaran.'
                                </small>
                            </td>
                            <td align="right">
                                <small>
                                    '. $label_jumlah_sisa.'
                                </small>
                            </td>
                    ';
                    echo '</tr>';

                    # Akumali total tagihan, pembayaran dan sisa
                    $total_tagihan       = $total_tagihan + $jumlah_tagihan;
                    $total_pembayaran    = $total_pembayaran + $jumlah_pembayaran;
                    $total_sisa          = $total_sisa + $jumlah_sisa;

                    //Number Plus-plus
                    $no++;
                }

                //Format total tagihan, pembayaran dan sisa
                $total_tagihan_format       = "" . number_format($total_tagihan,0,',','.');
                $total_pembayaran_format    = "" . number_format($total_pembayaran,0,',','.');
                $total_sisa_format          = "" . number_format($total_sisa,0,',','.');

                //Menghitung Colspan
                $colspan = $jumlah_komponen+2; // 2 adalah kolom nomor dan nama siswa

                //Tampilkan baris akhir
                echo '
                    <tr>
                        <td class="text-end" colspan="'.$colspan.'"><small><b>TOTAL TAGIHAN</b></small></td>
                        <td class="text-end"><small><b>'.$total_tagihan_format.'</b></small></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td class="text-end" colspan="'.$colspan.'"><small><b>TOTAL PEMBAYARAN</b></small></td>
                        <td class="text-end"><small><b>'.$total_pembayaran_format.'</b></small></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td class="text-end" colspan="'.$colspan.'"><small><b>TOTAL SISA TUNGGAKAN</b></small></td>
                        <td class="text-end"></td>
                        <td class="text-end"><small><b>'.$total_sisa_format.'</b></small></td>
                    </tr>
                ';
    echo '      </tbody>';
    echo '  </table>';
    echo '</div>';

    //Atur title table dan tombol untuk kembali
    echo '
        <script>
            $("#title_riincian_tagihan_biaya_pendidikan").html(' . json_encode($title_riincian_tagihan_biaya_pendidikan) . ');
            $("#export_rincian_tagihan_siswa").attr("href", "_Page/Exporter/ExportMatrixTagihanSiswa.php?id_organization_class='.$id_organization_class.'");
        </script>
    ';
?>