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

//Validasi Input
if (empty($_POST['id_fee_component'])) {
    echo '
        <div class="alert alert-danger">
            <small>
                ID Komponen tidak boleh kosong!
            </small>
        </div>
    ';
    exit;
}

//Buat variabel
$id_fee_component = validateAndSanitizeInput($_POST['id_fee_component']);

//Ambil Data
$Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
$Qry->bind_param("i", $id_fee_component);
if (!$Qry->execute()) {
    $error = $Conn->error;
    echo '
        <div class="alert alert-danger">
            <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : ' . $error . '</small>
        </div>
    ';
} else {
    $Result = $Qry->get_result();
    if ($Result->num_rows > 0) {
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $id_fee_component   = $Data['id_fee_component'];
        $component_name     = $Data['component_name'];
        $component_category = $Data['component_category'];
        $periode_month      = $Data['periode_month'];
        $periode_year       = $Data['periode_year'];
        $periode_start      = $Data['periode_start'];
        $periode_end        = $Data['periode_end'];
        $fee_nominal        = $Data['fee_nominal'];

        //Format Rupiah
        $fee_nominal_format = number_format($fee_nominal, 0, ',', '.');

        //Array Nama Bulan
        $nama_bulan = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember"
        ];

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_fee_component" value="' . $id_fee_component . '">
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="component_name">
                        <small>Biaya Pendidikan <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                    <input type="text" name="component_name" id="component_name" class="form-control" value="' . $component_name . '" required>
                    <small class="text text-grayish">Contoh : SPP Januari</small>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="component_category">
                        <small>Kategori <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                    <select class="form-control" name="component_category" id="component_category" required>
                        <option value="">Pilih</option>
                        <option value="SPP" ' . ($component_category == "SPP" ? "selected" : "") . '>SPP</option>
                        <option value="Non-SPP" ' . ($component_category == "Non-SPP" ? "selected" : "") . '>Non-SPP</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="periode_month">
                        <small>Periode Bulan <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                    <select class="form-control" name="periode_month" id="periode_month" required>
                        <option value="">Pilih</option>';
                        foreach ($nama_bulan as $key => $val) {
                            $selected = ($periode_month == $key) ? "selected" : "";
                            echo '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
                        }
        echo '
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="periode_year">
                        <small>Periode Tahun <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                    <input type="number" min="2000" name="periode_year" id="periode_year" class="form-control" value="' . $periode_year . '" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label>
                        <small>Tempo Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                </div>
                <div class="col-6">
                    <input type="date" name="periode_start" id="periode_start" class="form-control" value="' . $periode_start . '" required>
                    <small class="text text-grayish">Awal</small>
                </div>
                <div class="col-6">
                    <input type="date" name="periode_end" id="periode_end" class="form-control" value="' . $periode_end . '" required>
                    <small class="text text-grayish">Akhir</small>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="fee_nominal">
                        <small>Nominal Pembayaran <i class="bi bi-exclamation-circle" title="Wajib Diisi"></i></small>
                    </label>
                    <input type="text" name="fee_nominal" id="fee_nominal" class="form-control form-money" value="' . $fee_nominal_format . '" required>
                </div>
            </div>
        ';
    } else {
        echo '
            <div class="alert alert-warning">
                <small>Data tidak ditemukan!</small>
            </div>
        ';
    }
}
?>
