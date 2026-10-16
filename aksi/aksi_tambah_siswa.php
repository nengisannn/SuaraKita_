<?php
// aksi/aksi_tambah_siswa.php - Tambah Siswa Secure & Modular dengan No WhatsApp

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/kelola_siswa.php");
    exit;
}

$nama       = sanitize_input($_POST['nama'] ?? '');
$nis        = sanitize_input($_POST['nis'] ?? '');
$kelas      = sanitize_input($_POST['kelas'] ?? '');
$no_hp      = sanitize_input($_POST['no_hp'] ?? '');
$sekolah_id = $_SESSION['sekolah_id'];

if (empty($nama) || empty($nis)) {
    header("Location: ../admin/tambah_siswa.php?error=empty");
    exit;
}

// Generate username otomatis
$username = strtolower(str_replace(' ', '', $nama)) . rand(100, 999);

// Hash password (menggunakan NIS sebagai password awal yang terenkripsi Bcrypt)
$hashed_password = hash_password($nis);

db_execute(
    "INSERT INTO users (nama, username, password, nis, kelas, no_hp, level, sekolah_id) VALUES (?, ?, ?, ?, ?, ?, 'siswa', ?)",
    [$nama, $username, $hashed_password, $nis, $kelas, $no_hp, $sekolah_id],
    "ssssssi"
);

header("Location: ../admin/kelola_siswa.php?pesan=sukses");
exit;
