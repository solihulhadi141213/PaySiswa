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
                <td colspan="8" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang</small>
                </td>
            </tr>
            <script>
                $("#TabelRincianTagihanSiswa").html("");
                $("#button_tambah_tagihan_siswa").html("");
            </script>
        ';
        exit;
    }

    //Validasi id_student
    if(empty($_POST['id_student'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">ID Siswa Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>
                $("#TabelRincianTagihanSiswa").html("");
                $("#button_tambah_tagihan_siswa").html("");
            </script>
        ';
        exit;
    }

    //Validasi id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">ID Kelas Siswa Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>
                $("#TabelRincianTagihanSiswa").html("");
                $("#button_tambah_tagihan_siswa").html("");
            </script>
        ';
        exit;
    }

    //id_student
    $id_student             = $_POST['id_student'];
    $id_organization_class  = $_POST['id_organization_class'];

    //Buka Nama Siswa
    $student_name           = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_nis            = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_gender         = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');

    //Routing label gender
    if($student_gender=="Male"){
        $label_gender="Laki-laki";
    }else{
        if($student_gender=="Female"){
            $label_gender="Perempuan";
        }else{
            $label_gender="-";
        }
    }

    //Buka 'id_academic_period' dari tabel 'organization_class'
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka Periode Akademik
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));

    //Inisiasai Title Table
    $title_table = '
        <div class="row">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-5"><small>Nama Siswa</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$student_name.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>NIS</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$student_nis.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Gender</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$label_gender.'</small></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Jenjang/Kelas</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Kelas/Rombel</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
            </div>
        </div>
    ';
    $tombol_tambah_tagihan_siswa ='
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalTambahTagihanSiswa" data-id_student="'.$id_student.'" data-id_organization_class="'.$id_organization_class.'" title="Tambah Tagihan Siswa">
            <i class="bi bi-plus"></i> Tambah
        </button>
    ';

    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">Tidak Ada Data Tagihan Siswa Yang Ditampilkan</small>
                </td>
            </tr>
        ';
    }else{
        //Atur Nomor
        $no = 1;

        //Inisialisasi Jumlah Total
        $subtotal_tagihan           = 0;
        $subtotal_diskon            = 0;
        $subtotal_tagihan_netto     = 0;
        $subtotal_pembayaran        = 0;
        $subtotal_tunggakan         = 0;
        //Looping Query
        $query = mysqli_query($Conn, "SELECT * FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'");
        while ($data = mysqli_fetch_array($query)) {
            $id_fee_by_student  = $data['id_fee_by_student'];
            $id_fee_component   = $data['id_fee_component'];
            $fee_nominal        = $data['fee_nominal'];
            $fee_discount       = $data['fee_discount'];
            $jumlah_tagihan     = $fee_nominal-$fee_discount;

            $fee_nominal_format     = "Rp " . number_format($fee_nominal,0,',','.');
            $fee_discount_format    = "Rp " . number_format($fee_discount,0,',','.');
            $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');

            //Buka Komponen
            $component_name     = GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'component_name');
            $component_category = GetDetailData($Conn, ' fee_component', 'id_fee_component', $id_fee_component, 'component_category');

            //Hitung Jumlah Pembayaran
            $SumPembayaran              = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_by_student='$id_fee_by_student'"));
            $jumlah_pembayaran          = $SumPembayaran['jumlah_pembayaran'];
            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');

            //Menghitung Sisa Tagihan
            $jumlah_sisa_tagihan        = $jumlah_tagihan-$jumlah_pembayaran;
            $jumlah_sisa_tagihan_format = "Rp " . number_format($jumlah_sisa_tagihan,0,',','.');

            //menampilkan data pada baris tabel
            echo '
                <tr>
                    <td><small>'.$no.'</small></td>
                    <td>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetailTagihan" data-id="'.$id_fee_by_student .'">
                            <small class="underscore_doted">'.$component_name.'</small>
                        </a>
                    </td>
                    <td><small>'.$fee_nominal_format.'</small></td>
                    <td><small>'.$fee_discount_format.'</small></td>
                    <td><small>'.$jumlah_tagihan_format.'</small></td>
                    <td><small>'.$jumlah_pembayaran_format.'</small></td>
                    <td><small>'.$jumlah_sisa_tagihan_format.'</small></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                            <li class="dropdown-header text-start">
                                <h6>Option</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailTagihan" data-id="'.$id_fee_by_student .'">
                                    <i class="bi bi-info-circle"></i> Detail
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditTagihan" data-id="'.$id_fee_by_student .'">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusTagihan" data-id="'.$id_fee_by_student .'">
                                    <i class="bi bi-x"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            ';
            $no++;

            //Menghitung Subtotal
            $subtotal_tagihan           = $subtotal_tagihan + $fee_nominal;
            $subtotal_diskon            = $subtotal_diskon + $fee_discount;
            $subtotal_tagihan_netto     = $subtotal_tagihan_netto + $jumlah_tagihan;
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
                <td><b>SUBTOTAL</b></td>
                <td><b>'.$subtotal_tagihan_format.'</b></td>
                <td><b>'.$subtotal_diskon_format.'</b></td>
                <td><b>'.$subtotal_tagihan_netto_format.'</b></td>
                <td><b>'.$subtotal_pembayaran_format.'</b></td>
                <td><b>'.$subtotal_tunggakan_format.'</b></td>
                <td></td>
            </tr>
        ';
    }

    //Menampilkan title table
    echo '
        <script>
            $("#title_tagihan_siswa").html(' . json_encode($title_table) . ');
            $("#button_tambah_tagihan_siswa").html(' . json_encode($tombol_tambah_tagihan_siswa) . ');
            $("#put_id_organization_class").val("'.$id_organization_class.'");
            $("#put_id_student").val("'.$id_student.'");
        </script>
    ';


    
?>