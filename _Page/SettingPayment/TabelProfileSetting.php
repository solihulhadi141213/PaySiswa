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

    $no=1;
    foreach ($metadata as $metadata_list) {

        //Routing Status
        if($metadata_list['status']=="none"){
            $label_status='<span class="badge bg-danger"><i class="bi bi-x-circle"></i> None</span>';
        }else{
            $label_status='<span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>';
        }

        //Routing production
        if($metadata_list['production']=="false"){
            $label_production='<span class="badge badge-danger"><i class="bi bi-x-circle"></i> Sanbox</span>';
        }else{
            $label_production='<span class="badge badge-success"><i class="bi bi-check-circle"></i> Production</span>';
        }
        echo '
            <tr>
                <td><small>'.$no.'</small></td>
                <td>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetailProfilPaymentGateway" data-id="'.$metadata_list['id_setting_payment'].'">
                        <small class="text text-decoration-underline">'.$metadata_list['env_name'].'</small>
                    </a>
                </td>
                <td><small>'.$metadata_list['id_marchant'].'</small></td>
                <td><small><code class="text text-grayish">'.$metadata_list['client_key'].'</code></small></td>
                <td><small><code class="text text-grayish">'.$metadata_list['server_key'].'</code></small></td>
                <td><small>'.$label_production.'</small></td>
                <td><small>'.$label_status.'</small></td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                        <li class="dropdown-header text-start">
                            <h6>Option</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailProfilPaymentGateway" data-id="'.$metadata_list['id_setting_payment'].'">
                                <i class="bi bi-info-circle"></i> Detail Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditProfilPaymentGateway" data-id="'.$metadata_list['id_setting_payment'].'">
                                <i class="bi bi-pencil"></i> Ubah/Edit Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusProfilPaymentGateway" data-id="'.$metadata_list['id_setting_payment'].'">
                                <i class="bi bi-x"></i> Hapus Profil
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        $no++;
    }
?>