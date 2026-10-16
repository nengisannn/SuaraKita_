<?php
// database/update_passwords.php - Migration tool to hash all existing plaintext passwords in database

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

echo "<h2>🔐 SuaraKita - Password Hash Migration Tool</h2>";

$users = db_fetch_all("SELECT id, username, password FROM users");
$updated_count = 0;

foreach ($users as $user) {
    // Jika password belum dalam bentuk Bcrypt hash ($2y$...)
    if (!str_starts_with($user['password'], '$2y$')) {
        $hashed = hash_password($user['password']);
        $success = db_execute("UPDATE users SET password = ? WHERE id = ?", [$hashed, $user['id']], "si");
        if ($success) {
            echo "✅ Password untuk user <strong>" . htmlspecialchars($user['username']) . "</strong> berhasil di-hash.<br>";
            $updated_count++;
        }
    } else {
        echo "ℹ️ User <strong>" . htmlspecialchars($user['username']) . "</strong> sudah menggunakan password terenkripsi.<br>";
    }
}

echo "<hr><h3>Migrasi Selesai. Total account di-update: {$updated_count}</h3>";
