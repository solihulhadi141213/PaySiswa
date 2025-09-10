<?php
    // Koneksi & util
    include "../../_Config/Connection.php";
    
    if(!empty($_POST['id_academic_period'])){
        $id_academic_period=$_POST['id_academic_period'];
        //Tampilkan Level
        $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY class_level ASC");
        while ($data_level = mysqli_fetch_array($query_level)) {
            $class_level = $data_level['class_level'];
            echo '<optgroup label="'.$class_level.'">';

            //Tampilkan Kelas
            $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' AND id_academic_period='$id_academic_period' ORDER BY class_name ASC");
            while ($data_kelas = mysqli_fetch_array($query_kelas)) {
                $id_organization_class_list = $data_kelas['id_organization_class'];
                $class_name = $data_kelas['class_name'];
                echo '<option value="'.$id_organization_class_list.'">'.$class_level.'-'.$class_name.'</option>';
            }
            echo '</optgroup>';
        }
    }
?>