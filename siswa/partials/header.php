<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/notification.php';

$siswa_id = $_SESSION['siswa_id'] ?? 0;
$siswa = db_fetch_one(
    "SELECT s.*, se.nama_sekolah 
     FROM users s
     LEFT JOIN sekolah se ON s.sekolah_id = se.id
     WHERE s.id = ? AND s.level = 'siswa'",
    [$siswa_id],
    "i"
);

$unread_count = count_unread_notifications($siswa_id);
$notif_list   = get_unread_notifications($siswa_id, 5);
?>

<header class="fixed top-0 left-64 right-0 h-20 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/80 flex items-center px-8 z-40 transition-all font-sans text-slate-100">

    <!-- Header Title & Info Kampus -->
    <div class="flex-1 flex items-center gap-3">
        <div>
            <h1 class="text-base font-extrabold text-white tracking-tight flex items-center gap-2">
                <span>Portal Mahasiswa</span>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    🟢 ONLINE
                </span>
            </h1>
            <p class="text-xs text-slate-400 font-medium"><?= htmlspecialchars($siswa['nama_sekolah'] ?? 'Civitas Akademika Kampus Integratif') ?></p>
        </div>
    </div>

    <!-- Notification Bell, KITA-BOT Mascot & User Profile -->
    <div class="flex items-center gap-4">

        <!-- Suara AI Mascot Button in Header -->
        <div class="relative">
            <button onclick="toggleAiChat()" id="aiToggleBtnHeader" title="Suara AI - Asisten AI SuaraKita"
                    class="relative group flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 transition active:scale-95 focus:outline-none">
                <div class="relative flex items-center justify-center w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400">
                    <i class="fa-solid fa-robot text-sm animate-bounce"></i>
                    <span class="absolute -top-1 -right-1 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                <div class="text-left hidden md:block">
                    <div class="text-xs font-bold text-white flex items-center gap-1">
                        <span>Suara AI</span>
                        <span class="animate-wave inline-block origin-bottom-left">👋</span>
                    </div>
                    <div class="text-[9px] text-emerald-400 font-mono">AI Active</div>
                </div>
            </button>
        </div>

        <!-- Lonceng Notifikasi Dropdown -->
        <div class="relative">
            <button id="notifBellBtn" onclick="toggleNotifDropdown()" 
                    class="relative p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:border-emerald-500/40 transition active:scale-95 focus:outline-none">
                <i class="fa-solid fa-bell text-sm"></i>
                <?php if ($unread_count > 0): ?>
                    <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold font-mono text-white shadow-md animate-pulse">
                        <?= $unread_count ?>
                    </span>
                <?php endif; ?>
            </button>

            <!-- Dropdown Card -->
            <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-80 bg-slate-900/95 backdrop-blur-2xl rounded-2xl shadow-2xl border border-slate-800 z-50 overflow-hidden font-sans">
                <div class="p-3.5 bg-slate-950 border-b border-slate-800 flex justify-between items-center">
                    <span class="text-xs font-bold text-white flex items-center gap-1.5">
                        <i class="fa-solid fa-bell text-emerald-400 text-xs"></i> Notifikasi Baru
                    </span>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold">
                        <?= $unread_count ?> Unread
                    </span>
                </div>
                <div class="divide-y divide-slate-800/60 max-h-72 overflow-y-auto text-xs">
                    <?php if (empty($notif_list)): ?>
                        <div class="p-5 text-center text-slate-500 italic">Belum ada notifikasi baru.</div>
                    <?php else: ?>
                        <?php foreach ($notif_list as $n): ?>
                            <a href="detail_pengaduan.php?id=<?= $n['pengaduan_id'] ?>" class="block p-3.5 hover:bg-slate-800/60 transition <?= $n['is_read'] ? 'opacity-60' : 'bg-emerald-500/5 font-medium' ?>">
                                <div class="font-bold text-white mb-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-info text-emerald-400 text-[10px]"></i>
                                    <span><?= htmlspecialchars($n['judul']) ?></span>
                                </div>
                                <div class="text-slate-400 line-clamp-2 leading-relaxed text-[11px]"><?= htmlspecialchars($n['pesan']) ?></div>
                                <div class="text-[10px] text-slate-500 font-mono mt-1.5 flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[9px]"></i>
                                    <span><?= date('d M H:i', strtotime($n['created_at'])) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- User Info Pill -->
        <div class="flex items-center gap-3 border-l border-slate-800 pl-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-white tracking-tight">
                    <?= htmlspecialchars($_SESSION['siswa_username'] ?? 'Mahasiswa'); ?>
                </p>
                <p class="text-[10px] text-slate-400 font-mono"><?= htmlspecialchars($siswa['kelas'] ?? 'Mahasiswa Terverifikasi') ?></p>
            </div>

            <!-- Avatar Image -->
            <a href="profil_siswa.php" class="relative group focus:outline-none">
                <img src="../assets/img/<?= !empty($siswa['foto']) ? htmlspecialchars($siswa['foto']) : 'default.png'; ?>" 
                     class="w-10 h-10 rounded-2xl object-cover border-2 border-slate-800 group-hover:border-emerald-500 transition-all shadow-md">
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-slate-950"></span>
            </a>
        </div>

    </div>

</header>

<script>
function toggleNotifDropdown() {
    const dd = document.getElementById('notifDropdown');
    if (dd) dd.classList.toggle('hidden');
}
</script>
