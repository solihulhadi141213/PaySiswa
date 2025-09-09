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

    //Validasi Form Required
    $required = ['periode_tujuan','periode_asal'];
        foreach($required as $r){
        if(empty($_POST[$r])){
            echo '<div class="alert alert-danger"><small>Field '.htmlspecialchars($r).' wajib diisi!</small></div>';
            exit;
        }
    }
    
    //Buat Variabel
    $periode_asal       = validateAndSanitizeInput($_POST['periode_asal']);
    $periode_tujuan     = validateAndSanitizeInput($_POST['periode_tujuan']);

    //Hitung Jumlah data periode_asal
    $JumlahSumber=mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_component FROM fee_component WHERE id_academic_period='$periode_asal'"));

    //Looping Perode asal
    $validasi_berhasil=0;
    $query = mysqli_query($Conn, "SELECT*FROM fee_component WHERE id_academic_period='$periode_asal'");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_component   = $data['id_fee_component'];
        $component_name     = $data['component_name'];
        $component_category = $data['component_category'];
        $periode_month      = $data['periode_month'];
        $periode_year       = $data['periode_year'];
        $periode_start      = $data['periode_start'];
        $periode_end        = $data['periode_end'];
        $fee_nominal        = $data['fee_nominal'];

        //tambahkan ke periode tujuan
        $stmt = $Conn->prepare("INSERT INTO fee_component (
            id_academic_period, 
            component_name, 
            component_category, 
            periode_month, 
            periode_year, 
            periode_start, 
            periode_end,
            fee_nominal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", 
            $periode_tujuan, 
            $component_name, 
            $component_category, 
            $periode_month, 
            $periode_year, 
            $periode_start, 
            $periode_end,
            $fee_nominal
        );
        $Input = $stmt->execute();
        $stmt->close();

        if($Input){
            $validasi_berhasil=$validasi_berhasil+1;
        }else{
            $validasi_berhasil=$validasi_berhasil+0;
        }
    }

    //Validasi Proses
    if($validasi_berhasil==$JumlahSumber){
        echo '<code class="text-success" id="NotifikasiCopyBerhasil">Success</code>';
    }else{
        echo '<div class="alert alert-danger"><small>Terjadi kesalahan pada saat menyimpan data</small></div>';
    }
?>