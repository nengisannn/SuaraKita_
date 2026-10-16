<?php
// database/migrate_urgensi.php - Add urgensi column to existing database

require_once __DIR__ . '/../includes/db.php';

echo "<h2>⚡ SuaraKita - Migration Tingkat Urgensi Pengaduan</h2>";

// Cek apakah kolom urgensi sudah ada di tabel pengaduan
$columns = db_fetch_all("SHOW COLUMNS FROM pengaduan LIKE 'urgensi'");

if (empty($columns)) {
    $result = db_execute("ALTER TABLE pengaduan ADD COLUMN urgensi ENUM('rendah','sedang','tinggi','darurat') NOT NULL DEFAULT 'sedang' AFTER kategori");
    if ($result) {
        echo "✅ Kolom <strong>urgensi</strong> berhasil ditambahkan ke tabel <code>pengaduan</code>.<br>";
    } else {
        echo "❌ Gagal menambahkan kolom urgensi.<br>";
    }
} else {
    echo "ℹ️ Kolom <strong>urgensi</strong> sudah ada di tabel <code>pengaduan</code>.<br>";
}

echo "<hr><h3>Migrasi Selesai.</h3>";
