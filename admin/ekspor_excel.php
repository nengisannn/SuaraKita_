<?php
// admin/ekspor_excel.php - Ekspor Rekap Pengaduan ke Excel (CSV UTF-8)

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

$sekolah_id = $_SESSION['sekolah_id'];

// Ambil info nama sekolah untuk judul file
$sekolah = db_fetch_one("SELECT nama_sekolah FROM sekolah WHERE id = ?", [$sekolah_id], "i");
$nama_sekolah = $sekolah['nama_sekolah'] ?? 'Institusi';

// Parameter Filter
$keyword     = sanitize_input($_GET['q'] ?? '');
$cat_filter  = sanitize_input($_GET['kategori'] ?? '');
$urg_filter  = sanitize_input($_GET['urgensi'] ?? '');
$stat_filter = sanitize_input($_GET['status'] ?? '');
$tgl_mulai   = sanitize_input($_GET['tgl_mulai'] ?? '');
$tgl_selesai = sanitize_input($_GET['tgl_selesai'] ?? '');

// Construct Dynamic Query
$conditions = ["p.sekolah_id = ?"];
$params     = [$sekolah_id];
$types      = "i";

if ($keyword !== '') {
    $conditions[] = "(u.nama LIKE ? OR u.kelas LIKE ? OR p.judul LIKE ? OR p.isi LIKE ?)";
    $likeKw = '%' . $keyword . '%';
    $params[] = $likeKw;
    $params[] = $likeKw;
    $params[] = $likeKw;
    $params[] = $likeKw;
    $types .= "ssss";
}

if ($cat_filter !== '') {
    $conditions[] = "p.kategori = ?";
    $params[]     = $cat_filter;
    $types       .= "s";
}

if ($urg_filter !== '') {
    $conditions[] = "p.urgensi = ?";
    $params[]     = $urg_filter;
    $types       .= "s";
}

if ($stat_filter !== '') {
    $conditions[] = "p.status = ?";
    $params[]     = $stat_filter;
    $types       .= "s";
}

if ($tgl_mulai !== '') {
    $conditions[] = "DATE(p.created_at) >= ?";
    $params[]     = $tgl_mulai;
    $types       .= "s";
}

if ($tgl_selesai !== '') {
    $conditions[] = "DATE(p.created_at) <= ?";
    $params[]     = $tgl_selesai;
    $types       .= "s";
}

$where_sql = implode(" AND ", $conditions);

$pengaduan_list = db_fetch_all("
    SELECT p.*, u.nama, u.nis, u.kelas
    FROM pengaduan p
    JOIN users u ON p.siswa_id = u.id
    WHERE $where_sql
    ORDER BY p.created_at DESC
", $params, $types);

// Header untuk Download File Excel (.csv)
$filename = "Rekap_Pengaduan_" . preg_replace('/[^a-zA-Z0-9]/', '_', $nama_sekolah) . "_" . date('Ymd_His') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// UTF-8 BOM untuk Microsoft Excel
fputs($output, "\xEF\xBB\xBF");

// Baris Judul Laporan
fputcsv($output, ["REKAPITULASI DOKUMEN PENGADUAN & ASPIRASI"]);
fputcsv($output, ["Institusi: " . $nama_sekolah]);
fputcsv($output, ["Tanggal Ekspor: " . date('d F Y H:i:s')]);
fputcsv($output, []); // Baris kosong

// Header Kolom Tabel
fputcsv($output, [
    'No',
    'ID Pengaduan',
    'Tanggal Masuk',
    'Nama Pelapor',
    'NIM / NIS',
    'Kelas / Prodi',
    'Kategori',
    'Tingkat Urgensi',
    'Judul Pengaduan',
    'Isi Pengaduan',
    'Status Pengaduan',
    'Alasan Penolakan (Jika Ada)',
    'Ada Lampiran Bukti'
]);

$no = 1;
foreach ($pengaduan_list as $row) {
    $is_anon = !empty($row['is_anonim']);
    fputcsv($output, [
        $no++,
        '#TK-' . str_pad($row['id'], 5, '0', STR_PAD_LEFT),
        date('d/m/Y H:i', strtotime($row['created_at'])),
        $is_anon ? 'Anonim (Dirahasiakan)' : $row['nama'],
        $is_anon ? '-' : ($row['nis'] ?? '-'),
        $is_anon ? '-' : ($row['kelas'] ?? '-'),
        $row['kategori'] ?? 'Fasilitas',
        ucfirst($row['urgensi'] ?? 'sedang'),
        $row['judul'],
        $row['isi'],
        ucfirst($row['status']),
        $row['alasan_penolakan'] ?? '-',
        !empty($row['lampiran']) ? 'Ya' : 'Tidak'
    ]);
}

fclose($output);
exit;
