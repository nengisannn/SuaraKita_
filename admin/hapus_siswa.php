<?php
// admin/hapus_siswa.php - Hapus Siswa Secure & Modular

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$sekolah_id = $_SESSION['sekolah_id'];

if ($id) {
    db_execute(
        "DELETE FROM users WHERE id = ? AND level = 'siswa' AND sekolah_id = ?",
        [$id, $sekolah_id],
        "ii"
    );
}

header("Location: kelola_siswa.php");
exit;