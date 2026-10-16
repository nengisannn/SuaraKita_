<?php
// aksi/kirim_tanggapan_admin.php - Tanggapan Admin & Trigger Notifikasi Siswa

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/notification.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/dashboard_admin.php");
    exit;
}

$pengaduan_id = filter_input(INPUT_POST, 'pengaduan_id', FILTER_VALIDATE_INT);
$pesan        = sanitize_input($_POST['isi'] ?? '');
$sekolah_id   = $_SESSION['sekolah_id'];

if (!$pengaduan_id || empty($pesan)) {
    header("Location: ../admin/detail_pengaduan.php?id=" . $pengaduan_id);
    exit;
}

// Validasi tiket pengaduan ada dan milik sekolah admin
$pengaduan = db_fetch_one(
    "SELECT id, siswa_id, judul FROM pengaduan WHERE id = ? AND sekolah_id = ?",
    [$pengaduan_id, $sekolah_id],
    "ii"
);

if (!$pengaduan) {
    http_response_code(403);
    die("Akses Ditolak: Tiket pengaduan tidak ditemukan atau di luar wewenang sekolah Anda.");
}

// Simpan tanggapan admin
db_execute(
    "INSERT INTO tanggapan_pengaduan (pengaduan_id, pengirim, pesan, created_at) VALUES (?, 'admin', ?, NOW())",
    [$pengaduan_id, $pesan],
    "is"
);

// Trigger notifikasi ke siswa pelapor
$notif_text = "Admin telah membalas pengaduan Anda '" . $pengaduan['judul'] . "': \"" . substr($pesan, 0, 60) . "...\"";
create_notification($pengaduan['siswa_id'], $pengaduan_id, "💬 Tanggapan Baru dari Admin", $notif_text);

header("Location: ../admin/detail_pengaduan.php?id=" . $pengaduan_id);
exit;
