<?php
    //Special Captcha
    function GenerateCaptcha($length) {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Menghindari karakter ambigu
        $captcha = '';
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $captcha;
    }
    function GenerateKodeBarang($length){
        $token = "";
        $codeAlphabet= "0123456789";
        $max = strlen($codeAlphabet); // edited
        
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }
        return $token;
    }
    //Membuat Token
    function GenerateToken($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $charLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }

    //Membuat Randome String
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $charLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }

    //Membersihkan Variabel
    function validateAndSanitizeInput($input) {
        // Menghapus karakter yang tidak diinginkan
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        $input = addslashes($input);
        return $input;
    }

    //Data Detail
    function GetDetailData($Conn, $Tabel, $Param, $Value, $Colom) {
        // Validasi input yang diperlukan
        if (empty($Conn)) {
            return "No Database Connection";
        }
        if (empty($Tabel)) {
            return "No Table Selected";
        }
        if (empty($Param)) {
            return "No Parameter Selected";
        }
        if (empty($Value)) {
            return "No Value Provided";
        }
        if (empty($Colom)) {
            return "No Column Selected";
        }
    
        // Escape table name and column name untuk mencegah SQL Injection
        $Tabel = mysqli_real_escape_string($Conn, $Tabel);
        $Param = mysqli_real_escape_string($Conn, $Param);
        $Colom = mysqli_real_escape_string($Conn, $Colom);
    
        // Menggunakan prepared statement
        $Qry = $Conn->prepare("SELECT $Colom FROM $Tabel WHERE $Param = ?");
        if ($Qry === false) {
            return "Query Preparation Failed: " . $Conn->error;
        }
    
        // Bind parameter
        $Qry->bind_param("s", $Value);
    
        // Eksekusi query
        if (!$Qry->execute()) {
            return "Query Execution Failed: " . $Qry->error;
        }
    
        // Mengambil hasil
        $Result = $Qry->get_result();
        $Data = $Result->fetch_assoc();
    
        // Menutup statement
        $Qry->close();
    
        // Mengembalikan hasil
        if (empty($Data[$Colom])) {
            return "";
        } else {
            return $Data[$Colom];
        }
    }
    
    //Loging
    function addLog($Conn,$id_access,$log_datetime,$kategori_log,$deskripsi_log){
        $entry="INSERT INTO access_log (
            id_access,
            log_datetime,
            log_category,
            log_description
        ) VALUES (
            '$id_access',
            '$log_datetime',
            '$kategori_log',
            '$deskripsi_log'
        )";
        $Input=mysqli_query($Conn, $entry);
        if($Input){
            $Response="Success";
        }else{
            $Response="Input Log Gagal";
        }
        return $Response;
    }
    
    //Membuat Randome Number
    function generateRandomNumber($length) {
        $characters = '0123456789';
        $randomString = '';
        $charLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }
    //Send Email
    function SendEmail($NamaTujuan,$EmailTujuan,$Subjek,$Pesan,$email_gateway,$password_gateway,$url_provider,$nama_pengirim,$port_gateway,$url_service) {
        if(empty($NamaTujuan)){
            $Response="Nama tujuan pesan tidak boleh kosong!";
        }else{
            if(empty($EmailTujuan)){
                $Response="Email tujuan pesan tidak boleh kosong!";
            }else{
                if(empty($Subjek)){
                    $Response="Subjek pesan tidak boleh kosong!";
                }else{
                    if(empty($Pesan)){
                        $Response="Isi Pesan Tidak Boleh Kosong!";
                    }else{
                        if(empty($email_gateway)){
                            $Response="Akun Email Gateway Tidak Boleh Kosong!";
                        }else{
                            if(empty($password_gateway)){
                                $Response="Password Tidak Boleh Kosong!";
                            }else{
                                if(empty($url_provider)){
                                    $Response="URL Provider Tidak Boleh Kosong!";
                                }else{
                                    if(empty($nama_pengirim)){
                                        $Response="Nama pengirim Tidak Boleh Kosong!";
                                    }else{
                                        if(empty($port_gateway)){
                                            $Response="Port Tidak Boleh Kosong!";
                                        }else{
                                            if(empty($url_service)){
                                                $Response="Url Service Tidak Boleh Kosong!";
                                            }else{
                                                //Kirim email
                                                $ch = curl_init();
                                                $headers = array(
                                                    'Content-Type: Application/JSON',          
                                                    'Accept: Application/JSON'     
                                                );
                                                $arr = array(
                                                    "subjek" => "$Subjek",
                                                    "email_asal" => "$email_gateway",
                                                    "password_email_asal" => "$password_gateway",
                                                    "url_provider" => "$url_provider",
                                                    "nama_pengirim" => "$nama_pengirim",
                                                    "email_tujuan" => "$EmailTujuan",
                                                    "nama_tujuan" => "$NamaTujuan",
                                                    "pesan" => "$Pesan",
                                                    "port" => "$port_gateway"
                                                );
                                                $json = json_encode($arr);
                                                curl_setopt($ch, CURLOPT_URL, "$url_service");
                                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                curl_setopt($ch, CURLOPT_TIMEOUT, 3); 
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                $content = curl_exec($ch);
                                                $err = curl_error($ch);
                                                curl_close($ch);
                                                $get =json_decode($content, true);
                                                $Response=$content;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $Response;
    }
    //Delete Data
    function DeleteData($Conn,$NamaDb,$NamaParam,$IdParam){
        $HapusData = mysqli_query($Conn, "DELETE FROM $NamaDb WHERE $NamaParam='$IdParam'") or die(mysqli_error($Conn));
        if($HapusData){
            $Response="Success";
        }else{
            $Response="Hapus Data Gagal";
        }
        return $Response;
    }
    function NamaHari($no){
        if($no==1){
            $Response="Senin";
        }else{
            if($no==2){
                $Response="Selasa";
            }else{
                if($no==3){
                    $Response="Rabu";
                }else{
                    if($no==4){
                        $Response="Kamis";
                    }else{
                        if($no==5){
                            $Response="Jumat";
                        }else{
                            if($no==6){
                                $Response="Sabtu";
                            }else{
                                if($no==7){
                                    $Response="Minggu";
                                }else{
                                    $Response="None";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $Response;
    }
    function checkImageGifExists($jsonString,$type) {
        // Mengurai string JSON menjadi array PHP
        $data = json_decode($jsonString, true);
    
        // Pengecekan apakah $type ada dalam salah satu elemen array
        foreach ($data as $item) {
            if ($item['type'] === $type) {
                return true; // Jika ditemukan, kembalikan true
            }
        }
    
        return false; // Jika tidak ditemukan, kembalikan false
    }
    function MimeTiTipe($mim) {
        $Referensi = [
            ['name' => 'PDF', 'type' => 'application/pdf'],
            ['name' => 'XLS', 'type' => 'application/vnd.ms-excel'],
            ['name' => 'XLSX', 'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            ['name' => 'CSV1', 'type' => 'text/csv'],
            ['name' => 'CSV2', 'type' => 'application/csv'],
            ['name' => 'CSV3', 'type' => 'text/plain'],
            ['name' => 'DOC', 'type' => 'application/msword'],
            ['name' => 'DOCX', 'type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            ['name' => 'JPEG', 'type' => 'image/jpeg'],
            ['name' => 'PNG', 'type' => 'image/png'],
            ['name' => 'GIF', 'type' => 'image/gif'],
        ];
        foreach ($Referensi as $item) {
            if ($item['type'] === $mim) {
                $matchedIds[] = $item['name'];
            }
        }
        $NamaFile=implode(', ', $matchedIds);
        return $NamaFile;
    }
    
    function validateUploadedFile($file,$size) {
        // Tipe file yang diperbolehkan
        $allowedMimeTypes = [
            'image/jpeg', // Untuk file .jpg dan .jpeg
            'image/png',  // Untuk file .png
            'image/gif',  // Untuk file .gif
        ];
    
        // Maksimal ukuran file (5MB, misalnya)
        $maxFileSize = $size * 1024 * 1024; // 5MB dalam byte
    
        // Periksa apakah file diunggah tanpa error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Terjadi kesalahan saat mengunggah file.";
        }
    
        // Periksa ukuran file
        if ($file['size'] > $maxFileSize) {
            return "Ukuran file terlalu besar. Maksimal 5MB.";
        }
    
        // Dapatkan MIME type file
        $fileMimeType = mime_content_type($file['tmp_name']);
    
        // Validasi tipe MIME
        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            return "Tipe file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        }
    
        // Jika semua validasi lolos
        return true;
    }
    function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        // Pastikan format sesuai dan tanggal valid (misalnya tidak ada 30 Februari)
        return $d && $d->format($format) === $date;
    }
    function IjinAksesSaya($Conn, $SessionIdAccess, $id_access_feature){
        // Siapkan query dengan placeholder
        $stmt = $Conn->prepare("SELECT id_access FROM access_permission WHERE id_access = ? AND id_access_feature = ?");
        
        // Bind parameter (sama-sama integer, jadi 'ii')
        $stmt->bind_param("is", $SessionIdAccess, $id_access_feature);
        
        // Eksekusi
        $stmt->execute();
        
        // Ambil hasil
        $result = $stmt->get_result();
        $DataParam = $result->fetch_assoc();
        
        // Tentukan respon
        if(empty($DataParam['id_access'])){
            $Response = "Tidak Ada";
        }else{
            $Response = "Ada";
        }
        
        // Tutup statement
        $stmt->close();
        
        return $Response;
    }
    function CekFiturEntitias($Conn, $id_access_group, $id_access_feature){
        
        // Query dengan prepared statement
        $stmt = $Conn->prepare("SELECT id_access_reference 
                                FROM access_reference 
                                WHERE id_access_group = ? AND id_access_feature = ? 
                                LIMIT 1");
        if($stmt){
            $stmt->bind_param("is", $id_access_group, $id_access_feature);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0){
                $Response = "Ada";
            }else{
                $Response = "Tidak Ada";
            }
            $stmt->close();
        }else{
            $Response = "Tidak Ada";
        }
        return $Response;
    }
    function CheckParameterOnJson($jsonString,$type,$parameter) {
        // Mengurai string JSON menjadi array PHP
        $data = json_decode($jsonString, true);
    
        // Pengecekan apakah $type ada dalam salah satu elemen array
        foreach ($data as $item) {
            if ($item[$parameter] === $type) {
                return true; // Jika ditemukan, kembalikan true
            }
        }
    
        return false; // Jika tidak ditemukan, kembalikan false
    }
    function ValidasiKutip($string) {
        // Pola untuk mendeteksi tanda kutip tunggal atau ganda
        $pola = "/['\"]/";
    
        // Menggunakan preg_match untuk mengecek apakah string mengandung tanda kutip
        if (preg_match($pola, $string)) {
            return false; // String mengandung tanda kutip
        } else {
            return true; // String tidak mengandung tanda kutip
        }
    }
    function ValidasiHanyaAngka($string) {
        // Pola untuk mendeteksi hanya angka
        $pola = "/^\d+$/";
    
        // Menggunakan preg_match untuk mengecek apakah string hanya mengandung angka
        return preg_match($pola, $string);
    }
    function formatRupiah($angka,$mata_uang,$zero_padding) {
        return ''.$mata_uang.' ' . number_format($angka, $zero_padding, ',', '.');
    }
    // Function to get value by UID
    function getValueByUid($data, $uid) {
        foreach ($data as $item) {
            if ($item['uid'] == $uid) {
                return $item['value'];
            }
        }
        return null;
    }
    // Function to get value by UID
    function SearchStringIntoArray($data, $keyword) {
        foreach ($data as $item) {
            if ($item== $keyword) {
                return true;
            }
        }
        return false;
    }
    function GetSometingByKeyword($DatArray,$keyword_by,$keyword,$value_parameter) {
        foreach ($DatArray as $item) {
            if ($item[$keyword_by] == $keyword) {
                return $item[$value_parameter];
            }
        }
        return null;
    }
    function ValidasiRekening($input) {
        // Cek apakah input hanya berisi angka
        if (!ctype_digit($input)) {
            return "Nomor Rekening harus berisi angka saja.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Nomor Rekening tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    function ValidasiBank($input) {
        // Cek apakah input hanya berisi huruf dan spasi
        if (!preg_match('/^[a-zA-Z\s]*$/', $input)) {
            return "Nama Bank hanya boleh berisi huruf dan spasi.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Nama Bank tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    function ValidasiKontak($input) {
        // Cek apakah input hanya berisi huruf dan spasi
        if (!preg_match('/^[0-9]*$/', $input)) {
            return "Kontak hanya boleh berisi huruf dan spasi.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Kontak tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    function maskAccountNumber($accountNumber) {
        // Hitung panjang nomor rekening
        $length = strlen($accountNumber);
        
        // Ambil 4 digit terakhir
        $lastFourDigits = substr($accountNumber, -4);
        
        // Buat string bintang dengan panjang sesuai
        $maskedPart = str_repeat('*', $length - 4);
        
        // Gabungkan bagian yang di-mask dengan 4 digit terakhir
        return $maskedPart . $lastFourDigits;
    }
    function TokenValidation($input) {
        // Cek apakah input hanya berisi angka dan huruf
        if (!preg_match('/^[a-zA-Z0-9]*$/', $input)) {
            return "Input hanya boleh berisi huruf dan angka.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Input tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    //Creat Captcha
    function CreatCaptcha($Conn) {
        date_default_timezone_set("Asia/Jakarta");
        $now=date('Y-m-d H:i:s');
        //hasil kode acak disimpan di $code
        $length=6;
        $Captcha = "";
        $codeAlphabet = "ABCDEFGHJKLMNPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijkmnpqrstuvwxyz";
        $codeAlphabet.= "23456789";
        $max = strlen($codeAlphabet); // edited
        for ($i=0; $i < $length; $i++) {
            $Captcha .= $codeAlphabet[random_int(0, $max-1)];
        }
        //Ubah ke md5
        $CodeMd5=md5($Captcha);
        //Melakukan Pengecekan Kode Captcha Yang expired
        $expired=date('Y-m-d H:i:s', time() - 60);
        $query = mysqli_query($Conn, "SELECT*FROM log_captcha WHERE datetime_creat<'$expired'");
        while ($data = mysqli_fetch_array($query)) {
            $id_log_captcha= $data['id_log_captcha'];
            //Hapus Captcha
            $Hapus = mysqli_query($Conn, "DELETE FROM log_captcha WHERE id_log_captcha='$id_log_captcha'") or die(mysqli_error($Conn));
        }
        //Simpan Ke Database
        $EntryCaptcha="INSERT INTO log_captcha (
            datetime_creat,
            captcha
        ) VALUES (
            '$now',
            '$CodeMd5'
        )";
        $InputCaptcha=mysqli_query($Conn, $EntryCaptcha);
        if($InputCaptcha){
            $CaptchaFix=$Captcha;
        }else{
            $CaptchaFix="";
        }
        return $CaptchaFix;
    }
    function hitung_usia($tanggal_lahir) {
        $birthDate = new DateTime($tanggal_lahir);
        $today = new DateTime("today");
        if ($birthDate > $today) {
            exit("Usia tidak valid");
        }
        $y = $today->diff($birthDate)->y;
        $m = $today->diff($birthDate)->m;
        $d = $today->diff($birthDate)->d;
    
        if ($y > 0) {
            return $y." tahun";
        } else if ($m > 0) {
            return $m." bulan";
        } else {
            return $d." hari";
        }
    }
    function ValidasiTanggal($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    function calculateExpirationTimeFromDateTime($dateTime, $milliseconds) {
        // Membuat objek DateTime dari string input
        $date = new DateTime($dateTime);
    
        // Mengonversi milidetik ke detik dan mikrodetik
        $seconds = floor($milliseconds / 1000);
        $microseconds = ($milliseconds % 1000) * 1000;
    
        // Menambahkan detik dan mikrodetik ke objek DateTime
        $date->add(new DateInterval("PT{$seconds}S"));
        // Menambahkan mikrodetik menggunakan metode modify
        $date->modify("+{$microseconds} microseconds");
    
        // Mengembalikan waktu kedaluwarsa dalam format YYYY-mm-dd HH:ii:ss.uuu
        return $date->format('Y-m-d H:i:s');
    }
    function generateUuidV1() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 36;
        $uuid = '';
        for ($i = 0; $i < $length; $i++) {
            $uuid .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $uuid;
    }
    function getNamaBulan($angkaBulan) {
        // Array dengan nama-nama bulan
        $namaBulan = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
    
        // Mengembalikan nama bulan berdasarkan angka
        return $namaBulan[$angkaBulan] ?? 'Bulan tidak valid';
    }
    function getNamaBulanSingkat($angkaBulan) {
        // Array dengan nama-nama bulan
        $namaBulan = [
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'Mei',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Agu',
            '9' => 'Sept',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des'
        ];
    
        // Mengembalikan nama bulan berdasarkan angka
        return $namaBulan[$angkaBulan] ?? 'Bulan tidak valid';
    }
    function pembulatan_nilai($nilai){
        $nilai = (float) $nilai;
        $nilai = ($nilai == floor($nilai)) ? (int)$nilai : $nilai;
        return $nilai;
    }

    // Mendapatkan Detail fee_by_student
    function ShowFeeByStudent($id_student, $id_fee_component, $id_organization_class, $collom) {
        global $Conn; // pakai koneksi mysqli dari luar fungsi

        // siapkan query
        $Qry = $Conn->prepare("
            SELECT $collom 
            FROM fee_by_student 
            WHERE id_student = ? 
            AND id_fee_component = ? 
            AND id_organization_class = ?
        ");

        if (!$Qry) {
            return "Prepare failed: " . $Conn->error;
        }

        // bind parameter
        $Qry->bind_param("iii", $id_student, $id_fee_component, $id_organization_class);

        // eksekusi
        if (!$Qry->execute()) {
            $response = "Execute failed: " . $Qry->error;
        } else {
            $Result = $Qry->get_result();
            if ($Result && $Data = $Result->fetch_assoc()) {
                $response = $Data[$collom] ?? null;
            } else {
                $response = null;
            }
        }

        $Qry->close();
        return $response;
    }

    // Cari id_fee_by_student berdasarkan id_organization_class, id_student, id_fee_component 
    function CariFeeByStudent($id_organization_class,$id_student,$id_fee_component) {
        // pakai koneksi mysqli dari luar fungsi
        global $Conn;

        // siapkan query
        $Qry = $Conn->prepare("SELECT id_fee_by_student FROM fee_by_student WHERE id_student = ? AND id_fee_component = ? AND id_organization_class = ?");

        if (!$Qry) {
            return "Prepare failed: " . $Conn->error;
        }

        // bind parameter
        $Qry->bind_param("iii", $id_student, $id_fee_component, $id_organization_class);

        // eksekusi
        if (!$Qry->execute()) {
            $response = null;
        } else {
            $Result = $Qry->get_result();
            if ($Result && $Data = $Result->fetch_assoc()) {
                $response = $Data['id_fee_by_student'] ?? null;
            } else {
                $response = null;
            }
        }

        $Qry->close();
        return $response;
    }

    //Get Token
    function GetXToken($base_url,$USER_KEY,$SECRET_KEY) {

        //Inisialisasi CURL
        $curl = curl_init();

        //Creat Post Data
        $postData = json_encode([
            "USER_KEY"   => $USER_KEY,
            "SECRET_KEY" => $SECRET_KEY
        ]);

        // curl_setopt_array
        curl_setopt_array($curl, array(
            CURLOPT_URL            => rtrim($base_url, "/") . "/_API/get_token.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ),
            CURLOPT_SSL_VERIFYHOST => 0,  // ⚠️ Disable SSL check (testing only)
            CURLOPT_SSL_VERIFYPEER => 0   // ⚠️ Disable SSL check (testing only)
        ));
        
        //Variabel Response
        $response = curl_exec($curl);

        //tutup Curl
        curl_close($curl);

        return $response;
    }

    //CURL POST
    function CurlPost($postData,$url) {
       // Mulai CURL untuk Get Token
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ),
            CURLOPT_SSL_VERIFYHOST => 0,  // ⚠️ Disable SSL check (testing only)
            CURLOPT_SSL_VERIFYPEER => 0   // ⚠️ Disable SSL check (testing only)
        ));
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlErrNo = curl_errno($curl);
        $curlErr   = curl_error($curl);
        curl_close($curl);
        if ($curlErrNo) {
            $response= htmlspecialchars($curlErr);
        }else{  
            return $response;
        }
    }

    //Request X-token
    function RequestXtoken($Conn, $USER_KEY = null, $SECRET_KEY = null){
        // ID setting payment (static)
        $id_setting_payment = 1;

        /* =============================
        * AMBIL SETTING PAYMENT
        * ============================= */
        $qry = $Conn->prepare(
            "SELECT api_payment_url, USER_KEY, SECRET_KEY 
            FROM setting_payment 
            WHERE id_setting_payment = ?"
        );

        if (!$qry) {
            return [
                'status'  => 'error',
                'message' => $Conn->error,
                'x-token' => null
            ];
        }

        $qry->bind_param("i", $id_setting_payment);

        if (!$qry->execute()) {
            return [
                'status'  => 'error',
                'message' => $qry->error,
                'x-token' => null
            ];
        }

        $result = $qry->get_result();
        $data   = $result->fetch_assoc();
        $qry->close();

        if (!$data) {
            return [
                'status'  => 'error',
                'message' => 'Setting payment tidak ditemukan',
                'x-token' => null
            ];
        }

        $api_payment_url = rtrim($data['api_payment_url'], '/');
        $USER_KEY        = $data['USER_KEY'];
        $SECRET_KEY      = $data['SECRET_KEY'];

        /* =============================
        * PAYLOAD REQUEST
        * ============================= */
        $payload = json_encode([
            'USER_KEY'   => $USER_KEY,
            'SECRET_KEY' => $SECRET_KEY
        ]);

        /* =============================
        * REQUEST CURL
        * ============================= */
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $api_payment_url . "/_API/get_token.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            ],
            CURLOPT_SSL_VERIFYHOST => 0, // ⚠️ testing only
            CURLOPT_SSL_VERIFYPEER => 0  // ⚠️ testing only
        ]);

        $response   = curl_exec($curl);
        $curlErrNo  = curl_errno($curl);
        $curlErr    = curl_error($curl);
        curl_close($curl);

        if ($curlErrNo) {
            return [
                'status'  => 'error',
                'message' => htmlspecialchars($curlErr),
                'x-token' => null
            ];
        }

        /* =============================
        * DECODE RESPONSE
        * ============================= */
        $response_array = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status'  => 'error',
                'message' => 'Response JSON tidak valid',
                'x-token' => null
            ];
        }

        /* =============================
        * VALIDASI RESPONSE
        * ============================= */
        if (
            !isset($response_array['code']) ||
            $response_array['code'] != 200 ||
            empty($response_array['metadata']['x-token'])
        ) {
            return [
                'status'  => 'error',
                'message' => htmlspecialchars($response_array['status'] ?? 'Request token gagal'),
                'x-token' => null
            ];
        }

        /* =============================
        * SIMPAN TOKEN KE DATABASE
        * ============================= */
        $x_token          = $response_array['metadata']['x-token'];
        $datetime_creat   = $response_array['metadata']['datetime_creat'];
        $datetime_expired = $response_array['metadata']['datetime_expired'];

        $stmt = $Conn->prepare(
            "INSERT INTO auth_payment 
            (id_setting_payment, x_token, datetime_creat, datetime_expired) 
            VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            return [
                'status'  => 'error',
                'message' => $Conn->error,
                'x-token' => null
            ];
        }

        $stmt->bind_param(
            "isss",
            $id_setting_payment,
            $x_token,
            $datetime_creat,
            $datetime_expired
        );

        if (!$stmt->execute()) {
            $stmt->close();
            return [
                'status'  => 'error',
                'message' => 'Gagal menyimpan X-Token ke database',
                'x-token' => null
            ];
        }

        $stmt->close();

        /* =============================
        * RESPONSE SUCCESS
        * ============================= */
        return [
            'status'  => 'success',
            'message' => 'X-Token berhasil dibuat',
            'x-token' => $x_token
        ];
    }


    //Curl GET header
    function list_setting($x_token,$url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => ''.$url.'/_API/list_setting.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-token: '.$x_token.''
            ),
            CURLOPT_SSL_VERIFYHOST => 0,  // ⚠️ Disable SSL check (testing only)
            CURLOPT_SSL_VERIFYPEER => 0   // ⚠️ Disable SSL check (testing only)
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
?>