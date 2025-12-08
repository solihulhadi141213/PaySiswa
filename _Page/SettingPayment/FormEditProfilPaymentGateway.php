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
                $label_production="selected";
                $label_deveopment="";
            }else{
                $label_production="";
                $label_deveopment="selected";
            }
             if($metadata_list['status']=="none"){
                $label_active='';
                $label_none='selected';
            }else{
                $label_active='selected';
                $label_none='';
            }
            echo '
                <input type="hidden" name="id_setting_payment" value="'.$metadata_list['id_setting_payment'].'">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="env_name_edit">Nama Profil</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="env_name" id="env_name_edit" class="form-control" required value="'.$metadata_list['env_name'].'">
                        <small>
                            <code class="text text-grayish">
                                Nama profil pengaturan (Contoh: Staging, Development, Production)
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="urll_call_back_edit">URL Call Back</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="urll_call_back" id="urll_call_back_edit" class="form-control" value="'.$metadata_list['urll_call_back'].'">
                        <small>
                            <code class="text text-grayish">
                                URL yang digunakan untuk memproses pembaharuan status transaksi. (Apabila tidak digunakan, silahkan kosongkan)
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="url_status_edit">URL Status</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="url_status" id="url_status_edit" class="form-control" placeholder="https://" value="'.$metadata_list['url_status'].'">
                        <small>
                            <code class="text text-grayish">
                                URL yang digunakan untuk meminta status transaksi berdasarkan Order ID
                                <ul>
                                    <li>Sanbox : https://api.sandbox.midtrans.com/v2</li>
                                    <li>Production : https://api.midtrans.com/v2</li>
                                </ul>
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="id_marchant_edit">ID Merchant</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="id_marchant" id="id_marchant_edit" class="form-control" required value="'.$metadata_list['id_marchant'].'">
                        <small>
                            <code class="text text-grayish">
                                Diisi dengan <b>ID Merchant</b> yang sesuai pada <i>Access Key</i> yang disediakan provider.
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="client_key_edit">Client Key</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="client_key" id="client_key_edit" class="form-control" required value="'.$metadata_list['client_key'].'">
                        <small>
                            <code class="text text-grayish">
                                Diisi dengan <b>Client Key</b> yang sesuai pada <i>Access Key</i> yang disediakan provider.
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="server_key_edit">Server Key</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="server_key" id="server_key_edit" class="form-control" required value="'.$metadata_list['server_key'].'">
                        <small>
                            <code class="text text-grayish">
                                Diisi dengan <b>Server Key</b> yang sesuai pada <i>Access Key</i> yang disediakan provider.
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="snap_url_edit">Snap URL</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="snap_url" id="snap_url_edit" class="form-control" required value="'.$metadata_list['snap_url'].'">
                        <small>
                            <code class="text text-grayish">
                                <b>Snap URL</b> sesuai pada dokumentasi yang disediakan provider.
                                <ul>
                                    <li>Sanbox: https://app.sandbox.midtrans.com/snap/snap.js</li>
                                    <li>Production: https://app.midtrans.com/snap/snap.js</li>
                                </ul>
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="production">Environment</label>
                    </div>
                    <div class="col-md-8">
                        <select name="production" id="production"  class="form-control">
                            <option '.$label_deveopment.' value="false">Sanbox</option>
                            <option '.$label_production.' value="true">Production</option>
                        </select>
                        <small>
                            <code class="text text-grayish">
                                Diisi dengan <b>Snap URL</b> yang sesuai pada dokumentasi yang disediakan provider.
                            </code>
                        </small>
                    </div>
                </div>
                <div class="row mb-3 mt-4">
                    <div class="col-md-4">
                        <label class="form-label" for="status_profil">Status</label>
                    </div>
                    <div class="col-md-8">
                        <select name="status" id="status_profil" class="form-control">
                            <option value="">-Pilih-</option>
                            <option '.$label_active.' value="active">Active</option>
                            <option '.$label_none.' value="none">None</option>
                        </select>
                        <small>
                            <code class="text text-grayish">
                                Apabila anda mengaktifkan pengaturan ini maka semua transaksi akan menggunakan metode pembayaran yang disediakan provider payment gateway.
                            </code>
                        </small>
                    </div>
                </div>
            ';
        }
    }
?>