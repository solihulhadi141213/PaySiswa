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
    //Tangkap id_organization_class
    if(empty($_POST['id_fee_component'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    Tidak Ada Komponen Biaya Pendidikan Yang Dipilih!
                </small>
            </div>
        ';
        exit;
    }
    
    //Apabila tidak ada yang dipilih
    if(empty(count($_POST['id_fee_component']))){
         echo '
            <div class="alert alert-danger">
                <small>
                    Tidak Ada Komponen Biaya Pendidikan Yang Dipilih!
                </small>
            </div>
        ';
        exit;
    }

    //Buat Variabel
    $id_fee_component=$_POST['id_fee_component'];

    //Tampilkan Data
    echo '<small><b>Komponen Biaya Yang Dipilih : </b></small><br>';
    echo '<div class="row">';
    echo '  <div class="col-12 mt-3 mb-3" style="overflow-y:auto; height:200px;">';
        //looping data
        echo '<ol>';
        foreach ($id_fee_component as $id_fee_component_list) {
            //Buka nama akomponen
            $component_name  = GetDetailData($Conn, 'fee_component', 'id_fee_component', $id_fee_component_list, 'component_name');
            echo '
                <li>
                    <small>
                    '.$component_name.'
                    <input type="hidden" name="id_fee_component[]" value="'.$id_fee_component_list.'">
                    </small>
                </li>
            ';
        }
        echo '</ol>';
    echo '  </div>';
    echo '</div>';
    echo '
        <div class="row mb-2">
           <div class="col-12">
                <label for="tahun_multiple"><small>Periode Tahun</small></label>
                <input type="number" min="2000" step="1" class="form-control" name="tahun_multiple" id="tahun_multiple">
           </div>
        </div>
        <script>
            $("#button_edit_tahun_multiple").prop("disabled", false);
        </script>
    ';

?>