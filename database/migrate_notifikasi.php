<?php
// database/migrate_notifikasi.php - Create notifikasi table for in-app & gateway notifications

require_once __DIR__ . '/../includes/db.php';

echo "<h2>🔔 SuaraKita - Migration Sistem Notifikasi</h2>";

$sql = "
CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `pengaduan_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `pengaduan_id` (`pengaduan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

$result = db_execute($sql);

if ($result !== false) {
    echo "✅ Tabel <code>notifikasi</code> berhasil dibuat / sudah tersedia.<br>";
} else {
    echo "❌ Gagal membuat tabel notifikasi.<br>";
}

echo "<hr><h3>Migrasi Notifikasi Selesai.</h3>";
