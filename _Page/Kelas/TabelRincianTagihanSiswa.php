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
                <td colspan="8" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>
                $("#put_student_name").html("-");
                $("#put_student_nis").html("-");
                $("#put_academic_period").html("-");
                $("#put_class_name").html("-");
                $("#put_button_tambah_rincian_tagihan_siswa").html("");
            </script>
        ';
        exit;
    }

    //id_organization_class wajib terisi
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">ID Kelas Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>
                $("#put_student_name").html("-");
                $("#put_student_nis").html("-");
                $("#put_academic_period").html("-");
                $("#put_class_name").html("-");
                $("#put_button_tambah_rincian_tagihan_siswa").html("");
            </script>
        ';
    }

    //id_student wajib terisi
    if(empty($_POST['id_student'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">ID Siswa Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>
                $("#put_student_name").html("-");
                $("#put_student_nis").html("-");
                $("#put_academic_period").html("-");
                $("#put_class_name").html("-");
                $("#put_button_tambah_rincian_tagihan_siswa").html("");
            </script>
        ';
    }
    
    //Buat variabel id_organization_class
    $id_organization_class=$_POST['id_organization_class'];

    //Buat variabel id_student
    $id_student=$_POST['id_student'];

    //Buka class_name
    $class_name     = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $class_level    = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');

    //Buka id_academic_period
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');

    //Nama Periode
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Buka 'student_name' dan 'student_nis' dari tabel 'student_name'
    $student_name   = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_nis    = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    
    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student  FROM fee_by_student  WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'"));
    
    //Jika Data Kosong
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="8" class="text-center">
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

    //Tampilkan Data tagihan siswa pada tabel fee_by_student
    $query = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_student='$id_student'");

    //Looping Tampiilkan Data
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_by_student  = $data['id_fee_by_student'];
        $id_fee_component   = $data['id_fee_component'];
        $fee_nominal        = $data['fee_nominal'];
        $fee_discount       = $data['fee_discount'];

        //Hitung Tgaihan
        $tagihan            = $fee_nominal - $fee_discount;

        //Buka Nama Komponen Biaya
        $component_name     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');

        //Hitung Jumlah Pembayaran
        $SumPembayaran      = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_by_student='$id_fee_by_student'"));
        $jumlah_pembayaran  = $SumPembayaran['jumlah_pembayaran'];

        //Sisa tunggakan
        $sisa_tunggakan     = $tagihan - $jumlah_pembayaran;

        //Akumulasi Total
        $total_nomiinal     = $total_nomiinal + $fee_nominal;
        $total_diskon       = $total_diskon + $fee_discount;
        $total_tagihan      = $total_tagihan + $tagihan;
        $total_pembayaran   = $total_pembayaran + $jumlah_pembayaran;
        $total_tunggakan    = $total_tunggakan + $sisa_tunggakan;

        //Format Rupiah
        $fee_nominal_format         = "Rp " . number_format($fee_nominal,0,',','.');
        $fee_discount_format        = "Rp " . number_format($fee_discount,0,',','.');
        $tagihan_format             = "Rp " . number_format($tagihan,0,',','.');
        $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
        $sisa_tunggakan_format      = "Rp " . number_format($sisa_tunggakan,0,',','.');

        //Routiing tooltip san style
        if(empty($sisa_tunggakan)){
            $tooltip_sisa="Lunas";
            $style_sisa_tagihan = "text-success";
        }else{
            $tooltip_sisa="Perlu Pembayaran";
            $style_sisa_tagihan = "text-danger";
        }
               
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td align="right"><small>'.$fee_nominal_format.'</small></td>
                <td align="right"><small>'.$fee_discount_format.'</small></td>
                <td align="right"><small>'.$tagihan_format.'</small></td>
                <td align="right"><small>'.$jumlah_pembayaran_format.'</small></td>
                <td align="right">
                    <a href="javascript:void(0);" class="'.$style_sisa_tagihan.'" data-bs-toggle="modal" data-bs-target="#ModalDetailTagihan" data-id="'.$id_fee_by_student .'">
                        <small class="underscore_doted" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="'.$tooltip_sisa.'">
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
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailTagihan" data-id="'.$id_fee_by_student .'">
                                <i class="bi bi-info-circle"></i> Detail Tagihan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditRincianTagihan" data-id="'.$id_fee_by_student .'">
                                <i class="bi bi-pencil"></i> Edit Tagihan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusRincianTagihan" data-id="'.$id_fee_by_student .'">
                                <i class="bi bi-trash"></i> Hapus Tagihan
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
            <td colspan="2" class="text-end">
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
    echo '
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="id_student" value="'.$id_student.'">
    ';

    //Buat Tombol Tambah Rincian Tagihan Siswa
    $button_tambah_rincian_tagihan_siswa = '
        <button type="button" class="btn btn-md btn-primary button_tambah_rincian_tagihan_siswa" data-id_organization_class="'.$id_organization_class.'" data-id_student="'.$id_student.'">
            <i class="bi bi-plus-circle-dotted"></i> Tambah
        </button>
    ';
    echo '
        <script>
            $("#put_student_name").html("'.$student_name.'");
            $("#put_student_nis").html("'.$student_nis.'");
            $("#put_jumlah_kbp").html("'.$jml_data.' Komponen");
            $("#put_academic_period").html("'.$academic_period.'");
            $("#put_class_level").html("'.$class_level.'");
            $("#put_class_name").html("'.$class_name.'");
            $("#put_button_tambah_rincian_tagihan_siswa").html(' . json_encode($button_tambah_rincian_tagihan_siswa) . ');
        </script>
    ';
?>