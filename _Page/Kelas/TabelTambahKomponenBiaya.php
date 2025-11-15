<?php
    //koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");
    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="7" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>
                $("#title_komponen_biaya").html("");
            </script>
        ';
        exit;
    }

    //Validasi id_organization_class harus ada
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="7" class="text-center">
                    <small class="text-danger">Belum ada data kelas yang dipilih!</small>
                </td>
            </tr>
            <script>
                $("#title_komponen_biaya").html("");
            </script>
        ';
        exit;
    }
    //Validasi id_academic_period harus ada
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr>
                <td colspan="7" class="text-center">
                    <small class="text-danger">Periode Akademik Tidak Boleh Kosong! Silahkan pilih tahun akademik terlebih dulu!</small>
                </td>
            </tr>
            <script>
                $("#title_komponen_biaya").html("");
            </script>
        ';
        exit;
    }
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Detail Informasi Kelas
    $class_level  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Detail Periode Akademik
    $academic_period  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Validasi id_academic_periode
    if(empty($academic_period)){
        echo '
            <tr>
                <td colspan="7" class="text-center">
                    <small class="text-danger">ID Periode Akademik yang Anda Pilih ('.$id_academic_period.') Tidak Valid</small>
                </td>
            </tr>
            <script>
                $("#title_komponen_biaya").html("");
            </script>
        ';
        exit;
    }

    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));
    
    //Inisiasi Komponen untuk 'title_komponen_biaya'
    $title_komponen_biaya = '
        <div class="row">
            <div class="col-md-5">
                <div class="row mb-2">
                    <div class="col-5"><small>Tahun Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Jenjang/Level</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_level.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Kelas/Level</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$class_name.'</small></div>
                </div>
            </div>
        </div>
    ';

    //Mengatur Halaman
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="7" class="text-center">
                    <small class="text-danger">Tidak Ada Data Komponen Biiaya Pendidikan Untuk Periode <b>'.$academic_period.'</b> ini!</small>
                </td>
            </tr>
            <script>
                $("#title_komponen_biaya").html(' . json_encode($title_komponen_biaya) . ');
            </script>
        ';
        exit;
    }
    $no = 1;
    $query = mysqli_query($Conn, "SELECT*FROM fee_component WHERE id_academic_period='$id_academic_period'");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_component   = $data['id_fee_component'];
        $component_name     = $data['component_name'];
        $component_category = $data['component_category'];
        $periode_month      = $data['periode_month'];
        $periode_year       = $data['periode_year'];
        $periode_start      = $data['periode_start'];
        $periode_end        = $data['periode_end'];
        $fee_nominal        = $data['fee_nominal'];

        //Nama Bulan
        $nama_bulan=getNamaBulan($periode_month);
        
        //Format Rupiah
        $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');

        //Cek Apakah Komponen Biaya Sudah Ada
        $cek_komponen_biaya= mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_fee_component='$id_fee_component' AND id_organization_class='$id_organization_class'"));

        //Routing Tombol
        if(empty($cek_komponen_biaya)){
            $tombol='<button type="button" class="btn btn-sm btn-primary btn-floating tambah_komponen" data-id_1="'.$id_fee_component .'" data-id_2="'.$id_organization_class .'"><i class="bi bi-plus"></i></button>';
        }else{
            $tombol='<button type="button" class="btn btn-sm btn-danger btn-floating hapus_komponen" data-id_1="'.$id_fee_component .'" data-id_2="'.$id_organization_class .'"><i class="bi bi-trash"></i></button>';
        }

        //Routing Kategori
        if($component_category=="SPP"){
            $label_spp='<span class="badge bg-primary">SPP</span>';
        }else{
            $label_spp='<span class="badge bg-success">Non-SPP</span>';
        }
        //Tampilkan Data
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$label_spp.'</small></td>
                <td><small>'.$nama_bulan.'</small></td>
                <td><small>'.$periode_year.'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
                <td><small>'.$tombol.'</small></td>
            </tr>
        ';
        $no++;
    }
    echo '
        <script>
            $("#title_komponen_biaya").html(' . json_encode($title_komponen_biaya) . ');
        </script>
    ';
?>