<?php
    //Koneksi dan Functiion
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    
    //Inisialisasi Form
    echo '<option value="">Pilih Komponen</option>';
    
    //Tangkap id_organization_class
    if(!empty($_POST['id_organization_class'])){
        $id_organization_class = $_POST['id_organization_class'];

        //Menampilkan komponen biaya siswa
         $query = mysqli_query($Conn, "SELECT id_fee_by_class, id_fee_component FROM fee_by_class WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
        while ($data = mysqli_fetch_array($query)) {
            $id_fee_by_class            = $data['id_fee_by_class'];
            $id_fee_component           = $data['id_fee_component'];

            //Buka Data Komponen
            $Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
            $Qry->bind_param("i", $id_fee_component);
            if (!$Qry->execute()) {
                $error=$Conn->error;
                echo '<option value="">'.$error.'</option>';
                exit;
            }
            $Result = $Qry->get_result();
            $Data = $Result->fetch_assoc();
            $Qry->close();

            //Buat Variabel
            $component_name     = $Data['component_name'] ?? '';
            $fee_nominal        = $Data['fee_nominal'] ?? '0';
            echo '<option value="'.$id_fee_component.'" nominal="'.$fee_nominal.'">'.$component_name.'</option>';
        }
    }
?>