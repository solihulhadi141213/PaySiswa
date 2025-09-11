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
                <td colspan="6" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Validasi id_organization_class harus ada
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr>
                <td colspan="6" class="text-center">
                    <small class="text-danger">Belum ada data kelas yang dipilih!</small>
                </td>
            </tr>
        ';
        exit;
    }
    //Validasi id_academic_period harus ada
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr>
                <td colspan="6" class="text-center">
                    <small class="text-danger">Periode Akademik Tidak Boleh Kosong! Silahkan pilih tahun akademik terlebih dulu!</small>
                </td>
            </tr>
        ';
        exit;
    }
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_academic_period  = validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Detail Informasi Kelas
    $class_level  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');

    //Detail Periode Akademik
    $academic_period  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component  FROM fee_component WHERE id_academic_period='$id_academic_period'"));
    
    //Mengatur Halaman
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="6" class="text-center">
                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                </td>
            </tr>
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
?>

<script>
    var academic_period = "<?php echo $academic_period; ?>";
    var class_level     = "<?php echo $class_level; ?>";
    var class_name      = "<?php echo $class_name; ?>";
    $('#title_komponen_biaya').html(`<b>Daftar Biaya Pendidikan ${class_level} (${class_name}) <br>Periode ${academic_period}</b><br><small>Silahkan Tambahkan Komponen Biaya Pendidikan Pada Kelas Tersebut</small>`);
</script>