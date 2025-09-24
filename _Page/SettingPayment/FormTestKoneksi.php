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
        // Mulai CURL untuk Get Token
        $curl = curl_init();
        $postData = json_encode([
            "USER_KEY"   => $USER_KEY,
            "SECRET_KEY" => $SECRET_KEY
        ]);

        curl_setopt_array($curl, array(
            CURLOPT_URL            => rtrim($api_payment_url, "/") . "/_API/get_token.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ),
            CURLOPT_SSL_VERIFYHOST => 0,  // ⚠️ Disable SSL check (testing only)
            CURLOPT_SSL_VERIFYPEER => 0   // ⚠️ Disable SSL check (testing only)
        ));
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlErrNo = curl_errno($curl);
        $curlErr   = curl_error($curl);
        curl_close($curl);

        if ($curlErrNo) {
            // Jika ada error CURL
            echo '
                <div class="alert alert-danger">
                    <small>
                        <b>CURL Error!</b><br>
                        Kode Error: ' . $curlErrNo . '<br>
                        Pesan: ' . htmlspecialchars($curlErr) . '
                    </small>
                </div>
            ';
            exit;
        }

        // Decode Response JSON
        $response_array = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Jika JSON tidak valid
            echo '
                <div class="alert alert-danger">
                    <small>
                        <b>Response bukan JSON valid!</b><br>
                        HTTP Code: ' . $httpCode . '<br>
                        Response Asli: <pre>' . htmlspecialchars($response) . '</pre>
                    </small>
                </div>
            ';
            exit;
        }

        // Cek isi response
        if (isset($response_array['code']) && $response_array['code'] == 200) {
            echo '
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <div class="alert alert-success">
                            <h2><i class="bi bi-check-circle"></i></h2>
                            <b>Koneksi Berhasil!</b><br>
                            <small>X-Token : <code class="text text-grayish">' . htmlspecialchars($response_array['metadata']['x-token'] ?? '-') . '</code></small>
                        </div>
                    </div>
                </div>
            ';
        } else {
            echo '
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <small>
                                <b>Koneksi gagal!</b><br>
                                HTTP Code: ' . $httpCode . '<br>
                                Pesan: ' . htmlspecialchars($response_array['status'] ?? 'Tidak ada keterangan') . '
                            </small>
                        </div>
                    </div>
                </div>
            ';
        }
    }
?>
