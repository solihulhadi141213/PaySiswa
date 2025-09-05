<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($_POST['KeywordBy'])){
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }else{
        $KeywordBy=$_POST['KeywordBy'];
        if($KeywordBy=="datetime"){
            echo '<input type="date" name="keyword" id="keyword" class="form-control">';
        }else{
            if($KeywordBy=="Production"){
                echo '<select name="keyword" id="keyword" class="form-control">';
                echo '  <option value="false">False</option>';
                echo '  <option value="true">True</option>';
                echo '</select>';
            }else{
                if($KeywordBy=="email"){
                    echo '<input type="email" name="keyword" id="keyword" class="form-control">';
                }else{
                    echo '<input type="text" name="keyword" id="keyword" class="form-control">';
                }
            }
        }
    }
?>