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
            <div class="alert alert-danger">
                <small>
                    Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                </small>
            </div>
        ';
        exit;
    }
    
    //Tangkap 'id_fee_component'
    if(empty($_POST['id_fee_component'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    ID Komponen Biaya Pendidikan Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Tangkap 'form_name'
    if(empty($_POST['form_name'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    Nama Form Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }
    

    //Buat Variabel
    $id_fee_component   = $_POST['id_fee_component'];
    $form_name          = $_POST['form_name'];

    //Buka 'component_category'
    $component_category  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_category');

    //Buka 'component_name'
    $component_name  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'component_name');

    //Buka 'component_name'
    $periode_month  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_month');

    //Buka 'periode_year'
    $periode_year  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_year');

    //Buka 'periode_start'
    $periode_start  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_start');

    //Buka 'periode_end'
    $periode_end  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'periode_end');

    //Buka 'periode_end'
    $fee_nominal  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component, 'fee_nominal');

    //Routing Selected
    if($component_category=="SPP"){
        $select_spp="selected";
        $select_non_spp="";
    }else{
        $select_spp="";
        $select_non_spp="selected";
    }

    //Form input 'id_fee_component'
    echo '<input type="hidden" name="id_fee_component" value="'.$id_fee_component.'">';
    echo '<input type="hidden" name="form_name" value="'.$form_name.'">';
    
    //Routing Form Berdasarkan 'form_name'

    # Nama Komponen
    if($form_name=="nama"){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <label for="form_value"><small>Nama Komponen Biaya</small></label>
                    <input type="text" name="form_value" id="form_value" class="form-control" value="'.$component_name.'">
                </div>
            </div>
        ';
    }

    # Kategori Komponen
    if($form_name=="kategori"){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                        <label for="form_value"><small>Kategori Komponen Biaya</small></label>
                        <select name="form_value" id="form_value" class="form-control">
                            <option value="">Pilih</option>
                            <option '.$select_spp.' value="SPP">SPP</option>
                            <option '.$select_non_spp.' value="Non-SPP">Non-SPP</option>
                        </select>
                </div>
            </div>
        ';
    }

    # Periode Bulan
    if($form_name=="bulan"){
        echo '<div class="row mb-2">';
        echo '  <div class="col-12">';
        echo '      <label for="form_value"><small>Periode Bulan</small></label>';
        echo '      <select name="form_value" id="form_value" class="form-control">';
        echo '          <option value="">Pilih</option>';
        //Loop 1-12
        for ($i=1; $i <=12 ; $i++) { 
            $nama_bulan = getNamaBulan($i);
            if($i==$periode_month){
                echo '<option selected value="'.$i.'">'.$nama_bulan.'</option>';
            }else{
                echo '<option value="'.$i.'">'.$nama_bulan.'</option>';
            }
        }
        echo '';
        echo '      </select>';
        echo '  </div>';
        echo '</div>';
    }

    if($form_name=="tahun"){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <label for="form_value"><small>Periode Tahun</small></label>
                    <input type="number" min="1990" step="1" name="form_value" id="form_value" class="form-control" value="'.$periode_year.'">
                </div>
            </div>
        ';
    }

    if($form_name=="tempo_awal"){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <label for="form_value"><small>Tempo Periode Awal</small></label>
                    <input type="date" name="form_value" id="form_value" class="form-control" value="'.$periode_start.'">
                </div>
            </div>
        ';
    }

    if($form_name=="tempo_akhir"){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <label for="form_value"><small>Tempo Periode Akhir</small></label>
                    <input type="date" name="form_value" id="form_value" class="form-control" value="'.$periode_end.'">
                </div>
            </div>
        ';
    }

    if($form_name=="nominal"){
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <label for="form_value"><small>Nominal Biaya</small></label>
                    <input type="textdate" name="form_value" id="form_value" class="form-control form-money" value="'.$fee_nominal.'">
                </div>
            </div>
        ';
    }


    //Enable Button
    echo '
        <script>
            $("#button_edit_parsial").prop("disabled", false);
            $("#form_value").focus();
        </script>
    ';

?>