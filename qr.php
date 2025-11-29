<?php
    if(!empty($_GET['text'])){
        require 'assets/vendor/phpqrcode/qrlib.php'; // Sesuaikan path

        // Data yang ingin dijadikan QR
        $text = $_GET['text'];

        // Set header agar browser tahu ini gambar PNG
        header('Content-Type: image/png');

        // Generate & tampilkan langsung ke output (tanpa save file)
        QRcode::png($text);
    }
?>