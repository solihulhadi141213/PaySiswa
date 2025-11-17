<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi Session Akses
    if (empty($SessionIdAccess)) {
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>$("#title_rekap_tagihan").html("");</script>
        ';
        exit;
    }

    //Validasi 'id_fee_component'
    if(empty($_POST['id_fee_component'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">ID Komponen Biaya Pendidikan Tidak Boleh Kosong!</small>
                </td>
            </tr>
            <script>$("#title_rekap_tagihan").html("");</script>
        ';
        exit;
    }

    //Buat Variabel dan sanitasi
    $id_fee_component=validateAndSanitizeInput($_POST['id_fee_component']);

    //Buka fee_component
    //Buka Data fee_component
    $Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
    $Qry->bind_param("i", $id_fee_component);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
                </td>
            </tr>
            <script>$("#title_rekap_tagihan").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Jika Data Tidak Ditemukan
    if(empty($Data['id_fee_component'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">ID Komponen Biaya Pendidikan Tidak Valid</small>
                </td>
            </tr>
            <script>$("#title_rekap_tagihan").html("");</script>
        ';
        exit;
    }

    //Buat Variabel
    $id_academic_period     = $Data['id_academic_period'];
    $periode_month          = $Data['periode_month'];
    $periode_year           = $Data['periode_year'];
    $component_name         = $Data['component_name'];
    $component_category     = $Data['component_category'];
    
    //Nama Bulan 
    $nama_bulan=getNamaBulan($periode_month);

    //Buka Informasi Periode Akademik
    $academic_period        = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    $academic_period_start  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_start');
    $academic_period_end    = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period_end');

    //Buat title
    $title_rekap_tagihan = '
        <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">
        <div class="row">
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-5"><small>Komponen Biaya</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_name.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Periode Akademik</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-5"><small>Kategori</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$component_category.'</small></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><small>Bulan, Tahun</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-6"><small class="text text-grayish">'.$nama_bulan.' '.$periode_year.'</small></div>
                </div>
            </div>
        </div>
    ';
    //Inisiasi Akumulasi
    $total_tagihan          = 0;
    $total_diskon           = 0;
    $total_tagihan_bersih   = 0;
    $total_pembayaran       = 0;
    $total_sisa             = 0;
    //Menampilkan data kelas
    $no_level=1;
    $jumlah_level=0;
    $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
    while ($data_level = mysqli_fetch_array($query_level)) {
        $class_level = $data_level['class_level'];

        //Hitung Jumlah Level
        $jumlah_level=$jumlah_level+1;
        echo '
            <tr>
                <td align="center" class="bg bg-body-secondary"><b>'.$no_level.'</b></td>
                <td colspan="8" class="bg bg-body-secondary"><b>'.$class_level.'</b></td>
            </tr>
        ';
        //Menampilkan List Kelas
        $no_kelas=1;
        $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
        while ($data_kelas = mysqli_fetch_array($query_kelas)) {
            $id_organization_class = $data_kelas['id_organization_class'];
            $class_name = $data_kelas['class_name'];

            //menghitung jumlah tagihan
            $SumNominalTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal) AS nominal_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
            $jumlah_nominal_tagihan = $SumNominalTagihan['nominal_tagihan'];
            $jumlah_nominal_tagihan_format  = "Rp " . number_format($jumlah_nominal_tagihan,0,',','.');
            if(empty($jumlah_nominal_tagihan)){
                $label_nominal = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_nominal = '<span class="text text-dark">'.$jumlah_nominal_tagihan_format.'</span>';
            }

            //Hitung Jumlah Diskon
            $SumDiskon = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_discount) AS jumlah_diskon FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
            $jumlah_diskon = $SumDiskon['jumlah_diskon'];
            $jumlah_diskon_format  = "Rp " . number_format($jumlah_diskon,0,',','.');
            if(empty($jumlah_diskon)){
                $label_diskon = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_diskon = '<span class="text text-dark">'.$jumlah_diskon_format.'</span>';
            }

            //Hitung Jumlah Tagihan
            $SumTagihan = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal - fee_discount) AS total_tagihan FROM fee_by_student WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
            $jumlah_tagihan = $SumTagihan['total_tagihan'];
            $jumlah_tagihan_format  = "Rp " . number_format($jumlah_tagihan,0,',','.');
            if(empty($jumlah_tagihan)){
                $label_tagihan = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_tagihan = '<span class="text text-dark">'.$jumlah_tagihan_format.'</span>';
            }

            //Hitung Jumlah Pembayaran
            $SumPembayaran = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(payment_nominal) AS payment_nominal FROM payment WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
            $jumlah_pembayaran = $SumPembayaran['payment_nominal'];
            $jumlah_pembayaran_format   = "Rp " . number_format($jumlah_pembayaran,0,',','.');
            if(empty($jumlah_pembayaran)){
                $label_pembayaran = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_pembayaran = '<span class="text text-dark">'.$jumlah_pembayaran_format.'</span>';
            }

            //Menghitung Sisa Tagihan
            $sisa_tagihan = $jumlah_tagihan-$jumlah_pembayaran;
            $sisa_tagihan_format   = "Rp " . number_format($sisa_tagihan,0,',','.');
            if(empty($sisa_tagihan)){
                $label_sisa = '<span class="text text-grayish">Rp 0</span>';
            }else{
                $label_sisa = '<span class="text text-dark">'.$sisa_tagihan_format.'</span>';
            }

            //Hitung akumulasi
            $total_tagihan          = $total_tagihan + $jumlah_nominal_tagihan;
            $total_diskon           = $total_diskon + $jumlah_diskon;
            $total_tagihan_bersih   = $total_tagihan_bersih + $jumlah_tagihan;
            $total_pembayaran       = $total_pembayaran + $jumlah_pembayaran;
            $total_sisa             = $total_sisa + $sisa_tagihan;

            echo '
                <tr>
                    <td align="left"></td>
                    <td align="right"><small>'.$no_level.'.'.$no_kelas.'</small></td>
                    <td><small>'.$class_name.'</small></td>
                    <td class="text-end"><small>'.$label_nominal.'</small></td>
                    <td class="text-end"><small>'.$label_diskon.'</small></td>
                    <td class="text-end"><small>'.$label_tagihan.'</small></td>
                    <td class="text-end"><small>'.$label_pembayaran.'</small></td>
                    <td class="text-end"><small>'.$label_sisa.'</small></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-secondary btn-floating modal_tagihan_siswa" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat Rincian Tagihan Per Siswa" data-id_fee_component="'.$id_fee_component.'" data-id_organization_class="'.$id_organization_class.'">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </td>
                </tr>
            ';

            $no_kelas++;
        }
        $no_level++;
    }

    //Format akumulasi
    $total_tagihan_format          = "Rp " . number_format($total_tagihan,0,',','.');
    $total_diskon_format           = "Rp " . number_format($total_diskon,0,',','.');
    $total_tagihan_bersih_format   = "Rp " . number_format($total_tagihan_bersih,0,',','.');
    $total_pembayaran_format       = "Rp " . number_format($total_pembayaran,0,',','.');
    $total_sisa_format             = "Rp " . number_format($total_sisa,0,',','.');

    //Tampilkan Akumulasi
    echo '
        <tr>
            <td colspan="3"><small><b>JUMLAH/TOTAL</b></small></td>
            <td class="text-end"><small><b>'.$total_tagihan_format.'</b></small></td>
            <td class="text-end"><small><b>'.$total_diskon_format.'</b></small></td>
            <td class="text-end"><small><b>'.$total_tagihan_bersih_format.'</b></small></td>
            <td class="text-end"><small><b>'.$total_pembayaran_format.'</b></small></td>
            <td class="text-end"><small><b>'.$total_sisa_format.'</b></small></td>
            <td class="text-end"></td>
        </tr>
    ';

    //Menampilkan title
    echo '
        <script>
            $("#title_rekap_tagihan").html(' . json_encode($title_rekap_tagihan) . ');
        </script>
    ';
?>