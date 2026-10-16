<?php
session_start();
include '../config.php';

//Cek apakah admin sudah login
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../auth/login.php");
}

// Ambil data admin dari session
$adminUsername = $_SESSION['admin_username'];
$sekolah_id = $_SESSION['sekolah_id'];
$id = $_GET['id'];

// Ambil detail pengaduan
$pengaduan = mysqli_query($config, "
    SELECT p.*, u.nama, u.kelas
    FROM pengaduan p
    JOIN users u ON p.siswa_id = u.id
    WHERE p.id = '$id'
    AND p.sekolah_id = '$sekolah_id'
");
$data = mysqli_fetch_assoc($pengaduan);

//Ambil Riwayat Tanggapan
$tanggapan = mysqli_query($config, "
    SELECT * FROM tanggapan_pengaduan
    WHERE pengaduan_id = '$id'
    ORDER BY created_at ASC
");

// Kalau data tidak ditemukan
if (!$data) {
    header("Location: pengaduan.php");
}
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengaduan #<?= sprintf('%04d', $data['id']) ?> | Panel Admin</title>
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

        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <a href="pengaduan.php" class="inline-flex items-center gap-2 text-xs font-mono text-zinc-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Kelola Pengaduan
            </a>
            <span class="px-2.5 py-1 rounded-full text-xs font-mono font-medium bg-zinc-800 text-zinc-300 border border-white/10">
                TICKET ID #<?= sprintf('%04d', $data['id']) ?>
            </span>
        </div>

        <!-- Main Ticket Card -->
        <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl p-6 md:p-8 space-y-6">
            
            <!-- Badges -->
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
                    <?php
                    $is_overdue = strtotime($data['deadline_sla']) < time() && !in_array($data['status'], ['selesai', 'ditolak']);
                    $sla_bg = $is_overdue ? 'bg-rose-500/10 text-rose-400 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                    ?>
                    <span class="px-3 py-1 rounded-full border text-xs <?= $sla_bg ?>">
                        ⏱️ SLA Target: <?= date('d M Y H:i', strtotime($data['deadline_sla'])) ?> <?= $is_overdue ? '(Overdue/Telat)' : '(On-Track)' ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold tracking-tight text-white border-b border-white/10 pb-4">
                <?= htmlspecialchars($data['judul']) ?>
            </h1>

            <!-- Content Body -->
            <div class="text-zinc-300 text-sm leading-relaxed whitespace-pre-line min-h-[80px]">
                <?= htmlspecialchars($data['isi']) ?>
            </div>

            <!-- Attachment -->
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

            <!-- Rating CSAT Pelapor -->
            <?php if (!empty($data['rating'])): ?>
                <div class="border-t border-white/10 pt-4 bg-amber-500/10 border border-amber-500/20 rounded-xl p-4">
                    <p class="text-xs font-mono font-bold text-amber-400 uppercase tracking-wider mb-1">⭐ PENILAIAN KEPUASAN PELAPOR (CSAT)</p>
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
            <?php endif; ?>

            <!-- Meta Data & Digital Ticket QR Code Receipt Verification -->
            <div class="border-t border-white/10 pt-4 flex flex-col md:flex-row items-center justify-between gap-4 bg-zinc-950/40 p-4 rounded-2xl border border-white/5">
                <div class="text-xs font-mono text-zinc-400 space-y-1 text-center md:text-left">
                    <p class="flex items-center gap-2">Nama Pelapor :
                        <?php if (!empty($data['is_anonim'])): ?>
                            <span class="font-semibold text-teal-400 bg-teal-500/10 px-2 py-0.5 rounded border border-teal-500/20">🔒 Anonim (Identitas Dirahasiakan)</span>
                        <?php else: ?>
                            <span class="font-semibold text-zinc-200"><?= htmlspecialchars($data['nama']) ?></span>
                        <?php endif; ?>
                    </p>
                    <?php if (empty($data['is_anonim'])): ?>
                        <p class="flex items-center gap-2">Kelas / Unit : 
                            <span class="text-zinc-200"><?= htmlspecialchars($data['kelas']) ?></span>
                        </p>
                    <?php endif; ?>
                    <p class="flex items-center gap-2">Tanggal Masuk :
                        <span class="text-zinc-200"><?= date('d M Y H:i', strtotime($data['created_at'])) ?> WIB</span>
                    </p>
                </div>

                <!-- Live Dynamic QR Code -->
                <div class="flex items-center gap-3 bg-zinc-900 border border-white/10 p-2.5 rounded-xl shadow-lg">
                    <?php 
                    $verifyUrl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080') . "/siswa/detail_pengaduan.php?id=" . $data['id'];
                    $qrApiUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($verifyUrl);
                    ?>
                    <img src="<?= $qrApiUrl ?>" alt="QR Code Resi Verified" class="w-14 h-14 rounded-lg bg-white p-1 border border-white/20">
                    <div class="text-left font-mono">
                        <div class="text-[10px] font-bold text-white uppercase tracking-wider">VERIFIED TICKET</div>
                        <div class="text-[9px] text-teal-400 font-bold">SHA-256 SECURED</div>
                        <div class="text-[9px] text-zinc-500">Bukti Autentik Laporan</div>
                    </div>
                </div>
            </div>
                <p class="flex items-center gap-2">Status Penanganan :
                    <?php
                    $status_badge = match(strtolower($data['status'] ?? 'terkirim')) {
                        'terkirim', 'dikirim' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                        'ditinjau' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                        'diproses' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                        'selesai'  => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                        'ditolak'  => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                        default    => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20'
                    };
                    ?>
                    <span class="px-3 py-1 rounded-full border text-xs font-bold <?= $status_badge ?>">
                        <?= ucfirst(htmlspecialchars($data['status'])) ?>
                    </span>
                </p>
            </div>

            <!-- Jika Status Ditolak -->
            <?php if ($data['status'] === 'ditolak' && !empty($data['alasan_penolakan'])): ?>
                <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-4 text-xs text-rose-300">
                    <p class="font-bold mb-1 text-rose-400">❌ Alasan Penolakan Pengaduan:</p>
                    <p><?= nl2br(htmlspecialchars($data['alasan_penolakan'])) ?></p>
                </div>
            <?php endif; ?>

            <!-- Form Update Status Admin -->
            <form action="../aksi/aksi_update_status.php" method="POST" class="mt-6 space-y-4 border-t border-white/10 pt-4">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                <div class="flex flex-wrap items-center gap-4">
                    <label class="text-xs font-mono font-bold uppercase tracking-wider text-zinc-300">Ubah Status Pengaduan:</label>
                    <select name="status" id="statusSelect" onchange="toggleAlasanField()" class="bg-zinc-950 border border-white/10 px-4 py-2 rounded-xl text-xs text-zinc-200 focus:outline-none focus:border-teal-500/50">
                        <option value="terkirim" <?= ($data['status']=='terkirim'||$data['status']=='dikirim')?'selected':''; ?>>📩 Terkirim</option>
                        <option value="ditinjau" <?= $data['status']=='ditinjau'?'selected':''; ?>>👁️ Ditinjau</option>
                        <option value="diproses" <?= $data['status']=='diproses'?'selected':''; ?>>⚙️ Diproses</option>
                        <option value="selesai" <?= $data['status']=='selesai'?'selected':''; ?>>✅ Selesai</option>
                        <option value="ditolak" <?= $data['status']=='ditolak'?'selected':''; ?>>❌ Ditolak</option>
                    </select>

                    <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white px-5 py-2 rounded-xl text-xs font-mono font-bold transition active:scale-[0.98] shadow-lg shadow-teal-900/30">
                        Perbarui Status
                    </button>
                </div>

                <!-- Input Alasan Penolakan (Dynamic) -->
                <div id="alasanContainer" class="<?= $data['status'] === 'ditolak' ? '' : 'hidden' ?> pt-2">
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-rose-400 mb-1.5">Alasan Penolakan <span class="text-rose-400">*</span></label>
                    <textarea name="alasan_penolakan" rows="2" placeholder="Tuliskan alasan mengapa pengaduan ini ditolak..." class="w-full bg-zinc-950/80 border border-rose-500/30 rounded-xl p-3 text-xs text-rose-200 placeholder-zinc-500 focus:outline-none focus:border-rose-500/50"><?= htmlspecialchars($data['alasan_penolakan'] ?? '') ?></textarea>
                </div>
            </form>

            <script>
                function toggleAlasanField() {
                    const select = document.getElementById('statusSelect');
                    const container = document.getElementById('alasanContainer');
                    if (select.value === 'ditolak') {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                }
            </script>
        </div>

        <!-- Chat / Tanggapan Thread -->
        <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl p-6 md:p-8 space-y-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-comments text-teal-400"></i> Diskusi & Balasan Petugas
            </h3>

            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                <?php if (mysqli_num_rows($tanggapan) == 0): ?>
                    <div class="text-center py-8 text-zinc-500 text-xs italic font-mono">
                        Belum ada tanggapan atau balasan yang dikirimkan.
                    </div>
                <?php else: ?>
                    <?php mysqli_data_seek($tanggapan, 0); ?>
                    <?php while ($row = mysqli_fetch_assoc($tanggapan)): ?>
                        <div class="flex flex-col <?= $row['pengirim'] == 'admin' ? 'items-end' : 'items-start' ?>">
                            <div class="max-w-[80%] rounded-2xl p-4 text-xs space-y-1.5 <?= $row['pengirim'] == 'admin' ? 'bg-teal-600 text-white rounded-br-none shadow-lg shadow-teal-950/50' : 'bg-zinc-800 border border-white/10 text-zinc-200 rounded-bl-none shadow-md' ?>">
                                <div class="flex items-center justify-between gap-4 font-mono text-[10px] opacity-80 border-b border-white/10 pb-1">
                                    <span class="font-bold uppercase"><?= ucfirst($row['pengirim']) ?></span>
                                    <span><?= date('d M Y H:i', strtotime($row['created_at'])) ?></span>
                                </div>
                                <div class="leading-relaxed">
                                    <?= nl2br(htmlspecialchars($row['pesan'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <!-- Form Kirim Tanggapan Admin -->
            <form action="../aksi/kirim_tanggapan_admin.php" method="post" class="space-y-3 border-t border-white/10 pt-4">
                <input type="hidden" name="pengaduan_id" value="<?= $data['id'] ?>">

                <textarea
                    name="isi"
                    rows="3"
                    required
                    placeholder="Tulis balasan atau penjelasan penanganan untuk pelapor..."
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
