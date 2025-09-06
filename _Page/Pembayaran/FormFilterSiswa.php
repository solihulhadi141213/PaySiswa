<?php
    //Koneksi
    include "../../_Config/Connection.php";

    if(empty($_POST['keyword_by_siswa'])){
        echo '<input type="text" name="keyword_siswa" id="keyword_siswa" class="form-control" placeholder="Kata Kunci">';
    }else{
        $keyword_by_siswa=$_POST['keyword_by_siswa'];
        if($keyword_by_siswa=="id_organization_class"){
            echo '<select name="keyword_siswa" id="keyword_siswa" class="form-control">';
            echo '  <option value="">Pilih</option>';
            //Tampilkan Level
            $query_level = mysqli_query($Conn, "SELECT DISTINCT class_level FROM organization_class  ORDER BY class_level ASC");
            while ($data_level = mysqli_fetch_array($query_level)) {
                $class_level = $data_level['class_level'];
                echo '<optgroup label="'.$class_level.'">';

                //Tampilkan Kelas
                $query_kelas = mysqli_query($Conn, "SELECT id_organization_class, class_name FROM organization_class WHERE class_level='$class_level' ORDER BY class_name ASC");
                while ($data_kelas = mysqli_fetch_array($query_kelas)) {
                    $id_organization_class = $data_kelas['id_organization_class'];
                    $class_name = $data_kelas['class_name'];
                    echo '<option value="'.$id_organization_class.'">'.$class_level.'-'.$class_name.'</option>';
                }
                echo '</optgroup>';
            }
            echo '</select>';
        }else{
            echo '<input type="text" name="keyword_siswa" id="keyword_siswa" class="form-control" placeholder="Kata Kunci">';
        }
    }
?>