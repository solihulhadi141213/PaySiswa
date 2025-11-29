<?php
    include "../../_Config/Connection.php";

    if(empty($_POST['id_academic_period'])){
        echo '<option value="">Pilih</option>';
    }else{
        $id_academic_period = $_POST['id_academic_period'];

        echo '<option value="">Pilih</option>';
        //Loop 'academic_period'
        $qry = mysqli_query($Conn, "SELECT * FROM organization_class WHERE id_academic_period='$id_academic_period' ORDER BY id_organization_class ASC");
        while ($data = mysqli_fetch_array($qry)) {
            $id_organization_class  = $data['id_organization_class'];
            $class_level            = $data['class_level'];
            $class_name             = $data['class_name'];
            echo '      <option value="'.$id_organization_class.'">'.$class_level.' | '.$class_name.'</option>';
        }
    }
?>