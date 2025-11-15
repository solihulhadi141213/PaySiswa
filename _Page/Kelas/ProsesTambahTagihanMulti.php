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
            'status'                => 'error',
            'message'               => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
            'id_organization_class' => ''
        ]);
        exit;
    }

    //Validasi Form Required
    $required = ['id_organization_class','id_academic_period','id_fee_component'];
    foreach($required as $r){
        if(empty($_POST[$r])){
            echo json_encode([
                'status'                => 'error',
                'message'               => 'Field '.htmlspecialchars($r).' wajib diisi!',
                'id_organization_class' => ''
            ]);
            exit;
        }
    }

    //Buat Variabel
    $id_organization_class  = validateAndSanitizeInput($_POST['id_organization_class']);
    $id_academic_period     = validateAndSanitizeInput($_POST['id_academic_period']);
    $id_fee_component       = validateAndSanitizeInput($_POST['id_fee_component']);

    //Tangkap nominal dan diskon
    $fee_nominal = !empty($_POST['fee_nominal']) ? str_replace('.', '', $_POST['fee_nominal']) : 0;
    $fee_discount = !empty($_POST['fee_discount']) ? str_replace('.', '', $_POST['fee_discount']) : 0;

    //Hitung Jumlah Data Siswa
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar'"));

    //Mulai transaksi
    $Conn->autocommit(false);
    $all_success = true;

    $query = mysqli_query($Conn, "SELECT * FROM student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar'");
    while ($data = mysqli_fetch_array($query)) {
        $id_student = $data['id_student'];
        $id_organization_class= $data['id_organization_class'];

        //Cari id_fee_by_student
        $id_fee_by_student = CariFeeByStudent($id_organization_class,$id_student,$id_fee_component);

        if(empty($id_fee_by_student)){
            //Insert data baru
            $stmt = $Conn->prepare("INSERT INTO fee_by_student (id_organization_class, id_student, id_fee_component, fee_nominal, fee_discount) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiii", $id_organization_class, $id_student, $id_fee_component, $fee_nominal, $fee_discount);
            if(!$stmt->execute()){
                $all_success = false;
            }
            $stmt->close();
        }else{
            if(!empty($_POST['UpdateYangSudahAda'])){
                //Update data lama
                $stmt = $Conn->prepare("UPDATE fee_by_student SET fee_nominal=?, fee_discount=? WHERE id_fee_by_student=?");
                $stmt->bind_param("iii", $fee_nominal, $fee_discount, $id_fee_by_student);
                if(!$stmt->execute()){
                    $all_success = false;
                }
                $stmt->close();
            }
        }

        if(!$all_success){
            break; // hentikan looping jika ada kegagalan
        }
    }

    //Selesai proses → commit/rollback
    if($all_success){
        $Conn->commit();
        echo json_encode([
            'status'                => 'success',
            'message'               => 'Tambah Tagihan Berhasil',
            'id_organization_class' => $id_organization_class
        ]);
    }else{
        $Conn->rollback();
        echo json_encode([
            'status'                => 'error',
            'message'               => 'Terjadi kesalahan pada saat menyimpan data. Semua perubahan dibatalkan.',
            'id_organization_class' => $id_organization_class
        ]);
    }

    //Kembalikan autocommit
    $Conn->autocommit(true);
?>
