<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Set header JSON
    header('Content-Type: application/json');

    // Validasi Akses
    if (empty($SessionIdAccess)) {
        echo json_encode([
            "status" => "error",
            "message" => "Sesi Akses Sudah Berakhir! Silahkan Login Ulang!"
        ]);
        exit;
    }

    // Validasi Form Required
    $required = ['id_organization_class','id_fee_component'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo json_encode([
                "status" => "error",
                "message" => "Field $r wajib diisi!"
            ]);
            exit;
        }
    }

    // Buat Variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);

    // Ambil nominal dari fee_component
    $fee_nominal = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'fee_nominal');

    // Default fee_discount
    $fee_discount = 0;

    // Cek apakah fee_component valid
    if ($fee_nominal === null) {
        echo json_encode([
            "status" => "error",
            "message" => "Data fee_component tidak ditemukan!"
        ]);
        exit;
    }

    try {
        // ===== Cek apakah sudah ada di fee_by_class =====
        $cekClass = $Conn->prepare("SELECT COUNT(*) FROM fee_by_class WHERE id_organization_class=? AND id_fee_component=?");
        $cekClass->bind_param("ii", $id_organization_class, $id_fee_component);
        $cekClass->execute();
        $cekClass->bind_result($countClass);
        $cekClass->fetch();
        $cekClass->close();

        if ($countClass > 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Komponen biaya sudah ada pada kelas ini!"
            ]);
            exit;
        }

        // Insert ke fee_by_class
        $stmt = $Conn->prepare("INSERT INTO fee_by_class (id_organization_class, id_fee_component) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_organization_class, $id_fee_component);
        $Input = $stmt->execute();
        $stmt->close();

        if(!$Input){
            echo json_encode([
                "status" => "error",
                "message" => "Gagal menambahkan data ke fee_by_class!"
            ]);
            exit;
        }

        // Looping Student Berdasarkan id_organization_class
        $query = $Conn->prepare("SELECT id_student FROM student WHERE id_organization_class=?");
        $query->bind_param("i", $id_organization_class);
        $query->execute();
        $result = $query->get_result();

        while ($data = $result->fetch_assoc()) {
            $id_student = $data['id_student'];

            // ===== Cek apakah sudah ada di fee_by_student =====
            $cekStudent = $Conn->prepare("SELECT COUNT(*) FROM fee_by_student WHERE id_organization_class=? AND id_student=? AND id_fee_component=?");
            $cekStudent->bind_param("iii", $id_organization_class, $id_student, $id_fee_component);
            $cekStudent->execute();
            $cekStudent->bind_result($countStudent);
            $cekStudent->fetch();
            $cekStudent->close();

            if ($countStudent == 0) {
                // Insert Data fee_by_student
                $stmt2 = $Conn->prepare("
                    INSERT INTO fee_by_student (id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt2->bind_param("iiiid", $id_organization_class, $id_student, $id_fee_component, $fee_nominal, $fee_discount);
                $stmt2->execute();
                $stmt2->close();
            }
        }

        echo json_encode([
            "status" => "success",
            "message" => "Komponen biaya berhasil ditambahkan tanpa duplikasi!"
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Terjadi kesalahan: " . $e->getMessage()
        ]);
    }
?>
