<?php
// admin/pengaduan.php - Kelola Data Pengaduan Admin dengan Dark Mesh Theme & JetBrains Mono

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

$adminUsername = $_SESSION['admin_username'];
$sekolah_id    = $_SESSION['sekolah_id'];

// Filter & Parameter Search
$keyword     = sanitize_input($_GET['q'] ?? '');
$cat_filter  = sanitize_input($_GET['kategori'] ?? '');
$urg_filter  = sanitize_input($_GET['urgensi'] ?? '');
$stat_filter = sanitize_input($_GET['status'] ?? '');
$tgl_mulai   = sanitize_input($_GET['tgl_mulai'] ?? '');
$tgl_selesai = sanitize_input($_GET['tgl_selesai'] ?? '');

$perPageOptions = [10, 20, 50, 100];
$perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $perPageOptions) ? (int)$_GET['per_page'] : 10;
$page    = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset  = ($page - 1) * $perPage;

$conditions = ["p.sekolah_id = ?"];
$params     = [$sekolah_id];
$types      = "i";

if ($keyword !== '') {
    $conditions[] = "(p.judul LIKE ? OR p.isi LIKE ? OR u.nama LIKE ? OR u.kelas LIKE ?)";
    $likeKw = '%' . $keyword . '%';
    array_push($params, $likeKw, $likeKw, $likeKw, $likeKw);
    $types .= "ssss";
}

if ($cat_filter !== '') {
    $conditions[] = "p.kategori = ?";
    $params[] = $cat_filter;
    $types .= "s";
}

if ($urg_filter !== '') {
    $conditions[] = "p.urgensi = ?";
    $params[] = $urg_filter;
    $types .= "s";
}

if ($stat_filter !== '') {
    $conditions[] = "p.status = ?";
    $params[] = $stat_filter;
    $types .= "s";
}

if ($tgl_mulai !== '') {
    $conditions[] = "DATE(p.created_at) >= ?";
    $params[] = $tgl_mulai;
    $types .= "s";
}

if ($tgl_selesai !== '') {
    $conditions[] = "DATE(p.created_at) <= ?";
    $params[] = $tgl_selesai;
    $types .= "s";
}

$where_sql = implode(" AND ", $conditions);

// Hitung Total Rows
$countRow = db_fetch_one("SELECT COUNT(*) as total FROM pengaduan p JOIN users u ON p.siswa_id = u.id WHERE $where_sql", $params, $types);
$totalData  = $countRow['total'] ?? 0;
$totalPages = ceil($totalData / $perPage) ?: 1;

// Fetch Complaints List
$data_params = array_merge($params, [$perPage, $offset]);
$data_types  = $types . "ii";

$pengaduan_list = db_fetch_all(
    "SELECT p.*, u.nama, u.kelas
     FROM pengaduan p
     JOIN users u ON p.siswa_id = u.id
     WHERE $where_sql
     ORDER BY p.created_at DESC
     LIMIT ? OFFSET ?",
    $data_params,
    $data_types
);

$categories = ['Fasilitas', 'Akademik', 'Keuangan', 'Kebersihan', 'Keamanan', 'Lainnya'];
$urgencies  = ['rendah', 'sedang', 'tinggi', 'darurat'];
$statuses   = ['terkirim', 'ditinjau', 'diproses', 'selesai', 'ditolak'];
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengaduan | Admin SuaraKita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="../assets/js/tailwindcss.js"></script>
    <script>window.tailwind || document.write('<script src="https://cdn.tailwindcss.com"><\/script>')</script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #09090b;
            color: #f4f4f5;
        }
        .bg-mesh {
            position: relative;
            background-color: #09090b;
        }
        .bg-mesh::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image: 
                radial-gradient(circle at 15% 20%, rgba(20, 184, 166, 0.22) 0px, transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(13, 148, 136, 0.18) 0px, transparent 50%),
                radial-gradient(circle at 50% 30%, rgba(30, 41, 59, 0.6) 0px, transparent 65%),
                radial-gradient(circle at 80% 10%, rgba(16, 185, 129, 0.15) 0px, transparent 40%),
                radial-gradient(circle at 20% 85%, rgba(6, 182, 212, 0.15) 0px, transparent 45%);
            filter: blur(60px);
            opacity: 0.95;
            pointer-events-none: none;
        }
        main, aside, header, #aiChatWidget {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-zinc-950 bg-mesh min-h-screen text-zinc-100 selection:bg-teal-500/30 selection:text-teal-200">

<?php include 'partials/sidebar.php'; ?>

<main class="ml-64 min-h-screen flex flex-col">
<?php include 'partials/header.php'; ?>

<section class="p-6 md:p-8 mt-16 flex-1 space-y-6">

    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-zinc-900/60 backdrop-blur-xl p-6 rounded-2xl border border-white/10 shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-teal-500/10 text-teal-400 border border-teal-500/20">
                    <i class="fa-solid fa-list-check mr-1.5"></i> DISPATCH LOG
                </span>
                <span class="text-xs text-zinc-400 font-mono">Total <?= number_format($totalData) ?> Pengaduan</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Kelola Data Pengaduan</h1>
            <p class="text-xs text-zinc-400">Gunakan pencarian dan filter untuk menyaring laporan siswa/mahasiswa.</p>
        </div>
        
        <?php $export_query = http_build_query($_GET); ?>
        <div class="flex flex-wrap items-center gap-2">
            <a href="cetak_pdf.php?<?= $export_query ?>" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-xl text-xs font-mono font-semibold transition">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Cetak PDF</span>
            </a>
            <a href="ekspor_excel.php?<?= $export_query ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl text-xs font-mono font-semibold transition">
                <i class="fa-solid fa-file-excel"></i>
                <span>Ekspor Excel</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-zinc-900/60 backdrop-blur-xl p-6 rounded-2xl border border-white/10 shadow-xl">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-400 mb-1.5">🔍 Kata Kunci</label>
                    <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari Judul, Isi, Nama, atau Kelas..."
                        class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-2 text-xs text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50">
                </div>

                <!-- Kategori Filter -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-400 mb-1.5">📂 Kategori</label>
                    <select name="kategori" class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-3 py-2 text-xs text-zinc-200 focus:outline-none">
                        <option value="">-- Semua Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat ?>" <?= $cat_filter === $cat ? 'selected' : '' ?> class="bg-zinc-900 text-zinc-200"><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Urgensi Filter -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-400 mb-1.5">⚡ Urgensi</label>
                    <select name="urgensi" class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-3 py-2 text-xs text-zinc-200 font-mono focus:outline-none">
                        <option value="">-- Semua Urgensi --</option>
                        <?php foreach ($urgencies as $urg): ?>
                            <option value="<?= $urg ?>" <?= $urg_filter === $urg ? 'selected' : '' ?> class="bg-zinc-900 text-zinc-200"><?= ucfirst($urg) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-400 mb-1.5">📊 Status</label>
                    <select name="status" class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-3 py-2 text-xs text-zinc-200 font-mono focus:outline-none">
                        <option value="">-- Semua Status --</option>
                        <?php foreach ($statuses as $st): ?>
                            <option value="<?= $st ?>" <?= $stat_filter === $st ? 'selected' : '' ?> class="bg-zinc-900 text-zinc-200"><?= ucfirst($st) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-400 mb-1.5">📅 Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="<?= htmlspecialchars($tgl_mulai) ?>" class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-zinc-200 focus:outline-none">
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-400 mb-1.5">📅 Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="<?= htmlspecialchars($tgl_selesai) ?>" class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-zinc-200 focus:outline-none">
                </div>

                <!-- Tombol Action -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-500 text-white px-4 py-2 rounded-xl text-xs font-mono font-semibold transition active:scale-[0.98]">
                        Terapkan
                    </button>
                    <a href="pengaduan.php" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 border border-white/10 px-4 py-2 rounded-xl text-xs font-mono transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-950/60 text-zinc-400 uppercase font-mono tracking-wider text-[10px] border-b border-white/10">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Pelapor</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Judul Pengaduan</th>
                        <th class="px-5 py-4">Urgensi</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($pengaduan_list)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-12 text-zinc-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-regular fa-folder-open text-3xl text-zinc-600"></i>
                                    <p class="text-sm text-zinc-400">Tidak ditemukan pengaduan yang sesuai dengan filter.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = $offset + 1; foreach ($pengaduan_list as $p): ?>
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="px-5 py-4 font-mono text-zinc-500">#<?= sprintf('%03d', $no++) ?></td>
                                <td class="px-5 py-4">
                                    <?php if (!empty($p['is_anonim'])): ?>
                                        <div class="font-medium text-teal-400 flex items-center gap-1.5">🔒 Anonim</div>
                                        <div class="text-[10px] text-zinc-500 font-mono">Identitas Dirahasiakan</div>
                                    <?php else: ?>
                                        <div class="font-medium text-zinc-200 text-sm"><?= htmlspecialchars($p['nama']) ?></div>
                                        <div class="text-[10px] text-zinc-400 font-mono"><?= htmlspecialchars($p['kelas']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-zinc-800 text-zinc-300 border border-white/10 font-mono text-[11px]">
                                        <?= htmlspecialchars($p['kategori'] ?? 'Fasilitas') ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <?php
                                    $urg = strtolower($p['urgensi'] ?? 'sedang');
                                    $senti_text = match($urg) {
                                        'darurat' => '⚠️ Kritis/Urgent',
                                        'tinggi'  => '⚡ Sentimen Tinggi',
                                        'sedang'  => '🟢 Sentimen Normal',
                                        'rendah'  => '💬 Informasi',
                                        default   => '🟢 Sentimen Normal'
                                    };
                                    $senti_color = match($urg) {
                                        'darurat' => 'text-rose-400 bg-rose-500/10 border-rose-500/20',
                                        'tinggi'  => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
                                        default   => 'text-teal-400 bg-teal-500/10 border-teal-500/20'
                                    };
                                    ?>
                                    <div class="font-medium text-zinc-200 text-sm max-w-xs truncate"><?= htmlspecialchars($p['judul']) ?></div>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-mono px-2 py-0.5 rounded-full border <?= $senti_color ?>">
                                            <i class="fa-solid fa-brain text-[9px]"></i> <?= $senti_text ?>
                                        </span>
                                        <?php if (!empty($p['lampiran'])): ?>
                                            <span class="inline-flex items-center gap-1 text-[10px] text-teal-400 font-mono">
                                                <i class="fa-solid fa-paperclip text-[9px]"></i> Bukti
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <?php
                                    $urgency_badge = match(strtolower($p['urgensi'] ?? 'sedang')) {
                                        'rendah' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'sedang' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                                        'tinggi' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'darurat' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                        default => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20'
                                    };
                                    ?>
                                    <span class="px-2.5 py-1 rounded-full border text-[11px] font-medium font-mono <?= $urgency_badge ?>">
                                        <?= ucfirst(htmlspecialchars($p['urgensi'] ?? 'sedang')) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <?php
                                    $status_badge = match(strtolower($p['status'] ?? 'terkirim')) {
                                        'terkirim', 'dikirim' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'ditinjau' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                        'diproses' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                                        'selesai'  => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'ditolak'  => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                        default    => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20'
                                    };
                                    ?>
                                    <span class="px-2.5 py-1 rounded-full border text-[11px] font-medium font-mono <?= $status_badge ?>">
                                        <?= ucfirst(htmlspecialchars($p['status'])) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-mono text-zinc-400 text-[11px]">
                                    <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="detail_pengaduan.php?id=<?= $p['id']; ?>"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-500/10 hover:bg-teal-500/20 text-teal-400 border border-teal-500/30 rounded-lg text-xs font-semibold transition">
                                        <span>Detail</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <?php if ($totalPages > 1): ?>
        <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-t border-white/10 bg-zinc-950/40 gap-3">
            <div class="text-xs text-zinc-400 font-mono">
                Halaman <strong><?= $page ?></strong> dari <strong><?= $totalPages ?></strong> (Total <strong><?= $totalData ?></strong> pengaduan)
            </div>
            <div class="flex items-center gap-1.5">
                <?php
                $queryParams = $_GET;
                for ($i = 1; $i <= $totalPages; $i++):
                    $queryParams['page'] = $i;
                    $link = '?' . http_build_query($queryParams);
                ?>
                    <a href="<?= $link ?>"
                        class="px-3 py-1.5 text-xs font-mono font-medium rounded-lg border transition <?= $i == $page ? 'bg-teal-600 text-white border-teal-500 shadow-md shadow-teal-900/30' : 'bg-zinc-900 text-zinc-400 border-white/10 hover:bg-zinc-800 hover:text-white' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

</main>
<?php include __DIR__ . '/partials/ai_widget.php'; ?>
</body>
</html>
