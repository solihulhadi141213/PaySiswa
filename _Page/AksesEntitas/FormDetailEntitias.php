<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Validasi Akses
    if (empty($SessionIdAccess)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir. Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }
    if(empty($_POST['id_access_group'])){
        echo '
            <div class="alert alert-danger">
                <small>ID Entitas Group Akses Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }
    
    //Buat Variabel
    $id_access_group=validateAndSanitizeInput($_POST['id_access_group']);

    //Buka Data
    $Qry = $Conn->prepare("SELECT * FROM access_group WHERE id_access_group = ?");
    $Qry->bind_param("i", $id_access_group);
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
        $group_name             =$Data['group_name'];
        $group_description      =$Data['group_description'];

        //Tampilkan Detail Entitas Group
        echo '
            <div class="row mb-3">
                <div class="col-4"><small>Entitas/Group</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$group_name.'</small></div>
            </div>
            <div class="row mb-3">
                <div class="col-4"><small>Deskripsi</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-grayish">'.$group_description.'</small></div>
            </div>
            <div class="row mb-3">
                <div class="col-12 border-1 border-bottom"></div>
            </div>
        ';
        echo '<div class="row mb-3">';
        echo '  <div class="col-12">';
        echo '      <ul>';

        //Menampilkan Kategori Fitur
        $no=1;
        $QryKategori = mysqli_query($Conn, "SELECT DISTINCT feature_category FROM access_feature ORDER BY feature_category ASC");
        while ($DataKategori = mysqli_fetch_array($QryKategori)) {
            $feature_category= $DataKategori['feature_category'];
            echo '<li class="mb-3">';
            echo '  <small>'.$feature_category.'</small>';
            
            //Menampilkan Fitur
            echo '  <ul>';
            $QryFitur = mysqli_query($Conn, "SELECT * FROM access_feature WHERE feature_category='$feature_category' ORDER BY feature_name ASC");
            while ($DataFitur = mysqli_fetch_array($QryFitur)) {
                $id_access_feature= $DataFitur['id_access_feature'];
                $feature_name= $DataFitur['feature_name'];

                //Cek Apakah Entitas Tersebut Memiliki Referensi Ke Sini
                $CekFiturEntitias=CekFiturEntitias($Conn,$id_access_group,$id_access_feature);
                if($CekFiturEntitias=="Ada"){
                    echo '
                        <li>
                            <small class="text text-success" title="'.$CekFiturEntitias.'-'.$id_access_group.'-'.$id_access_feature.'">
                                '.$feature_name.' <i class="bi bi-check-circle"></i>
                            </small>
                        </li>
                    ';
                }else{
                    echo '
                        <li>
                            <small class="text text-grayish" title="'.$CekFiturEntitias.'-'.$id_access_group.'-'.$id_access_feature.'">'.$feature_name.'</small>
                        </li>
                    ';
                }
                
            }
            echo '  </ul>';
            echo '</li>';
        }
        echo '';
        echo '      </ul>';
        echo '  </div>';
        echo '</div>';
    }
?>