<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Zona Waktu
    date_default_timezone_set("Asia/Jakarta");

    //Session Akses
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="5" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
            <script>
                $("#title_siswa_aktual").html("");
            </script>
        ';
        exit;
    }

    //Validasi id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    ID Kelas Tidak Boleh Kosong!
                </small>
            </div>
            <script>
                $("#title_siswa_aktual").html("");
            </script>
        ';
        exit;
    }

    //Sanitasi Variabel
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka nama kelas
    $level              = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $kelas              = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    $label_kelas        = "$level-$kelas";

    //Buka Periode Akademik
    $id_academic_period = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $academic_period    = GetDetailData($Conn, 'academic_period ', 'id_academic_period', $id_academic_period, 'academic_period');

    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE id_organization_class='$id_organization_class'"));

    //Jika Tidak Ada Data Kelas
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="5" class="text-center">
                    <small class="text-danger">Tidak Ada Data Siswa Yang Ditampilkan</small>
                </td>
            </tr>
        ';
    }else{

        $no=1;
        $qry_siswa = mysqli_query($Conn, "SELECT * FROM student WHERE id_organization_class='$id_organization_class' ORDER BY student_name ASC");
        while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
            $id_student = $data_siswa['id_student'];
            $student_nis = $data_siswa['student_nis'];
            $student_name = $data_siswa['student_name'];
            $student_gender = $data_siswa['student_gender'];
            $student_status = $data_siswa['student_status'];

            //NIS
            if(empty($data_siswa['student_nis'])){
                $student_nis='-';
            }else{
                $student_nis=$data_siswa['student_nis'];
            }

            //Routing Gender
            if($student_gender=="Male"){
                $gender_label='L';
            }else{
                $gender_label='P';
            }

            //Routing Status Siswa
            if($student_status=="Terdaftar"){
                $label_status='<span class="badge badge-success">Terdaftar</span>';
            }else{
                if($student_status=="Lulus"){
                    $label_status='<span class="badge badge-warning">Lulus</span>';
                }else{
                    $label_status='<span class="badge badge-danger">Keluar</span>';
                }
            }

            echo '
                <tr>
                    <td><small>'.$no.'</small></td>
                    <td><small>'.$student_name.'</small></td>
                    <td><small>'.$student_nis.'</small></td>
                    <td><small>'.$gender_label.'</small></td>
                    <td><small>'.$label_status.'</small></td>
                </tr>
            ';
            $no++;
        }
    }
    $title_siswa_aktual = '
        <div class="row mb-2">
            <div class="col-5"><small>Periode Akademik</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$academic_period.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Jenjang / Level</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$level.'</small></div>
        </div>
        <div class="row mb-2">
            <div class="col-5"><small>Kelas / Rombel</small></div>
            <div class="col-1"><small>:</small></div>
            <div class="col-6"><small class="text text-grayish">'.$kelas.'</small></div>
        </div>
    ';
    echo '
        <script>
            $("#title_siswa_aktual").html(' . json_encode($title_siswa_aktual) . ');
        </script>
    ';
?>