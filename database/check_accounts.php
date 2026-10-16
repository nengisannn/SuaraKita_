<?php
// database/check_accounts.php - Check all valid user accounts in DB

require_once __DIR__ . '/../includes/db.php';

$users = db_fetch_all("
    SELECT u.id, u.username, u.level, u.nama, u.nis, u.kelas, s.nama_sekolah
    FROM users u
    LEFT JOIN sekolah s ON u.sekolah_id = s.id
    ORDER BY u.level ASC, u.id ASC
");

header('Content-Type: application/json');
echo json_encode($users, JSON_PRETTY_PRINT);
