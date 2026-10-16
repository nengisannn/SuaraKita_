<?php
// includes/auth.php - Helper Autentikasi, Password & Guard Akses Modular

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Cek apakah pengguna sudah login
 */
function is_logged_in() {
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
}

/**
 * Guard khusus Admin (wajib login & level admin)
 */
function require_admin($redirect_url = "../auth/login.php") {
    if (!is_logged_in() || ($_SESSION['level'] ?? '') !== 'admin') {
        header("Location: " . $redirect_url);
        exit;
    }
}

/**
 * Guard khusus Siswa (wajib login & level siswa)
 */
function require_siswa($redirect_url = "../auth/login.php") {
    if (!is_logged_in() || ($_SESSION['level'] ?? '') !== 'siswa') {
        header("Location: " . $redirect_url);
        exit;
    }
}

/**
 * Enkripsi password menggunakan Bcrypt
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verifikasi password (mendukung Bcrypt dan migrasi password lama plaintext)
 */
function verify_user_password($input_password, $stored_password) {
    // Jika password di DB adalah hash Bcrypt ($2y$, $2a$, $2b$)
    if (str_starts_with($stored_password, '$2y$') || str_starts_with($stored_password, '$2a$') || str_starts_with($stored_password, '$2b$')) {
        return password_verify($input_password, $stored_password);
    }
    // Jika password di DB masih plaintext
    return $input_password === $stored_password;
}
