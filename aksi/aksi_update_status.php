<?php
// aksi/aksi_update_status.php - Update Status Pengaduan & Trigger Notifikasi

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/notification.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/dashboard_admin.php");
    exit;
}

$id               = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status           = sanitize_input($_POST['status'] ?? '');
$alasan_penolakan = sanitize_input($_POST['alasan_penolakan'] ?? '');
$sekolah_id       = $_SESSION['sekolah_id'];

$allowed_statuses = ['dikirim', 'terkirim', 'ditinjau', 'diproses', 'selesai', 'ditolak'];

if ($id && in_array($status, $allowed_statuses, true)) {
    // Ambil data pengaduan & siswa_id untuk notifikasi
    $pengaduan = db_fetch_one("SELECT siswa_id, judul FROM pengaduan WHERE id = ? AND sekolah_id = ?", [$id, $sekolah_id], "ii");

    if ($pengaduan) {
        if ($status === 'ditolak') {
            db_execute(
                "UPDATE pengaduan SET status = ?, alasan_penolakan = ? WHERE id = ? AND sekolah_id = ?",
                [$status, $alasan_penolakan, $id, $sekolah_id],
                "ssii"
            );
            $notif_pesan = "Status pengaduan '" . $pengaduan['judul'] . "' diubah menjadi Ditolak. Alasan: " . ($alasan_penolakan ?: 'Tidak ada alasan spesifik.');
            create_notification($pengaduan['siswa_id'], $id, "❌ Status Pengaduan Ditolak", $notif_pesan);
        } else {
            db_execute(
                "UPDATE pengaduan SET status = ?, alasan_penolakan = NULL WHERE id = ? AND sekolah_id = ?",
                [$status, $id, $sekolah_id],
                "sii"
            );
            $notif_pesan = "Status pengaduan '" . $pengaduan['judul'] . "' diperbarui menjadi '" . ucfirst($status) . "'.";
            create_notification($pengaduan['siswa_id'], $id, "🔄 Pembaruan Status Pengaduan", $notif_pesan);
        }

        log_activity($sekolah_id, $_SESSION['admin_id'], 'admin', 'Update Status', "Mengubah status pengaduan #$id menjadi $status");
    }
}

header("Location: ../admin/detail_pengaduan.php?id=" . $id);
exit;
