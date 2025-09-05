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
        $period_value       =$Data['period_value'] ?? '-';
        $period_unit        =$Data['period_unit'] ?? '-';
        $periode_start      =$Data['periode_start'] ?? '-';
        $periode_end        =$Data['periode_end'] ?? '-';
        $fee_nominal        =$Data['fee_nominal'] ?? '-';
        
        //Format Rupiah
        $fee_nominal_format="Rp" . number_format($fee_nominal,0,',','.');

        //Routing Periode Unit
        $select_month="";
        $select_year="";
        $select_once="";
        if($period_unit=="month"){
            $select_month="selected";
        }else{
            if($period_unit=="year"){
                $select_year="selected";
            }else{
                if($period_unit=="once"){
                    $select_once="selected";
                }
            }
        }

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">
            <div class="row mb-3">
                <div class="col-4">
                    <label for="component_name_edit">
                        <small>Nama Komponen <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="component_name" id="component_name_edit" class="form-control" value="'.$component_name.'" required>
                    <small class="text text-grayish">Contoh : SPP Kelas 1</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="period_value_edit">
                        <small>Periode Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="number" name="period_value" id="period_value_edit" class="form-control" value="'.$period_value.'">
                    <small class="text text-grayish">Menunjukan berapa kali Komponen harus membayar tiap rute</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="period_unit_edit">
                        <small>Rute Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <select name="period_unit" id="period_unit_edit" class="form-control" required>
                        <option value="">Pilih</option>
                        <option '.$select_month.' value="month">Bulanan</option>
                        <option '.$select_year.' value="year">Tahunan</option>
                        <option '.$select_once.' value="once">Sekali</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="periode_start_edit">
                        <small>Periode Awal <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="date" name="periode_start" id="periode_start_edit" class="form-control" value="'.$periode_start.'" required>
                    <small class="text text-grayish">Waktu pembayaran berlaku</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="periode_end_edit">
                        <small>Periode Akhir <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="date" name="periode_end" id="periode_end_edit" class="form-control" value="'.$periode_end.'" required>
                    <small class="text text-grayish">Waktu pembayaran berakhir</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="fee_nominal_edit">
                        <small>Nominal Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-8">
                    <input type="text" name="fee_nominal" id="fee_nominal_edit" value="'.$fee_nominal.'" class="form-control form-money">
                </div>
            </div>
        ';
    }
?>