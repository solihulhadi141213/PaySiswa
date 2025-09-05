<?php
    $baseDir = __DIR__ . '/assets/img/';
    $allowedDirs = ['User', 'Siswa', 'Dokumen'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    $dir = $_GET['dir'] ?? '';
    $filename = $_GET['filename'] ?? '';

    if (!in_array($dir, $allowedDirs)) {
        http_response_code(403);
        exit('Akses folder tidak diizinkan.');
    }

    if (!preg_match('/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
        http_response_code(400);
        exit('Nama file tidak valid.');
    }

    $filePath = realpath($baseDir . $dir . '/' . $filename);
    if (!$filePath || strpos($filePath, realpath($baseDir . $dir)) !== 0) {
        http_response_code(403);
        exit('Akses tidak sah.');
    }

    if (!file_exists($filePath) || !is_readable($filePath)) {
        http_response_code(404);
        exit('File tidak ditemukan.');
    }

    $info = getimagesize($filePath);
    if (!$info) {
        http_response_code(415);
        exit('File bukan gambar valid.');
    }

    $mime = $info['mime'];
    $srcWidth = $info[0];
    $srcHeight = $info[1];

    // Load image source
    switch ($mime) {
        case 'image/jpeg': $srcImage = @imagecreatefromjpeg($filePath); break;
        case 'image/png':  $srcImage = @imagecreatefrompng($filePath);  break;
        case 'image/gif':  $srcImage = @imagecreatefromgif($filePath);  break;
        case 'image/webp': $srcImage = @imagecreatefromwebp($filePath); break;
        default:
            http_response_code(415);
            exit('Tipe gambar tidak didukung.');
    }

    if (!$srcImage) {
        http_response_code(500);
        exit('Gagal memuat gambar.');
    }

    // Ukuran target final
    $targetSize = 500;

    // Hitung sisi persegi terkecil untuk crop
    $cropSize = min($srcWidth, $srcHeight);
    $cropX = (int)(($srcWidth - $cropSize) / 2);
    $cropY = (int)(($srcHeight - $cropSize) / 2);

    // Buat image sementara hasil crop persegi dari tengah
    $croppedImage = imagecreatetruecolor($cropSize, $cropSize);
    imagecopy($croppedImage, $srcImage, 0, 0, $cropX, $cropY, $cropSize, $cropSize);

    // Resize hasil crop ke ukuran target
    $resizedImage = imagecreatetruecolor($targetSize, $targetSize);
    imagecopyresampled($resizedImage, $croppedImage, 0, 0, 0, 0, $targetSize, $targetSize, $cropSize, $cropSize);

    // Tampilkan gambar
    header('Content-Type: ' . $mime);
    switch ($mime) {
        case 'image/jpeg': imagejpeg($resizedImage); break;
        case 'image/png':  imagepng($resizedImage);  break;
        case 'image/gif':  imagegif($resizedImage);  break;
        case 'image/webp': imagewebp($resizedImage); break;
    }

    // Bersihkan memori
    imagedestroy($srcImage);
    imagedestroy($croppedImage);
    imagedestroy($resizedImage);
    exit;
?>
