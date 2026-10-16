<?php
// database/migrate_lampiran.php - Add lampiran column to existing database

require_once __DIR__ . '/../includes/db.php';

echo "<h2>📎 SuaraKita - Migration Upload Bukti Lampiran</h2>";

// Cek apakah kolom lampiran sudah ada di tabel pengaduan
$columns = db_fetch_all("SHOW COLUMNS FROM pengaduan LIKE 'lampiran'");

if (empty($columns)) {
    $result = db_execute("ALTER TABLE pengaduan ADD COLUMN lampiran VARCHAR(255) DEFAULT NULL AFTER isi");
    if ($result) {
        echo "✅ Kolom <strong>lampiran</strong> berhasil ditambahkan ke tabel <code>pengaduan</code>.<br>";
    } else {
        echo "❌ Gagal menambahkan kolom lampiran.<br>";
    }
} else {
    echo "ℹ️ Kolom <strong>lampiran</strong> sudah ada di tabel <code>pengaduan</code>.<br>";
}

echo "<hr><h3>Migrasi Selesai.</h3>";
