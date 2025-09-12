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
    //Tangkap id_access_log
    if(empty($_POST['id_access_log'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Aktivitas Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_access_log=validateAndSanitizeInput($_POST['id_access_log']);

    //Buka Data access_log
    $Qry = $Conn->prepare("SELECT * FROM access_log WHERE id_access_log = ?");
    $Qry->bind_param("i", $id_access_log);
    if (!$Qry->execute()) {
        $error=$Conn->error;
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan pada saat membuka data dari database!<br>Keterangan : '.$error.'</small>
            </div>
        ';
    }else{
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
        $Qry->close();

        //Buat Variabel
        $id_access          = $Data['id_access'];
        $log_datetime       = $Data['log_datetime'];
        $log_category       = $Data['log_category'];
        $log_description    = $Data['log_description'];
       
        //Buka User Akses
        $access_name=GetDetailData($Conn, 'access', 'id_access', $id_access, 'access_name');

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_access_log" value="'.$id_access_log.'">
        ';
        echo '
            <div class="row mb-2">
                <div class="col-4"><small>Nama User</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$access_name.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Tanggal/Jam</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.date('d/m/Y H:i:s', strtotime($log_datetime)).'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kategori</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$log_category.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Deskripsi</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$log_description.'</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <small>
                            <b>Apakah anda yakin akan menghapus data tersebut?</b>
                        </small>
                    </div>
                </div>
            </div>
        ';
    }
?>