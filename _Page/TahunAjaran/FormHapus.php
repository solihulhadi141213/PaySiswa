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
    //Tangkap id_academic_period
    if(empty($_POST['id_academic_period'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Periode Akademik Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_academic_period=validateAndSanitizeInput($_POST['id_academic_period']);

    //Buka Data payment
    $Qry = $Conn->prepare("SELECT * FROM academic_period WHERE id_academic_period = ?");
    $Qry->bind_param("i", $id_academic_period);
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
        $academic_period        = $Data['academic_period'];
        $academic_period_start  = $Data['academic_period_start'];
        $academic_period_end    = $Data['academic_period_end'];
        $academic_period_status = $Data['academic_period_status'];

        //Routing Status
       if($academic_period_status==true){
            $label_status='<span class="badge badge-success"><i class="bi bi-check-circle"></i> Active</span>';
        }else{
            $label_status='<span class="badge badge-danger"><i class="bi bi-lock"></i> Locked</span>';
        }
        //Tampilkan Data
        echo '
            <input type="hidden" name="id_academic_period" value="'.$id_academic_period.'">
            <div class="row mb-2">
                <div class="col-4"><small>Tahun Akademik</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$academic_period.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Periode Awal</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$academic_period_start.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Periode Akhir</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$academic_period_end.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Status</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$label_status.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <h2>Penting!</h2>
                        <small>
                            Menghapus data periode akademik akan menghapus semua komponen database yang sudah diinput sebelumnya.<br>
                            <b>Apakah anda yakin tetap menghapusnya?</b>
                        </small>
                    </div>
                </div>
            </div>
        ';
    }
?>