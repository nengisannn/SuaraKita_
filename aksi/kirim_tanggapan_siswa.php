<?php
// aksi/kirim_tanggapan_siswa.php - Tanggapan Siswa Secure & Modular

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_siswa();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../siswa/dashboard_siswa.php");
    exit;
}

$pengaduan_id = filter_input(INPUT_POST, 'pengaduan_id', FILTER_VALIDATE_INT);
$pesan        = sanitize_input($_POST['isi'] ?? '');
$sekolah_id   = $_SESSION['sekolah_id'];
$siswa_id     = $_SESSION['siswa_id'];

if (!$pengaduan_id || empty($pesan)) {
    header("Location: ../siswa/detail_pengaduan.php?id=" . $pengaduan_id);
    exit;
}

// Validasi IDOR: Pastikan pengaduan benar milik siswa yang sedang login
$pengaduan = db_fetch_one(
    "SELECT id FROM pengaduan WHERE id = ? AND siswa_id = ? AND sekolah_id = ?",
    [$pengaduan_id, $siswa_id, $sekolah_id],
    "iii"
);

if (!$pengaduan) {
    http_response_code(403);
    die("Akses Ditolak: Tiket pengaduan tidak ditemukan atau bukan milik Anda.");
}

// Simpan tanggapan
db_execute(
    "INSERT INTO tanggapan_pengaduan (pengaduan_id, pengirim, pesan, created_at) VALUES (?, 'siswa', ?, NOW())",
    [$pengaduan_id, $pesan],
    "is"
);

// Kembalikan status ke diproses
db_execute(
    "UPDATE pengaduan SET status = 'diproses' WHERE id = ? AND sekolah_id = ? AND siswa_id = ?",
    [$pengaduan_id, $sekolah_id, $siswa_id],
    "iii"
);

header("Location: ../siswa/detail_pengaduan.php?id=" . $pengaduan_id);
exit;