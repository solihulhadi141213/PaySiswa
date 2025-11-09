<?php
    header('Content-Type: application/json');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    date_default_timezone_set("Asia/Jakarta");

    $search = isset($_POST['search']) ? mysqli_real_escape_string($Conn, $_POST['search']) : '';

    $result = [];

    if(empty($search)){
        $qry_siswa = mysqli_query($Conn, "
            SELECT id_student, student_nis, student_name 
            FROM student 
            ORDER BY student_name ASC 
            LIMIT 10
        ");
    } else {
        $qry_siswa = mysqli_query($Conn, "
            SELECT id_student, student_nis, student_name 
            FROM student 
            WHERE student_name LIKE '%$search%' 
            OR student_nis LIKE '%$search%' 
            ORDER BY student_name ASC 
            LIMIT 10
        ");
    }

    while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
        $result[] = [
            'id'   => $data_siswa['id_student'],
            'text' => $data_siswa['student_nis'].' - '.$data_siswa['student_name']
        ];
    }

    echo json_encode($result);
?>