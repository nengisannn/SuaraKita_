<?php
// database/migrate_rating.php - Add rating and ulasan_rating columns to pengaduan table

require_once __DIR__ . '/../includes/db.php';

echo "<h2>⭐ SuaraKita - Migration Rating Kepuasan (CSAT Feedback)</h2>";

// 1. Cek & Tambah kolom rating
$columns_rating = db_fetch_all("SHOW COLUMNS FROM pengaduan LIKE 'rating'");
if (empty($columns_rating)) {
    $result_rating = db_execute("ALTER TABLE pengaduan ADD COLUMN rating TINYINT(1) DEFAULT NULL AFTER status");
    if ($result_rating) {
        echo "✅ Kolom <strong>rating</strong> (1-5 Bintang) berhasil ditambahkan.<br>";
    }
} else {
    echo "ℹ️ Kolom <strong>rating</strong> sudah ada.<br>";
}

// 2. Cek & Tambah kolom ulasan_rating
$columns_ulasan = db_fetch_all("SHOW COLUMNS FROM pengaduan LIKE 'ulasan_rating'");
if (empty($columns_ulasan)) {
    $result_ulasan = db_execute("ALTER TABLE pengaduan ADD COLUMN ulasan_rating TEXT DEFAULT NULL AFTER rating");
    if ($result_ulasan) {
        echo "✅ Kolom <strong>ulasan_rating</strong> berhasil ditambahkan.<br>";
    }
} else {
    echo "ℹ️ Kolom <strong>ulasan_rating</strong> sudah ada.<br>";
}

echo "<hr><h3>Migrasi Rating CSAT Selesai.</h3>";
