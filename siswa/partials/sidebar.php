<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<script>
// Apply initial collapsed state BEFORE DOM render to prevent transition flash
(function() {
    if (localStorage.getItem('sidebarState') === 'collapsed') {
        document.write('<style>#sidebar{width:5rem !important;} header{left:5rem !important;} main{margin-left:5rem !important;} .sidebar-text{display:none !important;}</style>');
    }
})();
</script>

<aside id="sidebar"
       class="fixed top-0 left-0 h-full w-64 bg-zinc-950/95 backdrop-blur-2xl text-zinc-200 shadow-2xl flex flex-col transition-all duration-300 z-50 border-r border-white/10 font-sans group/sidebar">

    <!-- Logo & Brand Header -->
    <div class="h-20 px-5 flex items-center justify-between border-b border-white/10">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="relative flex items-center justify-center w-10 h-10 rounded-2xl bg-zinc-900 border border-white/10 shadow-md shrink-0">
                <span class="text-xl font-extrabold text-white">S</span>
                <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-500"></span>
                </span>
            </div>
            <div class="sidebar-text transition-all duration-300 whitespace-nowrap">
                <div class="flex items-center gap-1.5">
                    <h2 class="font-extrabold text-base tracking-tight text-white">SuaraKita</h2>
                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-teal-500/20 text-teal-300 font-mono border border-teal-500/30">STUDENT</span>
                </div>
                <p class="text-[10px] text-zinc-400 font-medium">Portal Aspirasi Digital</p>
            </div>
        </div>

        <!-- Toggle Collapse Button (Desktop & Mobile) -->
        <button onclick="toggleSidebarCollapse()" title="Minimize / Expand Sidebar" 
                class="w-8 h-8 rounded-xl bg-zinc-900 border border-white/10 text-zinc-400 hover:text-white hover:border-teal-500/50 flex items-center justify-center transition shrink-0">
            <i id="sidebarToggleIcon" class="fa-solid fa-angles-left text-xs transition-transform duration-300"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-6 space-y-1.5 font-medium text-xs overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="dashboard_siswa.php" title="Dashboard"
           class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all duration-200 active:scale-[0.98] group
            <?= $current_page == 'dashboard_siswa.php' 
                ? 'bg-teal-500/10 text-teal-400 font-bold border border-teal-500/30 shadow-[0_0_15px_rgba(20,184,166,0.15)]' 
                : 'text-zinc-400 hover:text-white hover:bg-zinc-900/80 border border-transparent' ?>">
            <div class="w-7 h-7 rounded-xl flex items-center justify-center text-sm shrink-0 transition-colors
                <?= $current_page == 'dashboard_siswa.php' ? 'bg-teal-500/20 text-teal-400' : 'bg-zinc-900 text-zinc-400 group-hover:text-teal-400' ?>">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <span class="sidebar-text whitespace-nowrap">Dashboard</span>
        </a>

        <!-- Buat Pengaduan -->
        <a href="buat_pengaduan.php" title="Buat Pengaduan"
           class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all duration-200 active:scale-[0.98] group
            <?= $current_page == 'buat_pengaduan.php' 
                ? 'bg-teal-500/10 text-teal-400 font-bold border border-teal-500/30 shadow-[0_0_15px_rgba(20,184,166,0.15)]' 
                : 'text-zinc-400 hover:text-white hover:bg-zinc-900/80 border border-transparent' ?>">
            <div class="w-7 h-7 rounded-xl flex items-center justify-center text-sm shrink-0 transition-colors
                <?= $current_page == 'buat_pengaduan.php' ? 'bg-teal-500/20 text-teal-400' : 'bg-zinc-900 text-zinc-400 group-hover:text-teal-400' ?>">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
            <span class="sidebar-text whitespace-nowrap">Buat Pengaduan</span>
        </a>

        <!-- Riwayat Pengaduan -->
        <a href="riwayat_pengaduan.php" title="Riwayat Pengaduan"
           class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all duration-200 active:scale-[0.98] group
            <?= $current_page == 'riwayat_pengaduan.php' 
                ? 'bg-teal-500/10 text-teal-400 font-bold border border-teal-500/30 shadow-[0_0_15px_rgba(20,184,166,0.15)]' 
                : 'text-zinc-400 hover:text-white hover:bg-zinc-900/80 border border-transparent' ?>">
            <div class="w-7 h-7 rounded-xl flex items-center justify-center text-sm shrink-0 transition-colors
                <?= $current_page == 'riwayat_pengaduan.php' ? 'bg-teal-500/20 text-teal-400' : 'bg-zinc-900 text-zinc-400 group-hover:text-teal-400' ?>">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <span class="sidebar-text whitespace-nowrap">Riwayat Pengaduan</span>
        </a>

        <!-- Profil Saya -->
        <a href="profil_siswa.php" title="Profil Akun"
           class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all duration-200 active:scale-[0.98] group
            <?= $current_page == 'profil_siswa.php' 
                ? 'bg-teal-500/10 text-teal-400 font-bold border border-teal-500/30 shadow-[0_0_15px_rgba(20,184,166,0.15)]' 
                : 'text-zinc-400 hover:text-white hover:bg-zinc-900/80 border border-transparent' ?>">
            <div class="w-7 h-7 rounded-xl flex items-center justify-center text-sm shrink-0 transition-colors
                <?= $current_page == 'profil_siswa.php' ? 'bg-teal-500/20 text-teal-400' : 'bg-zinc-900 text-zinc-400 group-hover:text-teal-400' ?>">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <span class="sidebar-text whitespace-nowrap">Profil Akun</span>
        </a>

    </nav>

    <!-- Sidebar Bottom Footer -->
    <div class="p-3 border-t border-white/10">
        <button type="button" onclick="openLogoutModal()" title="Keluar Akun"
           class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-600 border border-rose-500/20 transition-all duration-200 active:scale-[0.98]">
            <span class="flex items-center gap-2 overflow-hidden">
                <i class="fa-solid fa-right-from-bracket text-xs shrink-0"></i>
                <span class="sidebar-text whitespace-nowrap">Keluar Akun</span>
            </span>
            <i class="fa-solid fa-arrow-right text-[10px] opacity-70 sidebar-text"></i>
        </button>
    </div>

</aside>

<!-- Logout Confirmation Modal (Student) -->
<div id="logoutModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-all duration-300 opacity-0">
    <div class="relative w-full max-w-sm bg-zinc-900 border border-white/10 rounded-3xl p-6 shadow-2xl transform scale-95 transition-all duration-300 text-center font-sans">
        <!-- Icon Warning -->
        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4 text-2xl shadow-[0_0_20px_rgba(244,63,94,0.15)] animate-pulse">
            <i class="fa-solid fa-right-from-bracket"></i>
        </div>

        <h3 class="text-lg font-bold text-white mb-2">Konfirmasi Keluar</h3>
        <p class="text-xs text-zinc-400 mb-6 leading-relaxed">
            Apakah Anda yakin ingin keluar dari portal mahasiswa? Anda harus masuk kembali untuk mengelola pengaduan.
        </p>

        <div class="flex items-center gap-3">
            <button type="button" onclick="closeLogoutModal()" 
                    class="flex-1 py-2.5 px-4 rounded-xl border border-white/10 text-xs font-semibold text-zinc-300 hover:text-white hover:bg-zinc-800 transition active:scale-95">
                Batal
            </button>
            <a href="../auth/logout.php" 
               class="flex-1 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition shadow-lg shadow-rose-600/30 active:scale-95 flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i> Ya, Keluar
            </a>
        </div>
    </div>
</div>

<script>
function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
        modal.querySelector('div').classList.add('scale-100');
    }, 10);
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (!modal) return;
    modal.querySelector('div').classList.remove('scale-100');
    modal.querySelector('div').classList.add('scale-95');
    modal.classList.add('opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    const mainHeader = document.querySelector('header');
    const mainContent = document.querySelector('main');
    const toggleIcon = document.getElementById('sidebarToggleIcon');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');

    const isCollapsed = sidebar.classList.contains('w-20');

    if (isCollapsed) {
        // Expand Sidebar
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-64');
        if (mainHeader) {
            mainHeader.classList.remove('left-20');
            mainHeader.classList.add('left-64');
        }
        if (mainContent) {
            mainContent.classList.remove('ml-20');
            mainContent.classList.add('ml-64');
        }
        if (toggleIcon) {
            toggleIcon.classList.remove('rotate-180');
        }
        sidebarTexts.forEach(el => el.classList.remove('hidden'));
        localStorage.setItem('sidebarState', 'expanded');
    } else {
        // Collapse Sidebar (Minimize)
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');
        if (mainHeader) {
            mainHeader.classList.remove('left-64');
            mainHeader.classList.add('left-20');
        }
        if (mainContent) {
            mainContent.classList.remove('ml-64');
            mainContent.classList.add('ml-20');
        }
        if (toggleIcon) {
            toggleIcon.classList.add('rotate-180');
        }
        sidebarTexts.forEach(el => el.classList.add('hidden'));
        localStorage.setItem('sidebarState', 'collapsed');
    }
}

// Ensure correct classes and icon state on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('sidebarState') === 'collapsed') {
        const sidebar = document.getElementById('sidebar');
        const mainHeader = document.querySelector('header');
        const mainContent = document.querySelector('main');
        const toggleIcon = document.getElementById('sidebarToggleIcon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        if (sidebar) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
        }
        if (mainHeader) {
            mainHeader.classList.remove('left-64');
            mainHeader.classList.add('left-20');
        }
        if (mainContent) {
            mainContent.classList.remove('ml-64');
            mainContent.classList.add('ml-20');
        }
        if (toggleIcon) {
            toggleIcon.classList.add('rotate-180');
        }
        sidebarTexts.forEach(el => el.classList.add('hidden'));
    }
});
</script>
