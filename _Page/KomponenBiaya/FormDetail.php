<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }
    //Tangkap id_organization_class
    if(empty($_POST['id_fee_component'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Komponent Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_fee_component=validateAndSanitizeInput($_POST['id_fee_component']);

    //Buka Data fee_component
    $Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
    $Qry->bind_param("i", $id_fee_component);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
    }else{
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $id_fee_component   = $Data['id_fee_component'];
        $periode_month      = $Data['periode_month'];
        $periode_year      = $Data['periode_year'];
        $component_name     = $Data['component_name'] ?? '-';
        $component_category = $Data['component_category'] ?? '-';
        $periode_start      = $Data['periode_start'] ?? '-';
        $periode_end        = $Data['periode_end'] ?? '-';
        $fee_nominal        = $Data['fee_nominal'] ?? '-';

        //Nama Bulan 
        $nama_bulan=getNamaBulan($periode_month);
        
        //Format Rupiah
        $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');

        //Menghitung jumlah tagihan
        $jumlah_record_tagihan = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_student FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
        $jumlah_record_tagihan_format= "" . number_format($jumlah_record_tagihan,0,',','.');

        //Menghitung Jumlah Nominal Tagihan
        $SumTagihan                 = mysqli_fetch_array(mysqli_query($Conn,"SELECT SUM(fee_nominal-fee_discount) AS jumlah_tagihan FROM fee_by_student WHERE id_fee_component='$id_fee_component'"));
        if(!empty($SumTagihan['jumlah_tagihan'])){
            $jumlah_rp_tagihan = $SumTagihan['jumlah_tagihan'];
        }else{
            $jumlah_rp_tagihan = 0;
        }
        $jumlah_rp_tagihan_format   = "Rp " . number_format($jumlah_rp_tagihan,0,',','.');

        //Tampilkan Data
        echo '
            <div class="row mb-2">
                <div class="col-4"><small>Nama Komponen</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$component_name.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kategori</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$component_category.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Periode Bulan</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$nama_bulan.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Periode Tahun</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$periode_year.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Awal Berlaku</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.date('d/m/Y', strtotime($periode_start)).'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Akhir Berlaku</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.date('d/m/Y', strtotime($periode_end)).'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Tarif Komponen</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$fee_nominal_format.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Record Tagihan</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$jumlah_record_tagihan_format.' Record</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Rp Tagihan</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$jumlah_rp_tagihan_format.'</small>
                </div>
            </div>
        ';
    }
?>