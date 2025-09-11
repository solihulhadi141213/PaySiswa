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

    //Buka Detail Informasi Kelas
    $id_academic_period  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'id_academic_period');
    $class_level  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_level');
    $class_name  = GetDetailData($Conn, 'organization_class', 'id_organization_class', $id_organization_class, 'class_name');
    
    //Detail Periode Akademik
    $academic_period  = GetDetailData($Conn, 'academic_period', 'id_academic_period', $id_academic_period, 'academic_period');
    
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
            echo '
                <tr>
                    <td colspan="6" class="text-center">
                        <small class="text-danger">'.$error.'</small>
                    </td>
                </tr>
            ';
            exit;
        }
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $component_name     = $Data['component_name'] ?? '-';
        $component_category = $Data['component_category'] ?? '-';
        $periode_month      = $Data['periode_month'];
        $periode_year       = $Data['periode_year'];
        $periode_start      = $Data['periode_start'] ?? '-';
        $periode_end        = $Data['periode_end'] ?? '-';
        $fee_nominal        = $Data['fee_nominal'] ?? '-';
        
        //Format Rupiah
        $fee_nominal_format="Rp " . number_format($fee_nominal,0,',','.');
        
        //Nama Bulan
        $nama_bulan=getNamaBulan($periode_month);

        //Routing Kategori
        if($component_category=="SPP"){
            $label_spp='<span class="badge bg-primary">SPP</span>';
        }else{
            $label_spp='<span class="badge bg-success">Non-SPP</span>';
        }
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td><small>'.$component_name.'</small></td>
                <td><small>'.$label_spp.'</small></td>
                <td><small>'.$nama_bulan.'</small></td>
                <td><small>'.$periode_year.'</small></td>
                <td><small>'.$fee_nominal_format.'</small></td>
            </tr>
        ';
        $no++;
    }
?>
<script>
    var academic_period = "<?php echo $academic_period; ?>";
    var class_level     = "<?php echo $class_level; ?>";
    var class_name      = "<?php echo $class_name; ?>";
    $('#TitleListKomponenBiaya').html(`<div class="alert alert-info">Berikut ini adalah daftar standar biaya pendidikan yang berlaku di <b>${class_level} (${class_name})</b> pada periode <b>${academic_period}</b></div>`);
</script>