<style>
@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-25deg); }
    75% { transform: rotate(20deg); }
}
.animate-wave {
    animation: wave 1.2s infinite ease-in-out;
}
</style>
<!-- Footer -->
<footer class="bg-slate-900 border-t border-white/10 text-slate-400 pt-16 pb-12 relative overflow-hidden">
    <!-- Subtle Ambient Glow -->
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-48 bg-emerald-500/5 blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            
            <!-- Col 1: Brand Info & Social Media -->
            <div class="space-y-4">
                <a href="index.php" class="flex items-center gap-3.5 group focus:outline-none">
                    <!-- Monogram Logo (Senada dengan Navbar) -->
                    <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-slate-800 border border-slate-700/80 group-hover:border-emerald-500/50 transition-all duration-300 shadow-sm">
                        <span class="text-lg font-bold text-slate-100 group-hover:text-emerald-400 transition-colors">S</span>
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

                <p class="text-xs text-slate-400 leading-relaxed">
                    Platform pengaduan dan aspirasi mahasiswa berbasis digital yang terenkripsi, transparan, serta terintegrasi demi lingkungan kampus yang inklusif.
                </p>

                <!-- Social Media Buttons -->
                <div class="pt-2">
                    <span class="text-[11px] font-semibold text-slate-300 block mb-2.5 uppercase tracking-wider">Ikuti Kami</span>
                    <div class="flex items-center gap-2.5">
                        <a href="#" title="Instagram" aria-label="Instagram" class="group w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-400 border border-slate-700/60 hover:border-emerald-500/40 flex items-center justify-center text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10">
                            <i class="fa-brands fa-instagram transition-transform group-hover:scale-110"></i>
                        </a>
                        <a href="#" title="Twitter" aria-label="Twitter" class="group w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-400 border border-slate-700/60 hover:border-emerald-500/40 flex items-center justify-center text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10">
                            <i class="fa-brands fa-twitter transition-transform group-hover:scale-110"></i>
                        </a>
                        <a href="#" title="LinkedIn" aria-label="LinkedIn" class="group w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-400 border border-slate-700/60 hover:border-emerald-500/40 flex items-center justify-center text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10">
                            <i class="fa-brands fa-linkedin-in transition-transform group-hover:scale-110"></i>
                        </a>
                        <a href="#" title="YouTube" aria-label="YouTube" class="group w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-400 border border-slate-700/60 hover:border-emerald-500/40 flex items-center justify-center text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10">
                            <i class="fa-brands fa-youtube transition-transform group-hover:scale-110"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Col 2: Navigasi Utama -->
            <div class="lg:pl-8">
                <h4 class="text-white font-semibold text-xs mb-4 tracking-wider uppercase">Navigasi Utama</h4>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="#beranda" class="group flex items-center gap-1.5 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[8px] opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 text-emerald-400 transition-all"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tentang" class="group flex items-center gap-1.5 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[8px] opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 text-emerald-400 transition-all"></i>
                            <span>Tentang Kami</span>
                        </a>
                    </li>
                    <li>
                        <a href="#fitur" class="group flex items-center gap-1.5 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[8px] opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 text-emerald-400 transition-all"></i>
                            <span>Fitur Utama</span>
                        </a>
                    </li>
                    <li>
                        <a href="#panduan" class="group flex items-center gap-1.5 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[8px] opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 text-emerald-400 transition-all"></i>
                            <span>Panduan Penggunaan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Akses Cepat (Tanpa Daftar Akun) -->
            <div>
                <h4 class="text-white font-semibold text-xs mb-4 tracking-wider uppercase">Tautan Akses</h4>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="auth/login.php" class="group flex items-center gap-1.5 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[8px] opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 text-emerald-400 transition-all"></i>
                            <span>Masuk Akun</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group flex items-center gap-1.5 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[8px] opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 text-emerald-400 transition-all"></i>
                            <span>Syarat & Ketentuan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group flex items-center gap-1.5 hover:text-emerald-400 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[8px] opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 text-emerald-400 transition-all"></i>
                            <span>Kebijakan Privasi</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Pusat Bantuan -->
            <div>
                <h4 class="text-white font-semibold text-xs mb-4 tracking-wider uppercase">Pusat Bantuan</h4>
                <div class="space-y-3 text-xs">
                    <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Email Resmi</span>
                            <span class="text-slate-200 font-semibold">bantuan@suarakita.ac.id</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Layanan Helpdesk</span>
                            <span class="text-slate-200 font-semibold">Aktif 24/7 Hari Kerja</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Copyright Bottom Line -->
        <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
            <p>&copy; <?= date('Y'); ?> SuaraKita. Seluruh hak cipta dilindungi undang-undang.</p>
            <p class="flex items-center gap-1.5">
                <span>Dibuat dengan</span>
                <i class="fa-solid fa-heart text-emerald-500 text-[10px]"></i>
                <span>untuk Kemajuan Kampus</span>
            </p>
        </div>
    </div>
</footer>

<!-- ================= SUARAKITA AI CHATBOT WIDGET ================= -->
<div id="aiChatWidget" class="fixed bottom-8 right-8 z-[9999] flex flex-col items-end pointer-events-none">
    
    <!-- Chat Window Modal -->
    <div id="aiChatWindow" class="pointer-events-auto hidden w-[88vw] sm:w-96 rounded-3xl bg-slate-900/95 backdrop-blur-2xl border border-slate-700/80 shadow-2xl overflow-hidden mb-4 transition-all duration-300 transform scale-95 opacity-0 origin-bottom-right">
        
        <!-- Header Chat -->
        <div class="p-4 bg-slate-800/90 border-b border-slate-700/80 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative w-9 h-9 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md">
                    <i class="fa-solid fa-robot text-base"></i>
                    <span class="absolute -top-0.5 -right-0.5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white tracking-tight flex items-center gap-1.5">
                        <span>SuaraKita AI Assistant</span>
                        <span class="text-[9px] px-1.5 py-0.2 rounded bg-emerald-500/20 text-emerald-300 font-mono font-normal">v1.2</span>
                    </h4>
                    <span class="text-[10px] text-emerald-400 font-medium">🟢 Siap Membantu 24/7</span>
                </div>
            </div>

            <button onclick="toggleAiChat()" class="text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-slate-700/60 transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Chat Body / Messages Container -->
        <div id="aiChatMessages" class="p-4 h-72 overflow-y-auto space-y-3 text-xs font-sans scrollbar-thin scrollbar-thumb-slate-700">
            
            <!-- Welcome Bot Message -->
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-bot text-xs"></i>
                </div>
                <div class="p-3 rounded-2xl rounded-tl-xs bg-slate-800 border border-slate-700/60 text-slate-200 leading-relaxed max-w-[85%]">
                    Halo! Saya <strong>SuaraKita AI</strong> 🤖. Ada yang bisa saya bantu mengenai fitur platform atau alur pengaduan Anda?
                </div>
            </div>

            <!-- Quick Suggestion Chips -->
            <div id="aiQuickChips" class="pt-1 flex flex-wrap gap-1.5">
                <button onclick="sendQuickAiQuery('🔒 Bagaimana cara lapor anonim?')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-[11px] text-emerald-300 transition text-left">
                    🔒 Lapor Anonim
                </button>
                <button onclick="sendQuickAiQuery('⏱️ Berapa lama laporan diproses?')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-[11px] text-emerald-300 transition text-left">
                    ⏱️ Waktu Respon
                </button>
                <button onclick="sendQuickAiQuery('🏷️ Apa saja kategori aduan?')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-[11px] text-emerald-300 transition text-left">
                    🏷️ Kategori Aduan
                </button>
                <button onclick="sendQuickAiQuery('📁 Format file lampiran apa saja?')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-[11px] text-emerald-300 transition text-left">
                    📁 Syarat Lampiran
                </button>
            </div>

        </div>

        <!-- Chat Input Footer -->
        <div class="p-3 bg-slate-800/90 border-t border-slate-700/80 flex items-center gap-2">
            <input type="text" id="aiChatInput" onkeypress="handleAiKeyPress(event)" placeholder="Ketik pertanyaan seputar SuaraKita..." 
                   class="w-full px-3 py-2 bg-slate-950/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition">
            <button onclick="sendAiMessage()" class="w-8 h-8 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white flex items-center justify-center shrink-0 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
            </button>
        </div>

    </div>

    <!-- Floating Speech Bubble Prompt -->
    <div id="mascotSpeechBubble" class="pointer-events-auto mb-3 px-3.5 py-2 rounded-2xl bg-slate-900/95 text-white text-[11px] font-semibold border border-emerald-500/40 shadow-2xl backdrop-blur-xl animate-bounce flex items-center gap-2 transition-all">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
        </span>
        <span id="mascotSpeechText">Ada aduan yang ingin disalurkan? 💬</span>
    </div>

    <!-- 3D Full-Body Cute Robot Avatar Button -->
    <button onclick="toggleAiChat()" id="aiToggleBtn" title="KITA-BOT - SuaraKita AI Assistant" 
            class="pointer-events-auto group relative flex items-center justify-center w-20 h-24 transition-all duration-300 active:scale-90 hover:scale-110 focus:outline-none">
        
        <!-- Ambient Glowing Aura Behind Mascot -->
        <div class="absolute inset-2 rounded-full bg-emerald-500/30 blur-xl group-hover:bg-emerald-400/50 transition duration-500 animate-pulse"></div>

        <!-- FULL-BODY ROBOT MASCOT CONTAINER -->
        <div class="relative z-10 flex flex-col items-center transition-transform duration-300">
            
            <!-- 1. HEAD (Kepala Robot) -->
            <div class="relative flex flex-col items-center">
                <!-- Antenna with glowing orb -->
                <div class="w-1.5 h-2.5 bg-emerald-400 rounded-t-full relative -mb-0.5">
                    <div class="absolute -top-2 -left-1.5 w-4 h-4 bg-emerald-400 rounded-full blur-[2px] animate-ping opacity-75"></div>
                    <div class="absolute -top-2 -left-1.5 w-4 h-4 bg-emerald-300 rounded-full shadow-lg border border-white flex items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    </div>
                </div>

                <!-- Head Shell -->
                <div class="w-12 h-9 bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950 border-2 border-emerald-400/80 rounded-2xl flex items-center justify-center relative shadow-xl group-hover:border-emerald-300 transition">
                    <!-- Headphone Ears (Telinga Robot) -->
                    <div class="absolute -left-1.5 w-2 h-4 rounded-l-md bg-emerald-500/80 border border-emerald-300"></div>
                    <div class="absolute -right-1.5 w-2 h-4 rounded-r-md bg-emerald-500/80 border border-emerald-300"></div>

                    <!-- Digital Visor Screen -->
                    <div class="w-9 h-6 bg-slate-950 rounded-xl flex items-center justify-center gap-1.5 border border-emerald-500/50 shadow-inner group-hover:border-emerald-400 transition">
                        <!-- Cute Digital Eyes (Glowing) -->
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_#34d399] animate-pulse"></span>
                            <span class="w-2 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_#34d399] animate-pulse"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. BODY & ARMS (Badan & Tangan Robot) -->
            <div class="relative flex items-center justify-center -mt-0.5">
                <!-- Left Arm (Tangan Kiri) -->
                <div class="absolute -left-3 top-1 w-2.5 h-5 rounded-full bg-gradient-to-b from-slate-700 to-slate-900 border border-emerald-400/60 shadow-md">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 absolute bottom-0 left-0"></div>
                </div>

                <!-- Torso Body -->
                <div class="w-9 h-8 bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950 border-2 border-emerald-500/70 rounded-xl flex items-center justify-center shadow-lg relative">
                    <!-- Glowing Heart Emblem "S" -->
                    <div class="w-4 h-4 rounded-full bg-emerald-500/20 border border-emerald-400 flex items-center justify-center">
                        <span class="text-[9px] font-extrabold text-emerald-300 font-mono">S</span>
                    </div>
                </div>

                <!-- Right Arm (Tangan Kanan Waving Active 👋) -->
                <div class="absolute -right-3.5 -top-2 w-3 h-6 rounded-full bg-gradient-to-b from-emerald-500 to-slate-900 border border-emerald-300 shadow-lg animate-wave origin-bottom-left transition-all">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-300 border border-white absolute -top-1 -right-0.5 shadow-md flex items-center justify-center">
                        <span class="w-1 h-1 rounded-full bg-white animate-ping"></span>
                    </div>
                </div>
            </div>

            <!-- 3. LEGS & JET THRUSTERS (Kaki & Jet Melayang) -->
            <div class="flex items-center gap-2 -mt-0.5">
                <!-- Left Leg -->
                <div class="flex flex-col items-center">
                    <div class="w-2 h-3 bg-slate-800 border border-emerald-400/50 rounded-b-md"></div>
                    <!-- Jet Thruster Flame -->
                    <div class="w-1.5 h-2 bg-emerald-400 rounded-b-full shadow-[0_0_10px_#10b981] animate-pulse"></div>
                </div>

                <!-- Right Leg -->
                <div class="flex flex-col items-center">
                    <div class="w-2 h-3 bg-slate-800 border border-emerald-400/50 rounded-b-md"></div>
                    <!-- Jet Thruster Flame -->
                    <div class="w-1.5 h-2 bg-emerald-400 rounded-b-full shadow-[0_0_10px_#10b981] animate-pulse"></div>
                </div>
            </div>

        </div>
    </button>

</div>

<script>
function toggleAiChat() {
    const chatWin = document.getElementById('aiChatWindow');
    if (!chatWin) return;
    
    if (chatWin.classList.contains('hidden')) {
        chatWin.classList.remove('hidden');
        requestAnimationFrame(() => {
            chatWin.classList.remove('scale-95', 'opacity-0');
        });
    } else {
        chatWin.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            chatWin.classList.add('hidden');
        }, 200);
    }
}

function handleAiKeyPress(e) {
    if (e.key === 'Enter') {
        sendAiMessage();
    }
}

function sendQuickAiQuery(query) {
    const input = document.getElementById('aiChatInput');
    if (input) {
        input.value = query;
        sendAiMessage();
    }
}

function sendAiMessage() {
    const input = document.getElementById('aiChatInput');
    const container = document.getElementById('aiChatMessages');
    const chips = document.getElementById('aiQuickChips');
    if (!input || !container) return;

    const query = input.value.trim();
    if (!query) return;

    if (chips) chips.remove();

    // Render User Message
    const userDiv = document.createElement('div');
    userDiv.className = 'flex justify-end mb-2';
    userDiv.innerHTML = `
        <div class="p-3 rounded-2xl rounded-tr-xs bg-emerald-600 text-white leading-relaxed max-w-[85%] font-medium">
            ${escapeHtml(query)}
        </div>
    `;
    container.appendChild(userDiv);
    input.value = '';
    container.scrollTop = container.scrollHeight;

    // Simulated Typing Indicator
    const typingDiv = document.createElement('div');
    typingDiv.id = 'aiTypingIndicator';
    typingDiv.className = 'flex items-start gap-2.5 mb-2';
    typingDiv.innerHTML = `
        <div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-bot text-xs"></i>
        </div>
        <div class="p-3 rounded-2xl rounded-tl-xs bg-slate-800 border border-slate-700/60 text-slate-400 italic">
            <span class="animate-pulse">AI sedang mengetik...</span>
        </div>
    `;
    container.appendChild(typingDiv);
    container.scrollTop = container.scrollHeight;

    // AI Response Engine via Backend API
    fetch('api/chat_ai.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: query })
    })
    .then(res => res.json())
    .then(data => {
        if (typingDiv) typingDiv.remove();
        const responseText = data.reply || 'Mohon maaf, AI sedang memproses aduan lain. Silakan coba kembali.';

        const botDiv = document.createElement('div');
        botDiv.className = 'flex items-start gap-2.5 mb-2';
        botDiv.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bot text-xs"></i>
            </div>
            <div class="p-3 rounded-2xl rounded-tl-xs bg-slate-800 border border-slate-700/60 text-slate-200 leading-relaxed max-w-[85%]">
                ${responseText}
            </div>
        `;
        container.appendChild(botDiv);
        container.scrollTop = container.scrollHeight;
    })
    .catch(() => {
        if (typingDiv) typingDiv.remove();
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flex items-start gap-2.5 mb-2';
        errorDiv.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bot text-xs"></i>
            </div>
            <div class="p-3 rounded-2xl rounded-tl-xs bg-slate-800 border border-slate-700/60 text-slate-200 leading-relaxed max-w-[85%]">
                🤖 Maaf, koneksi ke server AI terputus. Silakan periksa jaringan Anda.
            </div>
        `;
        container.appendChild(errorDiv);
        container.scrollTop = container.scrollHeight;
    });
}

function escapeHtml(str) {
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
}

// Mascot Dynamic Speech Rotator
const mascotPrompts = [
    "Ada aduan yang ingin disalurkan? 💬",
    "Butuh bantuan lapor anonim? 🔒",
    "KITA-BOT siap bantu 24/7! 🤖",
    "Berapa lama laporan diproses? ⏱️"
];
let mascotPromptIndex = 0;
setInterval(() => {
    const el = document.getElementById('mascotSpeechText');
    if (el) {
        mascotPromptIndex = (mascotPromptIndex + 1) % mascotPrompts.length;
        el.textContent = mascotPrompts[mascotPromptIndex];
    }
}, 6000);
</script>