<?php
// siswa/profil_siswa.php - Tampilan Profil Siswa dengan Nomor WhatsApp & Modern Dark UI

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_siswa();

$siswa_id = $_SESSION['siswa_id'];
$siswa = db_fetch_one(
    "SELECT s.*, se.nama_sekolah 
     FROM users s
     LEFT JOIN sekolah se ON s.sekolah_id = se.id
     WHERE s.id = ? AND s.level = 'siswa'",
    [$siswa_id],
    "i"
);

if (!$siswa) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa | SuaraKita</title>
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

    <div class="p-6 md:p-8 mt-16 flex-1 max-w-4xl mx-auto w-full space-y-6">

        <!-- Top Header Card -->
        <div class="bg-zinc-900/60 backdrop-blur-xl p-8 rounded-2xl border border-white/10 shadow-2xl flex flex-col sm:flex-row items-center gap-6">
            <div class="relative group">
                <img src="../assets/img/<?= !empty($siswa['foto']) ? htmlspecialchars($siswa['foto']) : 'default.png'; ?>" 
                     class="w-28 h-28 rounded-2xl object-cover border-2 border-white/10 shadow-xl group-hover:border-teal-500/50 transition">
                
                <form action="upload_foto.php" method="POST" enctype="multipart/form-data" class="mt-3">
                    <label class="block cursor-pointer bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-[10px] font-mono font-bold px-3 py-1.5 rounded-lg border border-white/10 text-center transition">
                        <i class="fa-solid fa-camera mr-1"></i> Ubah Foto
                        <input type="file" name="foto" class="hidden" onchange="this.form.submit()" required>
                    </label>
                </form>
            </div>

            <div class="space-y-1.5 text-center sm:text-left flex-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-teal-500/10 text-teal-400 border border-teal-500/20">
                    <i class="fa-solid fa-graduation-cap mr-1.5"></i> CIVITAS AKADEMIKA
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-white"><?= htmlspecialchars($siswa['nama']); ?></h1>
                <p class="text-xs font-mono text-zinc-400"><?= htmlspecialchars($siswa['kelas']); ?> &bull; <?= htmlspecialchars($siswa['nama_sekolah'] ?? 'Institusi'); ?></p>
                
                <div class="pt-2 flex flex-wrap gap-2 justify-center sm:justify-start">
                    <a href="edit_profile.php" class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-mono font-bold transition active:scale-[0.98] shadow-lg shadow-teal-900/30">
                        <i class="fa-solid fa-user-pen"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Detail Information Grid -->
        <div class="bg-zinc-900/60 backdrop-blur-xl p-8 rounded-2xl border border-white/10 shadow-2xl space-y-4">
            <h3 class="text-sm font-mono font-bold uppercase tracking-wider text-zinc-300 border-b border-white/10 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-address-card text-teal-400"></i> Detail Akun Terdaftar
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                <div class="bg-zinc-950/60 p-4 rounded-xl border border-white/5 space-y-1">
                    <span class="text-zinc-500 uppercase text-[10px]">NIM / NIS</span>
                    <p class="font-bold text-zinc-200 text-sm"><?= htmlspecialchars($siswa['nis']); ?></p>
                </div>

                <div class="bg-zinc-950/60 p-4 rounded-xl border border-white/5 space-y-1">
                    <span class="text-zinc-500 uppercase text-[10px]">Username Login</span>
                    <p class="font-bold text-zinc-200 text-sm">@<?= htmlspecialchars($siswa['username']); ?></p>
                </div>

                <div class="bg-zinc-950/60 p-4 rounded-xl border border-white/5 space-y-1">
                    <span class="text-zinc-500 uppercase text-[10px]">No. WhatsApp Gateway</span>
                    <p class="font-bold text-teal-400 text-sm flex items-center gap-1.5">
                        <i class="fa-brands fa-whatsapp text-emerald-400"></i>
                        <?= !empty($siswa['no_hp']) ? htmlspecialchars($siswa['no_hp']) : '<span class="text-zinc-500 italic font-sans">Belum diisi</span>'; ?>
                    </p>
                </div>

                <div class="bg-zinc-950/60 p-4 rounded-xl border border-white/5 space-y-1">
                    <span class="text-zinc-500 uppercase text-[10px]">Kelas / Jurusan</span>
                    <p class="font-bold text-zinc-200 text-sm"><?= htmlspecialchars($siswa['kelas']); ?></p>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Mascot AI Assistant Widget -->
<?php include __DIR__ . '/partials/ai_widget.php'; ?>

</body>
</html>