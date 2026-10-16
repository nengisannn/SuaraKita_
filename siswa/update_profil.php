<?php
// siswa/update_profil.php - Update Profil Siswa Secure dengan No WhatsApp & Bcrypt Password Hashing

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_siswa();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profil_siswa.php");
    exit;
}

$siswa_id = $_SESSION['siswa_id'];
$nama     = sanitize_input($_POST['nama'] ?? '');
$username = sanitize_input($_POST['username'] ?? '');
$kelas    = sanitize_input($_POST['kelas'] ?? '');
$no_hp    = sanitize_input($_POST['no_hp'] ?? '');
$password = $_POST['password'] ?? '';

if (!empty($password)) {
    $hashed_pass = hash_password($password);
    db_execute(
        "UPDATE users SET nama = ?, username = ?, kelas = ?, no_hp = ?, password = ? WHERE id = ? AND level = 'siswa'",
        [$nama, $username, $kelas, $no_hp, $hashed_pass, $siswa_id],
        "sssssi"
    );
} else {
    db_execute(
        "UPDATE users SET nama = ?, username = ?, kelas = ?, no_hp = ? WHERE id = ? AND level = 'siswa'",
        [$nama, $username, $kelas, $no_hp, $siswa_id],
        "ssssi"
    );
}

$_SESSION['siswa_username'] = $username;

header("Location: profil_siswa.php?pesan=berhasil");
exit;