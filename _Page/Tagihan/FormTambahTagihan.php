<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Datetime Sekarang
    $now=date('Y-m-d H:i:s');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_student
    if(empty($_POST['id_student'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Siswa Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Validasi id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Periode Akademik Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    //Buat Variabel Dan Sanitasi
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka detail siswa
    $student_nis=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    $student_name=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
    $student_gender=GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');

    //Buka Detail Kelas
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Buka Detail Periode Akademik
    $academic_period = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Tampilkan Detail Siswa Dan Kelas
    echo '
        <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
        <input type="hidden" name="id_student" value="'.$id_student.'">
        <div class="row mb-2">
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
                    <div class="col-6"><small class="text text-grayish">'.$student_gender.'</small></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Jenjang/Level</small></div>
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

    //Menampilkan Komponen Biaya Pendidikan
    echo '<div class="row mb-2">';
    echo '  <div class="col-md-12">';
    echo '     <div class="table table-responsive border-1 border-top">';
    echo '          <table class="table table-hover table-striped">';
    echo '
                        <thead>
                            <tr>
                                <td><input class="form-check-input" type="checkbox" name="pilih_semua_komponen" value="ya"></td>
                                <td><b><small>Komponen Biaya</small></b></td>
                                <td><b><small>Kategori</small></b></td>
                                <td><b><small>Bulan</small></b></td>
                                <td><b><small>Tahun</small></b></td>
                                <td><b><small>Nominal</small></b></td>
                                <td><b><small>Diskon</small></b></td>
                            </tr>
                        </thead>
    ';
    echo '              <tbody>';
    //Menghitung Jumlah Data
    $jumlah_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));

    //Kondisi Jika Kosong
    if(empty($jumlah_data)){
        echo '
            <tr>
                <td class="text-center" colspan="7">
                    <small class="text-danger">Tidak Ada Komponen Biaya Untuk Periode Akademik Ini</small>
                </td>
            </tr>
        ';
    }else{
        $query_komponen = mysqli_query($Conn, "SELECT * FROM fee_component WHERE id_academic_period='$id_academic_period' ORDER BY component_category DESC, periode_year ASC, periode_month ASC");
        while ($data_komponen = mysqli_fetch_array($query_komponen)) {
            $id_fee_component   = $data_komponen['id_fee_component'];
            $component_name     = $data_komponen['component_name'];
            $component_category = $data_komponen['component_category'];
            $periode_month      = $data_komponen['periode_month'];
            $periode_year       = $data_komponen['periode_year'];
            $fee_nominal        = $data_komponen['fee_nominal'];
            
            //Format Rupiah
            $fee_nominal_format = "Rp " . number_format($fee_nominal,0,',','.');

            //Nama Bulan 
            $nama_bulan         = getNamaBulan($periode_month);
            echo '
                                <tr>
                                    <td><input class="form-check-input" type="checkbox" name="id_fee_component[]" value="'.$id_fee_component.'"></td>
                                    <td><small>'.$component_name.'</small></td>
                                    <td><small>'.$component_category.'</small></td>
                                    <td><small>'.$nama_bulan.'</small></td>
                                    <td><small>'.$periode_year.'</small></td>
                                    <td><input type="text" disabled name="fee_nominal['.$id_fee_component.']" class="form-control form-money" value="'.$fee_nominal.'"></td>
                                    <td><input type="text" disabled name="fee_discount['.$id_fee_component.']" class="form-control form-money" value="0"></td>
                                </tr>
            ';
        }
        echo '              </tbody>';
        echo '          </table>';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';

        echo '
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <small>
                            <b>Penting!</b> Jika data tagihan sudah ada sebelumnya (siswa dan komponen sama) maka sistem akan melakukan update berdasarkan data terbaru.
                        </small>
                    </div>
                </div>
            </div>
        ';
    }
?>