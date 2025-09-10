<?php
    // Koneksi & util
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }

    // Validasi Form Required
    $required = ['id_student','student_nis','student_name','student_gender','student_registered','student_status'];
    foreach($required as $r){
        if(!isset($_POST[$r]) || $_POST[$r]===''){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    // Variabel wajib
    $id_student         = (int)validateAndSanitizeInput($_POST['id_student']);
    $student_nis        = validateAndSanitizeInput($_POST['student_nis']);
    $student_name       = validateAndSanitizeInput($_POST['student_name']);
    $student_gender     = validateAndSanitizeInput($_POST['student_gender']);
    $student_registered = validateAndSanitizeInput($_POST['student_registered']);
    $student_status     = validateAndSanitizeInput($_POST['student_status']);

    // Variabel tidak wajib
    $student_nisn          = $_POST['student_nisn'] ?? '';
    $place_of_birth        = $_POST['place_of_birth'] ?? '';
    $date_of_birth_raw     = $_POST['date_of_birth'] ?? '';
    $student_contact       = $_POST['student_contact'] ?? '';
    $student_email         = $_POST['student_email'] ?? '';
    $student_address       = $_POST['student_address'] ?? '';
    $nama_ortu             = $_POST['nama_ortu'] ?? '';
    $kontak_ortu           = $_POST['kontak_ortu'] ?? '';

    // Normalisasi tanggal opsional (biar tidak '' ke kolom DATE)
    $date_of_birth = ($date_of_birth_raw !== '')
        ? validateAndSanitizeInput($date_of_birth_raw)
        : NULL;

    // Ambil NIS lama untuk cek unik bila berubah
    $student_nis_lama = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nis');
    if ($student_nis_lama === null) {
        echo '<div class="alert alert-danger"><small>Data siswa tidak ditemukan.</small></div>';
        exit;
    }

    // Jika NIS berubah, cek unik (exclude id_student yang sedang di-edit)
    if ($student_nis_lama !== $student_nis) {
        $stmt = $Conn->prepare("SELECT COUNT(*) FROM student WHERE student_nis = ? AND id_student <> ?");
        $stmt->bind_param("si", $student_nis, $id_student);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo '<div class="alert alert-danger"><small>NIS yang anda masukan sudah terdaftar</small></div>';
            exit;
        }
    }

    // Validasi & upload foto (jika ada), hapus foto lama bila sukses upload baru
    $student_foto = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_foto'); // default tetap yg lama
    $input_foto   = "Valid";

    if (!empty($_FILES['student_foto']['name'])) {
        $nama_gambar   = $_FILES['student_foto']['name'];
        $ukuran_gambar = $_FILES['student_foto']['size'];
        $tipe_gambar   = $_FILES['student_foto']['type'];
        $tmp_gambar    = $_FILES['student_foto']['tmp_name'];

        $ext       = pathinfo($nama_gambar, PATHINFO_EXTENSION);
        $nama_baru = generateRandomString(36);
        $baru_foto = $nama_baru.'.'.$ext;
        $dir_tujuan = "../../assets/img/Siswa/";
        $path_baru  = $dir_tujuan.$baru_foto;

        // Pastikan dir ada
        if (!is_dir($dir_tujuan)) {
            @mkdir($dir_tujuan, 0755, true);
        }

        // Validasi tipe & ukuran
        $tipe_valid = ["image/jpeg","image/jpg","image/png","image/gif"];
        if (in_array($tipe_gambar, $tipe_valid)) {
            if ($ukuran_gambar < 2000000) {
                if (!move_uploaded_file($tmp_gambar, $path_baru)) {
                    $input_foto = "Upload file gagal!";
                } else {
                    // Hapus foto lama jika ada
                    $foto_lama = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_foto');
                    if (!empty($foto_lama)) {
                        $path_foto_lama = $dir_tujuan.$foto_lama;
                        if (file_exists($path_foto_lama)) {
                            if (!@unlink($path_foto_lama)) {
                                // Tidak fatal untuk batalkan proses edit, tapi beri info
                                // Anda bisa ganti jadi fatal jika perlu
                            }
                        }
                    }
                    // Set nama foto baru untuk disimpan
                    $student_foto = $baru_foto;
                    $input_foto = "Valid";
                }
            } else {
                $input_foto = "File gambar maksimal 2MB!";
            }
        } else {
            $input_foto = "Tipe file tidak valid!";
        }
    }

    if ($input_foto !== "Valid") {
        echo '<div class="alert alert-danger"><small>'.$input_foto.'</small></div>';
        exit;
    }

    // JSON orang tua
    $parent_payload = [
        "nama"   => $nama_ortu,
        "kontak" => $kontak_ortu
    ];
    $student_parent = json_encode($parent_payload);

    // === UPDATE student (bukan INSERT) ===
    $sql = "UPDATE student SET
                student_nis           = ?,
                student_nisn          = ?,
                student_name          = ?,
                student_gender        = ?,
                place_of_birth        = ?,
                date_of_birth         = ?,
                student_contact       = ?,
                student_email         = ?,
                student_address       = ?,
                student_foto          = ?,
                student_parent        = ?,
                student_registered    = ?,
                student_status        = ?
            WHERE id_student = ?";

    $stmt = $Conn->prepare($sql);
    if (!$stmt) {
        echo '<div class="alert alert-danger"><small>Prepare gagal: '.htmlspecialchars($Conn->error).'</small></div>';
        exit;
    }

    /*
        Tipe bind:
        - id_organization_class : i (boleh NULL, tetap bind sebagai i; PHP null akan terkirim sebagai NULL)
        - 13 kolom string berikutnya : s
        - id_student : i
        => "isssssssssssssi" (1 i + 13 s + 1 i)
    */

    $stmt->bind_param(
        "sssssssssssssi",
        $student_nis,
        $student_nisn,
        $student_name,
        $student_gender,
        $place_of_birth,
        $date_of_birth,      // NULL jika kosong
        $student_contact,
        $student_email,
        $student_address,
        $student_foto,
        $student_parent,
        $student_registered,
        $student_status,
        $id_student
    );

    $Input = $stmt->execute();
    $err   = $stmt->error;
    $stmt->close();

    if ($Input) {
        $kategori_log  = "Siswa";
        $deskripsi_log = "Edit Siswa Berhasil";
        $InputLog = addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);

        if ($InputLog=="Success") {
            echo '<code class="text-success" id="NotifikasiEditBerhasil">Success</code>';
        } else {
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan Log</small></div>';
        }
    } else {
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data: '.htmlspecialchars($err).'</small></div>';
    }
?>
