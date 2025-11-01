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
            <script>
                $("#title_daftar_Siswa_per_kelas_1").html("");
                $("#title_daftar_Siswa_per_kelas_2").html("");
            </script>
        ';
        exit;
    }
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
        echo '
            <tr><td colspan="6" class="text-center"><small class="text-danger">ID Periode Akademik Tidak Boleh Kosong!</small></td></tr>
            <script>
                $("#title_daftar_Siswa_per_kelas_1").html("");
                $("#title_daftar_Siswa_per_kelas_2").html("");
            </script>
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
            <script>
                $("#title_daftar_Siswa_per_kelas_1").html("");
                $("#title_daftar_Siswa_per_kelas_2").html("");
            </script>
        ';
        exit;
    }
    $Result = $Qry->get_result();
    $Data = $Result->fetch_assoc();
    $Qry->close();

    //Buat Variabel
    $academic_period        = $Data['academic_period'];

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_organization_class FROM organization_class WHERE id_academic_period='$id_academic_period'"));

    if(empty($jml_data)){
        echo '
            <tr><td colspan="6" class="text-center"><small class="text-danger">Tidak Ada Daftar Kelas Untuk Periode Ini </small></td></tr>
            <script>
                $("#title_daftar_Siswa_per_kelas_1").html("");
                $("#title_daftar_Siswa_per_kelas_2").html("");
            </script>
        ';
        exit;
    }

    //Menampilkan Data Kelas
    $no_kelas=1;
    $total_siswa=0;
    $total_male=0;
    $total_female=0;

    //Looping Kelas
    $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_level, class_name FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_name ASC");
    while ($data_kelas = mysqli_fetch_array($query_kelas)) {
        $id_organization_class = $data_kelas['id_organization_class'];
        $class_level = $data_kelas['class_level'];
        $class_name = $data_kelas['class_name'];

        //Looping 'fee_by_student' distinct  'id_student'
        $male   = 0;
        $female = 0;
        $siswa = 0;
        $QueryFeeByStudent = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class'");
        while ($DataFeeByStudent = mysqli_fetch_array($QueryFeeByStudent)) {
            $id_student = $DataFeeByStudent['id_student'];

            //Buka Gender Siswa dari table 'student'
            $gender = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_gender');

            //Menghitung Jumlah Siswa By Gender
            if($gender=="Male"){
                $male   = $male + 1;
                $female = $female + 0;
            }else{
                $male   = $male + 0;
                $female = $female + 1;
            }
            $siswa = $siswa + 1;
        }

        //Menghitung Total
        $total_siswa    = $total_siswa + $siswa;
        $total_male     = $total_male + $male;
        $total_female   = $total_female + $female;

        //Tampilkan Baris Tabel Kelas Beserta Nilai-nilainya
        echo '
            <tr>
                <td align="center"><small>'.$no_kelas.'</small></td>
                <td align="left"><small>'.$class_level.' - '.$class_name.'</small></td>
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
    echo '
        <tr>
            <td colspan="2"><b><small>JUMLAH</small></b></td>
            <td align="center"><b><small>'.$total_male.'</small></b></td>
            <td align="center"><b><small>'.$total_female.'</small></b></td>
            <td align="center"><b><small>'.$total_siswa.'</small></b></td>
            <td align="center"></td>
        </tr>
    ';
    //Tampilkan Di Atas Tabel
    echo '
        <script>
            $("#title_daftar_Siswa_per_kelas_1").html("'.$class_level.' - '.$class_name.'");
            $("#title_daftar_Siswa_per_kelas_2").html("PERIODE '.$academic_period.'");
        </script>
    ';
?>