<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '<option value="">Pilih Kelas</option>';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '<option value="">Pilih Kelas</option>';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Menampilkan Data Kelas
    echo '<option value="">Pilih Kelas</option>';
    $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_level, class_name FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_name ASC");
    while ($data_kelas = mysqli_fetch_array($query_kelas)) {
        $id_organization_class = $data_kelas['id_organization_class'];
        $class_name = $data_kelas['class_name'];
        $class_level = $data_kelas['class_level'];

        //Hitung Jumlah Siswa
        $jumlah_siswa = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));
        echo '<option value="'.$id_organization_class.'">'.$class_level.' - '.$class_name.' ('.$jumlah_siswa.' Orang)</option>';
    }
?>