<?php
    include "../../_Config/Connection.php";
    if(empty($_POST['KeywordBy'])){
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }else{
        $keyword_by=$_POST['KeywordBy'];
        if($keyword_by=="id_access_group"){
            echo '<select type="text" name="keyword" id="keyword" class="form-control">';
            echo '  <option value="">Pilih</option>';
            $query = mysqli_query($Conn, "SELECT id_access_group, group_name FROM access_group ORDER BY group_name ASC");
            while ($data = mysqli_fetch_array($query)) {
                $id_access_group= $data['id_access_group'];
                $group_name= $data['group_name'];
                echo '  <option value="'.$id_access_group.'">'.$group_name.'</option>';
            }
            echo '</select>';
        }else{
            echo '<input type="text" name="keyword" id="keyword" class="form-control">';
        }
    }
?>