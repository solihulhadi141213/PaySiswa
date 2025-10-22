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

    try {
        // ===== Cek apakah data ada di fee_by_class =====
        $cekClass = $Conn->prepare("SELECT COUNT(*) FROM fee_by_class WHERE id_organization_class=? AND id_fee_component=?");
        $cekClass->bind_param("ii", $id_organization_class, $id_fee_component);
        $cekClass->execute();
        $cekClass->bind_result($countClass);
        $cekClass->fetch();
        $cekClass->close();

        if ($countClass == 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Data komponen biaya tidak ditemukan pada kelas ini!"
            ]);
            exit;
        }

        // ===== Hapus dari fee_by_class =====
        $stmt = $Conn->prepare("DELETE FROM fee_by_class WHERE id_organization_class=? AND id_fee_component=?");
        $stmt->bind_param("ii", $id_organization_class, $id_fee_component);
        $hapusClass = $stmt->execute();
        $stmt->close();

        if(!$hapusClass){
            echo json_encode([
                "status" => "error",
                "message" => "Gagal menghapus data dari fee_by_class!"
            ]);
            exit;
        }

        // ===== Hapus dari  fee_by_student  =====
        $stmt2 = $Conn->prepare("DELETE FROM fee_by_student WHERE id_organization_class=? AND id_fee_component=?");
        $stmt2->bind_param("ii", $id_organization_class, $id_fee_component);
        $HapusStudent = $stmt2->execute();
        $stmt2->close();

        //Jika Berhasil
        if(!$HapusStudent){
            echo json_encode([
                "status" => "error",
                "message" => "Gagal menghapus data dari fee_by_student!"
            ]);
            exit;
        }

        echo json_encode([
            "status" => "success",
            "message" => "Komponen biaya berhasil dihapus dari kelas dan siswa terkait!"
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Terjadi kesalahan: " . $e->getMessage()
        ]);
    }
?>
