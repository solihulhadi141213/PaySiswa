<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi Session Akses
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }

    //Buat Variabel
    $akses      = trim($_POST['akses'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $rules      = $_POST['rules'] ?? [];

    //Validasi Semua Variabel
    if ($akses === '') {
        echo '<div class="alert alert-danger"><small>Nama Entitas Akses Tidak Boleh Kosong!</small></div>';
        exit;
    }
    if ($keterangan === '') {
        echo '<div class="alert alert-danger"><small>Keterangan/Deskripsi Entitas Akses Tidak Boleh Kosong!</small></div>';
        exit;
    }
    if (empty($rules)) {
        echo '<div class="alert alert-danger"><small>Fitur Entitas Akses Tidak Boleh Kosong!</small></div>';
        exit;
    }
    if (strlen($akses) > 250) {
        echo '<div class="alert alert-danger"><small>Nama Entitas Akses Tidak Boleh Lebih Dari 250 Karakter!</small></div>';
        exit;
    }

    // Validasi duplikat
    $stmt = $Conn->prepare("SELECT COUNT(*) FROM access_group WHERE group_name=?");
    $stmt->bind_param("s", $akses);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo '<div class="alert alert-danger"><small>Data Entitas Akses Sudah Ada!</small></div>';
        exit;
    }

    // Mulai transaksi
    $Conn->begin_transaction();
    try {

        // Insert access_group
        $stmt = $Conn->prepare("INSERT INTO access_group (group_name, group_description) VALUES (?, ?)");
        $stmt->bind_param("ss", $akses, $keterangan);
        if (!$stmt->execute()) {
            throw new Exception("Gagal insert access_group");
        }
        $id_access_group = $Conn->insert_id;
        $stmt->close();

        // Insert access_reference
        $stmt2 = $Conn->prepare("INSERT INTO access_reference (id_access_group, id_access_feature) VALUES (?, ?)");
        foreach ($rules as $id_feature) {
            $stmt2->bind_param("is", $id_access_group, $id_feature);
            if (!$stmt2->execute()) {
                throw new Exception("Gagal insert access_reference");
            }
        }
        $stmt2->close();

        // Commit transaksi
        $Conn->commit();

        // Simpan log
        $kategori_log = "Entitas Akses";
        $deskripsi_log = "Input Entitas Akses";
        $log = addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);

        if ($log == "Success") {
            echo '<small class="text-success" id="NotifikasiTambahAksesEntitiasBerhasil">Success</small>';
        } else {
            echo '<small class="text-danger">Data tersimpan tapi gagal menulis log</small>';
        }

    } catch (Exception $e) {
        // Rollback jika ada error
        $Conn->rollback();
        echo '<div class="alert alert-danger"><small>' . $e->getMessage() . '</small></div>';
    }
?>
