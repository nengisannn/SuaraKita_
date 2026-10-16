<?php
// admin/cetak_pdf.php - Cetak Dokumen Laporan Pengaduan (PDF Printable)

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

$sekolah_id = $_SESSION['sekolah_id'];

// Ambil info sekolah
$sekolah = db_fetch_one("SELECT * FROM sekolah WHERE id = ?", [$sekolah_id], "i");
$nama_sekolah = $sekolah['nama_sekolah'] ?? 'Institusi';
$alamat_sekolah = $sekolah['alamat'] ?? 'Garut, Jawa Barat';

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

// Ringkasan Statistik Laporan
$total_laporan = count($pengaduan_list);
$total_selesai = count(array_filter($pengaduan_list, fn($p) => $p['status'] === 'selesai'));
$total_proses  = count(array_filter($pengaduan_list, fn($p) => in_array($p['status'], ['diproses', 'ditinjau'])));
$total_ditolak = count(array_filter($pengaduan_list, fn($p) => $p['status'] === 'ditolak'));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan_Pengaduan_<?= htmlspecialchars($nama_sekolah) ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            margin: 20px;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0 0 0;
            font-size: 14pt;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 10pt;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .meta-info {
            font-size: 10pt;
            margin-bottom: 15px;
        }
        .stat-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .stat-box td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
            background: #f9f9f9;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
        }
        table.data-table th {
            background-color: #eee;
            text-align: center;
        }
        .footer-sign {
            margin-top: 40px;
            float: right;
            width: 250px;
            text-align: center;
            font-size: 11pt;
        }
        .footer-sign .space {
            height: 70px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Action bar (No Print) -->
    <div class="no-print" style="margin-bottom: 20px; background: #e0f2fe; padding: 12px; border-radius: 8px; border: 1px solid #7dd3fc; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-family: sans-serif; font-size: 14px; font-weight: bold; color: #0369a1;">📄 Dokumen Laporan Pengaduan Siap Dicetak / Disimpan PDF</span>
        <button onclick="window.print()" style="background: #0284c7; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">🖨️ Cetak / Simpan PDF</button>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="header">
        <h2>SISTEM PENGADUAN & ASPIRASI SUARAKITA</h2>
        <h3><?= htmlspecialchars($nama_sekolah) ?></h3>
        <p><?= htmlspecialchars($alamat_sekolah) ?></p>
    </div>

    <!-- Judul Laporan -->
    <div class="title">REKAPITULASI BUKTI LAPORAN PENGADUAN & ASPIRASI</div>

    <div class="meta-info">
        Tanggal Cetak: <strong><?= date('d F Y H:i') ?></strong><br>
        Periode Laporan: <strong><?= !empty($tgl_mulai) ? date('d/m/Y', strtotime($tgl_mulai)) : 'Awal' ?> s/d <?= !empty($tgl_selesai) ? date('d/m/Y', strtotime($tgl_selesai)) : 'Sekarang' ?></strong>
    </div>

    <!-- Ringkasan Statistik -->
    <table class="stat-box">
        <tr>
            <td><strong>Total Laporan:</strong> <?= $total_laporan ?></td>
            <td><strong>Selesai Ditangani:</strong> <?= $total_selesai ?></td>
            <td><strong>Sedang Diproses:</strong> <?= $total_proses ?></td>
            <td><strong>Ditolak:</strong> <?= $total_ditolak ?></td>
        </tr>
    </table>

    <!-- Tabel Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Pelapor</th>
                <th width="12%">Kategori</th>
                <th width="10%">Urgensi</th>
                <th width="28%">Judul & Isi Pengaduan</th>
                <th width="12%">Status</th>
                <th width="12%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pengaduan_list)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px;">Tidak ada data pengaduan yang sesuai dengan filter.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($pengaduan_list as $row): ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td>
                            <?php if (!empty($row['is_anonim'])): ?>
                                <strong>🔒 Anonim</strong><br>
                                <small>(Identitas Dirahasiakan)</small>
                            <?php else: ?>
                                <strong><?= htmlspecialchars($row['nama']) ?></strong><br>
                                <small>(<?= htmlspecialchars($row['kelas'] ?? '-') ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['kategori'] ?? 'Fasilitas') ?></td>
                        <td style="text-align: center;"><?= ucfirst(htmlspecialchars($row['urgensi'] ?? 'sedang')) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['judul']) ?></strong><br>
                            <small><?= htmlspecialchars(substr($row['isi'], 0, 100)) ?><?= strlen($row['isi']) > 100 ? '...' : '' ?></small>
                        </td>
                        <td style="text-align: center;">
                            <strong><?= ucfirst(htmlspecialchars($row['status'])) ?></strong>
                            <?php if ($row['status'] === 'ditolak' && !empty($row['alasan_penolakan'])): ?>
                                <br><small style="color: red;">(<?= htmlspecialchars($row['alasan_penolakan']) ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tanda Tangan Pimpinan -->
    <div class="footer-sign">
        Garut, <?= date('d F Y') ?><br>
        Mengetahui,<br>
        <strong>Pengelola Sistem SuaraKita</strong>
        <div class="space"></div>
        <u>( <?= htmlspecialchars($adminUsername) ?> )</u><br>
        NIP / NIDN: ...............................
    </div>

    <script>
        // Auto trigger print dialog jika dibuka di jendela baru
        window.addEventListener('load', function() {
            if (window.location.search.includes('autoprint=1')) {
                window.print();
            }
        });
    </script>
</body>
</html>
