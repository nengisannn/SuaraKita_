<?php
// admin/edit_siswa.php - Edit Data Mahasiswa

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

$adminUsername = $_SESSION['admin_username'];
$id            = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$sekolah_id    = $_SESSION['sekolah_id'];

if (!$id) {
    header("Location: kelola_siswa.php");
    exit;
}

$siswa = db_fetch_one(
    "SELECT * FROM users WHERE id = ? AND level = 'siswa' AND sekolah_id = ?",
    [$id, $sekolah_id],
    "ii"
);

if (!$siswa) {
    header("Location: kelola_siswa.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa | Admin SuaraKita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<?php include 'partials/sidebar.php'; ?>

<main class="ml-64 min-h-screen flex flex-col">
    <?php include 'partials/header.php'; ?>

    <section class="p-6 md:p-8 mt-16 flex-1 max-w-4xl mx-auto w-full space-y-6">
        <div class="space-y-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-teal-500/10 text-teal-400 border border-teal-500/20 mb-1">
                <i class="fa-solid fa-user-pen mr-1.5"></i> MANAGEMENT SISWA
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-white">Edit Data Mahasiswa</h1>
            <p class="text-xs text-zinc-400">Perbarui informasi profil mahasiswa terdaftar.</p>
        </div>

        <form action="../aksi/aksi_edit_siswa.php" method="POST" class="bg-zinc-900/60 backdrop-blur-xl p-8 rounded-2xl border border-white/10 shadow-2xl space-y-5">
            <input type="hidden" name="id" value="<?= $siswa['id']; ?>">

            <div>
                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($siswa['nama']); ?>" required
                    class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50">
            </div>

            <div>
                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">NIM / NIS</label>
                <input type="text" name="nis" value="<?= htmlspecialchars($siswa['nis']); ?>" required
                    class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-3 text-xs font-mono text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50">
            </div>

            <div>
                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">Kelas / Jurusan</label>
                <input type="text" name="kelas" value="<?= htmlspecialchars($siswa['kelas']); ?>" required
                    class="w-full bg-zinc-950/80 border border-white/10 rounded-xl px-4 py-3 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50">
            </div>

            <div>
                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-zinc-300 mb-2">
                    No. WhatsApp Active <span class="text-teal-400 font-sans font-normal lowercase">(Untuk Notifikasi Real-Time)</span>
                </label>
                <div class="relative">
                    <i class="fa-brands fa-whatsapp absolute left-3.5 top-1/2 -translate-y-1/2 text-teal-400 text-sm"></i>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($siswa['no_hp'] ?? ''); ?>" placeholder="Contoh: 081234567890"
                        class="w-full bg-zinc-950/80 border border-white/10 rounded-xl pl-9 pr-4 py-3 text-xs font-mono text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <a href="kelola_siswa.php" class="px-5 py-2.5 border border-white/10 rounded-xl text-xs font-mono text-zinc-400 hover:text-white hover:bg-zinc-800 transition">
                    Batal
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white px-6 py-2.5 rounded-xl text-xs font-mono font-bold transition active:scale-[0.98] shadow-lg shadow-teal-900/30">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </section>
</main>

<!-- Mascot AI Assistant Widget -->
<?php include __DIR__ . '/partials/ai_widget.php'; ?>

</body>
</html>
