<?php
    // Koneksi dan session
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Validasi Sesi Akses
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

    // Buka pengaturan payment gateway
    $id_setting_payment = 1;
    try {
        $Qry = $Conn->prepare("SELECT id_setting_payment, api_payment_url, USER_KEY, SECRET_KEY 
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
        $api_payment_url = $Data['api_payment_url'] ?? "";
        $USER_KEY        = $Data['USER_KEY'] ?? "";
        $SECRET_KEY      = $Data['SECRET_KEY'] ?? "";

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

    if (empty($Data['id_setting_payment'])) {
        echo '
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <small><b>Maaf!</b> pengaturan koneksi payment gateway belum ada! Silahkan atur pengaturan terlebih dulu.</small>
                    </div>
                </div>
            </div>
        ';
    } else {
        // Melalui function, request x-token
        $res_x_token = RequestXtoken($Conn);
        if ($res_x_token['status'] !== 'success') {
            $message = $res_x_token['message'];
            echo '
                <div class="alert alert-danger">
                    <small>
                        <b>Terjadi Kesalahan Pada Saat Request X-Token!</b><br>
                        ' . $message . '
                    </small>
                </div>
            ';
            exit;
        }

        $x_token = $res_x_token['x-token'];

        echo '
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-success">
                        <h2><i class="bi bi-check-circle"></i></h2>
                        <b>Koneksi Berhasil!</b><br>
                        <small>X-Token : <code class="text text-grayish">' . htmlspecialchars($x_token) . '</code></small>
                    </div>
                </div>
            </div>
        ';
    }
?>
