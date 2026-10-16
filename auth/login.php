<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | SuaraKita</title>
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="../assets/js/tailwindcss.js"></script>
    <script>window.tailwind || document.write('<script src="https://cdn.tailwindcss.com"><\/script>')</script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                },
            },
        }
    </script>
    <style>
        .mesh-login {
            background-color: #09090b;
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(13, 148, 136, 0.15) 0px, transparent 50%);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center mesh-login px-4 font-sans text-slate-100 antialiased selection:bg-emerald-500 selection:text-white">

<div class="w-full max-w-md my-8">

    <!-- Logo Monogram -->
    <div class="text-center mb-6">
        <a href="../index.php" class="inline-flex items-center gap-3 group focus:outline-none mb-2">
            <div class="relative flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 group-hover:border-emerald-500/50 transition-all shadow-md">
                <span class="text-xl font-extrabold text-white group-hover:text-emerald-400 transition-colors">S</span>
                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
            </div>
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-white">SuaraKita</h1>
        <p class="text-xs text-slate-400 mt-1">Sistem Pengaduan & Aspirasi Digital Integratif</p>
    </div>

    <!-- Login Card (Glass Dark Surface) -->
    <div class="bg-slate-900/90 backdrop-blur-xl p-8 rounded-3xl border border-slate-800 shadow-2xl relative overflow-hidden">
        
        <div class="mb-6 text-center">
            <h2 class="text-lg font-bold text-white">Selamat Datang Kembali</h2>
            <p class="text-xs text-slate-400 mt-0.5">Masukkan kredensial akun Anda untuk mengakses portal</p>
        </div>

        <form method="post" action="../aksi/aksi_login.php" class="space-y-5">

            <!-- Username -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    Username / ID Pengguna
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input type="text" name="username" required placeholder="Masukkan username"
                           class="w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-mono">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    Kata Sandi
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>

                    <input type="password" id="password" name="password" required placeholder="Masukkan password"
                           class="w-full pl-10 pr-10 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-mono">

                    <!-- Toggle Eye Button -->
                    <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-emerald-400 transition focus:outline-none">
                        <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl text-xs uppercase tracking-wider shadow-md active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span>Masuk Ke Sistem</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>

        </form>

        <?php if(isset($_GET['error']) && $_GET['error'] == '1') { ?>
            <div id="errorAlert" class="mt-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs text-center font-semibold transition-opacity duration-500 flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> Username atau Password tidak cocok!
            </div>
        <?php } ?>

    </div>

    <!-- Kembali Ke Beranda -->
    <div class="text-center mt-6">
        <a href="../index.php" class="text-xs font-semibold text-slate-400 hover:text-emerald-400 transition-colors inline-flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Beranda Utama
        </a>
    </div>

</div>

<!-- JS -->
<script>
function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash", "text-emerald-400");
    } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash", "text-emerald-400");
        icon.classList.add("fa-eye");
    }
}

window.addEventListener("DOMContentLoaded", () => {
    const alertBox = document.getElementById("errorAlert");
    if (alertBox) {
        setTimeout(() => {
            alertBox.classList.add("opacity-0");
        }, 4000);
    }
});
</script>
</body>
</html>
