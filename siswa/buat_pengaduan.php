<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_siswa();

$siswaUsername = $_SESSION['siswa_username'];
$categories = ['Fasilitas', 'Akademik', 'Keuangan', 'Kebersihan', 'Keamanan', 'Lainnya'];
$urgencies = [
    'rendah'  => '🟢 Rendah (Masalah biasa / tidak mendesak)',
    'sedang'  => '🔵 Sedang (Membutuhkan perhatian)',
    'tinggi'  => '🟠 Tinggi (Penting / berdampak pada aktivitas)',
    'darurat' => '🔴 Darurat (Sangat mendesak / kondisi berbahaya)'
];
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengaduan | SuaraKita</title>
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

<!-- Sidebar siswa -->
<?php include 'partials/sidebar.php'; ?>
<main class="ml-64 min-h-screen flex flex-col">
    <!-- Header siswa -->
    <?php include 'partials/header.php'; ?>

    <!-- Content -->
    <div class="p-6 md:p-8 mt-16 flex-1 max-w-4xl mx-auto w-full space-y-6">

        <div class="bg-zinc-900/60 backdrop-blur-xl p-8 rounded-2xl border border-white/10 shadow-2xl">
            <div class="flex items-center justify-between pb-6 mb-6 border-b border-white/10">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-teal-500/10 text-teal-400 border border-teal-500/20 mb-2">
                        <i class="fa-solid fa-pen-nib mr-1.5"></i> FORMULIR ASPIRASI
                    </span>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white">
                        Form Pengaduan & Aspirasi Siswa
                    </h1>
                    <p class="text-xs text-zinc-400 mt-1">Sampaikan aspirasi dan kendala Anda dengan jelas untuk ditindaklanjuti secara akuntabel.</p>
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-mono font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <i class="fa-solid fa-shield-halved"></i> Enkripsi Terjamin
                </span>
            </div>

            <form action="../aksi/aksi_pengaduan.php" method="POST" enctype="multipart/form-data" class="space-y-6">

                <!-- Judul -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">
                        Judul Pengaduan <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="judul" required placeholder="Contoh: AC Rusak di Ruang Kelas 304"
                        class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50 focus:ring-1 focus:ring-teal-500/50 transition">
                </div>

                <!-- Grid Kategori & Urgensi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">
                            Kategori Pengaduan <span class="text-rose-400">*</span>
                        </label>
                        <select name="kategori" required class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-zinc-200 focus:outline-none focus:border-teal-500/50 focus:ring-1 focus:ring-teal-500/50 transition">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat ?>" class="bg-zinc-900 text-zinc-200"><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tingkat Urgensi -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">
                            Tingkat Urgensi <span class="text-rose-400">*</span>
                        </label>
                        <select name="urgensi" required class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-zinc-200 focus:outline-none focus:border-teal-500/50 focus:ring-1 focus:ring-teal-500/50 transition font-mono">
                            <option value="" disabled>-- Pilih Prioritas --</option>
                            <?php foreach ($urgencies as $key => $label): ?>
                                <option value="<?= $key ?>" <?= $key === 'sedang' ? 'selected' : '' ?> class="bg-zinc-900 text-zinc-200"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Isi -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300">
                            Isi Pengaduan <span class="text-rose-400">*</span>
                        </label>
                        <!-- AI Sentiment Indicator Trigger Badge -->
                        <span id="aiSentimentBadge" class="hidden text-[10px] font-mono font-semibold px-2.5 py-0.5 rounded-full border transition-all duration-300 items-center gap-1.5">
                            <i class="fa-solid fa-brain animate-pulse"></i>
                            <span id="aiSentimentText">Menganalisis sentimen...</span>
                        </span>
                    </div>
                    <textarea id="isiPengaduan" name="isi" rows="5" required oninput="analyzeSentimentLive()" placeholder="Jelaskan kronologi atau rincian keluhan Anda secara lengkap..."
                        class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50 focus:ring-1 focus:ring-teal-500/50 transition"></textarea>

                    <!-- Smart AI Auto-Priority Recommendation Box -->
                    <div id="aiRecommendationBox" class="hidden mt-3 p-3.5 rounded-2xl bg-teal-500/10 border border-teal-500/30 text-xs font-sans text-teal-200 transition-all flex items-start gap-3">
                        <div class="w-7 h-7 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="font-bold text-white flex items-center gap-2">
                                <span>Rekomendasi Otomatis Suara AI:</span>
                                <span id="aiUrgencyBadge" class="px-2 py-0.2 rounded text-[10px] font-mono font-bold uppercase"></span>
                            </div>
                            <p id="aiRecommendationText" class="text-[11px] text-zinc-300 leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                <!-- Upload Bukti Lampiran -->
                <div>
                    <label for="lampiranInput" class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">
                        Upload Bukti Foto / Dokumen <span class="text-xs text-zinc-400 font-sans lowercase font-normal">(Opsional, JPG/PNG/PDF, Maks 5MB)</span>
                    </label>
                    <div class="relative">
                        <input id="lampiranInput" name="lampiran" type="file" accept="image/*,.pdf" onchange="previewFileName(this)"
                            class="block w-full text-xs text-zinc-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-600 file:text-white hover:file:bg-teal-500 border border-white/10 rounded-xl cursor-pointer bg-zinc-950/80 focus:outline-none transition">
                    </div>
                    <p id="fileNameDisplay" class="mt-2 text-xs font-mono text-teal-400 hidden flex items-center gap-1.5">
                        <i class="fa-solid fa-paperclip"></i> File terpilih: <span id="fileNameText" class="underline font-semibold"></span>
                    </p>
                </div>

                <!-- Opsi Pengaduan Anonim -->
                <div class="bg-zinc-950/60 border border-white/10 rounded-xl p-4 transition hover:border-white/20">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_anonim" value="1" class="mt-1 rounded bg-zinc-900 border-white/20 text-teal-500 focus:ring-teal-500/30 h-4 w-4">
                        <div>
                            <span class="text-xs font-bold text-zinc-200 block">🔒 Kirim sebagai Anonim (Rahasiakan Identitas Saya)</span>
                            <span class="text-xs text-zinc-400 block mt-0.5">Nama dan identitas Anda akan disamarkan menjadi <strong class="font-mono text-teal-400">"Anonim"</strong> di laporan publik dan panel manajemen.</span>
                        </div>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2 flex items-center gap-4">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-mono font-bold uppercase tracking-wider active:scale-[0.98] transition shadow-lg shadow-teal-900/30">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Pengaduan Sekarang</span>
                    </button>
                    <a href="dashboard_siswa.php" class="px-5 py-3 text-xs font-mono text-zinc-400 hover:text-zinc-200 transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</main>

<script>
function previewFileName(input) {
    const display = document.getElementById('fileNameDisplay');
    const text = document.getElementById('fileNameText');
    if (input.files && input.files[0]) {
        text.textContent = input.files[0].name + " (" + (input.files[0].size / 1024 / 1024).toFixed(2) + " MB)";
        display.classList.remove('hidden');
    } else {
        display.classList.add('hidden');
    }
}

// Smart NLP Sentiment & Auto-Priority Detector Engine
let sentimentTimer = null;
function analyzeSentimentLive() {
    clearTimeout(sentimentTimer);
    const text = document.getElementById('isiPengaduan').value.trim();
    const badge = document.getElementById('aiSentimentBadge');
    const badgeText = document.getElementById('aiSentimentText');
    const box = document.getElementById('aiRecommendationBox');
    const urgencyBadge = document.getElementById('aiUrgencyBadge');
    const recText = document.getElementById('aiRecommendationText');
    const urgensiSelect = document.querySelector('select[name="urgensi"]');
    const kategoriSelect = document.querySelector('select[name="kategori"]');

    if (text.length < 15) {
        badge.classList.add('hidden');
        box.classList.add('hidden');
        return;
    }

    badge.classList.remove('hidden');
    badge.className = 'text-[10px] font-mono font-semibold px-2.5 py-0.5 rounded-full border transition-all duration-300 flex items-center gap-1.5 bg-zinc-800 text-zinc-300 border-white/10';
    badgeText.textContent = 'Menganalisis sentimen NLP...';

    sentimentTimer = setTimeout(() => {
        const lower = text.toLowerCase();
        
        // Keywords Classification Engine
        const dangerKeywords = ['kebakaran', 'bahaya', 'kekerasan', 'pelecehan', 'ancaman', 'rusak parah', 'darurat', 'bocor deras', 'listrik konslet', 'pingsan', 'kecelakaan', 'ambruk'];
        const highKeywords = ['rusak', 'mati lampu', 'hilang', 'error', 'tidak bisa', 'macet', 'kotor sekali', 'terkunci', 'ac mati', 'proyektor mati', 'gangguan'];
        const academicKeywords = ['dosen', 'nilai', 'krs', 'jadwal', 'kuliah', 'absensi', 'tugas', 'ujian', 'rekap', 'ijazah'];
        const facilityKeywords = ['ac', 'toilet', 'ruang', 'lampu', 'meja', 'kursi', 'parkir', 'wifi', 'lift', 'pintu'];

        let detectedUrgensi = 'sedang';
        let sentimentScore = 'Positif/Netral';
        let categorySuggest = '';
        let badgeColor = 'bg-teal-500/10 text-teal-400 border-teal-500/30';

        if (dangerKeywords.some(k => lower.includes(k))) {
            detectedUrgensi = 'darurat';
            sentimentScore = '⚠️ Sangat Kritis / Urgent';
            badgeColor = 'bg-rose-500/20 text-rose-400 border-rose-500/40 shadow-[0_0_10px_rgba(244,63,94,0.3)]';
        } else if (highKeywords.some(k => lower.includes(k))) {
            detectedUrgensi = 'tinggi';
            sentimentScore = '⚡ Kritis / Prioritas Tinggi';
            badgeColor = 'bg-amber-500/20 text-amber-400 border-amber-500/40';
        } else {
            sentimentScore = '🟢 Informasi / Biasa';
            badgeColor = 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40';
        }

        // Category Suggestion
        if (academicKeywords.some(k => lower.includes(k))) {
            categorySuggest = 'Layanan Akademik';
        } else if (facilityKeywords.some(k => lower.includes(k))) {
            categorySuggest = 'Fasilitas Kampus';
        }

        // Render UI Sentiment
        badge.className = `text-[10px] font-mono font-semibold px-2.5 py-0.5 rounded-full border transition-all duration-300 flex items-center gap-1.5 ${badgeColor}`;
        badgeText.textContent = sentimentScore;

        box.classList.remove('hidden');
        urgencyBadge.textContent = detectedUrgensi.toUpperCase();
        
        if (detectedUrgensi === 'darurat') {
            urgencyBadge.className = 'px-2 py-0.2 rounded text-[10px] font-mono font-bold uppercase bg-rose-500/20 text-rose-300 border border-rose-500/30';
            recText.innerHTML = `Laporan mengandung kata kunci <strong>darurat/keselamatan</strong>. Prioritas otomatis disesuaikan ke <span class="text-rose-400 font-bold">DARURAT</span> untuk respon tercepat.`;
        } else if (detectedUrgensi === 'tinggi') {
            urgencyBadge.className = 'px-2 py-0.2 rounded text-[10px] font-mono font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30';
            recText.innerHTML = `Laporan berindikasi kendala teknis/fasilitas prioritas. Direkomendasikan ke <span class="text-amber-400 font-bold">TINGGI</span>.`;
        } else {
            urgencyBadge.className = 'px-2 py-0.2 rounded text-[10px] font-mono font-bold uppercase bg-teal-500/20 text-teal-300 border border-teal-500/30';
            recText.innerHTML = `Deskripsi cukup jelas dan berada pada skala penanganan standar.`;
        }

        // Auto Select Urgensi if user hasn't changed it manually
        if (urgensiSelect) {
            urgensiSelect.value = detectedUrgensi;
        }

        // Auto Suggest Kategori if detected
        if (categorySuggest && kategoriSelect && !kategoriSelect.value) {
            kategoriSelect.value = categorySuggest;
        }
    }, 400);
}
</script>

<?php include 'partials/ai_widget.php'; ?>
</body>
</html>
