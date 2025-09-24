<?php
    //Koneksi dan session
    date_default_timezone_set('Asia/Jakarta');
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
    //Buka pengaturan payment gateway
    $api_payment_url=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','api_payment_url');
    $USER_KEY=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','USER_KEY');
    $SECRET_KEY=GetDetailData($Conn,'setting_payment','id_setting_payment ','1','SECRET_KEY');

    //Menampilkan Pengaturan
    echo '
        <div class="row mb-3">
            <div class="col-12">
                <label for="api_payment_url">
                    <small>ENDPOINT</small>
                </label>
                <input type="url" name="api_payment_url" id="api_payment_url" class="form-control" plceholder="https://" value="'.$api_payment_url.'">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="USER_KEY">
                    <small>USER KEY</small>
                </label>
                <input type="text" name="USER_KEY" id="USER_KEY" class="form-control" value="'.$USER_KEY.'">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="SECRET_KEY">
                    <small>SECRET KEY</small>
                </label>
                <input type="text" name="SECRET_KEY" id="SECRET_KEY" class="form-control" value="'.$SECRET_KEY.'">
            </div>
        </div>
    ';
?>