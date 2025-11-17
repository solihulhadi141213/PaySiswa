<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Session Akses
    if(empty($SessionIdAccess)){
        echo '
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang.</small></div>
                </div>
            </div>
        ';
        exit;
    }

    //id_fee_by_student wajib terisi
    if(empty($_POST['id_fee_by_student'])){
        echo '
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger"><small>ID Tagihan Tiidak Boleh Kosong!</small></div>
                </div>
            </div>
        ';
        exit;
    }

    //Buat variabel 'id_fee_by_student'
    $id_fee_by_student      = $_POST['id_fee_by_student'];

    //Buka 'id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount' dari tabel 'fee_by_student' 
    $id_organization_class  = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_organization_class');
    $id_student             = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_student');
    $id_fee_component       = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'id_fee_component');
    $fee_nominal            = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'fee_nominal');
    $fee_discount           = GetDetailData($Conn, 'fee_by_student', 'id_fee_by_student', $id_fee_by_student, 'fee_discount');
    $jumlah_tagihan         = $fee_nominal-$fee_discount;
    $fee_nominal_format     = "Rp " . number_format($fee_nominal,0,',','.');
    $fee_discount_format    = "Rp " . number_format($fee_discount,0,',','.');
    $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');

    //Membuka 'student_nis, student_name, student_gender, student_status dari table' 'student'
    $student_nis            = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name           = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_gender         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');
    $student_status         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_status');
    $id_organization_class2 = GetDetailData($Conn, 'student', 'id_student', $id_student, 'id_organization_class');
    $class_level1           = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class2, 'class_level');
    $class_name1            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class2, 'class_name');

    //Routing Student Gender
    if($student_gender=="Male"){
        $label_gender='Laki-laki';
    }else{
        if($student_gender=="Female"){
            $label_gender='Perempuan';
        }else{
            $label_gender='-';
        }
    }

    //Buka Periode Akademik
    $id_academic_period     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name             = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka Data Komponen
    $component_name         = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
    $component_category     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
    $periode_month          = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
    $periode_year           = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

    //Nama Bulan
    $nama_bulan             = getNamaBulan($periode_month);

    //Menampilkan Informasi Tagihan
    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
        <div class="row border-1 border-bottom">
            <div class="col-md-6 mb-3">
                <div class="row">
                    <div class="col-12">
                        <small><b># Periode Akademik</b></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Level/Jenjang</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Kelas/Rombel</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="row">
                    <div class="col-12">
                        <small><b># Informasi Siswa</b></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Nama Siswa</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$student_name.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>NIS</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$student_nis.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Gender</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$label_gender.'</small></div>
                </div>
            </div>
        </div>
        <div class="row border-1 border-bottom">
            <div class="col-md-6 mb-3">
                <div class="row">
                    <div class="col-12">
                        <small><b># Komponen Biaya</b></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Nama Komponen</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_name.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Kategori Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_category.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Periode Tagihan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="row">
                    <div class="col-12">
                        <small><b># Nominal Tagihan</b></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Nominal</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="">
                            <span class="text text-grayish">'.$fee_nominal_format.'</span>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Diskon</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="">
                            <span class="text text-grayish">'.$fee_discount_format.'</span>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Tagihan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="underscore_doted">
                            <span class="text text-grayish">'.$jumlah_tagihan_format.'</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    ';
    //Menampilkan Riwayat Pembayaran
    echo '
       <div class="row mt-3 mb-3">
            <div class="col-12">
                <small><b># Riwayat Pembayaran</b></small>
            </div>
       </div>
    ';
    echo '<div class="row mb-3">';
    echo '  <div class="col-md-12">';
    echo '      <div class="table table-responsive">';
    echo '          <table class="table table-striped table-hover border-1 border-top">';
    echo '              
                        <thead>
                            <tr>
                                <td align="center"><small><b>No</b></small></th>
                                <td align="left"><small><b>Tanggal</b></small></th>
                                <td align="left"><small><b>Jam</b></small></th>
                                <td align="left"><small><b>Metode</b></small></th>
                                <td align="right"><small><b>Pembayaran</b></small></th>
                                <td align="center"><small><b>Opsi</b></small></th>
                            </tr>
                        </thead>
    ';
    echo '              <tbody>';
    //Hitung Jumlah Pembayaran
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_payment FROM payment  WHERE id_fee_by_student='$id_fee_by_student'"));
    if(empty($jml_data)){
        echo '
            <tr>
                <td align="center" colspan="6"><small class="text-danger">Belum Ada Pembayaran Untuk Tagihan Ini</small></td>
            </tr>
        ';
    }else{
        //Menampilkan Riwayat Pembayaran
        $no = 1;
        $total_payment =0;
        $query = mysqli_query($Conn, "SELECT * FROM payment WHERE id_fee_by_student='$id_fee_by_student'");
        while ($data = mysqli_fetch_array($query)) {
            $id_payment  = $data['id_payment'];
            $payment_datetime  = $data['payment_datetime'];
            $payment_nominal  = $data['payment_nominal'];
            $payment_method  = $data['payment_method'];

            //Akumulasii Payment
            $total_payment = $total_payment + $payment_nominal;

            //Format Tanggal
            $payment_datetime_format    = date('d/m/Y', strtotime($payment_datetime));
            $payment_time_format        = date('H:i T', strtotime($payment_datetime));

            //Format Nominal
            $payment_nominal_format     = "Rp " . number_format($payment_nominal,0,',','.');

            //Tampilkan Data
            echo '
                <tr>
                    <td align="center"><small>'.$no.'</small></td>
                    <td align="left"><small>'.$payment_datetime_format.'</small></td>
                    <td align="left"><small>'.$payment_time_format.'</small></td>
                    <td align="left"><small>'.$payment_method.'</small></td>
                    <td align="right"><small>'.$payment_nominal_format.'</small></td>
                    <td align="center">
                        <a href="_page/Exporter/ExporterPembayaran.php?id='.$id_payment .'" target="_blank" class="btn btn-sm btn-outline-dark btn-floating">
                            <i class="bi bi-printer"></i>
                        </button>
                    </td>
                </tr>
            ';
            $no++;
        }

        //Format Total Payment
        $total_payment_format     = "Rp " . number_format($total_payment,0,',','.');

        //Menghitung Sisa Tagihan
        $sisa_tagihan           = $jumlah_tagihan - $total_payment;
        $sisa_tagihan_format    = "Rp " . number_format($sisa_tagihan,0,',','.');

        //Routing Status Pembayaran
        if($jumlah_tagihan<=$total_payment){
            $status_pembayaran  = '<b class="text-success">Lunas</b>';
        }else{
            $status_pembayaran  = '<b class="text-danger">Menunggu</b>';
        }
        echo '
            <tr>
                <td align="center"></td>
                <td align="left" colspan="2"><small><b>TOTAL PEMBAYARAN</b></small></td>
                <td align="left"></td>
                <td align="right"><small><b>'.$total_payment_format.'</b></small></td>
                <td align="center"></td>
            </tr>
        ';
        echo '
            <tr>
                <td align="center"></td>
                <td align="left" colspan="2"><small><b>SISA/TAGIHAN</b></small></td>
                <td align="left"></td>
                <td align="right"><small><b>'.$sisa_tagihan_format.'</b></small></td>
                <td align="center"></td>
            </tr>
        ';
        echo '
            <tr>
                <td align="center"></td>
                <td align="left" colspan="2"><small><b>KETERANGAN</b></small></td>
                <td align="left"></td>
                <td align="right"><small>'.$status_pembayaran.'</small></td>
                <td align="center"></td>
            </tr>
        ';

    }
    echo '';
    echo '              </tbody>';
    echo '          </table>';
    echo '      </div>';
    echo '  </div>';
    echo '</div>';
?>