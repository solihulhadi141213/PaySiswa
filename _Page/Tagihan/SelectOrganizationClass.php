<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Keterangan Waktu
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    //Validasi id_payment
    if(empty($_POST['id_academic_period'])){
        echo '<option value="">Pilih</option>';
        exit;
    }
    $id_academic_period=$_POST['id_academic_period'];

    //Menampilkan Kelas Berdasarkan id_academic_period
    echo '<option value="">Pilih</option>';
    $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
    while ($data_level = mysqli_fetch_array($query_level)) {
        $class_level = $data_level['class_level'];
        echo '<optgroup label="'.$class_level.'">';

        //Tampilkan Kelas
        $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
        while ($data_kelas = mysqli_fetch_array($query_kelas)) {
            $id_organization_class_list = $data_kelas['id_organization_class'];
            $class_name = $data_kelas['class_name'];
            //hitung jumlah siswa
            $jumlah_siswa=mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE id_organization_class='$id_organization_class_list'"));
            echo '<option value="'.$id_organization_class_list.'">'.$class_level.'-'.$class_name.' ('.$jumlah_siswa.')</option>';
        }
        echo '</optgroup>';
    }

?>