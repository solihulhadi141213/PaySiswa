<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Validasi Sesi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <tr>
                <td colspan="6" class="text-center">
                    <small class="text-danger">Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }
    //Tangkap id_organization_class
    if(empty($_POST['id_organization_class'])){
         echo '
            <tr>
                <td colspan="6" class="text-center">
                    <small class="text-danger"> ID Kelas Tidak Boleh Kosong!</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Buat variabel
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);
    
    //Looping Daftar Komponen
    $no = 1;
    $query = mysqli_query($Conn, "SELECT*FROM fee_by_class WHERE id_organization_class='$id_organization_class' ORDER BY id_fee_component ASC");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_by_class            = $data['id_fee_by_class'];
        $id_organization_class      = $data['id_organization_class'];
        $id_fee_component           = $data['id_fee_component'];

        //Buka Data Komponen
        $Qry = $Conn->prepare("SELECT * FROM fee_component WHERE id_fee_component = ?");
        $Qry->bind_param("i", $id_fee_component);
        if (!$Qry->execute()) {
            $error=$Conn->error;
            $component_name     =$Conn->error;
            $component_category =$Conn->error;
            $periode_start      =$Conn->error;
            $periode_end        =$Conn->error;
            $fee_nominal        =$Conn->error;
        }else{
            $Result = $Qry->get_result();
            $Data = $Result->fetch_assoc();
            $Qry->close();

            //Buat Variabel
            $component_name     =$Data['component_name'] ?? '-';
            $component_category =$Data['component_category'] ?? '-';
            $periode_start      =$Data['periode_start'] ?? '-';
            $periode_end        =$Data['periode_end'] ?? '-';
            $fee_nominal        =$Data['fee_nominal'] ?? '-';
            
            //Format Rupiah
            $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');
       
        }

        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$component_category.'</small></td>
                <td><small>'.date('d/m/Y', strtotime($periode_start)).' - '.date('d/m/Y', strtotime($periode_end)).'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
            </tr>
        ';
        $no++;
    }
?>