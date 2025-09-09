<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $JmlHalaman=0;
    $page=0;
    //Validasi Akses
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }
    //id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">Silahkan pilih <b>Periode Akademik</b> terlebih dulu untuk mulai menampilkan biaya pendidikan</small>
                </td>
            </tr>
        ';
        exit;
    }
    $id_academic_period=$_POST['id_academic_period'];
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));

    //Jika Tidak Ada Data
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">Belum ada data <b>Biaya Pendidikan</b> untuk <b>Periode Akademik</b> ini</small>
                </td>
            </tr>
        ';
        exit;
    }
    $no = 1;
    //Menampilkan Data
    $query = mysqli_query($Conn, "SELECT*FROM fee_component WHERE id_academic_period='$id_academic_period' ORDER BY component_category DESC, periode_year ASC, periode_month ASC");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_component   = $data['id_fee_component'];
        $component_name     = $data['component_name'];
        $component_category = $data['component_category'];
        $periode_month      = $data['periode_month'];
        $periode_year       = $data['periode_year'];
        $periode_start      = $data['periode_start'];
        $periode_end        = $data['periode_end'];
        $fee_nominal        = $data['fee_nominal'];
        
        //Format Rupiah
        $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');

        //Nama Bulan 
        $nama_bulan=getNamaBulan($periode_month);

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
                <td>
                    <a href="javascript:void(0);" class="text text-decoration-underline" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_fee_component .'">
                        <small>'.$component_name.'</small>
                    </a>
                </td>
                <td><small>'.$label_spp.'</small></td>
                <td><small>'.$nama_bulan.'</small></td>
                <td><small>'.$periode_year.'</small></td>
                <td>
                    <small>'.date('d/m/Y', strtotime($periode_start)).' - '.date('d/m/Y', strtotime($periode_end)).'</small>
                </td>
                <td>
                    <small>'.$fee_nominal_format.'</small>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                        <li class="dropdown-header text-start">
                            <h6>Option</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetail" data-id="'.$id_fee_component .'">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_fee_component .'">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="'.$id_fee_component .'">
                                <i class="bi bi-x"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        $no++;
    }
?>
<script>
    //Creat Javascript Variabel
    var jumlah_data=<?php echo $jml_data; ?>;
    
    //Put Into Pagging Element
    $('#page_info').html(''+jumlah_data+' Record');
    
</script>