<?php
    //koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Inisiasi Variabel Jumlah Halaman Dan Page
    $JmlHalaman=0;
    $page=0;

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
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);

    //keyword_by_komponen
    if(!empty($_POST['keyword_by_komponen'])){
        $keyword_by=$_POST['keyword_by_komponen'];
    }else{
        $keyword_by="";
    }
    //keyword_komponen
    if(!empty($_POST['keyword_komponen'])){
        $keyword=$_POST['keyword_komponen'];
    }else{
        $keyword="";
    }
    //batas
    if(!empty($_POST['batas_komponen'])){
        $batas=$_POST['batas_komponen'];
    }else{
        $batas="10";
    }
    //ShortBy
    if(!empty($_POST['ShortBy'])){
        $ShortBy=$_POST['ShortBy'];
    }else{
        $ShortBy="DESC";
    }
    //OrderBy
    if(!empty($_POST['OrderBy'])){
        $OrderBy=$_POST['OrderBy'];
    }else{
        $OrderBy="id_fee_component ";
    }
    //Atur Page
    if(!empty($_POST['page_komponen'])){
        $page=$_POST['page_komponen'];
        $posisi = ( $page - 1 ) * $batas;
    }else{
        $page="1";
        $posisi = 0;
    }
    if(empty($keyword_by)){
        if(empty($keyword)){
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component  FROM fee_component "));
        }else{
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component  FROM fee_component  WHERE component_name like '%$keyword%' OR component_category like '%$keyword%' OR periode_start like '%$keyword%' OR periode_end like '%$keyword%' OR fee_nominal like '%$keyword%'"));
        }
    }else{
        if(empty($keyword)){
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component  FROM fee_component"));
        }else{
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component  FROM fee_component WHERE $keyword_by like '%$keyword%'"));
        }
    }
    //Mengatur Halaman
    $JmlHalaman = ceil($jml_data/$batas); 
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="6" class="text-center">
                    <small class="text-danger">Tidak Ada Data Yang Ditampilkan!</small>
                </td>
            </tr>
        ';
    }else{
        $no = 1+$posisi;
        //KONDISI PENGATURAN MASING FILTER
        if(empty($keyword_by)){
            if(empty($keyword)){
                $query = mysqli_query($Conn, "SELECT*FROM fee_component  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
            }else{
                $query = mysqli_query($Conn, "SELECT*FROM fee_component WHERE component_name like '%$keyword%' OR component_category like '%$keyword%' OR periode_start like '%$keyword%' OR periode_end like '%$keyword%' OR fee_nominal like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
            }
        }else{
            if(empty($keyword)){
                $query = mysqli_query($Conn, "SELECT*FROM fee_component  ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
            }else{
                $query = mysqli_query($Conn, "SELECT*FROM fee_component  WHERE $keyword_by like '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
            }
        }
        while ($data = mysqli_fetch_array($query)) {
            $id_fee_component   = $data['id_fee_component'];
            $component_name     = $data['component_name'];
            $component_category = $data['component_category'];
            $periode_start      = $data['periode_start'];
            $periode_end        = $data['periode_end'];
            $fee_nominal        = $data['fee_nominal'];
            
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
            //Tampilkan Data
            echo '
                <tr>
                    <td><small>'.$no.'</small></td>
                    <td><small>'.$component_name.'</small></td>
                    <td>
                        <small>'.$component_category.'</small>
                    </td>
                    <td>
                        <small>'.date('d/m/Y', strtotime($periode_start)).' - '.date('d/m/Y', strtotime($periode_end)).'</small>
                    </td>
                    <td>
                        <small>'.$fee_nominal_format.'</small>
                    </td>
                    <td><small>'.$tombol.'</small></td>
                </tr>
            ';
            $no++;
        }
    }
?>
<script>
    //Creat Javascript Variabel
    var page_count=<?php echo $JmlHalaman; ?>;
    var curent_page=<?php echo $page; ?>;
    
    //Put Into Pagging Element
    $('#page_info_komponen').html('Page '+curent_page+' Of '+page_count+'');
    
    //Set Pagging Button
    if(curent_page==1){
        $('#prev_button_komponen').prop('disabled', true);
    }else{
        $('#prev_button_komponen').prop('disabled', false);
    }
    if(page_count<=curent_page){
        $('#next_button_komponen').prop('disabled', true);
    }else{
        $('#next_button_komponen').prop('disabled', false);
    }
</script>