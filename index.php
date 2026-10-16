<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuaraKita | Sistem Pengaduan & Aspirasi Mahasiswa</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="assets/js/tailwindcss.js"></script>
    <script>window.tailwind || document.write('<script src="https://cdn.tailwindcss.com"><\/script>')</script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        navy: {
                            800: '#0f172a',
                            900: '#020617',
                        },
                        tealBrand: '#0f766e',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'pulse-glow': 'pulseGlow 3s infinite',
                        'shimmer': 'shimmer 2.5s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { opacity: '0.4', transform: 'scale(1)' },
                            '50%': { opacity: '0.8', transform: 'scale(1.05)' },
                        },
                        shimmer: {
                            '100%': { transform: 'translateX(100%)' }
                        }
                    }
                },
            },
        }
    </script>
    
    <style>
        /* Modern Glassmorphism & UI Touches */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .glass-dark {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .gradient-text {
            background: linear-gradient(135deg, #4ade80 0%, #10b981 50%, #0d9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-border {
            position: relative;
            background-clip: padding-box;
        }
        .gradient-border::after {
            content: '';
            position: absolute;
            top: -1px; right: -1px; bottom: -1px; left: -1px;
            background: linear-gradient(135deg, rgba(34,197,94,0.4), rgba(15,118,110,0.1));
            z-index: -1;
            border-radius: inherit;
        }
        /* Page Entrance & Smooth Spring Transitions */
        @keyframes pageFadeIn {
            0% { opacity: 0; transform: translateY(8px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        body {
            animation: pageFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Mouse Parallax & Ambient Spotlight Glow */
        .tilt-card {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }
        .bento-card-transition {
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                        border-color 0.35s ease, 
                        box-shadow 0.35s ease;
            will-change: transform, border-color, box-shadow;
        }
        .bento-card-transition:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px -10px rgba(16, 185, 129, 0.15);
        }
        .bento-spotlight {
            position: relative;
        }
        .bento-spotlight::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(600px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(16, 185, 129, 0.14), transparent 40%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }
        .bento-spotlight:hover::before {
            opacity: 1;
        }
        .mesh-bg {
            background-image: radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.08) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(13, 148, 136, 0.08) 0px, transparent 50%);
        }
    </style>
</head>
<body id="beranda" class="bg-slate-900 text-slate-100 font-sans antialiased overflow-x-hidden selection:bg-brand-500 selection:text-white">

    <!-- Header / Navbar -->
    <?php include 'partials/header.php'; ?>

    <main>
        <!-- ================= HERO SECTION ================= -->
        <section class="relative min-h-screen pt-32 pb-20 flex items-center justify-center overflow-hidden">
            <!-- Background Image with Dark Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="assets/img/123.jpg" alt="Kampus Background" class="w-full h-full object-cover opacity-20 filter blur-[2px]">
                <div class="absolute inset-0 bg-gradient-to-b from-navy-900/90 via-navy-900/95 to-slate-900"></div>
            </div>

            <!-- Glowing Animated Blobs -->
            <div class="absolute top-1/4 left-10 w-96 h-96 bg-brand-500/20 rounded-full blur-[120px] pointer-events-none animate-float"></div>
            <div class="absolute bottom-1/3 right-10 w-96 h-96 bg-tealBrand/30 rounded-full blur-[140px] pointer-events-none animate-float-delayed"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Hero Left Content -->
                <div class="lg:col-span-7 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                    
                    <!-- Pill Tag -->
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full glass-dark border border-brand-500/30 text-brand-300 text-xs md:text-sm font-medium mb-6 shadow-lg backdrop-blur-md">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-500"></span>
                        </span>
                        <span>Sistem Pengaduan Digital Kampus Integratif</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] text-white">
                        Suarakan Aspirasi, <br />
                        <span class="gradient-text">Bangun Kampus Masa Depan.</span>
                    </h1>

                    <!-- Description -->
                    <p class="mt-6 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        Platform aman, transparan, dan terenkripsi bagi mahasiswa untuk menyampaikan aspirasi, laporan, dan masukan demi menciptakan transparansi serta kemajuan civitas akademika.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="auth/login.php" class="relative group overflow-hidden rounded-xl p-[1px] focus:outline-none w-full sm:w-auto">
                            <span class="absolute inset-0 bg-gradient-to-r from-brand-500 via-tealBrand to-brand-400 rounded-xl animate-shimmer"></span>
                            <span class="relative block px-8 py-4 bg-slate-900 rounded-[11px] text-white font-semibold group-hover:bg-transparent transition-all duration-300 text-center">
                                <i class="fa-solid fa-paper-plane mr-2 text-brand-400 group-hover:text-white"></i> Mulai Lapor Sekarang
                            </span>
                        </a>

                        <button onclick="toggleAiChat()" class="px-7 py-4 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 font-semibold border border-emerald-500/40 hover:border-emerald-400 transition-all duration-300 w-full sm:w-auto text-center flex items-center justify-center gap-2.5 active:scale-95 shadow-lg shadow-emerald-950/50">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <i class="fa-solid fa-robot text-base text-emerald-400"></i>
                            <span>Tanya Suara AI 👋</span>
                        </button>
                    </div>
                </div>

                <!-- Hero Right Interactive Card (Parallax Effect) -->
                <div class="lg:col-span-5" data-aos="fade-left" data-aos-duration="1000">
                    <div id="heroCard" class="tilt-card glass-dark p-8 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden group">
                        
                        <!-- Floating Decorative Badge -->
                        <div class="absolute -top-12 -right-12 w-40 h-40 bg-brand-500/10 rounded-full blur-xl group-hover:bg-brand-500/20 transition-all"></div>
                        
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-500/20 flex items-center justify-center text-brand-400">
                                    <i class="fa-solid fa-shield-halved text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-white font-bold text-sm">Keamanan Terjamin</h4>
                                    <p class="text-xs text-slate-400">Enkripsi Laporan End-to-End</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-brand-500/10 text-brand-400 text-xs font-semibold rounded-full border border-brand-500/20">Aktif</span>
                        </div>

                        <!-- Card Preview Item -->
                        <div class="space-y-4">
                            <div class="p-4 rounded-2xl bg-slate-800/60 border border-slate-700/50 flex items-start gap-4">
                                <div class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-slate-200">Verifikasi Berkas Instan</h5>
                                    <p class="text-xs text-slate-400 mt-1">Laporan diverifikasi oleh sistem secara otomatis dalam hitungan detik.</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-800/60 border border-slate-700/50 flex items-start gap-4">
                                <div class="p-2.5 bg-teal-500/10 text-teal-400 rounded-xl">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-slate-200">Transparansi Penanganan</h5>
                                    <p class="text-xs text-slate-400 mt-1">Lacak status penyelesaian tindak lanjut secara real-time.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-800 text-center">
                            <span class="text-xs text-slate-400">
                                <i class="fa-solid fa-users text-brand-400 mr-1"></i> Digunakan oleh ribuan mahasiswa terverifikasi.
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </section>

        <!-- ================= STATISTIK SECTION ================= -->
        <section class="relative py-16 bg-navy-800 border-y border-slate-800">
            <?php
            require_once 'config.php';

            $sekolah =$config->query("SELECT COUNT(*) AS total FROM sekolah");
            $totalSekolah = $sekolah ? $sekolah->fetch_assoc()['total'] : 0;

            $pengaduan =$config->query("SELECT COUNT(*) AS total FROM pengaduan");
            $totalPengaduan = $pengaduan ? $pengaduan->fetch_assoc()['total'] : 0;

            $pengguna =$config->query("SELECT COUNT(*) AS total FROM users ");
            $totalPengguna = $pengguna ? $pengguna->fetch_assoc()['total'] : 0;

            $ditanggapi =$config->query("SELECT COUNT(*) AS total FROM pengaduan WHERE status='selesai'");
            $totalDitanggapi = $ditanggapi ? $ditanggapi->fetch_assoc()['total'] : 0;
            $persenRespons =$totalPengaduan > 0 ? round(($totalDitanggapi / $totalPengaduan) * 100) : 0;
            ?>

            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8" data-aos="fade-up">
                    
                    <!-- Stat Card 1 -->
                    <div class="glass-dark p-6 rounded-2xl border border-slate-800 hover:border-brand-500/40 transition-all duration-300 text-center group">
                        <div class="w-12 h-12 mx-auto mb-4 bg-brand-500/10 rounded-xl flex items-center justify-center text-brand-400 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <div class="counter text-3xl md:text-4xl font-extrabold font-mono tracking-tight text-white mb-1" data-target="<?= $totalSekolah ?>">0</div>
                        <div class="text-xs md:text-sm font-medium text-slate-400">Kampus Terintegrasi</div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div class="glass-dark p-6 rounded-2xl border border-slate-800 hover:border-brand-500/40 transition-all duration-300 text-center group">
                        <div class="w-12 h-12 mx-auto mb-4 bg-brand-500/10 rounded-xl flex items-center justify-center text-brand-400 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-inbox text-xl"></i>
                        </div>
                        <div class="counter text-3xl md:text-4xl font-extrabold font-mono tracking-tight text-white mb-1" data-target="<?= $totalPengaduan ?>">0</div>
                        <div class="text-xs md:text-sm font-medium text-slate-400">Laporan Diterima</div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div class="glass-dark p-6 rounded-2xl border border-slate-800 hover:border-brand-500/40 transition-all duration-300 text-center group">
                        <div class="w-12 h-12 mx-auto mb-4 bg-brand-500/10 rounded-xl flex items-center justify-center text-brand-400 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-user-shield text-xl"></i>
                        </div>
                        <div class="counter text-3xl md:text-4xl font-extrabold font-mono tracking-tight text-white mb-1" data-target="<?= $totalPengguna ?>">0</div>
                        <div class="text-xs md:text-sm font-medium text-slate-400">Pengguna Aktif</div>
                    </div>

                    <!-- Stat Card 4 -->
                    <div class="glass-dark p-6 rounded-2xl border border-slate-800 hover:border-brand-500/40 transition-all duration-300 text-center group">
                        <div class="w-12 h-12 mx-auto mb-4 bg-brand-500/10 rounded-xl flex items-center justify-center text-brand-400 group-hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </div>
                        <div class="text-3xl md:text-4xl font-extrabold font-mono tracking-tight text-white mb-1">
                            <span class="counter" data-target="<?= $persenRespons ?>">0</span>%
                        </div>
                        <div class="text-xs md:text-sm font-medium text-slate-400">Tingkat Penanganan</div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= TENTANG SECTION ================= -->
        <section id="tentang" class="py-24 bg-slate-900 relative">
            <div class="max-w-7xl mx-auto px-6">
                
                <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                    <span class="text-brand-400 text-xs font-bold tracking-widest uppercase">Mengenal SuaraKita</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mt-2">Satu Wadah untuk Perubahan Positif</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    
                    <div class="lg:col-span-7 space-y-6" data-aos="fade-right">
                        <div class="p-8 rounded-3xl bg-slate-800/40 border border-slate-800 hover:border-brand-500/30 transition-all duration-300">
                            <h3 class="text-xl font-bold text-white mb-3 flex items-center gap-3">
                                <i class="fa-solid fa-lock text-brand-400"></i> Ruang Aman Bersuara
                            </h3>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                SuaraKita dirancang khusus untuk memastikan kerahasiaan pelapor terjamin, terhindar dari intimidasi, serta memungkinkan pengaduan yang terstruktur tanpa hambatan birokrasi.
                            </p>
                        </div>

                        <div class="p-8 rounded-3xl bg-slate-800/40 border border-slate-800 hover:border-brand-500/30 transition-all duration-300">
                            <h3 class="text-xl font-bold text-white mb-3 flex items-center gap-3">
                                <i class="fa-solid fa-chart-line text-brand-400"></i> Sistem Akuntabel & Terukur
                            </h3>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Setiap pengaduan memiliki nomor tiket unik yang memungkinkan transparansi pemantauan dari status peninjauan awal hingga penyelesaian akhir.
                            </p>
                        </div>
                    </div>

                    <!-- Visi Card -->
                    <div class="lg:col-span-5" data-aos="fade-left">
                        <div class="relative p-10 rounded-3xl bg-gradient-to-br from-brand-900/60 via-slate-800 to-navy-800 border border-brand-500/30 shadow-xl overflow-hidden group">
                            <div class="absolute -right-8 -bottom-8 w-36 h-36 bg-brand-500/20 rounded-full blur-2xl group-hover:scale-125 transition duration-500"></div>
                            
                            <div class="w-16 h-16 rounded-2xl bg-brand-500/20 flex items-center justify-center text-brand-400 mb-6">
                                <i class="fa-solid fa-bullseye text-2xl"></i>
                            </div>

                            <h4 class="text-2xl font-bold text-white mb-3">Visi Kami</h4>
                            <p class="text-slate-300 text-sm leading-relaxed italic">
                                "Menjadi pendorong terwujudnya tata kelola kampus yang responsif, transparan, serta mengedepankan keadilan demi kesejahteraan seluruh mahasiswa."
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= FITUR UNGGULAN (BENTO GRID) ================= -->
        <section id="fitur" class="py-24 bg-navy-800 relative mesh-bg">
            <div class="max-w-7xl mx-auto px-6">
                
                <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                    <span class="text-brand-400 text-xs font-bold tracking-widest uppercase">Arsitektur Modern</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mt-2">Platform Pengaduan Berbasis Bento UI</h2>
                </div>

                <!-- Bento Grid Container -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <!-- Bento Card 1: Live Status Tracker (Large - Span 7) -->
                    <div data-aos="fade-up" data-aos-delay="100" class="lg:col-span-7 group bento-spotlight bento-card-transition p-8 rounded-3xl bg-slate-900/90 border border-slate-800 hover:border-brand-500/50 relative overflow-hidden">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-brand-500/10 text-brand-400 rounded-2xl flex items-center justify-center text-xl group-hover:scale-105 transition">
                                    <i class="fa-solid fa-radar"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-white">Live Tracking Status</h4>
                                    <p class="text-xs text-slate-400">Pemantauan progres laporan transparan secara real-time</p>
                                </div>
                            </div>
                            <button onclick="copyTicket('#SK-2026-892')" title="Klik untuk menyalin nomor tiket" 
                                    class="font-mono text-xs px-2.5 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 font-semibold transition active:scale-95 flex items-center gap-1.5 group/copy">
                                <span>#SK-2026-892</span>
                                <i class="fa-regular fa-copy text-[10px] opacity-70 group-hover/copy:opacity-100"></i>
                            </button>
                        </div>

                        <!-- Live Status Preview Box -->
                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3 font-mono text-xs">
                            <div class="flex items-center justify-between text-slate-400 pb-2 border-b border-slate-800/80">
                                <span>Status Laporan</span>
                                <span class="text-emerald-400 flex items-center gap-1.5 font-bold">
                                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                                    DIPROSES // TIM FASILITAS
                                </span>
                            </div>
                            <!-- Mini Step Bar -->
                            <div class="grid grid-cols-3 gap-2 text-[10px] text-center pt-1">
                                <div class="py-1.5 rounded bg-emerald-900/40 text-emerald-300 border border-emerald-700/50">1. Terkirim</div>
                                <div class="py-1.5 rounded bg-emerald-900/40 text-emerald-300 border border-emerald-700/50 font-bold">2. Verifikasi</div>
                                <div class="py-1.5 rounded bg-slate-800 text-slate-500">3. Solusi</div>
                            </div>
                            <div class="text-[11px] text-slate-400 pt-1 font-sans">
                                ⏱️ Update terakhir: <span class="font-mono text-slate-300">08 Okt 2026, 21:15 WIB</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bento Card 2: Enkripsi & Anonim (Span 5) -->
                    <div data-aos="fade-up" data-aos-delay="200" class="lg:col-span-5 group bento-spotlight bento-card-transition p-8 rounded-3xl bg-slate-900/90 border border-slate-800 hover:border-brand-500/50 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 bg-teal-500/10 text-teal-400 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:scale-105 transition">
                                <i class="fa-solid fa-user-ninja"></i>
                            </div>
                            <h4 class="text-xl font-bold text-white mb-2">Mode Privasi & Anonim</h4>
                            <p class="text-slate-400 text-xs leading-relaxed mb-4">
                                Pelapor memiliki kontrol penuh atas identitasnya. Opsi mode Anonim menjamin kerahasiaan identitas 100%.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-800 flex items-center justify-between font-mono text-[11px] text-slate-400">
                            <span>PROTEKSI DATA</span>
                            <span class="px-2 py-0.5 rounded bg-teal-500/10 text-teal-300 border border-teal-500/20 font-bold">AES-256 ENCRYPTED</span>
                        </div>
                    </div>

                    <!-- Bento Card 3: Kategori Terstruktur (Span 5) -->
                    <div data-aos="fade-up" data-aos-delay="300" class="lg:col-span-5 group bento-spotlight bento-card-transition p-8 rounded-3xl bg-slate-900/90 border border-slate-800 hover:border-brand-500/50">
                        <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-2xl flex items-center justify-center text-xl mb-6 group-hover:scale-105 transition">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-2">Kategori Pengaduan</h4>
                        <p class="text-slate-400 text-xs leading-relaxed mb-6">Klasifikasi laporan otomatis untuk mempercepat proses ke unit kerja terkait.</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700">🏢 Fasilitas</span>
                            <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700">📚 Akademik</span>
                            <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700">💳 Keuangan</span>
                            <span class="px-3 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700">🛡️ Keamanan</span>
                        </div>
                    </div>

                    <!-- Bento Card 4: Respon Cepat & Akuntabel (Large - Span 7) -->
                    <div data-aos="fade-up" data-aos-delay="400" class="lg:col-span-7 group bento-spotlight bento-card-transition p-8 rounded-3xl bg-slate-900/90 border border-slate-800 hover:border-brand-500/50 flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center text-xl mb-4 group-hover:scale-105 transition">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <h4 class="text-xl font-bold text-white mb-2">SLA Respon Cepat</h4>
                                <p class="text-slate-400 text-xs leading-relaxed max-w-md">
                                    Setiap aduan yang masuk diprioritaskan berdasarkan tingkat urgensi (Rendah, Sedang, Tinggi, Darurat).
                                </p>
                            </div>
                            <div class="text-right font-mono">
                                <span class="text-3xl font-extrabold text-emerald-400 block tracking-tight">&lt; 24 Jam</span>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider">Rata-rata Respon</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i> Terintegrasi dengan Admin Prodi & Kampus
                            </span>
                            <a href="auth/login.php" class="text-brand-400 hover:text-brand-300 font-semibold flex items-center gap-1">
                                Coba Sekarang &rarr;
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= PANDUAN WORKFLOW ================= -->
        <section id="panduan" class="py-24 bg-slate-900">
            <div class="max-w-5xl mx-auto px-6">
                
                <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                    <span class="text-brand-400 text-xs font-bold tracking-widest uppercase">Langkah Mudah</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mt-2">Bagaimana Sistem Bekerja?</h2>
                </div>

                <div class="relative border-l-2 border-slate-800 ml-4 md:ml-32 space-y-12">
                    
                    <!-- Step 1 -->
                    <div data-aos="fade-up" class="relative pl-8 md:pl-12 group">
                        <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-slate-900 border-2 border-brand-500 flex items-center justify-center text-brand-400 font-bold text-xs group-hover:bg-brand-500 group-hover:text-white transition duration-300">
                            1
                        </div>
                        <div class="glass-dark p-6 rounded-2xl border border-slate-800">
                            <h4 class="text-lg font-bold text-white mb-1">Buat Laporan</h4>
                            <p class="text-slate-400 text-xs md:text-sm leading-relaxed">
                                Masukkan detail pengaduan, pilih kategori yang sesuai, dan unggah bukti pendukung secara lengkap.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div data-aos="fade-up" data-aos-delay="100" class="relative pl-8 md:pl-12 group">
                        <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-slate-900 border-2 border-brand-500 flex items-center justify-center text-brand-400 font-bold text-xs group-hover:bg-brand-500 group-hover:text-white transition duration-300">
                            2
                        </div>
                        <div class="glass-dark p-6 rounded-2xl border border-slate-800">
                            <h4 class="text-lg font-bold text-white mb-1">Proses Verifikasi</h4>
                            <p class="text-slate-400 text-xs md:text-sm leading-relaxed">
                                Tim admin memeriksa kelayakan data pengaduan sebelum diteruskan ke pihak berwenang.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div data-aos="fade-up" data-aos-delay="200" class="relative pl-8 md:pl-12 group">
                        <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-slate-900 border-2 border-brand-500 flex items-center justify-center text-brand-400 font-bold text-xs group-hover:bg-brand-500 group-hover:text-white transition duration-300">
                            3
                        </div>
                        <div class="glass-dark p-6 rounded-2xl border border-slate-800">
                            <h4 class="text-lg font-bold text-white mb-1">Tindak Lanjut & Solusi</h4>
                            <p class="text-slate-400 text-xs md:text-sm leading-relaxed">
                                Dapatkan pemberitahuan resmi dan hasil tanggapan langsung melalui riwayat akun Anda.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= FAQ SECTION (PUSAT BANTUAN) ================= -->
        <section id="faq" class="py-24 bg-navy-800 relative border-t border-slate-800/80">
            <div class="max-w-4xl mx-auto px-6">
                
                <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                    <span class="text-emerald-400 text-xs font-bold tracking-widest uppercase">Pusat Bantuan</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mt-2">Pertanyaan Umum (FAQ)</h2>
                    <p class="text-slate-400 text-xs sm:text-sm mt-2">Temukan jawaban cepat seputar transparansi, keamanan, dan alur pengaduan.</p>
                </div>

                <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
                    
                    <!-- FAQ 1 -->
                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden transition-all">
                        <button onclick="toggleFaq(this)" class="w-full p-6 text-left flex items-center justify-between gap-4 focus:outline-none group">
                            <span class="font-bold text-sm sm:text-base text-white group-hover:text-emerald-400 transition-colors">
                                🔒 Apakah identitas saya benar-benar aman jika memilih mode Anonim?
                            </span>
                            <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-out px-6">
                            <p class="pb-6 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-slate-800/60 pt-4">
                                Ya, 100% aman. Ketika Anda mencentang opsi <strong class="text-emerald-400 font-mono">"Kirim sebagai Anonim"</strong>, sistem secara otomatis mengosongkan dan mengaburkan rincian nama serta identitas Anda di semua panel publik maupun panel manajemen admin kampus.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden transition-all">
                        <button onclick="toggleFaq(this)" class="w-full p-6 text-left flex items-center justify-between gap-4 focus:outline-none group">
                            <span class="font-bold text-sm sm:text-base text-white group-hover:text-emerald-400 transition-colors">
                                ⏱️ Berapa lama laporan saya akan ditindaklanjuti?
                            </span>
                            <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-out px-6">
                            <p class="pb-6 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-slate-800/60 pt-4">
                                Rata-rata waktu verifikasi awal adalah kurang dari <strong class="font-mono text-white">&lt; 24 jam kerja</strong>. Laporan dengan tingkat urgensi <span class="font-mono text-rose-400">🔴 Darurat</span> atau <span class="font-mono text-amber-400">🟠 Tinggi</span> akan langsung masuk ke notifikasi prioritas tim verifikasi.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden transition-all">
                        <button onclick="toggleFaq(this)" class="w-full p-6 text-left flex items-center justify-between gap-4 focus:outline-none group">
                            <span class="font-bold text-sm sm:text-base text-white group-hover:text-emerald-400 transition-colors">
                                Format berkas apa saja yang didukung untuk bukti laporan?
                            </span>
                            <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-out px-6">
                            <p class="pb-6 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-slate-800/60 pt-4">
                                Anda dapat mengunggah bukti gambar (<code class="font-mono text-emerald-400">JPG, PNG, WEBP</code>) atau dokumen (<code class="font-mono text-emerald-400">PDF</code>) dengan ukuran maksimal <strong class="font-mono text-white">5 MB</strong>.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <!-- Floating System Toast Container -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!-- JS Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // 3D Parallax Tilt Effect on Hero Card
        const heroCard = document.getElementById('heroCard');
        if (heroCard) {
            document.addEventListener('mousemove', (e) => {
                const xAxis = (window.innerWidth / 2 - e.pageX) / 45;
                const yAxis = (window.innerHeight / 2 - e.pageY) / 45;
                heroCard.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
            });
        }

        // Counter Animation Logic
        const counters = document.querySelectorAll('.counter');
        const startCounter = (counter) => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const speed = 150;
                const increment = Math.ceil(target / speed) || 1;

                if (count < target) {
                    counter.innerText = Math.min(count + increment, target);
                    setTimeout(updateCount, 25);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        };

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));

        // Interactive Bento Card Spotlight Effect
        document.querySelectorAll('.group').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });

        // FAQ Accordion Handler
        function toggleFaq(btn) {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('i');
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

            document.querySelectorAll('.faq-content').forEach(el => el.style.maxHeight = null);
            document.querySelectorAll('#faq i').forEach(ic => ic.style.transform = 'rotate(0deg)');

            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl border shadow-xl backdrop-blur-xl transition-all duration-300 transform translate-y-4 opacity-0 text-xs font-semibold ${
                type === 'success' 
                    ? 'bg-emerald-950/90 text-emerald-300 border-emerald-500/30' 
                    : 'bg-rose-950/90 text-rose-300 border-rose-500/30'
            }`;
            
            toast.innerHTML = `
                <i class="${type === 'success' ? 'fa-solid fa-circle-check text-emerald-400' : 'fa-solid fa-circle-exclamation text-rose-400'} text-sm"></i>
                <span>${message}</span>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Copy Ticket Functionality
        function copyTicket(ticketCode) {
            navigator.clipboard.writeText(ticketCode).then(() => {
                showToast(`Nomor tiket ${ticketCode} berhasil disalin ke clipboard!`);
            }).catch(() => {
                showToast('Gagal menyalin nomor tiket.', 'error');
            });
        }
    </script>
</body>
</html>