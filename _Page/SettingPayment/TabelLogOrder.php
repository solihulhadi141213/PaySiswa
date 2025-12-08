<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    //Inisialisasi Jumlah Halaman, Jumlah Data Dan Posisi Halaman
    $data_count     = 0;
    $page_count     = 0;
    $current_page   = 1;
    
    //Validasi Session Akses Masih ADa
    if(empty($SessionIdAccess)){
        echo '
            <tr>
                <td colspan="10" class="text-center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan <b>Login</b> Ulang.</small>
                </td>
            </tr>
        ';
        exit;
    }

    //Keyword_by
    if(!empty($_POST['keyword_by'])){
        $keyword_by=$_POST['keyword_by'];
    }else{
        $keyword_by="";
    }

    //keyword
    if(!empty($_POST['keyword'])){
        $keyword=$_POST['keyword'];
    }else{
        $keyword="";
    }

    //batas
    if(!empty($_POST['batas'])){
        $batas=$_POST['batas'];
    }else{
        $batas="10";
    }

    //ShortBy
    if(!empty($_POST['ShortBy'])){
        $ShortBy=$_POST['ShortBy'];
    }else{
        $ShortBy="DESC";
    }

    //OrderBy
    if(!empty($_POST['OrderBy'])){
        $OrderBy=$_POST['OrderBy'];
    }else{
        $OrderBy="id_log_payment";
    }

    //Atur Page
    if(!empty($_POST['page'])){
        $page=$_POST['page'];
        $posisi = ( $page - 1 ) * $batas;
    }else{
        $page="1";
        $posisi = 0;
    }

    //Buka Pengaturan
    $api_payment_url    = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'api_payment_url');
    $USER_KEY           = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'USER_KEY');
    $SECRET_KEY         = GetDetailData($Conn, 'setting_payment', 'id_setting_payment', '1', 'SECRET_KEY');

    //Get Token
    $get_x_token    = GetXToken($api_payment_url,$USER_KEY,$SECRET_KEY);
    $response_array = json_decode($get_x_token, true);
    if($response_array['code']!==200){
        echo '
            <tr>
                <td colspan="10" class="text-center">
                    <small class="text-danger">Terjadi kesalahan pada saat membuat token '.$response_array['status'].'</small>
                </td>
            </tr>
        ';
        exit;
    }
    // ==============================
    // 1. Ambil x-token
    // ==============================
    $x_token = $response_array['metadata']['x-token'];

    // ==============================
    // 2. Buat RAW JSON persis seperti postman
    // ==============================
    $payload = json_encode([
        "limit"      => (int)$batas,
        "page"       => (int)$page,
        "short_by"   => $ShortBy,
        "order_by"   => $OrderBy,
        "keyword_by" => $keyword_by,
        "keyword"    => $keyword
    ], JSON_UNESCAPED_SLASHES);

    // ==============================
    // 3. Inisialisasi CURL
    // ==============================
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $api_payment_url.'/_API/log_payment.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'x-token: '.$x_token,
            'Content-Type: text/plain'
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    // ==============================
    // 4. Eksekusi
    // ==============================
    $raw_response = curl_exec($curl);
    $curl_error   = curl_error($curl);
    $info         = curl_getinfo($curl);
    curl_close($curl);

    if ($raw_response === false) {
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">CURL Error: ' . curl_error($curl).'</small>
                </td>
            </tr>
        ';
        exit;
    }
    
    //Menampilkan Data
    $response_array = json_decode($raw_response, true);

    if(empty($response_array['code'])){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">'.$raw_response.'</small>
                </td>
            </tr>
        ';
        exit;
    }
    
     
    
    // Jika response code tidak 200
    if($response_array['code']!==200){
        echo '
            <tr>
                <td colspan="9" class="text-center">
                    <small class="text-danger">'.$response_array['status'].'</small>
                </td>
            </tr>
        ';
        exit;
    }

    // Variabel metadata
    $data_count     = $response_array['metadata']['data_count'];
    $page_count     = $response_array['metadata']['page_count'];
    $current_page   = $response_array['metadata']['current_page'];

    // List
    $list           = $response_array['metadata']['list'];

    // Looping
    $no=1+$posisi;
    foreach($list as $list_data){
        $id_log_payment     = $list_data['id_log_payment'];
        $id_transaction     = $list_data['id_transaction'];
        $kode_transaksi     = $list_data['kode_transaksi'];
        $order_id           = $list_data['order_id'];
        $transaction_time   = $list_data['transaction_time'];
        $status_code        = $list_data['status_code'];
        $payment_type       = $list_data['payment_type'];
        $gross_amount       = $list_data['gross_amount'];
        $fraud_status       = $list_data['fraud_status'];
        $transaction_status = $list_data['transaction_status'];
        $gross_amount       = "" . number_format($gross_amount,0,',','.');

        // Format
        $order_id_format        = substr($order_id, 0, 8);
        $kode_transaksi_format  = substr($kode_transaksi, 0, 8);

        echo '
            <tr>
                <td align="center"><small>'.$no.'</small></td>
                <td align="left"><small>'.$kode_transaksi_format.'***</small></td>
                <td align="left"><small>'.$order_id_format.'***</small></td>
                <td align="left"><small>'.$transaction_time.'</small></td>
                <td align="left"><small>'.$status_code.'</small></td>
                <td align="left"><small>'.$payment_type.'</small></td>
                <td align="left"><small>'.$gross_amount.'</small></td>
                <td align="left"><small>'.$fraud_status.'</small></td>
                <td align="left"><small>'.$transaction_status.'</small></td>
            </tr>
        ';
        $no++;
    }
?>

<script>
    //Creat Javascript Variabel
    var page_count=<?php echo $page_count; ?>;
    var curent_page=<?php echo $current_page; ?>;

    //Put Into Pagging Element
    $('#page_info_order').html('Page '+curent_page+' Of '+page_count+'');

    //Set Pagging Button
    if(curent_page==1){
        $('#prev_button_order').prop('disabled', true);
    }else{
        $('#prev_button_order').prop('disabled', false);
    }
    if(page_count<=curent_page){
        $('#next_button_order').prop('disabled', true);
    }else{
        $('#next_button_order').prop('disabled', false);
    }
</script>