<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_siswa();

$siswa_id     = $_SESSION['siswa_id'];
$sekolah_id   = $_SESSION['sekolah_id'];
$pengaduan_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$pengaduan_id) {
    header("Location: dashboard_siswa.php");
    exit;
}

// Ambil detail pengaduan (Validasi IDOR: p.siswa_id = $siswa_id)
$data = db_fetch_one(
    "SELECT p.*, u.nama, u.kelas
     FROM pengaduan p
     JOIN users u ON p.siswa_id = u.id
     WHERE p.id = ? AND p.sekolah_id = ? AND p.siswa_id = ?",
    [$pengaduan_id, $sekolah_id, $siswa_id],
    "iii"
);

if (!$data) {
    http_response_code(403);
    die("Akses Ditolak: Pengaduan tidak ditemukan atau Anda tidak memiliki akses ke pengaduan ini.");
}

// Ambil tanggapan pengaduan
$tanggapan_list = db_fetch_all(
    "SELECT * FROM tanggapan_pengaduan WHERE pengaduan_id = ? ORDER BY created_at ASC",
    [$pengaduan_id],
    "i"
);
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengaduan #<?= sprintf('%04d', $data['id']) ?> | SuaraKita</title>
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

<!-- Sidebar -->
<?php include 'partials/sidebar.php'; ?>

<main class="ml-64 min-h-screen flex flex-col">
    <!-- Header -->
    <?php include 'partials/header.php'; ?>

    <!-- Content -->
    <div class="p-6 md:p-8 mt-16 flex-1 max-w-5xl mx-auto w-full space-y-6">

        <!-- Top Navigation Bar & Action Receipt -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <a href="riwayat_pengaduan.php" class="inline-flex items-center gap-2 text-xs font-mono text-zinc-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
            </a>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-3 py-1.5 rounded-xl text-xs font-mono font-bold bg-teal-500/10 hover:bg-teal-500/20 text-teal-300 border border-teal-500/30 transition flex items-center gap-1.5 active:scale-95">
                    <i class="fa-solid fa-print"></i> Cetak Bukti Resi (QR Verified)
                </button>
                <span class="px-2.5 py-1.5 rounded-xl text-xs font-mono font-medium bg-zinc-800 text-zinc-300 border border-white/10">
                    ID #<?= sprintf('%04d', $data['id']) ?>
                </span>
            </div>
        </div>

        <!-- Main Ticket Card -->
        <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl p-6 md:p-8 space-y-6">
            
            <!-- Badges Header -->
            <div class="flex flex-wrap items-center gap-2 text-xs font-mono">
                <span class="px-3 py-1 rounded-md bg-zinc-800 text-zinc-300 border border-white/10">
                    <i class="fa-solid fa-folder text-teal-400 mr-1.5"></i> <?= htmlspecialchars($data['kategori'] ?? 'Fasilitas') ?>
                </span>

                <?php
                $urgency_class = match(strtolower($data['urgensi'] ?? 'sedang')) {
                    'rendah' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'sedang' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                    'tinggi' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                    'darurat' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                    default => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20'
                };
                ?>
                <span class="px-3 py-1 rounded-full border <?= $urgency_class ?>">
                    ⚡ Urgensi: <?= ucfirst(htmlspecialchars($data['urgensi'] ?? 'sedang')) ?>
                </span>

                <?php if (!empty($data['deadline_sla'])): ?>
                    <span class="px-3 py-1 rounded-full border border-emerald-500/20 bg-emerald-500/10 text-emerald-400">
                        ⏱️ Estimasi SLA: <?= date('d M Y', strtotime($data['deadline_sla'])) ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Ticket Title -->
            <h1 class="text-2xl font-bold tracking-tight text-white border-b border-white/10 pb-4">
                <?= htmlspecialchars($data['judul']) ?>
            </h1>

            <!-- Ticket Content Body -->
            <div class="text-zinc-300 text-sm leading-relaxed whitespace-pre-line min-h-[80px]">
                <?= htmlspecialchars($data['isi']) ?>
            </div>

            <!-- Attachment File -->
            <?php if (!empty($data['lampiran'])): ?>
                <div class="border-t border-white/10 pt-4">
                    <p class="text-xs font-mono font-semibold text-zinc-400 mb-3">📎 BUKTI LAMPIRAN PENGADUAN:</p>
                    <?php
                    $ext = strtolower(pathinfo($data['lampiran'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])):
                    ?>
                        <a href="../assets/uploads/<?= htmlspecialchars($data['lampiran']) ?>" target="_blank" class="inline-block group">
                            <img src="../assets/uploads/<?= htmlspecialchars($data['lampiran']) ?>" alt="Bukti Lampiran" class="max-h-64 rounded-xl border border-white/10 shadow-lg group-hover:border-teal-500/50 transition">
                        </a>
                    <?php else: ?>
                        <a href="../assets/uploads/<?= htmlspecialchars($data['lampiran']) ?>" target="_blank" class="inline-flex items-center gap-2 bg-zinc-950/80 text-teal-400 px-4 py-2.5 rounded-xl border border-white/10 hover:border-teal-500/40 text-xs font-mono transition">
                            <i class="fa-solid fa-file-arrow-down text-sm"></i> Unduh Dokumen Lampiran (.<?= $ext ?>)
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Timeline Status -->
            <div class="border-t border-white/10 pt-5">
                <p class="text-xs font-mono font-semibold text-zinc-400 mb-3">📌 PROGRES PENANGANAN:</p>

                <?php if ($data['status'] === 'ditolak'): ?>
                    <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-4 text-xs text-rose-300 space-y-1">
                        <p class="font-bold text-rose-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-xmark"></i> Status: Pengaduan Ditolak
                        </p>
                        <p class="text-zinc-300"><strong>Alasan Penolakan:</strong> <?= nl2br(htmlspecialchars($data['alasan_penolakan'] ?? 'Tidak ada alasan spesifik.')) ?></p>
                    </div>
                <?php else: ?>
                    <?php
                    $statuses = ['terkirim', 'ditinjau', 'diproses', 'selesai'];
                    $current_status = ($data['status'] === 'dikirim') ? 'terkirim' : $data['status'];
                    $current_idx = array_search($current_status, $statuses);
                    if ($current_idx === false) $current_idx = 0;
                    ?>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs font-mono">
                        <?php foreach ($statuses as $idx => $st): ?>
                            <?php
                            $is_active = $idx <= $current_idx;
                            $st_labels = [
                                'terkirim' => '1. Terkirim',
                                'ditinjau' => '2. Ditinjau',
                                'diproses' => '3. Diproses',
                                'selesai'  => '4. Selesai'
                            ];
                            ?>
                            <div class="p-3 rounded-xl border transition <?= $is_active ? 'bg-teal-500/10 border-teal-500/30 text-teal-400 font-bold' : 'bg-zinc-950/40 border-white/5 text-zinc-600' ?>">
                                <?= $st_labels[$st] ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Meta Footer & Digital Ticket QR Code Receipt Verification -->
            <div class="border-t border-white/10 pt-4 flex flex-col md:flex-row items-center justify-between gap-4 bg-zinc-950/40 p-4 rounded-2xl border border-white/5">
                <div class="text-xs font-mono text-zinc-400 space-y-1 text-center md:text-left">
                    <div>👤 Pelapor: <strong class="text-zinc-200"><?= htmlspecialchars($data['nama']) ?></strong> (<?= htmlspecialchars($data['kelas']) ?>)</div>
                    <div>📅 Waktu Registrasi: <?= date('d M Y H:i', strtotime($data['created_at'])) ?> WIB</div>
                    <div class="text-[10px] text-teal-400 font-bold flex items-center justify-center md:justify-start gap-1">
                        <i class="fa-solid fa-shield-check"></i> Digital Signature SHA-256 Validated
                    </div>
                </div>

                <!-- Live Dynamic QR Code -->
                <div class="flex items-center gap-3 bg-zinc-900 border border-white/10 p-2.5 rounded-xl shadow-lg">
                    <?php 
                    $verifyUrl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080') . "/siswa/detail_pengaduan.php?id=" . $data['id'];
                    $qrApiUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($verifyUrl);
                    ?>
                    <img src="<?= $qrApiUrl ?>" alt="QR Code Resi Verified" class="w-14 h-14 rounded-lg bg-white p-1 border border-white/20">
                    <div class="text-left font-mono">
                        <div class="text-[10px] font-bold text-white uppercase tracking-wider">RESIPROKAL QR</div>
                        <div class="text-[9px] text-teal-400 font-bold">VERIFIED TICKET</div>
                        <div class="text-[9px] text-zinc-500">Scan bukti otentik</div>
                    </div>
                </div>
            </div>

            <!-- CSAT Rating Widget (If status finished) -->
            <?php if ($data['status'] === 'selesai'): ?>
                <div class="border-t border-white/10 pt-5">
                    <?php if (!empty($data['rating'])): ?>
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 text-zinc-200">
                            <p class="text-xs font-mono font-bold text-amber-400 uppercase tracking-wider mb-1.5">⭐ PENILAIAN KEPUASAN ANDA</p>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="text-amber-400 text-lg">
                                    <?= str_repeat('★', (int)$data['rating']) ?><?= str_repeat('☆', 5 - (int)$data['rating']) ?>
                                </div>
                                <span class="font-bold text-xs font-mono text-amber-300"><?= (int)$data['rating'] ?> / 5.0</span>
                            </div>
                            <?php if (!empty($data['ulasan_rating'])): ?>
                                <p class="text-xs italic text-zinc-300">"<?= htmlspecialchars($data['ulasan_rating']) ?>"</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-teal-500/10 border border-teal-500/20 rounded-xl p-5 space-y-3">
                            <div>
                                <h4 class="font-bold text-teal-300 text-xs font-mono uppercase tracking-wider">⭐ Berikan Penilaian Kepuasan Penanganan</h4>
                                <p class="text-xs text-zinc-400 mt-0.5">Bagaimana kualitas dan kecepatan pelayanan pengelola terhadap pengaduan ini?</p>
                            </div>
                            <form action="../aksi/aksi_rating.php" method="POST" class="space-y-3">
                                <input type="hidden" name="pengaduan_id" value="<?= $data['id'] ?>">

                                <div class="flex items-center gap-4 text-2xl cursor-pointer">
                                    <div class="flex flex-row-reverse justify-end gap-1 text-zinc-600 group">
                                        <?php for ($s = 5; $s >= 1; $s--): ?>
                                            <input type="radio" id="star<?= $s ?>" name="rating" value="<?= $s ?>" required class="sr-only peer">
                                            <label for="star<?= $s ?>" class="peer-checked:text-amber-400 hover:text-amber-400 hover:peer-all:text-amber-400 cursor-pointer transition">★</label>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-xs text-zinc-400 font-mono">(1 - 5 Bintang)</span>
                                </div>

                                <textarea name="ulasan_rating" rows="2" placeholder="Tulis masukan atau rincian ulasan Anda (opsional)..." class="w-full bg-zinc-950/80 border border-white/10 rounded-xl p-3 text-xs text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50"></textarea>

                                <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-xl text-xs font-mono font-semibold hover:bg-teal-500 transition">
                                    Kirim Penilaian Kepuasan
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Chat / Tanggapan Thread -->
        <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl p-6 md:p-8 space-y-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-comments text-teal-400"></i> Diskusi & Tanggapan Petugas
            </h3>

            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                <?php if (empty($tanggapan_list)): ?>
                    <div class="text-center py-8 text-zinc-500 text-xs italic font-mono">
                        Belum ada tanggapan atau pesan balasan dari pengelola.
                    </div>
                <?php else: ?>
                    <?php foreach ($tanggapan_list as $row): ?>
                        <div class="flex flex-col <?= $row['pengirim'] == 'siswa' ? 'items-end' : 'items-start' ?>">
                            <div class="max-w-[80%] rounded-2xl p-4 text-xs space-y-1.5 <?= $row['pengirim'] == 'siswa' ? 'bg-teal-600 text-white rounded-br-none shadow-lg shadow-teal-950/50' : 'bg-zinc-800 border border-white/10 text-zinc-200 rounded-bl-none shadow-md' ?>">
                                <div class="flex items-center justify-between gap-4 font-mono text-[10px] opacity-80 border-b border-white/10 pb-1">
                                    <span class="font-bold uppercase"><?= ucfirst($row['pengirim']) ?></span>
                                    <span><?= date('d M Y H:i', strtotime($row['created_at'])) ?></span>
                                </div>
                                <div class="leading-relaxed">
                                    <?= nl2br(htmlspecialchars($row['pesan'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Form Kirim Tanggapan Balasan -->
            <form action="../aksi/kirim_tanggapan_siswa.php" method="post" class="space-y-3 border-t border-white/10 pt-4">
                <input type="hidden" name="pengaduan_id" value="<?= $pengaduan_id ?>">

                <textarea
                    name="isi"
                    rows="3"
                    required
                    placeholder="Tulis balasan atau pertanyaan tindak lanjut..."
                    class="w-full bg-zinc-950/80 border border-white/10 rounded-xl p-3.5 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50 focus:ring-1 focus:ring-teal-500/50 transition"
                ></textarea>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-5 py-2.5 rounded-xl text-xs font-mono font-bold hover:bg-teal-500 transition active:scale-[0.98] shadow-lg shadow-teal-900/30"
                    >
                        <span>Kirim Balasan</span>
                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<!-- Mascot AI Assistant Widget -->
<?php include __DIR__ . '/partials/ai_widget.php'; ?>

</body>
</html>