<?php
    // Koneksi & Fungsi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    // Validasi Sesi
    if (empty($SessionIdAccess)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
            'id_organization_class' => ''
        ]);
        exit;
    }

    // Validasi Input Utama
    $requiredFields = [
        'id_organization_class' => 'ID Kelas Tidak Boleh Kosong!',
        'id_student' => 'ID Siswa Tidak Boleh Kosong!',
        'id_fee_component' => 'ID Komponen Tidak Boleh Kosong!'
    ];
    foreach ($requiredFields as $key => $errorMsg) {
        if (empty($_POST[$key])) {
            echo json_encode(['status' => 'error', 'message' => $errorMsg, 'id_organization_class' =>'']);
            exit;
        }
    }

    // Sanitasi Input
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);
    $id_fee_by_student      = !empty($_POST['id_fee_by_student']) ? validateAndSanitizeInput($_POST['id_fee_by_student']) : null;

    // Nominal & Diskon
    $fee_nominal = !empty($_POST['fee_nominal']) ? str_replace('.', '', $_POST['fee_nominal']) : 0;
    $fee_discount = !empty($_POST['fee_discount']) ? str_replace('.', '', $_POST['fee_discount']) : 0;
    $fee_nominal = is_numeric($fee_nominal) ? (int)$fee_nominal : 0;
    $fee_discount = is_numeric($fee_discount) ? (int)$fee_discount : 0;

    // Cek data existing
    $QryCek = $Conn->prepare("SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class = ? AND id_student = ? AND id_fee_component = ?");
    $QryCek->bind_param("iii", $id_organization_class, $id_student, $id_fee_component);
    if (!$QryCek->execute()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal membuka database tagihan siswa. Error: ' . $QryCek->error,
            'id_organization_class' => ''
        ]);
        exit;
    }
    $ResultCek = $QryCek->get_result();
    $DataCek = $ResultCek ? $ResultCek->fetch_assoc() : null;
    $QryCek->close();

    // Insert / Update
    if (empty($DataCek)) {
        // INSERT
        $stmt = $Conn->prepare("INSERT INTO fee_by_student (id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiii", $id_organization_class, $id_student, $id_fee_component, $fee_nominal, $fee_discount);
        $Input = $stmt->execute();
        $stmt->close();

        if ($Input) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Tambah data tagihan berhasil!',
                'id_organization_class' => $id_organization_class,
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal insert data tagihan siswa. Error: ' . $Conn->error,
                'id_organization_class' => ''
            ]);
        }
    } else {
        // UPDATE
        $id_fee_by_student = $DataCek['id_fee_by_student'];
        $stmt = $Conn->prepare("UPDATE fee_by_student SET fee_nominal = ?, fee_discount = ? WHERE id_fee_by_student = ?");
        $stmt->bind_param("iii", $fee_nominal, $fee_discount, $id_fee_by_student);
        $Update = $stmt->execute();
        $stmt->close();

        if ($Update) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Update data tagihan berhasil!',
                'id_organization_class' => $id_organization_class
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal update data tagihan siswa. Error: ' . $Conn->error,
                'id_organization_class' => ''
            ]);
        }
    }
?>
