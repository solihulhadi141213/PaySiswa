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
    //Tangkap id_access
    if(empty($_POST['id_access'])){
         echo '
            <div class="alert alert-danger">
                <small>
                    ID Access Tidak Boleh Kosong!
                </small>
            </div>
        ';
        exit;
    }

    //Buat variabel
    $id_access=validateAndSanitizeInput($_POST['id_access']);

    //Buka Data access
    $Qry = $Conn->prepare("SELECT * FROM access WHERE id_access = ?");
    $Qry->bind_param("i", $id_access);
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
        $id_access_group    =$Data['id_access_group'];
        $access_name        =$Data['access_name'];
        $access_email       =$Data['access_email'];
        $access_contact     =$Data['access_contact'];
        $access_foto_saya   =$Data['access_foto'];

        //Menampilkan Form
        echo '
            <input type="hidden" name="id_access" value="'.$id_access.'">
            <div class="row mb-3">
                <div class="col col-md-4">
                    <label for="nama_akses_edit">Nama</label>
                </div>
                <div class="col col-md-8">
                    <input type="text" name="nama_akses" id="nama_akses_edit" class="form-control" value="'.$access_name.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col col-md-4">
                    <label for="kontak_akses_edit">Kontak</label>
                </div>
                <div class="col col-md-8">
                    <input type="text" name="kontak_akses" id="kontak_akses_edit" class="form-control" value="'.$access_contact.'">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col col-md-4">
                    <label for="email_akses_edit">Email</label>
                </div>
                <div class="col col-md-8">
                    <input type="email" name="email_akses" id="email_akses_edit" class="form-control" value="'.$access_email.'">
                </div>
            </div>
        ';
        echo '<div class="row mb-3">';
        echo '  <div class="col-4">';
        echo '      <label for="akses_edit"><small>Entitas/Group</small></label>';
        echo '  </div>';
        echo '  <div class="col-8">';
        echo '      <select name="akses" id="akses_edit" class="form-control">';
        echo '          <option value="">Pilih</option>';
                        //Array Data Mitra
                        $QryMitra = mysqli_query($Conn, "SELECT id_access_group, group_name FROM access_group ORDER BY group_name ASC");
                        while ($DataMitra = mysqli_fetch_array($QryMitra)) {
                            $id_access_group_list= $DataMitra['id_access_group'];
                            $group_name= $DataMitra['group_name'];
                            if($id_access_group_list==$id_access_group){
                                echo '<option selected value="'.$id_access_group_list.'">'.$group_name.'</option>';
                            }else{
                                echo '<option value="'.$id_access_group_list.'">'.$group_name.'</option>';
                            }
                        }
        echo '      </select>';
        echo '  </div>';
        echo '</div>';
    } 
?>