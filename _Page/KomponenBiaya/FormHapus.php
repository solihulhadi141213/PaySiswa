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

    //Buka Data sISWA
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
        $id_fee_component   =$Data['id_fee_component'];
        $component_name     =$Data['component_name'] ?? '-';
        $component_category =$Data['component_category'] ?? '-';
        $periode_start      =$Data['periode_start'] ?? '-';
        $periode_end        =$Data['periode_end'] ?? '-';
        $fee_nominal        =$Data['fee_nominal'] ?? '-';
        
        //Format Rupiah
        $fee_nominal_format="Rp" . number_format($fee_nominal,0,',','.');

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">
        ';
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
                <div class="col-4"><small>Nominal</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$fee_nominal_format.'</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <h2>Penting!</h2>
                        <small>
                            Menghapus data komponen akan menghapus data turunannya, termasuk riwayat pembayaran.<br>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </small>
                    </div>
                </div>
            </div>
        ';
    }
?>