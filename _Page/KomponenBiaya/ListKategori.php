<?php
    //Koneksi
    include "../../_Config/Connection.php";

     $query = mysqli_query($Conn, "SELECT DISTINCT component_category FROM fee_component ORDER BY component_category ASC");
    while ($data = mysqli_fetch_array($query)) {
        $component_category= $data['component_category'];
        echo '<option value="'.$component_category.'">';
    }
?>