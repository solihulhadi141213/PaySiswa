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
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>$("#title_rekapitulasi_tagihan_siswa").html("");</script>
        ';
        exit;
    }

    //id_organization_class wajib terisi
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">ID Kelas Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>$("#title_rekapitulasi_tagihan_siswa").html("");</script>
        ';
        exit;
    }
    
    //Buat variabel id_organization_class
    $id_organization_class=$_POST['id_organization_class'];

    //Buka class_name
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');

    //Buka id_academic_period
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');

    //Nama Periode
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    
    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
    
    //Jika Data Kosong
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">Tida Ada Data Yang Ditampilkan</small>
                </td>
            </tr>
        ';
    }

    //Inisialisasi Total
    $total_nomiinal     = 0;
    $total_diskon       = 0;
    $total_tagihan      = 0;
    $total_pembayaran   = 0;
    $total_tunggakan    = 0;

    //Inisiaslisasi Nomor
    $no = 1;

    //Tampilkan Data Siswa (student) Dari Tabel fee_by_student
    $query = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'");

    //Looping Tampiilkan Data
    while ($data = mysqli_fetch_array($query)) {
        $id_student      = $data['id_student'];

        //Buka 'student_name' dan 'student_nis' dari tabel 'student_name'
        $student_name   = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
        $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');

        //Inisialiasai fee_nominal dan fee_discount
        $jumlah_fee_nominal     = 0;
        $jumlah_fee_discount    = 0;
        $jumlah_tagihan         = 0;

        //Looping Data dari tabel 'fee_by_student' 
        $query_fee_by_student = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'");
        while ($data_fee_by_student = mysqli_fetch_array($query_fee_by_student)) {
            $fee_nominal    = $data_fee_by_student['fee_nominal'];
            $fee_discount   = $data_fee_by_student['fee_discount'];
            $tagihan        = $fee_nominal - $fee_discount;

            //Akumulasikan fee_nominal dan fee_discount
            $jumlah_fee_nominal     = $jumlah_fee_nominal + $fee_nominal;
            $jumlah_fee_discount    = $jumlah_fee_discount + $fee_discount;
            $jumlah_tagihan         = $jumlah_tagihan + $tagihan;
        }

        //Hitung Jumlah Pembayaran
        $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
        $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];

        //Sisa tunggakan
        $sisa_tunggakan = $jumlah_tagihan - $jumlah_pembayaran;

        //Akumulasi Total
        $total_nomiinal     = $total_nomiinal + $jumlah_fee_nominal;
        $total_diskon       = $total_diskon + $fee_discount;
        $total_tagihan      = $total_tagihan + $jumlah_tagihan;
        $total_pembayaran   = $total_pembayaran + $jumlah_pembayaran;
        $total_tunggakan    = $total_tunggakan + $sisa_tunggakan;

        //Format Rupiah
        $jumlah_fee_nominal_format  = "Rp " . number_format($jumlah_fee_nominal,0,',','.');
        $jumlah_fee_discount_format = "Rp " . number_format($jumlah_fee_discount,0,',','.');
        $jumlah_tagihan_format      = "Rp " . number_format($jumlah_tagihan,0,',','.');
        $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
        $sisa_tunggakan_format      = "Rp " . number_format($sisa_tunggakan,0,',','.');

        //Routing style sisa tunggakan
        if(empty($sisa_tunggakan)){
            $style_sisa_tunggakan = "text-success";
        }else{
            $style_sisa_tunggakan = "text-danger";
        }
       
               
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$student_name.'</small></td>
                <td><small>'.$student_nis.'</small></td>
                <td align="right"><small>'.$jumlah_fee_nominal_format.'</small></td>
                <td align="right"><small>'.$jumlah_fee_discount_format.'</small></td>
                <td align="right"><small>'.$jumlah_tagihan_format.'</small></td>
                <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                <td align="right">
                    <a href="javascript:void(0);" class="underscore_doted" data-bs-toggle="modal" data-bs-target="#ModalRincianTagihanSiswa" data-id_organization_class="'.$id_organization_class .'" data-id_student="'.$id_student .'">
                        <small class="'.$style_sisa_tunggakan.'" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Click untuk melihat rincian komponen tagihan">
                            '.$sisa_tunggakan_format.'
                        </small>
                    </a>
                </td>
                <td align="left">
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalRincianTagihanSiswa" data-id_organization_class="'.$id_organization_class .'" data-id_student="'.$id_student .'">
                                <i class="bi bi-info-circle"></i> Lihat Rincian
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusTagihanPerSiswa" data-id_organization_class="'.$id_organization_class .'" data-id_student="'.$id_student .'">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        $no++;
    }

    //Format Total
    $total_nomiinal_format      = "Rp " . number_format($total_nomiinal,0,',','.');
    $total_diskon_format        = "Rp " . number_format($total_diskon,0,',','.');
    $total_tagihan_format       = "Rp " . number_format($total_tagihan,0,',','.');
    $total_pembayaran_format    = "Rp " . number_format($total_pembayaran,0,',','.');
    $total_tunggakan_format     = "Rp " . number_format($total_tunggakan,0,',','.');

    //Tampilkan Total
    echo '
        <tr>
            <td colspan="3" class="text-end">
                <small><b>JUMLAH / TOTAL</b></small>
            </td>
            <td align="right"><small><b>'.$total_nomiinal_format.'</b></small></td>
            <td align="right"><small><b>'.$total_diskon_format.'</b></small></td>
            <td align="right"><small><b>'.$total_tagihan_format.'</b></small></td>
            <td align="right"><small><b>'.$total_pembayaran_format.'</b></small></td>
            <td align="right"><small><b>'.$total_tunggakan_format.'</b></small></td>
            <td align="right"></td>
        </tr>
    ';
    //Buat Variabel  'title_rekapitulasi_tagihan_siswa'
    $title_rekapitulasi_tagihan_siswa = '
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4"><small>Level/Jenjang</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$class_level.'</small></div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Kelas</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row">
                    <div class="col-4"><small>Jumlah Siswa</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$jml_data.' Orang</small></div>
                </div>
            </div>
        </div>
    ';
    echo '<input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">';
    // Gunakan json_encode agar HTML aman dikirim ke JavaScript
    echo '
        <script>
            $("#title_rekapitulasi_tagihan_siswa").html(' . json_encode($title_rekapitulasi_tagihan_siswa) . ');
        </script>
    ';
?>