<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // 1. Tabel activity_logs untuk Audit Trail
    db_execute("
        CREATE TABLE IF NOT EXISTS `activity_logs` (
          `id` INT AUTO_INCREMENT PRIMARY KEY,
          `sekolah_id` INT NOT NULL,
          `user_id` INT NOT NULL,
          `user_role` ENUM('admin','siswa') NOT NULL,
          `action` VARCHAR(100) NOT NULL,
          `description` TEXT NOT NULL,
          `ip_address` VARCHAR(45) NULL,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 2. Tambah kolom deadline_sla pada tabel pengaduan jika belum ada
    $check_col = db_fetch_one("SHOW COLUMNS FROM pengaduan LIKE 'deadline_sla'");
    if (!$check_col) {
        db_execute("ALTER TABLE pengaduan ADD COLUMN `deadline_sla` DATETIME NULL AFTER `urgensi`");
        
        // Update deadline SLA otomatis untuk data existing berdasarkan urgensi & created_at
        db_execute("
            UPDATE pengaduan SET deadline_sla = CASE 
                WHEN urgensi = 'darurat' THEN DATE_ADD(created_at, INTERVAL 1 DAY)
                WHEN urgensi = 'tinggi' THEN DATE_ADD(created_at, INTERVAL 2 DAY)
                WHEN urgensi = 'sedang' THEN DATE_ADD(created_at, INTERVAL 5 DAY)
                ELSE DATE_ADD(created_at, INTERVAL 7 DAY)
            END WHERE deadline_sla IS NULL
        ");
    }

    echo "✅ Migration Activity Logs & SLA Deadline Successful!\n";
} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}
