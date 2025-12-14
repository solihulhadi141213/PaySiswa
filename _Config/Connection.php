<?php
    //Ini adalah halaman untuk melakukan konfigurasi database
    $servername = "localhost";
    $username = "root";
    $password = "arunaparasilvanursari";
    $db = "pay_siswa";
    
    // Create connection
    $Conn = new mysqli($servername, $username, $password, $db);
    
    // Check connection
    if ($Conn->connect_error) {
        die("Connection failed: " . $Conn->connect_error);
    }
?>