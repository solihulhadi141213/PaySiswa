<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    // JSON helper
    function jsonError($msg, $log = []) {
        echo json_encode([
            'status' => 'error',
            'message' => $msg,
            'log' => $log
        ]);
        exit;
    }

    // Validasi Sesi
    if (empty($SessionIdAccess)) jsonError("Sesi Akses Sudah Berakhir! Silahkan Login Ulang!");

    // Validasi Input
    if (empty($_POST['id_student'])) jsonError("ID Siswa Tidak Boleh Kosong!");
    if (empty($_POST['id_organization_class'])) jsonError("ID Kelas Tidak Boleh Kosong!");
    if (empty($_POST['id_fee_component'])) jsonError("Tidak Ada Komponen Biaya Yang Dipilih!");

    // Sanitasi
    $id_student            = validateAndSanitizeInput($_POST['id_student']);
    $id_organization_class = validateAndSanitizeInput($_POST['id_organization_class']);

    $id_fee_component_list = $_POST['id_fee_component'];
    $jumlah_data           = count($id_fee_component_list);

    // Jika tidak ada dipilih
    if ($jumlah_data < 1) jsonError("Tidak Ada Komponen Biaya Yang Dipilih!");

    // Logging
    $log = [];

    // Mulai transaksi → agar bisa rollback jika ada yang gagal
    $Conn->begin_transaction();

    try {

        foreach ($id_fee_component_list as $id_fee_component) {

            // Pastikan index data nominal & diskon tersedia
            if (!isset($_POST['fee_nominal'][$id_fee_component])) {
                $log[] = "Nominal untuk komponen ID $id_fee_component tidak ditemukan.";
                throw new Exception("Data tidak lengkap.");
            }

            $fee_nominal  = $_POST['fee_nominal'][$id_fee_component];
            $fee_discount = $_POST['fee_discount'][$id_fee_component] ?? 0;

            $fee_nominal    = str_replace('.', '', $fee_nominal);
            $fee_discount   = str_replace('.', '', $fee_discount);

            // Cek Duplikasi
            $QryCek = $Conn->prepare("
                SELECT id_fee_by_student 
                FROM fee_by_student 
                WHERE id_organization_class = ? AND id_student = ? AND id_fee_component = ?
            ");

            if (!$QryCek) {
                $log[] = "Gagal prepare cek duplikasi: " . $Conn->error;
                throw new Exception("Kesalahan sistem saat cek duplikasi.");
            }

            $QryCek->bind_param("iii", $id_organization_class, $id_student, $id_fee_component);

            if (!$QryCek->execute()) {
                $log[] = "Gagal execute cek duplikasi untuk komponen ID $id_fee_component: " . $QryCek->error;
                throw new Exception("Kesalahan saat cek duplikasi.");
            }

            $ResultCek = $QryCek->get_result();
            $DataCek   = $ResultCek->fetch_assoc();
            $id_fee_by_student = $DataCek['id_fee_by_student'] ?? null;
            $QryCek->close();


            // Jika data belum ada → INSERT
            if (empty($id_fee_by_student)) {

                $stmt = $Conn->prepare("
                    INSERT INTO fee_by_student 
                    (id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount)
                    VALUES (?, ?, ?, ?, ?)
                ");

                if (!$stmt) {
                    $log[] = "Gagal prepare INSERT untuk komponen ID $id_fee_component: " . $Conn->error;
                    throw new Exception("Kesalahan sistem saat insert data.");
                }

                $stmt->bind_param("iiiss", 
                    $id_organization_class, 
                    $id_student, 
                    $id_fee_component,
                    $fee_nominal, 
                    $fee_discount
                );

                if (!$stmt->execute()) {
                    $log[] = "Gagal INSERT komponen ID $id_fee_component: " . $stmt->error;
                    throw new Exception("Insert data gagal.");
                }

                $stmt->close();

            } else {
                // Update
                $stmt = $Conn->prepare("
                    UPDATE fee_by_student 
                    SET fee_nominal = ?, fee_discount = ? 
                    WHERE id_fee_by_student = ?
                ");

                if (!$stmt) {
                    $log[] = "Gagal prepare UPDATE untuk ID $id_fee_by_student: " . $Conn->error;
                    throw new Exception("Kesalahan sistem saat update data.");
                }

                $stmt->bind_param("ssi", $fee_nominal, $fee_discount, $id_fee_by_student);

                if (!$stmt->execute()) {
                    $log[] = "Gagal UPDATE ID $id_fee_by_student: " . $stmt->error;
                    throw new Exception("Update data gagal.");
                }

                $stmt->close();
            }
        }

        // Jika semua sukses → commit
        $Conn->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Semua data berhasil diproses.',
            'log' => $log
        ]);
        exit;

    } catch (Exception $e) {

        // Batalkan semua operasi
        $Conn->rollback();

        jsonError("Terjadi kesalahan pada saat memproses data!", $log);
    }
?>
