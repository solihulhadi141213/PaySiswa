<?php
    include "../../_Config/Connection.php";

    if(empty($_POST['id_academic_period'])){
        echo '<option value="">Pilih</option>';
    }else{
        $id_academic_period = $_POST['id_academic_period'];

        echo '<option value="">Pilih</option>';
        //Loop 'fee_component '
        $qry = mysqli_query($Conn, "SELECT * FROM fee_component WHERE id_academic_period='$id_academic_period' ORDER BY id_fee_component ASC");
        while ($data = mysqli_fetch_array($qry)) {
            $id_fee_component       = $data['id_fee_component'];
            $component_name         = $data['component_name'];
            echo '      <option value="'.$id_fee_component.'">'.$component_name.'</option>';
        }
    }
?>