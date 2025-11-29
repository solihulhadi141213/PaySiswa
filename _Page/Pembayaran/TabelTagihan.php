<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    //Tangkap 'id_student'
    if(empty($_POST['id_student'])){
        echo '
            <tr>
                <td colspan="11" align="center">
                    <small>ID Siswa Tidak Boleh Kosong!</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Tangkap 'id_organization_class'
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="11" align="center">
                    <small>ID Kelas Tidak Boleh Kosong!</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Buat Variabel
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka Informasi Siswa dan Kelas
    $student_nis        = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name       = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_gender     = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level        = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name         = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $academic_period    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Routing Gender
    if($student_gender=="Male"){
        $jenis_kelamin = "Laki-Laki";
    }else{
        if($student_gender=="Female"){
            $jenis_kelamin = "Perempuan";
        }else{
            $jenis_kelamin = "-";
        }
    }
    //Buatkan Konten Title
    $title_tagihan      = '
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Nama Siswa</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$student_name.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>NIS</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$student_nis.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Jenis Kelamin</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$jenis_kelamin.'</small></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-4"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Jenjang/Level</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$class_level.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4"><small>Kelas/Rombel</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
            </div>
        </div>
    ';

    //Menghitung Jumlah Data Tagihan
    $JumlahTagihan = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class'"));
    
    //Jika Tidak Ada Data Tagihan
    if(empty($JumlahTagihan)){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small>Tidak Ada Data Komponen Tagihan</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Inisiasi Jumlah/Total
    $subtotal_tarif     = 0;
    $subtotal_diskon    = 0;
    $subtotal_diskon    = 0;
    //Tampilkan Data Tagihan dari tabel 'fee_by_student'
    $no = 1;
    $query = mysqli_query($Conn, "SELECT*FROM fee_by_student WHERE id_student='$id_student' AND id_organization_class='$id_organization_class' ORDER BY id_fee_by_student ASC");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_by_student          = $data['id_fee_by_student'];
        $id_organization_class      = $data['id_organization_class'];
        $id_fee_component           = $data['id_fee_component'];
        $fee_nominal                = $data['fee_nominal'];
        $fee_discount               = $data['fee_discount'];
        $fee_tagihan                = $fee_nominal-$fee_discount;
        
        //Buka Data Dari Tabel 'fee_component'
        $component_name         = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
        $component_category     = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');
        $periode_month          = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');
        $periode_year           = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');
        $nama_bulan             = getNamaBulan($periode_month);

        //Format Rupiah
        $fee_nominal_format     ="" . number_format($fee_nominal,0,',','.');
        $fee_discount_format    ="" . number_format($fee_discount,0,',','.');
        $fee_tagihan_format     ="" . number_format($fee_tagihan,0,',','.');

        //Hitung Pembayaran Yang Sudah Masuk
        $JumlahPembayaranMasuk = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(payment_nominal) AS jumlah FROM payment WHERE id_student='$id_student' AND id_fee_component='$id_fee_component'"));
        $JumlahPembayaranMasuk = $JumlahPembayaranMasuk['jumlah'];

        //Format Rupiah
        $JumlahPembayaranMasukFormat="" . number_format($JumlahPembayaranMasuk,0,',','.');

        //Hitung sisa pembayaran
        $sisa_pembayaran=$fee_tagihan-$JumlahPembayaranMasuk;

        //Format Rupiah
        $sisa_pembayaran_format="" . number_format($sisa_pembayaran,0,',','.');

        //Routing Tombol Berdasarkan Sisa Pembayaran
        if($sisa_pembayaran>0){
            $tomol_bayar='<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ModalPembayaran" data-id_fee_component="'.$id_fee_component .'" data-id_student="'.$id_student .'">Bayar</button>';
        }else{
            $tomol_bayar='<button type="button" disabled class="btn btn-sm btn-success">Lunas</button>';
        }
        if(empty($sisa_pembayaran)){
            $color_text="text-success";
            $tombol_bayar = '
                <button type="button" class="btn btn-sm btn-outline-success btn-floating modal_detail_tagihan" data-id="'.$id_fee_by_student .'" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Lihat Detail Tagihan">
                    <i class="bi bi-arrow-up-right"></i>
                </button>
            ';
        }else{
            if(empty($JumlahPembayaranMasuk)){
                $color_text="text-grayish";
                $tombol_bayar = '
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating modal_bayar" data-id="'.$id_fee_by_student .'" title="Bayar Tagihan Ini">
                        <i class="bi bi-plus"></i>
                    </button>
                ';
            }else{
                $color_text="text-dark";
                $tombol_bayar = '
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating modal_bayar" data-id="'.$id_fee_by_student .'" title="Bayar Tagihan Ini">
                        <i class="bi bi-plus"></i>
                    </button>
                ';
            }
        }
        echo '
            <tr>
                <td><small class="text '.$color_text.'">'.$no.'</small></td>
                <td><small class="text '.$color_text.'">'.$component_name.'</small></td>
                <td><small class="text '.$color_text.'">'.$component_category.'</small></td>
                <td><small class="text '.$color_text.'">'.$nama_bulan.'</small></td>
                <td><small class="text '.$color_text.'">'.$periode_year.'</small></td>
                <td align="right"><small class="text '.$color_text.'">'.$fee_nominal_format.'</small></td>
                <td align="right"><small class="text '.$color_text.'">'.$fee_discount_format.'</small></td>
                <td align="right"><small class="text '.$color_text.'">'.$fee_tagihan_format.'</small></td>
                <td align="right"><small class="text '.$color_text.'">'.$JumlahPembayaranMasukFormat.'</small></td>
                <td align="right"><small class="text '.$color_text.'">'.$sisa_pembayaran_format.'</small></td>
                <td>'.$tombol_bayar.'</td>
            </tr>
        ';
        $no++;
    }
    echo '
        <script>
            $("#title_tagihan").html(' . json_encode($title_tagihan) . ');
        </script>
    ';
?>