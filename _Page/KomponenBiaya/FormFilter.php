<?php
    //Koneksi
    include "../../_Config/Connection.php";

    if(empty($_POST['KeywordBy'])){
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }else{
        $keyword_by=$_POST['KeywordBy'];
        if($keyword_by=="period_value"){
            echo '<input type="number" name="keyword" id="keyword" class="form-control">';
        }else{
            if($keyword_by=="period_unit"){
                echo '
                    <select name="keyword" id="keyword" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="month">Bulanan</option>
                        <option value="year">Tahunan</option>
                        <option value="once">Sekali</option>
                    </select>
                ';
            }else{
                if($keyword_by=="fee_nominal"){
                    echo '<input type="number" name="keyword" id="keyword" class="form-control">';
                }else{
                    echo '<input type="text" name="keyword" id="keyword" class="form-control">';
                }
            }
        }
    }
?>