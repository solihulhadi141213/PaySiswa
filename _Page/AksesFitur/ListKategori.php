<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Tampilkan Data
    $query = mysqli_query($Conn, "SELECT DISTINCT feature_category FROM access_feature ORDER BY feature_category ASC");
    while ($data = mysqli_fetch_array($query)) {
        $feature_category= $data['feature_category'];
        echo '<option value="'.$feature_category.'">';
    }
?>