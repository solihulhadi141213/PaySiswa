<?php
    //Koneksi
    include "../../_Config/Connection.php";

    if(empty($_POST['keyword_by_komponen'])){
        echo '<input type="text" name="keyword_komponen" id="keyword_komponen" class="form-control">';
    }else{
        $keyword_by=$_POST['keyword_by_komponen'];
        if($keyword_by=="periode_start"||$keyword_by=="periode_end"){
            echo '<input type="date" name="keyword_komponen" id="keyword_komponen" class="form-control">';
        }else{
            if($keyword_by=="component_category"){
                echo '<select name="keyword_komponen" id="keyword_komponen" class="form-control" required>';
                echo '  <option value="">Pilih</option>';
                        $query = mysqli_query($Conn, "SELECT DISTINCT component_category FROM fee_component ORDER BY component_category ASC");
                        while ($data = mysqli_fetch_array($query)) {
                            $component_category= $data['component_category'];
                            echo '<option value="'.$component_category.'">'.$component_category.'</option>';
                        }
                echo '</select>';
            }else{
                if($keyword_by=="fee_nominal"){
                    echo '<input type="number" name="keyword_komponen" id="keyword_komponen" class="form-control">';
                }else{
                    echo '<input type="text" name="keyword_komponen" id="keyword_komponen" class="form-control">';
                }
            }
        }
    }
?>