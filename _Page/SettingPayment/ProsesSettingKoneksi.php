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

    //Tangkap Data Dari Form
    if(empty($_POST['api_payment_url'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    URL Endpoint Payment gateway tidak boleh kosong!
                </small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['USER_KEY'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    USER KEY tidak boleh kosong!
                </small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['SECRET_KEY'])){
        echo '
            <div class="alert alert-danger">
                <small>
                    SECRET KEY tidak boleh kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabelnya
    $api_payment_url    = validateAndSanitizeInput($_POST['api_payment_url']);
    $USER_KEY           = validateAndSanitizeInput($_POST['USER_KEY']);
    $SECRET_KEY         = validateAndSanitizeInput($_POST['SECRET_KEY']);

    //Cek apakah sudah ada data pengaturan
    $id_setting_payment = GetDetailData($Conn,'setting_payment','id_setting_payment ','1','id_setting_payment');

    //Jika tidak ada lakukan insert
    if(empty($id_setting_payment)){
        $id_setting_payment=1;
        $stmt = $Conn->prepare("INSERT INTO setting_payment (id_setting_payment, api_payment_url, USER_KEY, SECRET_KEY) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_setting_payment, $api_payment_url, $USER_KEY, $SECRET_KEY);
        $Input = $stmt->execute();
        $stmt->close();
        if($Input){
            $validasi_proses="Success";
        }else{
            $validasi_proses="Terjadi kesalahan pada saat menyimpan data pengaturan koneksi payment gateway";
        }
    }else{

        //Jika sudah ada maka lakukan Update
        $id_setting_payment=1;
        $UpdateSetting = mysqli_query($Conn,"UPDATE setting_payment SET 
            api_payment_url='$api_payment_url',
            USER_KEY='$USER_KEY',
            SECRET_KEY='$SECRET_KEY'
        WHERE id_setting_payment='$id_setting_payment'") or die(mysqli_error($Conn)); 
        if($UpdateSetting){
            $validasi_proses="Success";
        }else{
            $validasi_proses="Terjadi kesalahan pada saat update data pengaturan koneksi payment gateway";
        }
    }

    //Validasi Proses
    if($validasi_proses!=="Success"){
        echo '
            <div class="alert alert-danger">
                <small>
                    '.$validasi_proses.'
                </small>
            </div>
        ';
    }else{
        echo '
            <div class="alert alert-success">
                <small id="NotifikasiSettingKoneksiBerhasil">Success</small>
            </div>
        ';
    }
?>