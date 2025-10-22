<?php
    //koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    //Tangkap id_organization_class
    if(empty($_POST['id_organization_class'])){
        echo '<option value="">Pilih</option>';
    }else{
        echo '<option value="">Pilih</option>';
        $id_organization_class  =   $_POST['id_organization_class'];

        //Tampilkan id_fee_component secara distinct
        $qry_siswa = mysqli_query($Conn, "SELECT DISTINCT id_fee_component FROM fee_by_student WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
        while ($data_siswa = mysqli_fetch_array($qry_siswa)) {
            $id_fee_component = $data_siswa['id_fee_component'];

            //Buka Nama Komponen
            $component_name = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');
            echo '<option value="'.$id_fee_component.'">'.$component_name.'</option>';
        }
    }
?>