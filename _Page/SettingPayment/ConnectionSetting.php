<?php
    //Koneksi dan session
    date_default_timezone_set('Asia/Jakarta');
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
    
    //Buka pengaturan payment gateway
    $id_setting_payment = 1;
    try {
        // Gunakan prepared statement
        $Qry = $Conn->prepare("SELECT api_payment_url, USER_KEY, SECRET_KEY 
                            FROM setting_payment 
                            WHERE id_setting_payment = ?");
        if (!$Qry) {
            throw new Exception("Gagal mempersiapkan query: " . $Conn->error);
        }

        $Qry->bind_param("i", $id_setting_payment);

        if (!$Qry->execute()) {
            throw new Exception("Gagal mengeksekusi query: " . $Qry->error);
        }

        $Result = $Qry->get_result();
        if (!$Result) {
            throw new Exception("Gagal mengambil hasil query: " . $Qry->error);
        }

        $Data = $Result->fetch_assoc() ?? [];

        // Buat Variabel dengan fallback default "-"
        $api_payment_url = $Data['api_payment_url'] ?? "-";
        $USER_KEY        = $Data['USER_KEY'] ?? "-";
        $SECRET_KEY      = $Data['SECRET_KEY'] ?? "-";

        $Qry->close();

    } catch (Exception $e) {
        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat membuka data dari database!<br>
                    Keterangan: ' . htmlspecialchars($e->getMessage()) . '
                </small>
            </div>
        ';
        exit;
    }

    //Menampilkan Pengaturan
    echo '
        <div class="row mb-3">
            <div class="col-4"><small>ENDPOINT</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$api_payment_url.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>USER KEY</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$USER_KEY.'</small></div>
        </div>
        <div class="row mb-3">
            <div class="col-4"><small>SECRET KEY</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-7"><small class="text text-grayish">'.$SECRET_KEY.'</small></div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#ModalSettingKoneksi" title="Ubah Pengaturan">
                    <i class="bi bi-gear"></i> Ubah Pengaturan
                </button>
                <button type="button" class="btn btn-md btn-secondary" data-bs-toggle="modal" data-bs-target="#ModalTestKoneksi" title="Uji Coba Koneksi">
                    <i class="bi bi-arrows-angle-contract"></i> Test Koneksi
                </button>
            </div>
        </div>
    ';
?>