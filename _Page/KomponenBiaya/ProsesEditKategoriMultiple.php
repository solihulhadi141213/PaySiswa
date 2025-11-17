<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Datetime Sekarang
    $now=date('Y-m-d H:i:s');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Akses Sudah Berakhir, Silahkan Login Ulang!',
        ]);
        exit;
    }

    //Validasi kategori_multiple
    if (empty($_POST['kategori_multiple'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Informasi Kategori Biaya Pendidikan Tidak Boleh Kosong!',
        ]);
        exit;
    }

    //Validasi id_fee_component
    if (empty($_POST['id_fee_component'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Komponen Biaya Pendidikan Tidak Boleh Kosong!',
        ]);
        exit;
    }

    //Buat Variabel
    $kategori_multiple = $_POST['kategori_multiple'];
    $id_fee_component = $_POST['id_fee_component'];

    //jumlah data
    $jumlah_data = count($id_fee_component);

    //Mulai Transaction
    mysqli_begin_transaction($Conn);

    try {

        //Prepared statement delete
        $stmt = mysqli_prepare($Conn, "UPDATE fee_component SET component_category = ? WHERE id_fee_component = ?");

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan statement");
        }

        $jumlah_berhasil = 0;

        foreach ($id_fee_component as $id_fee_component_list) {

            //Binding parameter
            mysqli_stmt_bind_param($stmt, 'si', $kategori_multiple, $id_fee_component_list);

            //Eksekusi
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Gagal Update Data Kategori Biaya Pendidikan ID $id_fee_component_list");
            }

            $jumlah_berhasil++;
        }

        //Jika semua berhasil → commit
        if ($jumlah_berhasil == $jumlah_data) {
            mysqli_commit($Conn);
            echo json_encode([
                'status' => 'success',
                'message' => 'Update Kategori Komponen Biaya Pendidikan Berhasil!',
            ]);
            exit;
        } else {
            throw new Exception("Jumlah berhasil tidak sesuai jumlah data");
        }

    } catch (Exception $e) {

        //Rollback jika ada 1 error sekalipun
        mysqli_rollback($Conn);

        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada saat update kategori biaya pendidikan'
        ]);
        exit;
    }
?>
