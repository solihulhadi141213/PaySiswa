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
                <td colspan="12" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>$("#id_academic_period_terpilih").html("None"); $("#id_academic_period").val("");</script>
        ';
        exit;
    }
    //Data 'id_academic_period' tidak boleh kosong
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr>
                <td colspan="12" class="text-center">
                    <small class="text-danger">Silahkan pilih <b>Periode Akademik</b> terlebih dulu untuk mulai menampilkan biaya pendidikan</small>
                </td>
            </tr>
            <script>$("#id_academic_period_terpilih").html("None"); $("#id_academic_period").val("");</script>
        ';
        exit;
    }

    //Buat Variabel 'id_academic_period'
    $id_academic_period=$_POST['id_academic_period'];

    //Buka Nama Periode
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$id_academic_period'"));

    //Jika Tidak Ada Data
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="12" class="text-center">
                    <small class="text-danger">Belum ada data <b>Biaya Pendidikan</b> untuk <b>Periode Akademik</b> ini</small>
                </td>
            </tr>
            <script>$("#id_academic_period_terpilih").html("None"); $("#id_academic_period").val("");</script>
        ';
        exit;
    }
    $no = 1;
    //Menampilkan Data
    $total_biaya_pendidikan = 0;
    $total_tagihan = 0;
    $total_pembayaran = 0;
    $total_tunggakan = 0;
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
            $label_spp='
                <a href="javascript:void(0);" class="click_edit_parsial" data-id="'.$id_fee_component.'" data-form_name="kategori" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X Untuk Mengubah Kategori">
                    <span class="badge bg-primary">SPP</span>
                </a>
            ';
        }else{
            $label_spp='
                <a href="javascript:void(0);" class="click_edit_parsial" data-id="'.$id_fee_component.'" data-form_name="kategori" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X Untuk Mengubah Kategori">
                    <span class="badge bg-success">Non-SPP</span>
                </a>
            ';
        }

        //Menghitung Jumlah Nominal Tagihan
        $SumTagihan                 = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS jumlah_tagihan FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
        if(!empty($SumTagihan['jumlah_tagihan'])){
            $jumlah_rp_tagihan = $SumTagihan['jumlah_tagihan'];
        }else{
           $jumlah_rp_tagihan = 0;
        }
        $jumlah_rp_tagihan_format   = "Rp " . number_format($jumlah_rp_tagihan,0,',','.');
        
        if(empty($jumlah_rp_tagihan)){
            $label_jumlah_rp_tagihan = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_jumlah_rp_tagihan = '<span class="text text-dark">'.$jumlah_rp_tagihan_format.'</span>';
        }

        //Menghitung Jumlah Pembayaran
        $SumPembayaran                  = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS jumlah_pembayaran FROM payment WHERE id_fee_component='$id_fee_component'"));
        if(!empty($SumPembayaran['jumlah_pembayaran'])){
            $jumlah_pembayaran = $SumPembayaran['jumlah_pembayaran'];
        }else{
            $jumlah_pembayaran = 0;
        }
        $jumlah_pembayaran_format       = "Rp " . number_format($jumlah_pembayaran,0,',','.');
        
        if(empty($jumlah_pembayaran)){
            $label_jumlah_rp_pembayaran = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_jumlah_rp_pembayaran = '<span class="text text-dark">'.$jumlah_pembayaran_format.'</span>';
        }

        $sisa_tagihan = $jumlah_rp_tagihan - $jumlah_pembayaran;
        $sisa_tagihan_format       = "Rp " . number_format($sisa_tagihan,0,',','.');
        if(empty($sisa_tagihan)){
            $label_sisa_tagihan = '<span class="text text-grayish">Rp 0</span>';
        }else{
            $label_sisa_tagihan = '<span class="text text-dark">'.$sisa_tagihan_format.'</span>';
        }

        //Akumulasi
        $total_biaya_pendidikan = $total_biaya_pendidikan + $fee_nominal;
        $total_tagihan          = $total_tagihan + $jumlah_rp_tagihan;
        $total_pembayaran       = $total_pembayaran + $jumlah_pembayaran;
        $total_tunggakan        = $total_tunggakan + $sisa_tagihan;

        //Tampilkan Data
        echo '
            <tr>
                <td>
                    <input type="checkbox" name="id_fee_component[]" class="form-check-input" value="'.$id_fee_component .'">
                </td>
                <td><small>'.$no.'</small></td>
                <td>
                    <a href="javascript:void(0);" class="text-dark click_edit_parsial" data-id="'.$id_fee_component .'" data-form_name="nama" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X untuk mengubah nama komponen biaya">
                        <small>'.$component_name.'</small>
                    </a>
                </td>
                <td><small>'.$label_spp.'</small></td>
                <td>
                    <a href="javascript:void(0);" class="text-dark click_edit_parsial" data-id="'.$id_fee_component .'" data-form_name="bulan" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X untuk mengubah periode bulan">
                        <small>'.$nama_bulan.'</small>
                    </a>
                </td>
                <td>
                    <a href="javascript:void(0);" class="text-dark click_edit_parsial" data-id="'.$id_fee_component .'" data-form_name="tahun" data-form_name="tahun" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X untuk mengubah periode tahun">
                        <small>'.$periode_year.'</small>
                    </a>
                </td>
                <td>
                    <a href="javascript:void(0);" class="text-dark click_edit_parsial" data-id="'.$id_fee_component .'" data-form_name="tempo_awal" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X untuk mengubah periode tahun">
                         <small>'.date('d/m/y', strtotime($periode_start)).' - </small>
                    </a>
                    <a href="javascript:void(0);" class="text-dark click_edit_parsial" data-id="'.$id_fee_component .'" data-form_name="tempo_akhir" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X untuk mengubah periode tahun">
                         <small>'.date('d/m/y', strtotime($periode_end)).'</small>
                    </a>
                </td>
                <td class="text-end">
                    <a href="javascript:void(0);" class="text-dark click_edit_parsial" data-id="'.$id_fee_component .'" data-form_name="nominal" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Click 2X untuk mengubah tarif/biaya pendidikan">
                        <small>'.$fee_nominal_format.'</small>
                    </a>
                </td>
                <td class="text-end">
                    <a href="javascript:void(0);" class="underscore_doted modal_rekap_tagihan" data-id="'.$id_fee_component .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat rekapitulasi tagihan">
                        <small>'.$label_jumlah_rp_tagihan.'</small>
                    </a>
                </td>
                <td class="text-end">
                    <a href="javascript:void(0);" class="underscore_doted modal_rekap_tagihan" data-id="'.$id_fee_component .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat rekapitulasi tagihan">
                        <small>'.$label_jumlah_rp_pembayaran.'</small>
                    </a>
                </td>
                <td class="text-end">
                    <a href="javascript:void(0);" class="underscore_doted modal_rekap_tagihan" data-id="'.$id_fee_component .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat rekapitulasi tagihan">
                        <small>'.$label_sisa_tagihan.'</small>
                    </a>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow bg-body-secondary" style="">
                        <li class="dropdown-header text-start">
                            <h6>Option</h6>
                        </li>
                        <li><hr class="dropdown-divider border-1 border-bottom"></li>
                        <li>
                            <a class="dropdown-item detail_komponen_biaya_pendidikan" href="javascript:void(0)" data-id="'.$id_fee_component .'">
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
                        <li><hr class="dropdown-divider border-1 border-bottom"></li>
                        <li>
                            <a class="dropdown-item modal_rekap_tagihan" href="javascript:void(0)" data-id="'.$id_fee_component .'">
                                <i class="bi bi-table"></i> Rekapitulasi Tagihan
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        $no++;
    }
    
    //Format Akumulasi
    $total_biaya_pendidikan_format  = "Rp " . number_format($total_biaya_pendidikan,0,',','.');
    $total_tagihan_format           = "Rp " . number_format($total_tagihan,0,',','.');
    $total_pembayaran_format        = "Rp " . number_format($total_pembayaran,0,',','.');
    $total_tunggakan_format         = "Rp " . number_format($total_tunggakan,0,',','.');

    //Tampilkan Akumulasi
    echo '
        <tr>
            <td></td>
            <td></td>
            <td><small><b>JUMLAH/TOTAL</b></small></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-end"><small><b>'.$total_biaya_pendidikan_format.'</b></small></td>
            <td class="text-end"><small><b>'.$total_tagihan_format.'</b></small></td>
            <td class="text-end"><small><b>'.$total_pembayaran_format.'</b></small></td>
            <td class="text-end"><small><b>'.$total_tunggakan_format.'</b></small></td>
            <td></td>
        </tr>
    ';
    echo '<script>$("#id_academic_period_terpilih").html("'.$academic_period.'"); $("#id_academic_period").val("'.$academic_period.'");</script>';
?>
<script>
    //Creat Javascript Variabel
    var jumlah_data=<?php echo $jml_data; ?>;
    
    //Put Into Pagging Element
    $('#page_info').html(''+jumlah_data+' Record');
    
</script>