<?php
// database/migrate_kategori.php - Add kategori column to existing database

require_once __DIR__ . '/../includes/db.php';

echo "<h2>🏷️ SuaraKita - Migration Kategori Pengaduan</h2>";

// Cek apakah kolom kategori sudah ada di tabel pengaduan
$columns = db_fetch_all("SHOW COLUMNS FROM pengaduan LIKE 'kategori'");

if (empty($columns)) {
    $result = db_execute("ALTER TABLE pengaduan ADD COLUMN kategori VARCHAR(50) NOT NULL DEFAULT 'Fasilitas' AFTER sekolah_id");
    if ($result) {
        echo "✅ Kolom <strong>kategori</strong> berhasil ditambahkan ke tabel <code>pengaduan</code>.<br>";
    } else {
        echo "❌ Gagal menambahkan kolom kategori.<br>";
    }
} else {
    echo "ℹ️ Kolom <strong>kategori</strong> sudah ada di tabel <code>pengaduan</code>.<br>";
}

echo "<hr><h3>Migrasi Selesai.</h3>";
