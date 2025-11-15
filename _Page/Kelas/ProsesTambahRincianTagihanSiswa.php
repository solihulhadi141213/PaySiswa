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
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Validasi 'id_organization_class' tidak boleh kosong
    if (empty($_POST['id_organization_class'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Kelas Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Validasi 'id_student' tidak boleh kosong
    if (empty($_POST['id_student'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Siswa Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Validasi 'id_fee_component' tidak boleh kosong
    if (empty($_POST['id_fee_component'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Komponen Biaya Pendidikan Tidak Boleh Kosong!',
            'id_organization_class' => '',
            'id_student' => ''
        ]);
        exit;
    }

    //Buat Variabel Dan Sanitasi
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_student             = validateAndSanitizeInput($_POST['id_student']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);

    //Tangkap nominal dan diskon
    if(empty($_POST['fee_nominal'])){
        $fee_nominal    =0;
    }else{
        $fee_nominal    =$_POST['fee_nominal'];
        $fee_nominal    = str_replace('.', '', $fee_nominal);
    }
    if(empty($_POST['fee_discount'])){
        $fee_discount   =0;
    }else{
        $fee_discount   =$_POST['fee_discount'];
        $fee_discount   = str_replace('.', '', $fee_discount);
    }

    //Cek Apakah Data Sudah Ada
    $QryCek = $Conn->prepare("SELECT id_fee_by_student FROM fee_by_student WHERE id_organization_class = ? AND id_student = ? AND id_fee_component = ?");
    $QryCek->bind_param("iii", $id_organization_class, $id_student, $id_fee_component);
    if (!$QryCek->execute()) {
        $error=$Conn->error;
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'',
            'id_organization_class' => $id_organization_class,
            'id_student' => $id_student
        ]);
        exit;
    }
    $ResultCek = $QryCek->get_result();
    $DataCek = $ResultCek->fetch_assoc();
    $QryCek->close();

    //Proses Insert OR Update
    if(empty($DataCek['id_fee_by_student'])){
        //Insert data
        $stmt = $Conn->prepare("INSERT INTO fee_by_student (id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss",$id_organization_class, $id_student, $id_fee_component, $fee_nominal, $fee_discount);
        $Input = $stmt->execute();
        $stmt->close();

        if($Input){
            echo json_encode([
                'status' => 'success',
                'message' => 'Insert Data Tagihan Siswa Berhasil',
                'id_organization_class' => $id_organization_class,
                'id_student' => $id_student
            ]);
            exit;
        }else{
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada saat insert data tagihan siswa',
                'id_organization_class' => $id_organization_class,
                'id_student' => $id_student
            ]);
            exit;
        }
    }else{
        //Buat Variabel
        $id_fee_by_student  = $DataCek['id_fee_by_student'];
        
        // Update Data Query menggunakan prepared statement
        $Qry = $Conn->prepare("
            UPDATE fee_by_student 
            SET fee_nominal = ?, 
                fee_discount = ?
            WHERE id_fee_by_student = ?
        ");

        // Validasi prepare
        if (!$Qry) {
            die("Prepare failed: " . $Conn->error);
        }

        // Bind parameter
        // tipe data: s = string, i = integer, d = double
        // fee_nominal dan fee_discount biasanya angka → gunakan "s" bila diposting sebagai string
        $Qry->bind_param("ssi", $fee_nominal, $fee_discount, $id_fee_by_student);

        // Eksekusi query
        if ($Qry->execute()) {
            $response = [
                'status'  => 'success',
                'message' => 'Data berhasil diperbarui',
                'id_organization_class' => $id_organization_class,
                'id_student' => $id_student
            ];
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Gagal memperbarui data: ' . $Qry->error,
                'id_organization_class' => $id_organization_class,
                'id_student' => $id_student
            ];
        }

        // Tutup statement
        $Qry->close();

        // Output JSON (opsional)
        echo json_encode($response);
    }
?>  