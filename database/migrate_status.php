<?php
// database/migrate_status.php - Update status enum and add alasan_penolakan column

require_once __DIR__ . '/../includes/db.php';

echo "<h2>🔄 SuaraKita - Migration Alur Status Pengaduan & Alasan Penolakan</h2>";

// 1. Update ENUM status di tabel pengaduan
$result_status = db_execute("ALTER TABLE pengaduan MODIFY COLUMN status ENUM('dikirim','terkirim','ditinjau','diproses','selesai','ditolak') DEFAULT 'terkirim'");
if ($result_status) {
    echo "✅ Enum <strong>status</strong> berhasil diperbarui (terkirim, ditinjau, diproses, selesai, ditolak).<br>";
} else {
    echo "ℹ️ Enum status sudah diperbarui atau tidak ada perubahan.<br>";
}

// 2. Cek dan tambah kolom alasan_penolakan
$columns = db_fetch_all("SHOW COLUMNS FROM pengaduan LIKE 'alasan_penolakan'");
if (empty($columns)) {
    $result_alasan = db_execute("ALTER TABLE pengaduan ADD COLUMN alasan_penolakan TEXT DEFAULT NULL AFTER status");
    if ($result_alasan) {
        echo "✅ Kolom <strong>alasan_penolakan</strong> berhasil ditambahkan ke tabel <code>pengaduan</code>.<br>";
    } else {
        echo "❌ Gagal menambahkan kolom alasan_penolakan.<br>";
    }
} else {
    echo "ℹ️ Kolom <strong>alasan_penolakan</strong> sudah ada di tabel <code>pengaduan</code>.<br>";
}

echo "<hr><h3>Migrasi Status Selesai.</h3>";
