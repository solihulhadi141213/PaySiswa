<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');

    // Time Now Tmp
    $now = date('Y-m-d H:i:s');
    $response = array('status' => 'error', 'message' => '');

    if (empty($SessionIdAkses)) {
        $response['message'] = 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang';
    } else {
        // Ambil dan validasi input
        $api_payment_url = !empty($_POST['api_payment_url']) ? validateAndSanitizeInput($_POST['api_payment_url']) : '';
        $urll_call_back = !empty($_POST['urll_call_back']) ? validateAndSanitizeInput($_POST['urll_call_back']) : '';
        $url_status = !empty($_POST['url_status']) ? validateAndSanitizeInput($_POST['url_status']) : '';
        $api_key = !empty($_POST['api_key']) ? validateAndSanitizeInput($_POST['api_key']) : '';
        $id_marchant = !empty($_POST['id_marchant']) ? validateAndSanitizeInput($_POST['id_marchant']) : '';
        $client_key = !empty($_POST['client_key']) ? validateAndSanitizeInput($_POST['client_key']) : '';
        $server_key = !empty($_POST['server_key']) ? validateAndSanitizeInput($_POST['server_key']) : '';
        $snap_url = !empty($_POST['snap_url']) ? validateAndSanitizeInput($_POST['snap_url']) : '';
        $production = !empty($_POST['production']) ? validateAndSanitizeInput($_POST['production']) : 'false';
        $aktif_payment_gateway = !empty($_POST['aktif_payment_gateway']) ? validateAndSanitizeInput($_POST['aktif_payment_gateway']) : 'Tidak';

        // Update ke Database menggunakan prepared statement
        $stmt = $Conn->prepare("UPDATE setting_payment SET 
            api_payment_url = ?, 
            urll_call_back = ?, 
            url_status = ?, 
            api_key = ?, 
            id_marchant = ?, 
            client_key = ?, 
            server_key = ?, 
            snap_url = ?, 
            production = ?, 
            aktif_payment_gateway = ? 
            WHERE id_setting_payment = '1'");

        if ($stmt) {
            $stmt->bind_param(
                "ssssssssss",
                $api_payment_url,
                $urll_call_back,
                $url_status,
                $api_key,
                $id_marchant,
                $client_key,
                $server_key,
                $snap_url,
                $production,
                $aktif_payment_gateway
            );

            if ($stmt->execute()) {
                // Lakukan Update Ke Server menggunakan cURL
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_payment_url . '/UpdateSetting.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode(array(
                        "api_key" => $api_key,
                        "setting" => array(
                            "urll_call_back" => $urll_call_back,
                            "url_status" => $url_status,
                            "id_marchant" => $id_marchant,
                            "client_key" => $client_key,
                            "server_key" => $server_key,
                            "snap_url" => $snap_url,
                            "production" => $production
                        )
                    ), JSON_UNESCAPED_SLASHES),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                ));
                $responseCurl = curl_exec($curl); // Ambil respons dari cURL
                $curl_error = curl_error($curl); // Ambil error cURL jika ada
                $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Ambil kode HTTP

                curl_close($curl); // Tutup koneksi cURL

                // Debugging
                if ($curl_error) {
                    $response['message'] = 'Curl error: ' . $curl_error;
                    $response['http_code'] = $http_code; // Tambahkan kode HTTP ke respons
                } else {
                    // Cek apakah respons valid
                    $arry_res = json_decode($responseCurl, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $response['message'] = 'Invalid JSON response: ' . $responseCurl;
                        $response['http_code'] = $http_code; // Tambahkan kode HTTP ke respons
                    } elseif ($arry_res['status'] !== "Success") {
                        $StatusResponse = $arry_res['status'];
                        $response['message'] = 'Terjadi kesalahan pada saat update data pengaturan ke server (Keterangan: ' . $StatusResponse . ')';
                    } else {
                        // Log aktivitas
                        $kategori_log = "Setting";
                        $deskripsi_log = "Setting Payment";
                        $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);
                        if ($InputLog == "Success") {
                            $_SESSION["NotifikasiSwal"] = "Simpan Setting Payment Berhasil";
                            $response['status'] = 'success';
                            $response['message'] = 'Pengaturan berhasil disimpan';
                        } else {
                            $response['message'] = 'Terjadi kesalahan pada saat menyimpan log aktivitas';
                        }
                    }
                }
            } else {
                $response['message'] = 'Terjadi kesalahan pada saat update data pengaturan';
            }

            // Tutup prepared statement
            $stmt->close();
        } else {
            $response['message'] = 'Terjadi kesalahan pada persiapan query';
        }
    }

    // Kirim response dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>
