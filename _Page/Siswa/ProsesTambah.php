<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '<div class="alert alert-danger"><small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></div>';
        exit;
    }

    //Validasi Form Required
    $required = ['student_nis','student_name','student_gender','student_registered','student_status'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }

    //Buat Variabel
    $student_nis        = validateAndSanitizeInput($_POST['student_nis']);
    $student_name       = validateAndSanitizeInput($_POST['student_name']);
    $student_gender     = validateAndSanitizeInput($_POST['student_gender']);
    $student_registered = validateAndSanitizeInput($_POST['student_registered']);
    $student_status     = validateAndSanitizeInput($_POST['student_status']);

    //Buat Variabel Yang Tidak Wajib
    $student_nisn           = $_POST['student_nisn'] ?? '';
    $id_organization_class  = !empty($_POST['id_organization_class']) ? (int)$_POST['id_organization_class'] : NULL;
    $place_of_birth         = $_POST['place_of_birth'] ?? '';
    $date_of_birth          = $_POST['date_of_birth'] ?? '';
    $student_contact        = $_POST['student_contact'] ?? '';
    $student_email          = $_POST['student_email'] ?? '';
    $student_address        = $_POST['student_address'] ?? '';
    $nama_ortu              = $_POST['nama_ortu'] ?? '';
    $kontak_ortu            = $_POST['kontak_ortu'] ?? '';

    //Validasi NIS harus unik
    $stmt = $Conn->prepare("SELECT COUNT(*) FROM student WHERE student_nis=?");
    $stmt->bind_param("s", $student_nis);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo '<div class="alert alert-danger"><small>NIS yang anda masukan sudah terdaftar</small></div>';
        exit;
    }

    //Validasi File Foto Jika Ada
    $student_foto = "";
    $input_foto = "Valid";
    if(!empty($_FILES['student_foto']['name'])){
        $nama_gambar    =$_FILES['student_foto']['name'];
        $ukuran_gambar  =$_FILES['student_foto']['size'];
        $tipe_gambar    =$_FILES['student_foto']['type'];
        $tmp_gambar     =$_FILES['student_foto']['tmp_name'];

        $ext            = pathinfo($nama_gambar, PATHINFO_EXTENSION);
        $nama_baru      =generateRandomString(36);
        $student_foto   =''.$nama_baru.'.'.$ext.'';
        $path           ="../../assets/img/Siswa/".$student_foto;

        if(in_array($tipe_gambar, ["image/jpeg","image/jpg","image/png","image/gif"])){
            if($ukuran_gambar < 2000000){
                if(!move_uploaded_file($tmp_gambar, $path)){
                    $input_foto = "Upload file gagal!";
                }else{
                    $input_foto = "Valid";
                }
            }else{
                $input_foto = "File gambar maksimal 2MB!";
            }
        }else{
            $input_foto = "Tipe file tidak valid!";
        }
    }

    if($input_foto!=="Valid"){
        echo '<div class="alert alert-danger"><small>'.$input_foto.'</small></div>';
        exit;
    }
    
    //Json Orang Tua
    $parent_payload=[
        "nama" => $nama_ortu,
        "kontak" => $kontak_ortu
    ];

    $student_parent=json_encode($parent_payload);
    
    // Menggunakan Prepared Statement
    $stmt = $Conn->prepare("INSERT INTO student (
    id_organization_class, 
    student_nis, 
    student_nisn, 
    student_name, 
    student_gender, 
    place_of_birth, 
    date_of_birth, 
    student_contact, 
    student_email, 
    student_address, 
    student_foto, 
    student_parent, 
    student_registered, 
    student_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssss", 
        $id_organization_class, 
        $student_nis, 
        $student_nisn, 
        $student_name, 
        $student_gender, 
        $place_of_birth, 
        $date_of_birth, 
        $student_contact, 
        $student_email, 
        $student_address, 
        $student_foto, 
        $student_parent, 
        $student_registered, 
        $student_status
    );
    $Input = $stmt->execute();
    $stmt->close();

    if($Input){
        $kategori_log="Siswa";
        $deskripsi_log="Input Siswa Berhasil";
        $InputLog=addLog($Conn, $SessionIdAccess, $now, $kategori_log, $deskripsi_log);
        if($InputLog=="Success"){
            echo '<code class="text-success" id="NotifikasiTambahBerhasil">Success</code>';
        }else{
            echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan Log</small></div>';
        }
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data</small></div>';
    }
?>