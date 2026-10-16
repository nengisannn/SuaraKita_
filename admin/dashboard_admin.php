<?php
// admin/dashboard_admin.php - Executive Dashboard Admin dengan Dark Mesh Theme & JetBrains Mono

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

$adminUsername = $_SESSION['admin_username'];
$sekolah_id    = $_SESSION['sekolah_id'];

// Ambil info nama sekolah
$sekolah_row  = db_fetch_one("SELECT nama_sekolah FROM sekolah WHERE id = ?", [$sekolah_id], "i");
$nama_sekolah = $sekolah_row['nama_sekolah'] ?? 'Institusi';

// Query Ringkasan Statistik Dashboard
$count_all = db_fetch_one("SELECT COUNT(*) as total FROM pengaduan WHERE sekolah_id = ?", [$sekolah_id], "i");
$total_pengaduan = $count_all['total'] ?? 0;

$count_proses = db_fetch_one("SELECT COUNT(*) as total FROM pengaduan WHERE sekolah_id = ? AND status IN ('terkirim','dikirim','ditinjau','diproses')", [$sekolah_id], "i");
$total_proses = $count_proses['total'] ?? 0;

$count_selesai = db_fetch_one("SELECT COUNT(*) as total FROM pengaduan WHERE sekolah_id = ? AND status = 'selesai'", [$sekolah_id], "i");
$total_selesai = $count_selesai['total'] ?? 0;

// Query Indeks Kepuasan CSAT
$rating_row  = db_fetch_one("SELECT AVG(rating) as avg_rating, COUNT(rating) as rated_count FROM pengaduan WHERE sekolah_id = ? AND rating IS NOT NULL", [$sekolah_id], "i");
$avg_rating  = $rating_row['avg_rating'] ? number_format($rating_row['avg_rating'], 1) : 0;
$rated_count = $rating_row['rated_count'] ?? 0;

// Query Jumlah Mahasiswa Terdaftar
$count_siswa = db_fetch_one("SELECT COUNT(*) as total FROM users WHERE sekolah_id = ? AND level = 'siswa'", [$sekolah_id], "i");
$total_siswa = $count_siswa['total'] ?? 0;

// Query Tren Bulanan (12 Bulan)
$monthly_data = db_fetch_all(
    "SELECT MONTH(created_at) as bulan, COUNT(*) as total 
     FROM pengaduan 
     WHERE sekolah_id = ? AND YEAR(created_at) = YEAR(CURRENT_DATE())
     GROUP BY MONTH(created_at)
     ORDER BY bulan ASC",
    [$sekolah_id],
    "i"
);

$bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$total_bulanan = array_fill(0, 12, 0);
foreach ($monthly_data as $row) {
    $idx = (int)$row['bulan'] - 1;
    if ($idx >= 0 && $idx < 12) {
        $total_bulanan[$idx] = (int)$row['total'];
    }
}

// 5 Pengaduan Terbaru
$recent_list = db_fetch_all(
    "SELECT p.*, u.nama, u.kelas 
     FROM pengaduan p 
     JOIN users u ON p.siswa_id = u.id 
     WHERE p.sekolah_id = ? 
     ORDER BY p.created_at DESC LIMIT 5",
    [$sekolah_id],
    "i"
);
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | SuaraKita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <div class="p-6 md:p-8 mt-16 flex-1 space-y-6">
        
        <!-- Welcome Hero Banner -->
        <div class="relative overflow-hidden bg-zinc-900/60 backdrop-blur-xl rounded-2xl p-7 text-white shadow-2xl border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="relative z-10 space-y-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-semibold bg-teal-500/10 text-teal-400 border border-teal-500/20 mb-1">
                    🏢 <?= htmlspecialchars($nama_sekolah) ?>
                </span>
                <h2 class="text-2xl font-extrabold tracking-tight text-white">
                    Dashboard Pengelola Admin
                </h2>
                <p class="text-zinc-400 text-xs md:text-sm max-w-xl leading-relaxed">
                    Pantau seluruh laporan, evaluasi indeks kepuasan, dan tanggapi aspirasi mahasiswa secara cepat &amp; efisien.
                </p>
            </div>
            <div class="relative z-10 flex items-center gap-3">
                <a href="pengaduan.php" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-500 text-white px-5 py-2.5 rounded-xl text-xs font-mono font-bold transition-all active:scale-[0.98] shadow-lg shadow-teal-900/30">
                    <span>Kelola Pengaduan</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- STATISTIC WIDGETS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card Total -->
            <div class="bg-zinc-900/60 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Total Laporan</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-extrabold text-white font-mono tracking-tight"><?= number_format($total_pengaduan) ?></span>
                        <span class="text-[11px] font-mono text-zinc-500">aduan</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 text-lg">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>

            <!-- Card Diproses -->
            <div class="bg-zinc-900/60 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Perlu Penanganan</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-extrabold text-amber-400 font-mono tracking-tight"><?= number_format($total_proses) ?></span>
                        <span class="text-[11px] font-mono text-zinc-500">aktif</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 text-lg">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>

            <!-- Card Selesai -->
            <div class="bg-zinc-900/60 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Tuntas Selesai</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-extrabold text-emerald-400 font-mono tracking-tight"><?= number_format($total_selesai) ?></span>
                        <span class="text-[11px] font-mono text-zinc-500">tuntas</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <!-- Card CSAT Average -->
            <div class="bg-zinc-900/60 backdrop-blur-xl p-5 rounded-2xl border border-white/10 shadow-xl flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-zinc-400 uppercase tracking-wider block mb-1">Kepuasan CSAT</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-extrabold text-teal-400 font-mono tracking-tight"><?= $avg_rating > 0 ? $avg_rating : '-' ?></span>
                        <span class="text-[11px] font-mono text-zinc-500">/ 5.0 (<?= $rated_count ?>)</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 text-lg">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>

        </div>

        <!-- CHART & RECENT DATA SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Chart Container (2-Col) -->
            <div class="lg:col-span-2 bg-zinc-900/60 backdrop-blur-xl rounded-2xl p-6 shadow-2xl border border-white/10 space-y-4">
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-white tracking-tight">Tren Pengaduan Bulanan</h3>
                        <p class="text-xs text-zinc-400">Volume laporan yang masuk sepanjang tahun <?= date('Y') ?></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-semibold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                        ANALYTICS
                    </span>
                </div>
                <div class="relative h-60">
                    <canvas id="chartPengaduan"></canvas>
                </div>
            </div>

            <!-- Audit Logs & System Stats (1-Col) -->
            <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl p-6 shadow-2xl border border-white/10 flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between border-b border-white/10 pb-3 mb-4">
                        <span class="text-xs font-mono font-bold text-zinc-300 uppercase tracking-wider">Audit Activity Log</span>
                        <span class="text-[10px] font-mono bg-teal-500/20 text-teal-300 px-2 py-0.5 rounded font-bold">Real-time</span>
                    </div>
                    <?php
                    $logs = db_fetch_all("SELECT * FROM activity_logs WHERE sekolah_id = ? ORDER BY created_at DESC LIMIT 3", [$sekolah_id], "i");
                    ?>
                    <div class="space-y-3 text-xs">
                        <?php if (empty($logs)): ?>
                            <p class="text-zinc-500 italic text-center py-4">Belum ada catatan aktivitas.</p>
                        <?php else: ?>
                            <?php foreach ($logs as $l): ?>
                                <div class="p-3 rounded-xl bg-zinc-950/60 border border-white/5 space-y-1">
                                    <div class="flex items-center justify-between text-[11px] font-semibold text-zinc-200">
                                        <span class="font-mono text-teal-400"><?= htmlspecialchars($l['action']) ?></span>
                                        <span class="text-[10px] text-zinc-500 font-mono"><?= date('H:i', strtotime($l['created_at'])) ?></span>
                                    </div>
                                    <p class="text-[11px] text-zinc-400 line-clamp-1"><?= htmlspecialchars($l['description']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs font-mono">
                    <span class="text-zinc-400">Mahasiswa Terdaftar:</span>
                    <span class="font-extrabold text-teal-400"><?= number_format($total_siswa) ?> Akun</span>
                </div>
            </div>

        </div>

        <!-- RECENT COMPLAINTS LIST -->
        <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 overflow-hidden">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white tracking-tight">Laporan Pengaduan Terbaru</h3>
                    <p class="text-xs text-zinc-400">5 pengaduan terakhir yang masuk dari mahasiswa.</p>
                </div>
                <a href="pengaduan.php" class="text-xs font-mono font-semibold text-teal-400 hover:text-teal-300 transition">
                    Lihat Selengkapnya ➔
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-950/60 text-zinc-400 uppercase font-mono tracking-wider text-[10px] border-b border-white/10">
                        <tr>
                            <th class="px-5 py-4">Pelapor</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Judul Pengaduan</th>
                            <th class="px-5 py-4">Urgensi</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($recent_list)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-10 text-zinc-500 italic">
                                    Belum ada pengaduan yang masuk.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_list as $row): ?>
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="px-5 py-4">
                                        <?php if (!empty($row['is_anonim'])): ?>
                                            <div class="font-medium text-teal-400 flex items-center gap-1.5">🔒 Anonim</div>
                                        <?php else: ?>
                                            <div class="font-medium text-zinc-200 text-sm"><?= htmlspecialchars($row['nama']) ?></div>
                                            <div class="text-[10px] text-zinc-400 font-mono"><?= htmlspecialchars($row['kelas']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-zinc-800 text-zinc-300 border border-white/10 font-mono text-[11px]">
                                            <?= htmlspecialchars($row['kategori'] ?? 'Fasilitas') ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-zinc-200 text-sm max-w-xs truncate"><?= htmlspecialchars($row['judul']) ?></div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <?php
                                        $urg_badge = match(strtolower($row['urgensi'] ?? 'sedang')) {
                                            'rendah' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'sedang' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                                            'tinggi' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'darurat' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            default => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20'
                                        };
                                        ?>
                                        <span class="px-2.5 py-1 rounded-full border text-[11px] font-medium font-mono <?= $urg_badge ?>">
                                            <?= ucfirst(htmlspecialchars($row['urgensi'] ?? 'sedang')) ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <?php
                                        $stat_badge = match(strtolower($row['status'] ?? 'terkirim')) {
                                            'terkirim', 'dikirim' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'ditinjau' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                            'diproses' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                                            'selesai'  => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'ditolak'  => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            default    => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20'
                                        };
                                        ?>
                                        <span class="px-2.5 py-1 rounded-full border text-[11px] font-medium font-mono <?= $stat_badge ?>">
                                            <?= ucfirst(htmlspecialchars($row['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="detail_pengaduan.php?id=<?= $row['id'] ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-500/10 hover:bg-teal-500/20 text-teal-400 border border-teal-500/30 rounded-lg text-xs font-semibold transition">
                                            <span>Respon</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<script>
    const ctx = document.getElementById('chartPengaduan').getContext('2d');
    const chartPengaduan = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($bulan_labels) ?>,
            datasets: [{
                label: 'Jumlah Pengaduan',
                data: <?= json_encode($total_bulanan) ?>,
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#14b8a6',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#a1a1aa', font: { family: 'JetBrains Mono', size: 10 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { stepSize: 1, color: '#a1a1aa', font: { family: 'JetBrains Mono', size: 10 } }
                }
            }
        }
    });
</script>

<?php include __DIR__ . '/partials/ai_widget.php'; ?>
</body>
</html>
