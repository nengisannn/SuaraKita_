<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_siswa();

$siswaUsername = $_SESSION['siswa_username'];
$siswa_id      = $_SESSION['siswa_id'];
$sekolah_id    = $_SESSION['sekolah_id'];

// Ambil statistik pengaduan siswa ini
$total_pengaduan = db_fetch_one("SELECT COUNT(id) AS count FROM pengaduan WHERE sekolah_id = ? AND siswa_id = ?", [$sekolah_id, $siswa_id], "ii")['count'] ?? 0;
$total_proses    = db_fetch_one("SELECT COUNT(id) AS count FROM pengaduan WHERE sekolah_id = ? AND siswa_id = ? AND status IN ('terkirim','ditinjau','diproses')", [$sekolah_id, $siswa_id], "ii")['count'] ?? 0;
$total_selesai   = db_fetch_one("SELECT COUNT(id) AS count FROM pengaduan WHERE sekolah_id = ? AND siswa_id = ? AND status = 'selesai'", [$sekolah_id, $siswa_id], "ii")['count'] ?? 0;

// Ambil 5 pengaduan terbaru
$recent_list = db_fetch_all("SELECT * FROM pengaduan WHERE sekolah_id = ? AND siswa_id = ? ORDER BY created_at DESC LIMIT 5", [$sekolah_id, $siswa_id], "ii");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa | SuaraKita</title>
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="../assets/js/tailwindcss.js"></script>
    <script>window.tailwind || document.write('<script src="https://cdn.tailwindcss.com"><\/script>')</script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                },
            },
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
            pointer-events: none;
        }
        main, aside, header, #aiChatWidget {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-zinc-950 bg-mesh min-h-screen text-zinc-100 selection:bg-teal-500/30 selection:text-teal-200">

<?php include 'partials/sidebar.php'; ?>

<main class="ml-64 min-h-screen pb-20">
    <?php include 'partials/header.php'; ?>

    <div class="p-8 mt-20 max-w-7xl mx-auto space-y-6">
        
        <!-- Welcome Hero (Competition Grade Glassmorphism Hero) -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-950/70 via-slate-900 to-slate-950 rounded-3xl p-8 border border-emerald-500/30 shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-6 group">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none group-hover:scale-125 transition duration-700"></div>
            
            <div class="relative z-10 space-y-2">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                    PORTAL ASPIRASI DIGITAL
                </span>
                <h2 class="text-3xl font-extrabold tracking-tight text-white">
                    Selamat Datang, <?= htmlspecialchars($siswaUsername); ?>! 👋
                </h2>
                <p class="text-slate-400 text-xs md:text-sm max-w-xl leading-relaxed">
                    Sampaikan keluhan dan aspirasi Anda secara aman, terenkripsi, dan transparan demi kemajuan civitas akademika.
                </p>
            </div>

            <div class="relative z-10 shrink-0">
                <a href="buat_pengaduan.php" class="inline-flex items-center gap-2.5 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3.5 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-xl active:scale-[0.98] transition-all">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Buat Pengaduan Baru</span>
                </a>
            </div>
        </div>

        <!-- STATISTIC WIDGETS (Sleek Dark Hairline Cards Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            
            <!-- Card Total -->
            <div class="bg-slate-900/90 backdrop-blur-xl p-5 rounded-3xl border border-slate-800 hover:border-slate-700 transition-all flex items-center justify-between shadow-xl">
                <div>
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest block mb-1">Total Pengaduan</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-white font-mono tracking-tight"><?= $total_pengaduan ?></span>
                        <span class="text-[11px] font-medium text-slate-400">laporan terdaftar</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-800/80 border border-slate-700/80 flex items-center justify-center text-slate-300 text-lg">
                    <i class="fa-solid fa-folder-closed"></i>
                </div>
            </div>

            <!-- Card Diproses -->
            <div class="bg-slate-900/90 backdrop-blur-xl p-5 rounded-3xl border border-slate-800 hover:border-sky-500/40 transition-all flex items-center justify-between shadow-xl">
                <div>
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest block mb-1">Dalam Penanganan</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-sky-400 font-mono tracking-tight"><?= $total_proses ?></span>
                        <span class="text-[11px] font-medium text-slate-400">sedang diproses</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400 text-lg">
                    <i class="fa-solid fa-spinner animate-spin"></i>
                </div>
            </div>

            <!-- Card Selesai -->
            <div class="bg-slate-900/90 backdrop-blur-xl p-5 rounded-3xl border border-slate-800 hover:border-emerald-500/40 transition-all flex items-center justify-between shadow-xl">
                <div>
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest block mb-1">Telah Selesai</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-emerald-400 font-mono tracking-tight"><?= $total_selesai ?></span>
                        <span class="text-[11px] font-medium text-slate-400">tuntas ditangani</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

        </div>

        <!-- RECENT COMPLAINTS TABLE (High Density Dark Glass Table) -->
        <div class="bg-slate-900/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-emerald-400 text-sm"></i>
                        <span>Riwayat Pengaduan Terbaru</span>
                    </h3>
                    <p class="text-xs text-slate-400">5 pengaduan terakhir yang Anda buat pada sistem.</p>
                </div>

                <!-- Instant Search Input Bar -->
                <div class="flex items-center gap-3">
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" id="liveSearchInput" onkeyup="filterTable()" placeholder="Cari aduan instan..." 
                               class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all font-sans">
                    </div>
                    <a href="riwayat_pengaduan.php" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition whitespace-nowrap flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="complaintsTable" class="w-full text-xs text-left">
                    <thead class="bg-slate-950/80 text-slate-400 text-[10px] font-mono font-bold uppercase tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Judul Pengaduan</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Urgensi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-medium">
                        <?php if (empty($recent_list)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-500 text-xs italic">
                                    Belum ada pengaduan yang dibuat. Klik "+ Buat Pengaduan Baru" untuk membuat laporan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_list as $row): ?>
                                <tr class="hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white text-xs truncate max-w-xs mb-0.5"><?= htmlspecialchars($row['judul']) ?></div>
                                        <?php if (!empty($row['is_anonim'])): ?>
                                            <span class="inline-flex items-center gap-1 text-[10px] text-teal-400 font-mono">
                                                <i class="fa-solid fa-lock text-[9px]"></i> Mode Anonim
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-300 font-medium">
                                        <?= htmlspecialchars($row['kategori'] ?? 'Fasilitas') ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $urg_badge = match($row['urgensi'] ?? 'sedang') {
                                            'rendah' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'sedang' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                                            'tinggi' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'darurat' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            default => 'bg-slate-800 text-slate-400 border-slate-700'
                                        };
                                        ?>
                                        <span class="px-2.5 py-1 rounded-lg border font-mono text-[10px] font-bold uppercase <?= $urg_badge ?>">
                                            <?= htmlspecialchars($row['urgensi'] ?? 'sedang') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $stat_badge = match($row['status'] ?? 'terkirim') {
                                            'terkirim', 'dikirim' => 'bg-amber-500/10 text-amber-300 border-amber-500/20',
                                            'ditinjau' => 'bg-purple-500/10 text-purple-300 border-purple-500/20',
                                            'diproses' => 'bg-sky-500/10 text-sky-300 border-sky-500/20',
                                            'selesai'  => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
                                            'ditolak'  => 'bg-rose-500/10 text-rose-300 border-rose-500/20',
                                            default    => 'bg-slate-800 text-slate-400 border-slate-700'
                                        };
                                        ?>
                                        <span class="px-2.5 py-1 rounded-lg border font-mono text-[10px] font-bold uppercase flex items-center gap-1.5 w-fit <?= $stat_badge ?>">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current animate-ping"></span>
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 font-mono">
                                        <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="detail_pengaduan.php?id=<?= $row['id'] ?>" 
                                           class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 px-3.5 py-1.5 rounded-xl transition-all active:scale-95">
                                            <span>Detail</span>
                                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
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
function filterTable() {
    const input = document.getElementById('liveSearchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('complaintsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.innerText.toLowerCase();
        if (text.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}
</script>
<?php include 'partials/ai_widget.php'; ?>
</body>
</html>
