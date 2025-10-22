<?php
    //koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Tangkap id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '<option value="">Pilih</option>';
    }else{
        echo '<option value="">Pilih</option>';
        $id_organization_class  =   $_POST['id_organization_class'];

        //Menampilkan daftar siswa secara distinct dari fee_by_student
        $qry_siswa = mysqli_query($Conn, "SELECT DISTINCT id_student FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_student ASC");
        while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
            $id_student = $data_siswa['id_student'];

            //Buka Nama Siswa dari tabel student
            $student_name = GetDetailData($Conn, 'student', 'id_student', $id_student, 'student_name');

            //Tampilkan selct option
            echo '<option value="'.$id_student.'">'.$student_name.'</option>';
        }
    }
?>