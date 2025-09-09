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
        echo '<input type="hidden" name="id_academic_period" value="'.$id_academic_period.'">';
        if($academic_period_status==true){
            echo '
                <div class="row mb-3">
                    <div class="col-md-12 text-center">
                       <div class="alert alert-warning">
                            <h1><i class="bi bi-lock"></i></h1>
                            <small>
                                Dengan mengunci periode akademik ini maka semua data yang terhubung pada periode tersebut tidak bisa diubah/dihapus. 
                                Anda masih bisa membuka kunci dari tahun akademik tersebut setelahnya.<br>
                                <b>Apakah anda yakin akan mengunci periode akademik ini?</b>
                            </small>
                       </div>
                    </div>
                </div>
            ';
        }else{
            echo '
                <div class="row mb-3">
                    <div class="col-md-12 text-center">
                       <div class="alert alert-info">
                            <h1><i class="bi bi-unlock"></i></h1>
                            <small>
                                Dengan membuka kunci periode akademik ini maka semua data yang terhubung pada periode tersebut bisa kembali diubah/dihapus. 
                                Anda masih bisa mengunci data tahun akademik tersebut setelahnya.<br>
                                <b>Apakah anda yakin akan membuka kunci periode akademik ini?</b>
                            </small>
                       </div>
                    </div>
                </div>
            ';
        }
    }
?>