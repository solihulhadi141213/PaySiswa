<?php
    include "../../_Config/Connection.php";
    if(empty($_POST['keyword_by'])){
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }else{
        $keyword_by=$_POST['keyword_by'];
        if($keyword_by=="datetime_creat"||$keyword_by=="datetime_update"){
            echo '<input type="date" name="keyword" id="keyword" class="form-control">';
        }else{
            if($keyword_by=="kategori"){
                echo '<select name="keyword" id="keyword" class="form-control">';
                echo '  <option value="">Pilih</option>';
                $query = mysqli_query($Conn, "SELECT DISTINCT kategori FROM help ORDER BY kategori ASC");
                while ($data = mysqli_fetch_array($query)) {
                    if(!empty($data['kategori'])){
                        $kategori= $data['kategori'];
                        echo '  <option value="'.$kategori.'">'.$kategori.'</option>';
                    }
                }
                echo '</select>';
            }else{
                if($keyword_by=="status"){
                    echo '<select name="keyword" id="keyword" class="form-control">';
                    echo '  <option value="">Pilih</option>';
                    $query = mysqli_query($Conn, "SELECT DISTINCT status FROM help ORDER BY status ASC");
                    while ($data = mysqli_fetch_array($query)) {
                        if(!empty($data['status'])){
                            $status= $data['status'];
                            echo '  <option value="'.$status.'">'.$status.'</option>';
                        }
                    }
                    echo '</select>';
                }else{
                    echo '<input type="text" name="keyword" id="keyword" class="form-control">';
                }
            }
        }
    }
?>