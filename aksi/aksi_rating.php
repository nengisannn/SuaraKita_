<?php
// aksi/aksi_rating.php - Submit Rating Kepuasan CSAT Student

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_siswa();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../siswa/dashboard_siswa.php");
    exit;
}

$pengaduan_id  = filter_input(INPUT_POST, 'pengaduan_id', FILTER_VALIDATE_INT);
$rating        = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
$ulasan_rating = sanitize_input($_POST['ulasan_rating'] ?? '');
$siswa_id      = $_SESSION['siswa_id'];
$sekolah_id    = $_SESSION['sekolah_id'];

if (!$pengaduan_id || !$rating || $rating < 1 || $rating > 5) {
    header("Location: ../siswa/detail_pengaduan.php?id=" . $pengaduan_id);
    exit;
}

// Validasi ownership & status 'selesai'
$pengaduan = db_fetch_one(
    "SELECT id FROM pengaduan WHERE id = ? AND siswa_id = ? AND sekolah_id = ? AND status = 'selesai'",
    [$pengaduan_id, $siswa_id, $sekolah_id],
    "iii"
);

if (!$pengaduan) {
    http_response_code(403);
    die("Akses Ditolak: Rating hanya bisa diberikan untuk pengaduan Anda yang telah selesai ditangani.");
}

// Update rating & ulasan
db_execute(
    "UPDATE pengaduan SET rating = ?, ulasan_rating = ? WHERE id = ? AND siswa_id = ?",
    [$rating, $ulasan_rating, $pengaduan_id, $siswa_id],
    "isii"
);

header("Location: ../siswa/detail_pengaduan.php?id=" . $pengaduan_id . "&pesan=rating_sukses");
exit;
