<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi dan Konfigurasi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    $academic_period = "";

    // Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <tr><td colspan="7" class="text-center"><small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small></td></tr>
            <script>
                $("#title_siswa_per_kelas").html("");
            </script>
        ';
        exit;
    }

    // Tangkap id_academic_period dan Sanitasi Input
    if (empty($_POST['id_academic_period'])) {
        echo '
            <tr><td colspan="7" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>
               $("#title_siswa_per_kelas").html("");
            </script>
        ';
        exit;
    }
    
    // Gunakan fungsi sanitasi yang sudah didefinisikan di GlobalFunction.php (asumsi validateAndSanitizeInput bekerja dengan baik)
    $id_academic_period = validateAndSanitizeInput($_POST['id_academic_period']);

    // Buka Informasi Periode Akdemik (Menggunakan Prepared Statement)
    $Qry = $Conn->prepare("SELECT academic_period FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
    if (!$Qry->execute()) {
        $error = $Conn->error;
        echo '
            <tr><td colspan="7" class="text-center"><small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small></td></tr>
            <script>
                $("#title_siswa_per_kelas").html("");
            </script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    if ($Result->num_rows === 0) {
        echo '
            <tr><td colspan="7" class="text-center"><small class="text-danger">Periode Akademik tidak ditemukan!</small></td></tr>
            <script>
               $("#title_siswa_per_kelas").html("");
            </script>
        ';
        exit;
    }
    $Data = $Result->fetch_assoc();
    $Qry->close();

    // Buat Variabel
    $academic_period = $Data['academic_period'];

    // Menghitung Jumlah Kelas
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    if (empty($jml_data)) {
        echo '
            <tr><td colspan="7" class="text-center"><small class="text-danger">Tidak Ada Daftar Kelas Untuk Periode Ini </small></td></tr>
            <script>
               $("#title_siswa_per_kelas").html("");
            </script>
        ';
        exit;
    }

    // Inisialisasi Nomor Urutan 'class_level'
    $no_level = 1;

    // Inisialisasi Jumlah total akhir
    $total_siswa    = 0;
    $total_male     = 0;
    $total_female   = 0;

    // Menampilkan 'class_level' dari tabel 'organization_class'
    $query_class_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
    
    while ($data_class_level = mysqli_fetch_assoc($query_class_level)) {
        $class_level = $data_class_level['class_level'];
        
        // Inisialisasi Subtotal per Level
        $subtotal_level_siswa   = 0;
        $subtotal_level_male    = 0;
        $subtotal_level_female  = 0;

        // Tampilkan Baris 'class_level' (placeholder untuk subtotal)
        $id_row_level = "level_row_" . $no_level;
        echo '
            <tr id="'.$id_row_level.'">
                <td class="bg bg-body-secondary"><small><b>'.$no_level.'</b></small></td>
                <td class="bg bg-body-secondary"><small><b>'.$class_level.'</b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
                <td class="bg bg-body-secondary subtotal_male_'.$no_level.'" align="center"><small><b>...</b></small></td>
                <td class="bg bg-body-secondary subtotal_female_'.$no_level.'" align="center"><small><b>...</b></small></td>
                <td class="bg bg-body-secondary subtotal_siswa_'.$no_level.'" align="center"><small><b>...</b></small></td>
                <td class="bg bg-body-secondary"><small><b></b></small></td>
            </tr>
        ';
        
        // Looping Kelas
        $no_kelas = 1;
        $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE id_academic_period='$id_academic_period' AND class_level='$class_level' ORDER BY class_name ASC");
        
        while ($data_kelas = mysqli_fetch_assoc($query_kelas)) {
            $id_organization_class  = $data_kelas['id_organization_class'];
            $class_name             = $data_kelas['class_name'];

            // --- OPTIMALISASI QUERY: Hitung Siswa, Male, Female dalam SATU QUERY per Kelas ---
            $QuerySiswa = "
                SELECT 
                    COUNT(DISTINCT t1.id_student) AS siswa,
                    SUM(CASE WHEN t2.student_gender = 'Male' THEN 1 ELSE 0 END) AS male,
                    SUM(CASE WHEN t2.student_gender = 'Female' THEN 1 ELSE 0 END) AS female
                FROM fee_by_student t1
                LEFT JOIN student t2 ON t1.id_student = t2.id_student
                WHERE t1.id_organization_class = '$id_organization_class'
            ";
            $ResultSiswa = mysqli_query($Conn, $QuerySiswa);
            $DataSiswa = mysqli_fetch_assoc($ResultSiswa);
            
            $siswa  = (int)$DataSiswa['siswa'];
            $male   = (int)$DataSiswa['male'];
            $female = (int)$DataSiswa['female'];

            // Menghitung Subtotal Level
            $subtotal_level_siswa   += $siswa;
            $subtotal_level_male    += $male;
            $subtotal_level_female  += $female;

            // Menghitung Total Akhir
            $total_siswa    += $siswa;
            $total_male     += $male;
            $total_female   += $female;

            // Tampilkan Baris 'class_name' Beserta Nilai-nilainya
            echo '
                <tr>
                    <td align="left"></td>
                    <td align="left"><small>'.$no_level.'. '.$no_kelas.'</small></td>
                    <td align="left"><small>'.$class_name.'</small></td>
                    <td align="center"><small>'.$male.'</small></td>
                    <td align="center"><small>'.$female.'</small></td>
                    <td align="center"><small>'.$siswa.'</small></td>
                    <td align="center">
                        <button type="button" class="btn btn-sm btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalDaftarSiswa" data-id1="'.$id_academic_period .'" data-id2="'.$id_organization_class .'">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </td>
                </tr>
            ';
            $no_kelas++;
        }

        // --- UPDATE SUBTOTAL LEVEL DENGAN JAVASCRIPT ---
        // Karena baris level sudah dicetak, kita update nilainya menggunakan JS
        echo '
            <script>
                $(".subtotal_male_'.$no_level.'").html("<small><b>'.$subtotal_level_male.'</b></small>");
                $(".subtotal_female_'.$no_level.'").html("<small><b>'.$subtotal_level_female.'</b></small>");
                $(".subtotal_siswa_'.$no_level.'").html("<small><b>'.$subtotal_level_siswa.'</b></small>");
            </script>
        ';

        $no_level++;
    }

    // Tampilkan Baris TOTAL AKHIR
    echo '
        <tr>
            <td colspan="3"><b><small>JUMLAH / TOTAL</small></b></td>
            <td align="center"><b><small>'.$total_male.'</small></b></td>
            <td align="center"><b><small>'.$total_female.'</small></b></td>
            <td align="center"><b><small>'.$total_siswa.'</small></b></td>
            <td align="center"></td>
        </tr>
    ';
    
    // Tampilkan Di Atas Tabel
    echo '
        <script>
            $("#title_siswa_per_kelas").html("<b>PERIODE :</b> <b class=\'text-primary underscore_doted\'>'.$academic_period.'</b>");
        </script>
    ';
?>