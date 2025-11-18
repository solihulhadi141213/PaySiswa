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
    $jumlah_tagihan         = "Rp " . number_format($jumlah_tagihan,0,',','.');

    //Membuka 'student_nis, student_name, student_gender, student_status dari table' 'student'
    $student_nis            = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name           = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_gender         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');
    $student_status         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_status');
    $id_organization_class2 = GetDetailData($Conn, 'student', 'id_student', $id_student, 'id_organization_class');
    $class_level1           = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class2, 'class_level');
    $class_name1            = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class2, 'class_name');

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

    //Menampilkan Informasi Tagihan
    echo '
        <input type="hidden" name="id_fee_by_student" value="'.$id_fee_by_student.'">
        <div class="row border-1 border-bottom">
            <div class="col-md-6 mb-3">
                <div class="row">
                    <div class="col-12">
                        <small><b># Informasi Tagihan</b></small>
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
                    <div class="col-5"><small>Kelas / Rombel</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>K.B.P</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_name.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Kategori</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_category.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Periode Tagihan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$periode_month.' / '.$periode_year.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Nominal Tagihan</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="underscore_doted">
                            <span class="text text-grayish">'.$fee_nominal_format.'</span>
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Diskon</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6">
                        <small class="underscore_doted">
                            <span class="text text-grayish">'.$fee_discount_format.'</span>
                        </small>
                    </div>
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
                    <div class="col-6"><small class="text text-grayish">'.$student_gender.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Jenjang/Level (Aktual)</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_level1.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Kelas (Aktual)</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_name1.'</small></div>
                </div>
                <div class="row">
                    <div class="col-5"><small>Status Siswa</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$student_status.'</small></div>
                </div>
            </div>
        </div>
    ';
    //Menampilkan Riwayat Pembayaran
    echo '
       <div class="row mt-3 mb-3">
            <div class="col-8">
                <small><b># Riwayat Pembayaran</b></small>
            </div>
            <div class="col-4 text-end">
                <button type="button" class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahPembayaran" data-id="'.$id_fee_by_student .'">
                    <i class="bi bi-plus"></i> Bayar
                </button>
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
                                <td align="left"><small><b>Tanggal Bayar</b></small></th>
                                <td align="left"><small><b>Metode</b></small></th>
                                <td align="right"><small><b>Nominal</b></small></th>
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
                <td align="center" colspan="5"><small>Belum Ada Pembayaran Untuk Tagihan Ini</small></td>
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
            $payment_datetime_format    = date('d/m/Y H:i', strtotime($payment_datetime));

            //Format Nominal
            $payment_nominal_format     = "Rp " . number_format($payment_nominal,0,',','.');

            //Tampilkan Data
            echo '
                <tr>
                    <td align="center"><small>'.$no.'</small></td>
                    <td align="left"><small>'.$payment_datetime_format.'</small></td>
                    <td align="left"><small>'.$payment_method.'</small></td>
                    <td align="right"><small>'.$payment_nominal_format.'</small></td>
                    <td align="center">
                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                            <li>
                                <a class="dropdown-item" href="_page/Exporter/ExporterPembayaran.php?id='.$id_payment .'" target="_blank">
                                    <i class="bi bi-info-circle"></i> Cetak Pembayaran
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusPembayaran" data-id_payment="'.$id_payment .'" data-id_fee_by_student="'.$id_fee_by_student .'">
                                    <i class="bi bi-trash"></i> Hapus Pembayaran
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            ';
            $no++;
        }

        //Format Total Payment
        $total_payment_format     = "Rp " . number_format($total_payment,0,',','.');

        //Routing Status Pembayaran
        if($jumlah_tagihan<=$total_payment){
            $status_pembayaran  = "LUNAS";
        }else{
            $status_pembayaran  = "-";
        }
        echo '
            <tr>
                <td align="center"></td>
                <td align="left"><small><b>TOTAL BAYAR</b></small></td>
                <td align="left"></td>
                <td align="right"><small><b>'.$total_payment_format.'</b></small></td>
                <td align="center"></td>
            </tr>
        ';
        echo '
            <tr>
                <td align="center"></td>
                <td align="left"><small><b>KETERANGAN</b></small></td>
                <td align="left"></td>
                <td align="right"><small><b>'.$status_pembayaran.'</b></small></td>
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