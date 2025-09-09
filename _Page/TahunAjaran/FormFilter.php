<?php
    //Koneksi
    include "../../_Config/Connection.php";

    //Jika kosong
    if(empty($_POST['KeywordBy'])){
        echo '<input type="text" name="keyword" id="keyword" class="form-control">';
    }else{

        //Periode awal
        if($_POST['KeywordBy']=="academic_period_start"){
            echo '<input type="date" name="keyword" id="keyword" class="form-control">';
        }else{

            //Periode akhir
            if($_POST['KeywordBy']=="academic_period_end"){
                echo '<input type="date" name="keyword" id="keyword" class="form-control">';
            }else{
                //Status
                if($_POST['KeywordBy']=="academic_period_status"){
                    echo '
                        <select name="keyword" id="keyword" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="1">Unlock</option>
                            <option value="0">Lock</option>
                        </select>
                    ';
                }else{
                    echo '<input type="text" name="keyword" id="keyword" class="form-control">';
                }
            }
        }
    }
?>