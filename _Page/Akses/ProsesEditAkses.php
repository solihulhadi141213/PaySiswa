<?php
    // Koneksi & dependensi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // --- Validasi sesi ---
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi akses sudah berakhir! Silahkan login ulang!</small></div>';
        exit;
    }

    // --- Validasi input wajib ---
    $required = ['id_access','nama_akses','kontak_akses','email_akses','akses'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    // --- Sanitasi input ---
    $id_access          = validateAndSanitizeInput($_POST['id_access']);
    $access_name        = validateAndSanitizeInput($_POST['nama_akses']);
    $access_contact     = validateAndSanitizeInput($_POST['kontak_akses']);
    $access_email       = validateAndSanitizeInput($_POST['email_akses']);
    $id_access_group    = intval($_POST['akses']); // integer

    // --- Ambil id_access_group lama ---
    $id_access_group_lama = GetDetailData($Conn, 'access', 'id_access', $id_access, 'id_access_group');

    // --- Update data access ---
    $sql = "UPDATE access SET id_access_group=?, access_name=?, access_email=?, access_contact=? WHERE id_access=?";
    $stmt = $Conn->prepare($sql);
    $stmt->bind_param("isssi", $id_access_group, $access_name, $access_email, $access_contact, $id_access);

    if ($stmt->execute()) {

        // Jika group akses berubah → reset permission
        if ($id_access_group !== $id_access_group_lama) {

            // Hapus permission lama
            $del = $Conn->prepare("DELETE FROM access_permission WHERE id_access=?");
            $del->bind_param("i", $id_access);
            if ($del->execute()) {

                // Ambil semua fitur dari access_reference berdasarkan group baru
                $qryRef = $Conn->prepare("SELECT id_access_feature FROM access_reference WHERE id_access_group=?");
                $qryRef->bind_param("i", $id_access_group);
                $qryRef->execute();
                $resRef = $qryRef->get_result();

                $okCount = 0;
                $total = $resRef->num_rows;

                $ins = $Conn->prepare("INSERT INTO access_permission (id_access, id_access_feature) VALUES (?, ?)");
                while ($row = $resRef->fetch_assoc()) {
                    $feature = $row['id_access_feature']; // varchar (UUID)
                    $ins->bind_param("is", $id_access, $feature);
                    if ($ins->execute()) {
                        $okCount++;
                    }
                }

                if ($okCount === $total) {
                    echo '<small class="text-success" id="NotifikasiEditAksesBerhasil">Success</small>';
                } else {
                    echo '<div class="alert alert-warning"><small>Data akses berhasil diupdate, tetapi tidak semua permission tersimpan!</small></div>';
                }

            } else {
                echo '<div class="alert alert-danger"><small>Gagal menghapus permission lama!</small></div>';
            }

        } else {
            // Tidak ada perubahan group → selesai
            echo '<small class="text-success" id="NotifikasiEditAksesBerhasil">Success</small>';
        }

    } else {
        echo '<div class="alert alert-danger"><small>Gagal update data akses!</small></div>';
    }

    $stmt->close();
?>
