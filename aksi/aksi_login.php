<?php
// aksi/aksi_login.php - Login Handler Modular & Secure

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../auth/login.php");
    exit;
}

$username = sanitize_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header("Location: ../auth/login.php?error=1");
    exit;
}

// Ambil user menggunakan Prepared Statement via db_fetch_one
$user = db_fetch_one("SELECT * FROM users WHERE username = ?", [$username], "s");

if ($user && verify_user_password($password, $user['password'])) {
    
    // Otomatis re-hash password ke Bcrypt jika password di DB masih plaintext
    if (password_get_info($user['password'])['algo'] === 0) {
        $newHash = hash_password($password);
        db_execute("UPDATE users SET password = ? WHERE id = ?", [$newHash, $user['id']], "si");
    }

    session_regenerate_id(true); // Regenerate session ID untuk keamanan dari Session Fixation

    $_SESSION['login']      = true;
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['level']      = $user['level'];
    $_SESSION['sekolah_id'] = $user['sekolah_id'];

    if ($user['level'] === 'admin') {
        $_SESSION['admin_id']       = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        header("Location: ../admin/dashboard_admin.php");
        exit;
    } elseif ($user['level'] === 'siswa') {
        $_SESSION['siswa_id']       = $user['id'];
        $_SESSION['siswa_username'] = $user['username'];
        header("Location: ../siswa/dashboard_siswa.php");
        exit;
    }
}

header("Location: ../auth/login.php?error=1");
exit;
