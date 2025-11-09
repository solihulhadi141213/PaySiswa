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
    
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <div class="alert alert-danger"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></div>
            <script>$("#title_riincian_tagihan_biaya_pendidikan").html("");</script>
        ';
        exit;
    }

    //Tangkap id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger"><small class="text-danger">ID Kelas Tidak Boleh Kosong!</small></div>
            <script>$("#title_riincian_tagihan_biaya_pendidikan").html("");</script>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka academic_period dari tabel 'academic_period'
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka nama kelas
    $class_level            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name             = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

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
    echo '              <td class="text-center" colspan="'.$jumlah_komponen.'" valign="middle"><b><small>Komponen Biaya</small></b></td>';
    echo '              <td class="text-center" rowspan="2" valign="middle"><b><small>Tagiihan</small></b></td>';
    echo '              <td class="text-center" rowspan="2" valign="middle"><b><small>Pembayaran</small></b></td>';
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
    echo '          </tr>';
    echo '      </thead>';
    echo '      <tbody>';
                
                //Loopiing Data Siswa pada tabel 'fee_by_student'
                $no                 = 1;
                $jumlah_total       = 0;
                $total_pembayaran   = 0;
                $total_sisa         = 0;
                $qry_siswa = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_student ASC");
                while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
                    $id_student = $data_siswa['id_student'];

                    //Buka nama siswa
                    $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
                    $student_nis = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

                    //Tampilkan Data
                    echo '<tr>';
                    echo '  <td><small>'.$no.'</small></td>';
                    echo '  <td><small>'.$student_name.'<br>('.$student_nis.')</small></td>';
                    
                    //Looping Komponen Biaya
                    $jumlah_fee             = 0;
                    $subtotal_pembayaran    = 0;
                    $qry_komponen = mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
                    while ($data_komponen = mysqli_fetch_array($qry_komponen)) {
                        $id_fee_component = $data_komponen['id_fee_component'];

                        //Buka 'fee_nominal' dan 'fee_discount' dari tabel 'fee_by_student'
                        $QryComponent = $Conn->prepare("SELECT id_fee_by_student, fee_nominal, fee_discount FROM fee_by_student WHERE id_organization_class = ? AND id_student = ? AND id_fee_component = ?");
                        $QryComponent->bind_param("iii", $id_organization_class, $id_student, $id_fee_component);

                        //Jika Terjadi kesalahan Pada Saat Membuka Data
                        if (!$QryComponent->execute()) {
                            $id_fee_by_student  = $Conn->error;
                            $fee_nominal        = $Conn->error;
                            $fee_discount       = $Conn->error;
                            $fee_total          = $Conn->error;
                            $jumlah_fee         = $jumlah_fee + 0;
                            $jumlah_total       = $jumlah_total + 0;
                        }else{
                            $ResultComponent    = $QryComponent->get_result();
                            $DataComponent      = $ResultComponent->fetch_assoc();
                            $QryComponent->close();
                            //Buat Variabel
                            if(!empty($DataComponent['id_fee_by_student'])){
                                $id_fee_by_student  = $DataComponent['id_fee_by_student'];
                                $fee_nominal        = $DataComponent['fee_nominal'];
                                $fee_discount       = $DataComponent['fee_discount'];
                                $fee_total          = $fee_nominal - $fee_discount;
                                $jumlah_fee         = $jumlah_fee + $fee_total;
                                $jumlah_total       = $jumlah_total + $fee_total;
                                $fee_total          = "Rp " . number_format($fee_total,0,',','.');
                            }else{
                                $id_fee_by_student  = "";
                                $fee_nominal        = 0;
                                $fee_discount       = 0;
                                $fee_total          = 0;
                                $jumlah_fee         = 0;
                                $jumlah_total       = 0;
                                $fee_total          = "Rp " . number_format($fee_total,0,',','.');
                            }
                            
                        }

                        //Buka Data Pembayaran dari tabel 'payment'
                        $jumlah_pembayaran = 0; 
                        $QryPayment = mysqli_query($Conn, "SELECT payment_nominal FROM payment WHERE id_fee_by_student='$id_fee_by_student' ORDER BY id_payment ASC");
                        while ($DataPayment = mysqli_fetch_array($QryPayment)) {
                            $payment_nominal        = $DataPayment['payment_nominal'];
                            $jumlah_pembayaran      = $jumlah_pembayaran + $payment_nominal; 
                            $subtotal_pembayaran    = $subtotal_pembayaran + $jumlah_pembayaran;
                        }
                        
                        //Format 'jumlah_pembayaran'
                        $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');

                        //Routing '$_label_jumlah_pembayaran'
                        if($jumlah_pembayaran){
                            $_label_jumlah_pembayaran = '<span class="text text-success">('.$jumlah_pembayaran_format.')</span>';
                        }else{
                            $_label_jumlah_pembayaran = '<span class="text text-grayish">('.$jumlah_pembayaran_format.')</span>';
                        }

                        //Tampilkan Nilai 'fee_total' pada masing-masing komponen biaya
                        echo '<td align="right"><small>'. $fee_total.'<br>'.$_label_jumlah_pembayaran.'</small></td>';
                    }

                    //Format jumlah_fee
                    $jumlah_fee_format          = "Rp " . number_format($jumlah_fee,0,',','.');
                    $subtotal_pembayaran_format = "Rp " . number_format($subtotal_pembayaran,0,',','.');

                    //Menghiitung Sisa
                    $sisa           = $jumlah_fee - $subtotal_pembayaran;
                    $sisa_format    = "Rp " . number_format($sisa,0,',','.');
                    $total_sisa     = $total_sisa + $sisa;

                    //Tampilkan jumlah_fee_format
                    echo '  <td align="right"><small>'. $jumlah_fee_format.'</small></td>';
                    echo '  <td align="right"><small>'. $subtotal_pembayaran_format.'</small></td>';
                    echo '  <td align="right"><small>'. $sisa_format.'</small></td>';
                    echo '</tr>';
                    $no++;
                }

                //Format Jumlah Total
                $jumlah_total_format        = "Rp " . number_format($jumlah_total,0,',','.');
                $total_pembayaran_format    = "Rp " . number_format($total_pembayaran,0,',','.');
                $total_sisa_format          = "Rp " . number_format($total_sisa,0,',','.');
                echo '
                    <tr>
                        <td colspan="2"><b>JUMLAH-TOTAL</b></td>
                        <td colspan="'.$jumlah_komponen.'"></td>
                        <td class="text-end"><b>'.$jumlah_total_format.'</b></td>
                        <td class="text-end"><b>'.$total_pembayaran_format.'</b></td>
                        <td class="text-end"><b>'.$total_sisa_format.'</b></td>
                    </tr>
                ';
    echo '      </tbody>';
    echo '  </table>';
    echo '</div>';

    //Atur title table dan tombol untuk kembali
    echo '
        <script>
            $("#title_riincian_tagihan_biaya_pendidikan").html("PERIODE '.$academic_period.' '.$class_level.' - '.$class_name.'");
            $("#button_kembali_ke_tagihan_siswa").attr("data-id", "'.$id_academic_period.'");
            $("#ExportRincianTagihanSiswaPdf").attr("href", "_Page/TahunAjaran/ProsesExportRiincianTagihanSiswa.php?type=PDF&id_academic_period='.$id_academic_period.'&id_organization_class='.$id_organization_class.'");
            $("#ExportRincianTagihanSiswaHtml").attr("href", "_Page/TahunAjaran/ProsesExportRiincianTagihanSiswa.php?type=HTML&id_academic_period='.$id_academic_period.'&id_organization_class='.$id_organization_class.'");
        </script>
    ';
?>