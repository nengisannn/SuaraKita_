<!-- Header / Navbar -->
<header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between px-5 py-3 rounded-2xl border border-white/10 bg-slate-900/75 backdrop-blur-md shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] transition-all duration-300">
            
            <!-- Humanized Minimalist Logo -->
            <a href="index.php" class="flex items-center gap-3.5 group focus:outline-none">
                <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-slate-800 border border-slate-700/80 group-hover:border-emerald-500/50 group-hover:bg-slate-800/80 transition-all duration-300 shadow-sm">
                    <!-- Clean Monogram "S" -->
                    <span class="text-lg font-bold text-slate-100 group-hover:text-emerald-400 transition-colors">S</span>
                    <!-- Subtle Status Dot -->
                    <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                </div>
                
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="text-base font-bold text-white tracking-tight">SuaraKita</span>
                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">v1.0</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium tracking-wide">Aspirasi & Pengaduan Kampus</span>
                </div>
            </a>

            <!-- Aesthetic Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 bg-slate-800/40 p-1 rounded-xl border border-white/5">
                <a href="#beranda" class="nav-item relative px-4 py-1.5 text-xs font-semibold text-white bg-slate-800/80 rounded-lg shadow-sm border border-white/5 transition-all duration-200">
                    Beranda
                </a>
                <a href="#tentang" class="nav-item relative px-4 py-1.5 text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-800/40 rounded-lg transition-all duration-200">
                    Tentang
                </a>
                <a href="#fitur" class="nav-item relative px-4 py-1.5 text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-800/40 rounded-lg transition-all duration-200">
                    Fitur
                </a>
                <a href="#panduan" class="nav-item relative px-4 py-1.5 text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-800/40 rounded-lg transition-all duration-200">
                    Panduan
                </a>
            </nav>

            <!-- Refined Action Button (Masuk Only) -->
            <div class="hidden sm:flex items-center">
                <a href="auth/login.php" class="group relative px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-500 shadow-md shadow-emerald-900/30 transition-all duration-200 overflow-hidden active:scale-95">
                    <span class="relative z-10 flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        <span>Masuk</span>
                    </span>
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <button id="mobile-menu-btn" aria-label="Toggle Navigation" class="md:hidden text-slate-300 hover:text-white focus:outline-none p-2 rounded-xl hover:bg-slate-800/80 border border-transparent hover:border-slate-700/60 transition-all">
                <i class="fa-solid fa-bars-staggered text-lg"></i>
            </button>

        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden md:hidden max-w-7xl mx-auto px-4 sm:px-6 mt-1">
        <div class="p-5 rounded-2xl border border-white/10 bg-slate-900/95 backdrop-blur-xl shadow-2xl flex flex-col gap-3">
            <a href="#beranda" class="mobile-link text-slate-200 hover:text-emerald-400 font-medium text-sm py-2 px-3 rounded-lg hover:bg-slate-800/50 transition-all">Beranda</a>
            <a href="#tentang" class="mobile-link text-slate-300 hover:text-emerald-400 font-medium text-sm py-2 px-3 rounded-lg hover:bg-slate-800/50 transition-all">Tentang</a>
            <a href="#fitur" class="mobile-link text-slate-300 hover:text-emerald-400 font-medium text-sm py-2 px-3 rounded-lg hover:bg-slate-800/50 transition-all">Fitur</a>
            <a href="#panduan" class="mobile-link text-slate-300 hover:text-emerald-400 font-medium text-sm py-2 px-3 rounded-lg hover:bg-slate-800/50 transition-all">Panduan</a>
            
            <div class="pt-3 border-t border-slate-800 flex flex-col">
                <a href="auth/login.php" class="w-full text-center py-2.5 rounded-xl text-white bg-emerald-600 font-semibold text-xs shadow-md shadow-emerald-900/20 hover:bg-emerald-500 transition">
                    <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Masuk
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle logic
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileLinks = document.querySelectorAll('.mobile-link');

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    }

    // Header Blur Enhancement on Scroll
    window.addEventListener('scroll', () => {
        const headerContainer = document.querySelector('#main-header > div > div');
        if (window.scrollY > 20) {
            headerContainer.classList.add('bg-slate-900/90', 'shadow-xl');
            headerContainer.classList.remove('bg-slate-900/75');
        } else {
            headerContainer.classList.remove('bg-slate-900/90', 'shadow-xl');
            headerContainer.classList.add('bg-slate-900/75');
        }
    });
</script>