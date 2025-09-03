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
    if(empty($_POST['id_organization_class'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Kelas Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_organization_class=validateAndSanitizeInput($_POST['id_organization_class']);

    //Buka Data access
    $Qry = $Conn->prepare("SELECT * FROM organization_class WHERE id_organization_class = ?");
    $Qry->bind_param("i", $id_organization_class);
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
        $id_organization_class  =$Data['id_organization_class'];
        $class_level            =$Data['class_level'];
        $class_name             =$Data['class_name'];

        //Tampilkan Data
        echo '
            <input type="hidden" name="id_organization_class" value="'.$id_organization_class.'">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="class_level_edit">
                        <small>Level Kelas</small>
                    </label>
                    <input type="text" class="form-control" name="class_level" id="class_level_edit" list="ListLevelEdit" value="'.$class_level.'" required>
                    <small>
                        <small class="text text-muted">
                            Example : Kelas 1, Kelas2, Kelas 3
                        </small>
                    </small>
                    <datalist id="ListLevelEdit">
                        <!-- List Level Akan Muncul Disini -->
                    </datalist>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="class_name_edit">
                        <small>Nama Kelas</small>
                    </label>
                    <input type="text" class="form-control" name="class_name" id="class_name_edit" value="'.$class_name.'" required>
                    <small>
                        <small class="text text-muted">
                            Example : 3A, 3B, 3C
                        </small>
                    </small>
                </div>
            </div>
        ';
    }
?>