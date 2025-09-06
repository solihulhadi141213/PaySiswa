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
                <td colspan="6" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
                </td>
            </tr>
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
        ';
        exit;
    }

    //Sanitasi Variabel
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka nama kelas
    if(empty($_POST['id_organization_class'])){
        $label_kelas='-';
    }else{
        $level=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
        $kelas=GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
        $label_kelas="$level-$kelas";
    }
    //Hitung Jumlah Data
    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT id_student FROM student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar'"));

    //Jika Tidak Ada Data Kelas
    if(empty($jml_data)){
        echo '
            <tr>
                <td colspan="6" class="text-center">
                    <small class="text-danger">Tidak Ada Data Siswa Yang Ditampilkan</small>
                </td>
            </tr>
        ';
        exit;
    }

    $no=1;
    $qry_siswa = mysqli_query($Conn, "SELECT * FROM student WHERE id_organization_class='$id_organization_class' AND student_status='Terdaftar' ORDER BY student_name ASC");
    while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
        $id_student = $data_siswa['id_student'];
        $id_organization_class= $data_siswa['id_organization_class'];
        $student_name= $data_siswa['student_name'];
        $student_gender= $data_siswa['student_gender'];
        $student_registered= $data_siswa['student_registered'];
        $student_status= $data_siswa['student_status'];

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

        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$student_name.'</small></td>
                <td><small>'.$student_nis.'</small></td>
                <td><small>'.$gender_label.'</small></td>
                <td><small>'.$label_kelas.'</small></td>
            </tr>
        ';
        $no++;
    }
?>