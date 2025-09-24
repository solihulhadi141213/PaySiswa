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
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">
                        Sesi akses sudah berakhir. Silahkan <b>login</b> ulang!
                    </small>
                </td>
            </tr>
        ';
        exit;
    }

    //Apabila id_setting_payment kosong
    if(empty($_POST['id_setting_payment'])){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">
                        ID Profil Pengaturan Tidak Boleh Kosong!
                    </small>
                </td>
            </tr>
        ';
        exit;
    }

    //Buka pengaturan payment gateway
    $id_setting_payment = 1;
    try {
        // Gunakan prepared statement
        $Qry = $Conn->prepare("SELECT api_payment_url, USER_KEY, SECRET_KEY 
                            FROM setting_payment 
                            WHERE id_setting_payment = ?");
        if (!$Qry) {
            throw new Exception("Gagal mempersiapkan query: " . $Conn->error);
        }

        $Qry->bind_param("i", $id_setting_payment);

        if (!$Qry->execute()) {
            throw new Exception("Gagal mengeksekusi query: " . $Qry->error);
        }

        $Result = $Qry->get_result();
        if (!$Result) {
            throw new Exception("Gagal mengambil hasil query: " . $Qry->error);
        }

        $Data = $Result->fetch_assoc() ?? [];

        // Buat Variabel dengan fallback default "-"
        $api_payment_url = $Data['api_payment_url'] ?? "-";
        $USER_KEY        = $Data['USER_KEY'] ?? "-";
        $SECRET_KEY      = $Data['SECRET_KEY'] ?? "-";

        $Qry->close();

    } catch (Exception $e) {
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small class="text-danger">
                        Terjadi kesalahan pada saat membuka data dari database!<br>
                        Keterangan: ' . htmlspecialchars($e->getMessage()) . '
                    </small>
                </td>
            </tr>
        ';
        exit;
    }

    //Generate x-token
    $payload        = json_encode([ "USER_KEY"   => $USER_KEY, "SECRET_KEY" => $SECRET_KEY]);
    $url            =rtrim($api_payment_url, "/") . "/_API/get_token.php";
    $curl_request   =CurlPost($payload,$url);
    $response_array = json_decode($curl_request, true);
    
    //jika x-token gagal
    if($response_array['code']!==200){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small>
                        Terjadi kesalahan pada saat generate x-token!<br>
                        Keterangan: '.$curl_request.'
                    </small>
                </td>
            </tr>
        ';
        exit;
    }
    $x_token=$response_array['metadata']['x-token'];

    //Panggil Curl list setting
    $list_setting=list_setting($x_token,$api_payment_url);
    $list_setting_array = json_decode($list_setting, true);
    if($list_setting_array['code']!==200){
        echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small>
                        Terjadi kesalahan pada saat menampilkan list setting!<br>
                        Keterangan: '.$list_setting_array.'
                    </small>
                </td>
            </tr>
        ';
        exit;
    }

    //Menampilkan Pengaturan
    $metadata=$list_setting_array['metadata'];
    if(empty(count($metadata))){
         echo '
            <tr>
                <td colspan="8" class="text-center">
                    <small>
                        Tidak ada profile pengaturan yang tersimpan! Silahkan buat profil pengaturan terlebih dulu.
                    </small>
                </td>
            </tr>
        ';
        exit;
    }

    //Pengaturan yang dipilih
    $IdProfilPengaturan=$_POST['id_setting_payment'];
    foreach ($metadata as $metadata_list) {
        if($IdProfilPengaturan==$metadata_list['id_setting_payment']){
            if($metadata_list['production']=="true"){
                $label_env='<span class="text-success">Production</span>';
            }else{
                $label_env='<span class="text-danger">Development</span>';
            }
             if($metadata_list['status']=="none"){
                $label_status='<span class="badge badge-danger">None</span>';
            }else{
                $label_status='<span class="badge badge-success">Active</span>';
            }
            echo '
                <div class="row mb-3">
                    <div class="col-4"><small>Nama Profil</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$metadata_list['env_name'].'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>Call Back</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$metadata_list['urll_call_back'].'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>URL Status</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$metadata_list['url_status'].'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>ID Marchant</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$metadata_list['id_marchant'].'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>Client Key</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$metadata_list['client_key'].'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>Server Key</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$metadata_list['server_key'].'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>Snap URL</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$metadata_list['snap_url'].'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>Environment</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$label_env.'</code></small></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><small>Status</small></div>
                    <div class="col-1"><small>:</small></div>
                    <div class="col-7"><small><code class="text text-grayish">'.$label_status.'</code></small></div>
                </div>
            ';
        }
    }
?>