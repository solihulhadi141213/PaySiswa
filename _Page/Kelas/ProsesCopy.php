<?php
    // Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    // Koneksi & Konfigurasi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Validasi sesi
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small>
            </div>
        ';
        exit;
    }

    // Validasi parameter
    if (empty($_POST['curent_id_academic_period'])) {
        echo '
            <div class="alert alert-danger">
                <small>ID Periode Akademik Tujuan Tidak Ditemukan!</small>
            </div>
        ';
        exit;
    }
    if (empty($_POST['id_academic_period_sumber'])) {
        echo '
            <div class="alert alert-danger">
                <small>ID Periode Akademik Sumber Tidak Ditemukan!</small>
            </div>
        ';
        exit;
    }

    // Variabel
    $curent_id_academic_period = $_POST['curent_id_academic_period'];
    $id_academic_period_sumber = $_POST['id_academic_period_sumber'];

    try {
        // Cek jumlah data sumber
        $sql_count = "SELECT COUNT(*) AS total FROM organization_class WHERE id_academic_period = ?";
        $stmt_count = $Conn->prepare($sql_count);
        $stmt_count->bind_param("i", $id_academic_period_sumber);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result()->fetch_assoc();
        $stmt_count->close();

        $jml_data = (int)$result_count['total'];
        if ($jml_data === 0) {
            echo '
                <div class="alert alert-warning">
                    <small>Tidak ada data kelas pada periode sumber.</small>
                </div>
            ';
            exit;
        }

        // Mulai transaksi
        $Conn->begin_transaction();

        $sql_select = "SELECT class_level, class_name FROM organization_class WHERE id_academic_period = ?";
        $stmt_select = $Conn->prepare($sql_select);
        $stmt_select->bind_param("i", $id_academic_period_sumber);
        $stmt_select->execute();
        $result_select = $stmt_select->get_result();

        $inserted = 0;
        $skipped  = 0;
        $debug_log = [];

        // Loop data sumber
        while ($row = $result_select->fetch_assoc()) {
            $class_level = $row['class_level'];
            $class_name  = $row['class_name'];

            // Cek apakah sudah ada
            $sql_check = "SELECT id_organization_class FROM organization_class WHERE class_level = ? AND class_name = ? AND id_academic_period = ?";
            $stmt_check = $Conn->prepare($sql_check);
            $stmt_check->bind_param("ssi", $class_level, $class_name, $curent_id_academic_period);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $exists = $result_check->num_rows > 0;
            $stmt_check->close();

            if ($exists) {
                $skipped++;
                $debug_log[] = "Lewati: Kelas {$class_name} (level {$class_level}) sudah ada.";
                continue;
            }

            // Insert baru
            $sql_insert = "INSERT INTO organization_class (id_academic_period, class_level, class_name) VALUES (?, ?, ?)";
            $stmt_insert = $Conn->prepare($sql_insert);
            $stmt_insert->bind_param("iss", $curent_id_academic_period, $class_level, $class_name);
            $success = $stmt_insert->execute();
            $stmt_insert->close();

            if ($success) {
                $inserted++;
                $debug_log[] = "Berhasil salin: {$class_name} (level {$class_level})";
            } else {
                $error = $Conn->error;
                throw new Exception("Gagal insert data kelas {$class_name}: {$error}");
            }
        }

        $stmt_select->close();

        // Jika semua berhasil
        $Conn->commit();

        echo '
            <div class="alert alert-success">
                <small>
                    <b>Copy data kelas berhasil!</b><br>
                    Jumlah disalin: '.$inserted.' / '.$jml_data.'<br>
                    Dilewati (sudah ada): '.$skipped.'
                </small>
            </div>
        ';

        // Debug log
        echo "<pre style='background:#f9f9f9;border:1px solid #ccc;padding:6px;font-size:11px;'>";
        echo "=== DEBUG LOG ===\n";
        foreach ($debug_log as $line) {
            echo htmlspecialchars($line) . "\n";
        }
        echo "</pre>";

    } catch (Exception $e) {
        // Batalkan semua perubahan jika gagal
        $Conn->rollback();

        echo '
            <div class="alert alert-danger">
                <small>
                    Terjadi kesalahan pada saat menyalin data kelas.<br>
                    Proses dibatalkan!<br>
                    <b>Pesan error:</b> '.htmlspecialchars($e->getMessage()).'
                </small>
            </div>
        ';
    }
?>
