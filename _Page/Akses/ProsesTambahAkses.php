<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // --- Validasi singkat ---
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }
    $required = ['nama_akses','kontak_akses','email_akses','password1','password2','akses'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    // Sanitasi input
    $nama_akses   = validateAndSanitizeInput($_POST['nama_akses']);
    $kontak_akses = validateAndSanitizeInput($_POST['kontak_akses']);
    $email_akses  = validateAndSanitizeInput($_POST['email_akses']);
    $password1    = validateAndSanitizeInput($_POST['password1']);
    $password2    = validateAndSanitizeInput($_POST['password2']);
    $akses        = intval($_POST['akses']); // id_access_group = INT

    // Validasi password sama
    if ($password1 !== $password2) {
        echo '<div class="alert alert-danger"><small>Password tidak sama!</small></div>';
        exit;
    }
    // Validasi panjang password
    if(strlen($password1) < 6 || strlen($password1) > 20 || !preg_match("/^[a-zA-Z0-9]*$/", $password1)){
        echo '<div class="alert alert-danger"><small>Password harus 6-20 karakter huruf/angka!</small></div>';
        exit;
    }

    // Hash password
    $password = password_hash($password1, PASSWORD_DEFAULT);

    // Upload gambar (opsional)
    $namabaru = "";
    if(!empty($_FILES['image_akses']['name'])){
        $nama_gambar=$_FILES['image_akses']['name'];
        $ukuran_gambar=$_FILES['image_akses']['size'];
        $tipe_gambar=$_FILES['image_akses']['type'];
        $tmp_gambar=$_FILES['image_akses']['tmp_name'];

        $ext = pathinfo($nama_gambar, PATHINFO_EXTENSION);
        $nama_baru=generateRandomString(36);
        $namabaru =''.$nama_baru.'.'.$ext.'';
        $path="../../assets/img/User/".$namabaru;

        if(in_array($tipe_gambar, ["image/jpeg","image/jpg","image/png","image/gif"])){
            if($ukuran_gambar < 2000000){
                if(!move_uploaded_file($tmp_gambar, $path)){
                    echo '<div class="alert alert-danger"><small>Upload file gagal!</small></div>';
                    exit;
                }
            }else{
                echo '<div class="alert alert-danger"><small>File gambar maksimal 2MB!</small></div>';
                exit;
            }
        }else{
            echo '<div class="alert alert-danger"><small>Tipe file tidak valid!</small></div>';
            exit;
        }
    }

    // --- Mulai Transaction ---
    $Conn->begin_transaction();

    try {
        // Insert ke access
        $stmt = $Conn->prepare("INSERT INTO access 
            (id_access_group, access_name, access_email, access_contact, access_password, access_foto, access_client) 
            VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("isssss", $akses, $nama_akses, $email_akses, $kontak_akses, $password, $namabaru);
        if(!$stmt->execute()){
            throw new Exception("Gagal insert ke tabel access");
        }
        $id_access = $stmt->insert_id; 
        $stmt->close();

        // Ambil semua fitur dari access_reference
        $stmt = $Conn->prepare("SELECT id_access_feature FROM access_reference WHERE id_access_group = ?");
        $stmt->bind_param("i", $akses);
        $stmt->execute();
        $result = $stmt->get_result();
        $features = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if(empty($features)){
            throw new Exception("Tidak ada fitur di Referensi untuk entitas/group ini!");
        }

        // Insert ke access_permission
        $stmt = $Conn->prepare("INSERT INTO access_permission (id_access, id_access_feature) VALUES (?, ?)");
        foreach($features as $f){
            $id_access_feature = $f['id_access_feature']; // UUID (varchar)
            $stmt->bind_param("is", $id_access, $id_access_feature);
            if(!$stmt->execute()){
                throw new Exception("Gagal insert access_permission");
            }
        }
        $stmt->close();

        // Commit jika semua sukses
        $Conn->commit();
        echo '<small class="text-success" id="NotifikasiTambahAksesBerhasil">Success</small>';

    } catch (Exception $e) {
        $Conn->rollback();
        echo '<div class="alert alert-danger"><small>'.$e->getMessage().'</small></div>';
    }
?>
