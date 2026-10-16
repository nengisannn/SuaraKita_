<?php
// Konfigurasi Database
$host = getenv('DB_HOST') ?: "127.0.0.1"; // nama host (127.0.0.1 untuk TCP connection)
$user = getenv('DB_USER') ?: "root";      // username database
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ""; // password database
$db   = getenv('DB_NAME') ?: "suarakita"; // nama database

// Konfigurasi WhatsApp API Gateway (Fonnte)
define('FONNTE_TOKEN', getenv('FONNTE_TOKEN') ?: "bK1p9PJFrpqN2PEPntGF"); 

// koneksi ke database
$config = mysqli_connect($host, $user, $pass, $db);

// cek koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit;
}
