<?php
// database/migrate_anonim.php - Add is_anonim column to existing database

require_once __DIR__ . '/../includes/db.php';

echo "<h2>🔒 SuaraKita - Migration Mode Pengaduan Anonim</h2>";

// Cek apakah kolom is_anonim sudah ada di tabel pengaduan
$columns = db_fetch_all("SHOW COLUMNS FROM pengaduan LIKE 'is_anonim'");

if (empty($columns)) {
    $result = db_execute("ALTER TABLE pengaduan ADD COLUMN is_anonim TINYINT(1) NOT NULL DEFAULT 0 AFTER lampiran");
    if ($result) {
        echo "✅ Kolom <strong>is_anonim</strong> berhasil ditambahkan ke tabel <code>pengaduan</code>.<br>";
    } else {
        echo "❌ Gagal menambahkan kolom is_anonim.<br>";
    }
} else {
    echo "ℹ️ Kolom <strong>is_anonim</strong> sudah ada di tabel <code>pengaduan</code>.<br>";
}

echo "<hr><h3>Migrasi Anonim Selesai.</h3>";
