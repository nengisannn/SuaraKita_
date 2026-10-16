<?php
// includes/security.php - Helper Keamanan & Sanitasi Input Modular

/**
 * Sanitasi string input dasar untuk mencegah XSS
 */
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi IDOR (memastikan entitas milik user / institusi yang sah)
 */
function check_ownership($resource_owner_id, $current_user_id) {
    if ((int)$resource_owner_id !== (int)$current_user_id) {
        http_response_code(403);
        die("Akses Ditolak: Anda tidak memiliki hak untuk melihat atau mengubah data ini.");
    }
}

/**
 * Generate CSRF Token
 */
function generate_csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifikasi CSRF Token dari form POST
 */
function verify_csrf_token($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Log Aktivitas User / Admin (Audit Trail)
 */
function log_activity($sekolah_id, $user_id, $user_role, $action, $description) {
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    db_execute(
        "INSERT INTO activity_logs (sekolah_id, user_id, user_role, action, description, ip_address) VALUES (?, ?, ?, ?, ?, ?)",
        [$sekolah_id, $user_id, $user_role, $action, $description, $ip_address],
        "iissss"
    );
}
