<?php
    // Default Header JSON
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");

    // Connection Dan Function
    include "../_Config/Connection.php";
    include "../_Config/GlobalFunction.php";

    // Validasi Metode Pengiriman Data Hanya Boleh POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["status" => false,"code" => 405,"message" => "Method Not Allowed, hanya boleh POST"]);
        exit;
    }

    // Ambil token dari header
    $headers = getallheaders();
    if (!isset($headers['x-token'])) {
        http_response_code(401);
        echo json_encode([
            "status" => false,
            "code" => 401,
            "message" => "x-token tidak ditemukan"
        ]);
        exit;
    }
    $x_token = validateAndSanitizeInput($headers['x-token']);

    // Validasi token
    $cekToken = $Conn->prepare("SELECT id_account FROM api_token WHERE api_token=?");
    $cekToken->bind_param("s", $x_token);
    $cekToken->execute();
    $cekToken->store_result();
    if ($cekToken->num_rows == 0) {
        http_response_code(401);
        echo json_encode([
            "status" => false,
            "code" => 401,
            "message" => "Token tidak valid"
        ]);
        exit;
    }
    $cekToken->bind_result($id_account);
    $cekToken->fetch();
    $cekToken->close();

    // Ambil request body
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['id_setting_payment'])) {
        http_response_code(400);
        echo json_encode([
            "status" => false,
            "code" => 400,
            "message" => "id_setting_payment wajib dikirim"
        ]);
        exit;
    }
    $id_setting_payment = intval($data['id_setting_payment']);

    // Validasi kepemilikan setting
    $cekSetting = $Conn->prepare("SELECT status FROM setting_payment WHERE id_setting_payment=? AND id_account=?");
    $cekSetting->bind_param("ii", $id_setting_payment, $id_account);
    $cekSetting->execute();
    $cekSetting->store_result();
    if ($cekSetting->num_rows == 0) {
        http_response_code(403);
        echo json_encode([
            "status" => false,
            "code" => 403,
            "message" => "Setting tidak ditemukan atau bukan milik anda"
        ]);
        exit;
    }
    $cekSetting->bind_result($statusSetting);
    $cekSetting->fetch();
    $cekSetting->close();

    // Validasi jika status active tidak boleh dihapus
    if ($statusSetting === "active") {
        http_response_code(400);
        echo json_encode([
            "status" => false,
            "code" => 400,
            "message" => "Setting dengan status active tidak bisa dihapus"
        ]);
        exit;
    }

    // Proses delete
    $delete = $Conn->prepare("DELETE FROM setting_payment WHERE id_setting_payment=? AND id_account=?");
    $delete->bind_param("ii", $id_setting_payment, $id_account);
    if ($delete->execute()) {
        echo json_encode([
            "status" => true,
            "code" => 200
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => false,
            "code" => 500,
            "message" => "Gagal menghapus data"
        ]);
    }
    $delete->close();
    $Conn->close();
?>