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
    echo '
        <tr>
            <td colspan="6">
                <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
            </td>
        </tr>
    ';
    //Looping Daftar Komponen
    $no = 1;
    $query = mysqli_query($Conn, "SELECT*FROM fee_component  ORDER BY id_fee_component ASC");
    while ($data = mysqli_fetch_array($query)) {
        $id_fee_component   = $data['id_fee_component'];
        $component_name     = $data['component_name'];
        $period_value       = $data['period_value'];
        $period_unit        = $data['period_unit'];
        $periode_start      = $data['periode_start'];
        $periode_end        = $data['periode_end'];
        $fee_nominal        = $data['fee_nominal'];
        
        //Format Rupiah
        $fee_nominal_format="Rp" . number_format($fee_nominal,0,',','.');

        //Check Apakkah komponen tersebut terpilih
        $cek_komponen = mysqli_num_rows(mysqli_query($Conn, "SELECT id_fee_by_class FROM fee_by_class WHERE id_organization_class='$id_organization_class' AND id_fee_component='$id_fee_component'"));
        if(empty($cek_komponen)){
            $label_cek_komponen="";
        }else{
            $label_cek_komponen="checked";
        }
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$period_value.' '.$period_unit.'</small></td>
                <td><small>'.date('d/m/Y', strtotime($periode_start)).' - '.date('d/m/Y', strtotime($periode_end)).'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
                <td>
                    <input type="checkbox" '.$label_cek_komponen.' class="form-check-input"  name="id_fee_component[]" value="'.$id_fee_component.'">
                </td>
            </tr>
        ';
        $no++;
    }
?>