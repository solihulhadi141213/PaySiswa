<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($_POST['KeywordBy'])){
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }else{
        $KeywordBy=$_POST['KeywordBy'];
        if($KeywordBy=="id_access"){
            echo '<select name="keyword" id="keyword" class="form-control">';
            echo '  <option value="">Pilih</option>';
            $query = mysqli_query($Conn, "SELECT id_access, access_name FROM access ORDER BY access_name ASC");
            while ($data = mysqli_fetch_array($query)) {
                $id_access= $data['id_access'];
                $access_name= $data['access_name'];
                echo '<option value="'.$id_access.'">'.$access_name.'</option>';
            }
            echo '</select>';
        }else{
            if($KeywordBy=="log_datetime"){
                echo '<input type="date" name="keyword" id="keyword" class="form-control">';
            }else{
                if($KeywordBy=="log_category"){
                    echo '<select name="keyword" id="keyword" class="form-control">';
                    echo '  <option value="">Pilih</option>';
                    $query = mysqli_query($Conn, "SELECT DISTINCT log_category FROM access_log  ORDER BY log_category ASC");
                    while ($data = mysqli_fetch_array($query)) {
                        $log_category= $data['log_category'];
                        echo '<option value="'.$log_category.'">'.$log_category.'</option>';
                    }
                    echo '</select>';
                }else{
                    echo '<input type="text" name="keyword" id="keyword" class="form-control">';
                }
            }
        }
    }
?>