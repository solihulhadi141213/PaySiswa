<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Ambil protokol (http/https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Ambil host (domain atau IP + port)
    $host = $_SERVER['HTTP_HOST'];

    // Ambil folder root project (level pertama setelah domain)
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $projectRoot = explode('/', trim($scriptName, '/'))[0]; // "PaySiswa"

    // Satukan jadi base URL root project
    $base_url_with_path = rtrim($protocol . $host . '/' . $projectRoot, '/');

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

        //Buka Nama Group
        $group_name=GetDetailData($Conn, 'access_group', 'id_access_group', $id_access_group, 'group_name');

        //Routing URL image
        if(!empty($access_foto_saya)){
            $image_url= ''.$app_base_url.'/image_proxy.php?dir=User&filename='.$access_foto_saya;
        }else{
            $image_url= ''.$app_base_url.'/image_proxy.php?dir=User&filename=No-Image.png';
        }

        //Tampilkan Data
        echo '
            <div class="row mb-3">
                <div class="col-12 mb-3 text-center">
                    <img src="'.$image_url.'" alt="'.$access_foto_saya.'" width="70%" class="rounded-circle">
                </div>
            </div>
        ';
        echo '
            <div class="row mb-2">
                <div class="col-4"><small>Nama</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$access_name.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Email</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$access_email.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kontak</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$access_contact.'</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Entitas/Group</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7">
                    <small class="text text-grayish">'.$group_name.'</small>
                </div>
            </div>
        ';
    }
?>