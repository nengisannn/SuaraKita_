<?php
// admin/kelola_siswa.php - Kelola Data Siswa/Mahasiswa Secure & Modular dengan Pagination & WhatsApp Column

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

require_admin();

$adminUsername = $_SESSION['admin_username'];
$sekolah_id    = $_SESSION['sekolah_id'];

// Parameter Search & Pagination
$keyword = sanitize_input($_GET['q'] ?? '');

$perPageOptions = [10, 20, 50, 100];
$perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $perPageOptions) ? (int)$_GET['per_page'] : 10;
$page    = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset  = ($page - 1) * $perPage;

// Prepared SQL Query Condition
$conditions = ["level = 'siswa'", "sekolah_id = ?"];
$params     = [$sekolah_id];
$types      = "i";

if ($keyword !== '') {
    $conditions[] = "(nama LIKE ? OR username LIKE ? OR nis LIKE ? OR kelas LIKE ? OR no_hp LIKE ?)";
    $likeKw = '%' . $keyword . '%';
    $params[] = $likeKw;
    $params[] = $likeKw;
    $params[] = $likeKw;
    $params[] = $likeKw;
    $params[] = $likeKw;
    $types .= "sssss";
}

$where_sql = implode(" AND ", $conditions);

// Count Total Rows for Pagination
$countRow = db_fetch_one("SELECT COUNT(*) as total FROM users WHERE $where_sql", $params, $types);
$totalData  = $countRow['total'] ?? 0;
$totalPages = ceil($totalData / $perPage) ?: 1;

// Fetch Students Data
$data_params = array_merge($params, [$perPage, $offset]);
$data_types  = $types . "ii";

$siswa_list = db_fetch_all(
    "SELECT * FROM users WHERE $where_sql ORDER BY id DESC LIMIT ? OFFSET ?",
    $data_params,
    $data_types
);
?>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa / Siswa | Admin SuaraKita</title>
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

<!-- Sidebar -->
<?php include 'partials/sidebar.php'; ?>

<!-- Main Content -->
<main class="ml-64 min-h-screen flex flex-col">

    <!-- Header -->
    <?php include 'partials/header.php'; ?>

    <!-- Content -->
    <section class="p-6 md:p-8 mt-16 flex-1 space-y-6">

        <!-- Header Action -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-zinc-900/60 backdrop-blur-xl p-6 rounded-2xl border border-white/10 shadow-xl">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-teal-500/10 text-teal-400 border border-teal-500/20">
                        <i class="fa-solid fa-users mr-1.5"></i> CIVITAS DATABASE
                    </span>
                    <span class="text-xs text-zinc-400 font-mono">Total <?= number_format($totalData) ?> Akun Mahasiswa</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Kelola Data Mahasiswa</h1>
                <p class="text-xs text-zinc-400">Daftar pengguna terdaftar yang berhak menyampaikan aspirasi pengaduan.</p>
            </div>

            <a href="tambah_siswa.php" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-mono font-bold transition-all active:scale-[0.98] shadow-lg shadow-teal-900/30">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>+ Tambah Mahasiswa Baru</span>
            </a>
        </div>

        <div class="bg-zinc-900/60 backdrop-blur-xl rounded-2xl border border-white/10 shadow-xl overflow-hidden">
            <!-- Search & Controls -->
            <form method="GET" class="p-4 border-b border-white/10 bg-zinc-950/40 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex items-center gap-3 w-full md:w-auto flex-1 max-w-md">
                    <div class="relative w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-500 text-xs"></i>
                        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama, NIM, kelas, WA, atau username..."
                            class="w-full bg-zinc-900/90 border border-white/10 rounded-xl pl-9 pr-4 py-2 text-xs text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-teal-500/50" />
                    </div>
                    <button type="submit" class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-200 border border-white/10 rounded-xl text-xs font-semibold transition">
                        Cari
                    </button>
                    <?php if (!empty($keyword)): ?>
                        <a href="kelola_siswa.php" class="text-xs text-zinc-400 hover:text-zinc-200 underline whitespace-nowrap">Reset</a>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-2 text-xs text-zinc-400 ml-auto">
                    <span>Tampilkan:</span>
                    <select name="per_page" onchange="this.form.submit()" class="bg-zinc-900 border border-white/10 rounded-xl px-3 py-1.5 text-xs text-zinc-300 focus:outline-none">
                        <?php foreach ($perPageOptions as $opt): ?>
                            <option value="<?= $opt ?>" <?= $perPage == $opt ? 'selected' : '' ?>><?= $opt ?> per halaman</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-950/60 text-zinc-400 uppercase font-mono tracking-wider text-[10px] border-b border-white/10">
                        <tr>
                            <th class="px-5 py-4">No</th>
                            <th class="px-5 py-4">Nama Lengkap</th>
                            <th class="px-5 py-4">Username</th>
                            <th class="px-5 py-4">NIM / NIS</th>
                            <th class="px-5 py-4">Kelas / Jurusan</th>
                            <th class="px-5 py-4">No. WhatsApp</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($siswa_list)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-12 text-zinc-500">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-regular fa-folder-open text-3xl text-zinc-600"></i>
                                        <p class="text-sm text-zinc-400">Data mahasiswa tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = $offset + 1; foreach ($siswa_list as $row): ?>
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="px-5 py-4 font-mono text-zinc-500">#<?= sprintf('%03d', $no++); ?></td>
                                    <td class="px-5 py-4 font-medium text-zinc-200 text-sm"><?= htmlspecialchars($row['nama']); ?></td>
                                    <td class="px-5 py-4 font-mono text-teal-400">@<?= htmlspecialchars($row['username']); ?></td>
                                    <td class="px-5 py-4 font-mono text-zinc-400"><?= htmlspecialchars($row['nis'] ?? '-'); ?></td>
                                    <td class="px-5 py-4 text-zinc-300"><?= htmlspecialchars($row['kelas'] ?? '-'); ?></td>
                                    <td class="px-5 py-4 font-mono">
                                        <?php if (!empty($row['no_hp'])): ?>
                                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $row['no_hp'])) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition">
                                                <i class="fa-brands fa-whatsapp text-xs"></i>
                                                <span><?= htmlspecialchars($row['no_hp']) ?></span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-zinc-600 italic font-sans">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-5 py-4 text-center space-x-1.5">
                                        <a href="edit_siswa.php?id=<?= $row['id']; ?>"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-200 border border-white/10 font-mono text-[11px] transition">
                                            <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                                        </a>
                                        <a href="hapus_siswa.php?id=<?= $row['id']; ?>"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 font-mono text-[11px] transition">
                                            <i class="fa-solid fa-trash text-[10px]"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <?php if ($totalPages > 1): ?>
            <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-t border-white/10 bg-zinc-950/40 gap-3">
                <div class="text-xs text-zinc-400 font-mono">
                    Halaman <strong><?= $page ?></strong> dari <strong><?= $totalPages ?></strong> (Total <strong><?= $totalData ?></strong> mahasiswa)
                </div>
                <div class="flex items-center gap-1.5">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?q=<?= urlencode($keyword) ?>&per_page=<?= $perPage ?>&page=<?= $i ?>"
                            class="px-3 py-1.5 text-xs font-mono font-medium rounded-lg border transition <?= $i == $page ? 'bg-teal-600 text-white border-teal-500 shadow-md shadow-teal-900/30' : 'bg-zinc-900 text-zinc-400 border-white/10 hover:bg-zinc-800 hover:text-white' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Mascot AI Assistant Widget -->
<?php include __DIR__ . '/partials/ai_widget.php'; ?>

</body>
</html>
