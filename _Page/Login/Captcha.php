<?php
    // Koneksi Database
    include "../../_Config/Connection.php";

    // Tetapkan Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    // Fungsi untuk membuat kode Captcha
    function GenerateCaptcha($length = 5) {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Menghindari karakter ambigu
        $captcha = '';
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $captcha;
    }

    // Set Captcha
    $captcha = GenerateCaptcha(5);

    // Timestamp
    // Tetapkan waktu sekarang dan waktu expired
    $timestamp_now = date('Y-m-d H:i:s'); // Format untuk kolom TIMESTAMP
    $timestamp_expired = date('Y-m-d H:i:s', time() + 60); // Tambahkan 60 detik untuk waktu expired

    // Hapus captcha yang sudah expired
    $deleteExpiredCaptchas = $Conn->prepare("DELETE FROM captcha WHERE datetime_expired < ?");
    $captchaCategory = 'Captcha';
    $deleteExpiredCaptchas->bind_param("s", $timestamp_now);
    $deleteExpiredCaptchas->execute();
    
    // Simpan Captcha Di Database
    $query = "INSERT INTO captcha (
        captcha, 
        datetime_creat, 
        datetime_expired
    ) VALUES (?, ?, ?)";

    // Persiapkan statement
    $stmt = $Conn->prepare($query);
    if ($stmt) {
        // Bind parameter
        $stmt->bind_param(
            "sss",
            $captcha,
            $timestamp_now,
            $timestamp_expired
        );

        // Eksekusi statement
        if ($stmt->execute()) {
            // Header untuk gambar
            header('Content-Type: image/png');

            // Ukuran gambar
            $width = 150;
            $height = 50;

            // Membuat gambar kosong
            $image = imagecreatetruecolor($width, $height);

            // Warna latar belakang
            $bgColor = imagecolorallocate($image, 255, 255, 255); // Putih
            imagefill($image, 0, 0, $bgColor);

            // Tambahkan noise berupa titik acak
            for ($i = 0; $i < 100; $i++) {
                $dotColor = imagecolorallocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
                imagesetpixel($image, rand(0, $width), rand(0, $height), $dotColor);
            }

            // Tambahkan garis noise acak
            for ($i = 0; $i < 5; $i++) {
                $lineColor = imagecolorallocate($image, rand(150, 200), rand(150, 200), rand(150, 200));
                imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
            }

            // Warna teks Captcha
            $textColor = imagecolorallocate($image, 0, 0, 0); // Hitam

            // Path ke font
            $font = __DIR__ . '/../../assets/font/ClassicalDiary.ttf';
            if (!file_exists($font)) {
                die("Font file not found!");
            }

            // Tambahkan teks Captcha ke gambar
            $fontSize = 20;
            $textX = 10; // Posisi X
            $textY = 35; // Posisi Y
            imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, $font, $captcha);

            // Output gambar
            imagepng($image);

            // Membersihkan memori
            imagedestroy($image);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode([
                "status" => "Error",
                "message" => "Gagal menyimpan Captcha ke database."
            ]);
        }

        // Tutup statement
        $stmt->close();
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "status" => "Error",
            "message" => "Gagal mempersiapkan query."
        ]);
    }

    // Tutup koneksi database
    $Conn->close();
?>
