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
        echo '
            <tr><td colspan="6" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>$("#title_daftar_Siswa_1").html("");$("#title_daftar_Siswa_2").html("");</script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="6" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>$("#title_daftar_Siswa_1").html("");$("#title_daftar_Siswa_2").html("");</script>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Informasi Periode Akdemik
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <tr><td colspan="6" class="text-center"><small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small></td></tr>
            <script>$("#title_daftar_Siswa_1").html("");$("#title_daftar_Siswa_2").html("");</script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Tangkap id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '
            <tr><td colspan="6" class="text-center"><small class="text-danger">Siilahkan Pilih Kelas Terlebih Dulu</small></td></tr>
        ';
        echo '<script>$("#title_daftar_Siswa_1").html("DAFTAR SISWA '.$label_kelas.'");$("#title_daftar_Siswa_2").html("PERIODE '.$academic_period.'");</script>';
        exit;
    }
    $id_organization_class  = $_POST['id_organization_class'];
    $level                  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $kelas                  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $label_kelas            = "$level-$kelas";

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'"));

    if(empty($jml_data)){
        echo '
            <tr><td colspan="6" class="text-center"><small class="text-danger">Tidak Ada Siswa Untuk Periode Ini </small></td></tr>
        ';
        echo '<script>$("#title_daftar_Siswa_1").html("DAFTAR SISWA '.$label_kelas.'");$("#title_daftar_Siswa_2").html("PERIODE '.$academic_period.'");</script>';
        exit;
    }

    //Menampilkan Data Siswa
    $no=1;
    $QuerySiswa = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_by_student ASC");
    while ($data_siswa = mysqli_fetch_array($QuerySiswa)) {
        $id_student         = $data_siswa['id_student'];
        
        //Buka Nama Siswa
        $student_name       = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');
        $student_nisn       = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_nisn');
        $student_gender     = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');
        $student_registered = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_registered');
        $student_status     = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_status');

        //Buka List Sswa
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$student_name.'</small></td>
                <td><small>'.$student_nisn.'</small></td>
                <td><small>'.$student_gender.'</small></td>
                <td><small>'.$student_registered.'</small></td>
                <td><small>'.$student_status.'</small></td>
            </tr>
        ';
        $no++;
    }
    echo '<script>$("#title_daftar_Siswa_1").html("DAFTAR SISWA '.$label_kelas.'");$("#title_daftar_Siswa_2").html("PERIODE '.$academic_period.'");</script>';

?>