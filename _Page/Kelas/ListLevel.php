<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Tampilkan Data
    $query = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class ORDER BY class_level ASC");
    while ($data = mysqli_fetch_array($query)) {
        $class_level= $data['class_level'];
        echo '<option value="'.$class_level.'">';
    }
?>