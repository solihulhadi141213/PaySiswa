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
        if(empty($_POST['kode_transaksi'])){
            $response['message'] = 'Kode Transaksi Tidak Boleh Kosong';
        }else{
            if (empty($_POST['order_id'])) {
                $response['message'] = 'Order ID Tidak Boleh Kosong';
            } else {
                if (empty($_POST['gross_amount'])) {
                    $response['message'] = 'Jumlah tagihan Tidak Boleh Kosong';
                } else {
                    if (empty($_POST['first_name'])) {
                        $response['message'] = 'Nama Depan Tidak Boleh Kosong';
                    } else {
                        if (empty($_POST['last_name'])) {
                            $response['message'] = 'Nama Belakang Tidak Boleh Kosong';
                        } else {
                            if (empty($_POST['email'])) {
                                $response['message'] = 'Email Tidak Boleh Kosong';
                            } else {
                                if (empty($_POST['phone'])) {
                                    $response['message'] = 'Nomor Telepon Tidak Boleh Kosong';
                                } else {
                                    // Mengambil data dari POST dan sanitasi
                                    $kode_transaksi = validateAndSanitizeInput($_POST['kode_transaksi']);
                                    $order_id = validateAndSanitizeInput($_POST['order_id']);
                                    $gross_amount = validateAndSanitizeInput($_POST['gross_amount']);
                                    $first_name = validateAndSanitizeInput($_POST['first_name']);
                                    $last_name = validateAndSanitizeInput($_POST['last_name']);
                                    $email = validateAndSanitizeInput($_POST['email']);
                                    $phone = validateAndSanitizeInput($_POST['phone']);
                                    $gross_amount = str_replace('.', '', $gross_amount); // Format uang
                                    // Ambil Server Key dan Production dari database
                                    $api_key = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'api_key');
                                    $server_key = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'server_key');
                                    $production = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'production');
                                    $api_payment_url =GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'api_payment_url');
                                    // Set header
                                    $headers = array(
                                        'Content-Type: Application/x-www-form-urlencoded',
                                    );
                                    // Array data untuk dikirim via CURL
                                    $order = array(
                                        "order_id" => $order_id,
                                        "gross_amount" => $gross_amount,
                                        "first_name" => $first_name,
                                        "last_name" => $last_name,
                                        "email" => $email,
                                        "phone" => $phone,
                                        "kode_transaksi" => $kode_transaksi,
                                    );
                                    $arr = array(
                                        "api_key" => $api_key,
                                        "order" => $order
                                    );
                                    $json = json_encode($arr);

                                    // CURL init
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, "$api_payment_url/GenerateSnapToken.php");
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                                    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // Timeout lebih lama
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                    // Eksekusi CURL dan cek error
                                    $response_curl = curl_exec($curl);
                                    if ($response_curl === false) {
                                        $response['message'] = 'CURL Error: ' . curl_error($curl);
                                    } else {
                                        $data = json_decode($response_curl, true); // Decode response JSON
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $code = $data["code"];
                                            $status = $data["status"];
                                            $token = $data["token"];
                                            if ($code == "200") {
                                                $kategori_log = "Setting";
                                                $deskripsi_log = "Tes Generate Snap Token";
                                                $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);
                                                if ($InputLog == "Success") {
                                                    $response['status'] = 'success';
                                                    $response['message'] = $status;
                                                    $response['token'] = $token;
                                                } else {
                                                    $response['message'] = 'Terjadi kesalahan pada saat menyimpan Log!';
                                                }
                                            } else {
                                                $response['message'] = 'Snap Token Gagal Dibuat! Kode: ' . $code;
                                            }
                                        } else {
                                            $response['message'] = 'Error pada decoding JSON: '.$response_curl.' ' . json_last_error_msg();
                                        }
                                    }

                                    // Tutup CURL
                                    curl_close($curl);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // Kirim response dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>
