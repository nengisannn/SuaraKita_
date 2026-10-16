<?php
header('Content-Type: application/json');

// Tangkap input JSON dari frontend
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = isset($input['message']) ? trim($input['message']) : '';

if (empty($userMessage)) {
    echo json_encode([
        'status' => 'error',
        'reply'  => 'Mohon maaf, pesan Anda tidak boleh kosong.'
    ]);
    exit;
}

// Cek Environment Variables untuk API Key Real LLM (Gemini / OpenAI / DeepSeek)
$geminiApiKey   = getenv('GEMINI_API_KEY');
$openaiApiKey   = getenv('OPENAI_API_KEY');
$deepseekApiKey = getenv('DEEPSEEK_API_KEY');

// SYSTEM PROMPT DENGAN GUARDRAILS & TOPIK BATASAN KETAT
$systemPrompt = "BATASAN PERAN & DOMAIN KETAT:\n" .
    "Kamu adalah Suara AI (Asisten AI Resmi SuaraKita). Peranmu HANYA DAN KHUSUS membantu pertanyaan seputar platform 'SuaraKita', alur pengaduan kampus, fitur aplikasi, mode anonim, SLA waktu respon (<24 jam), tingkat urgensi aduan, dan syarat berkas lampiran.\n\n" .
    "ATURAN GUARDRAIL PENTING:\n" .
    "1. JIKA pengguna bertanya tentang topik di luar SuaraKita, sistem pengaduan, atau fitur kampus (seperti koding, tugas sekolah, matematika, resep makanan, cuaca, politik, hiburan, olahraga, atau umum), KAMU WAJIB MENOLAK SECARA SOPAN DAN RAMAH.\n" .
    "2. Beritahukan pengguna bahwa kamu adalah Asisten Khusus SuaraKita yang difokuskan hanya untuk menjawab seputar pengaduan dan fitur sistem SuaraKita.\n\n" .
    "Fakta Resmi Sistem SuaraKita:\n" .
    "- Fitur: Pengaduan terenkripsi, mode anonim (Whistleblower protection), live status tracking (#SK-xxxx-xxx), serta komunikasi 2 arah dengan kampus.\n" .
    "- Mode Anonim: Identitas pelapor disamarkan 100% dari publik dan tim verifikasi admin.\n" .
    "- Urgensi: Rendah (Biasa), Sedang (Perlu Perhatian), Tinggi (Penting), Darurat (Sangat Mendesak / Bahaya).\n" .
    "- SLA Respon: Kurang dari 24 jam kerja. Laporan darurat diprioritaskan langsung.\n" .
    "- Syarat Lampiran: Foto (JPG, PNG, WEBP) & PDF maksimal 5 MB.\n" .
    "- Cara Membuat Laporan: Login -> Klik 'Buat Pengaduan' -> Isi Judul, Kategori, Urgensi, Deskripsi, dan Lampiran (Opsional).\n\n" .
    "Jawablah selalu dalam Bahasa Indonesia yang santun, ramah, dan ringkas.";

$aiReply = '';

// 1. OPSI A: Menggunakan Google Gemini API jika GEMINI_API_KEY tersedia
if (!empty($geminiApiKey)) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . urlencode($geminiApiKey);
    
    $payload = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $systemPrompt . "\n\nPertanyaan Pengguna: " . $userMessage]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $resData = json_decode($response, true);
        if (isset($resData['candidates'][0]['content']['parts'][0]['text'])) {
            $aiReply = trim($resData['candidates'][0]['content']['parts'][0]['text']);
        }
    }
}

// 2. OPSI B: Menggunakan OpenAI API jika OPENAI_API_KEY tersedia & Gemini belum menjawab
if (empty($aiReply) && !empty($openaiApiKey)) {
    $url = "https://api.openai.com/v1/chat/completions";
    
    $payload = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => $systemPrompt],
            ["role" => "user", "content" => $userMessage]
        ],
        "temperature" => 0.5
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openaiApiKey
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $resData = json_decode($response, true);
        if (isset($resData['choices'][0]['message']['content'])) {
            $aiReply = trim($resData['choices'][0]['message']['content']);
        }
    }
}

// 3. OPSI C: Smart Guardrailed Semantic Engine Fallback
if (empty($aiReply)) {
    $aiReply = generateSmartSemanticResponse($userMessage);
}

// Kembalikan Response JSON ke Frontend
echo json_encode([
    'status' => 'success',
    'reply'  => $aiReply
]);


/**
 * Smart Semantic Response Engine dengan Guardrails Domain SuaraKita
 */
function generateSmartSemanticResponse($msg) {
    $q = mb_strtolower($msg, 'UTF-8');

    // 0. GUARDRAIL DETECTOR: Tolak Pertanyaan Luar Topik (Off-Topic Rejection)
    if (preg_match('/(resep|masak|makanan|nasi|koding|python|java|javascript|php|matematika|hitung|kuadrat|game|cuaca|hujan|lagu|film|cerita|dongeng|presiden|politik|gosip|olahraga|sepak bola)/i', $q)) {
        return "🤖 <strong>Batas Topik AI Assistant:</strong><br/>" .
               "Mohon maaf, sebagai asisten resmi <strong>SuaraKita</strong>, saya hanya dapat membantu menjawab pertanyaan seputar <strong>sistem pengaduan kampus, alur laporan, mode anonim, dan fitur platform SuaraKita</strong>.<br/><br/>" .
               "Silakan tanyakan seputar cara membuat laporan, melacak aduan, atau fitur sistem kami!";
    }

    // Intent: Anonim / Rahasia / Privasi
    if (preg_match('/(anonim|rahasi|privasi|keamanan|nama|sembunyi|identitas|takut)/i', $q)) {
        return "🔒 <strong>Sistem Proteksi Privasi & Anonimitas:</strong><br/>" .
               "Di SuaraKita, Anda dapat mengaktifkan mode <strong>'Kirim sebagai Anonim'</strong> saat mengisi formulir aduan. " .
               "Identitas serta nama Anda disamarkan 100% dari laporan publik maupun pihak admin verifikator untuk menjamin keamanan Anda sepenuhnya.";
    }

    // Intent: Waktu / Respon / SLA / Jam
    if (preg_match('/(lama|waktu|jam|proses|respon|tindak|berapa|kapan|sla)/i', $q)) {
        return "⏱️ <strong>Estimasi & SLA Penanganan Aduan:</strong><br/>" .
               "• Verifikasi awal laporan dilakukan dalam waktu <strong>&lt; 24 jam kerja</strong>.<br/>" .
               "• Laporan berkategori <span class='font-mono text-rose-400'>🔴 Darurat</span> atau <span class='font-mono text-amber-400'>🟠 Tinggi</span> langsung diprioritaskan oleh tim penanggung jawab unit terkait.";
    }

    // Intent: Cara lapor / Buat / Kirim aduan
    if (preg_match('/(cara lapor|buat aduan|buat pengaduan|kirim lapor|langkah lapor|gimana lapor|bagaimana lapor|cara buat)/i', $q)) {
        return "📝 <strong>Langkah Mudah Membuat Pengaduan:</strong><br/>" .
               "1. Masuk ke akun mahasiswa Anda di menu <strong>Masuk</strong>.<br/>" .
               "2. Klik tombol <strong>'Buat Pengaduan Baru'</strong>.<br/>" .
               "3. Isi Judul, Kategori, Urgensi, serta Deskripsi Kejadian.<br/>" .
               "4. Unggah bukti foto/PDF (opsional), lalu klik <strong>'Kirim Pengaduan'</strong>.";
    }

    // Intent: File / Ukuran / Format / Bukti
    if (preg_match('/(file|lampiran|upload|bukti|pdf|gambar|foto|size|ukuran|format)/i', $q)) {
        return "📁 <strong>Ketentuan Lampiran Bukti:</strong><br/>" .
               "• Format file yang didukung: <code class='font-mono text-emerald-400'>JPG, PNG, WEBP, PDF</code>.<br/>" .
               "• Ukuran berkas maksimum: <strong>5 Megabyte (5 MB)</strong>.<br/>" .
               "• Pastikan bukti foto/dokumen terlihat jelas untuk mempercepat proses verifikasi.";
    }

    // Intent: Kategori / Jenis / Bidang
    if (preg_match('/(kategori|jenis|bidang|masalah)/i', $q)) {
        return "🏷️ <strong>Kategori Pengaduan Terstruktur:</strong><br/>" .
               "• 🏢 <strong>Fasilitas:</strong> kerusakan sarana, AC, lab, atau kelas.<br/>" .
               "• 📚 <strong>Akademik:</strong> perkuliahan, nilai, atau jadwal.<br/>" .
               "• 💳 <strong>Keuangan:</strong> pembayaran UKT atau administrasi.<br/>" .
               "• 🛡️ <strong>Keamanan & Kesejahteraan:</strong> lingkungan kampus & parkir.";
    }

    // Intent: Status / Tiket / Lacak / Laporan
    if (preg_match('/(status|tiket|lacak|nomor|riwayat|cek)/i', $q)) {
        return "📊 <strong>Pelacakan Status & Nomor Tiket:</strong><br/>" .
               "Setiap laporan memiliki nomor tiket unik (misal: <code class='font-mono text-emerald-400'>#SK-2026-892</code>). " .
               "Anda dapat menyalin nomor tiket dan memantau perkembangan penanganan secara transparan di halaman <strong>Dashboard Mahasiswa</strong>.";
    }

    // Intent: Salam / Teguran
    if (preg_match('/(halo|hai|pagi|siang|malam|permisi|p|selamat)/i', $q)) {
        return "Halo! 👋 Saya <strong>SuaraKita AI Assistant</strong>. Ada yang bisa saya bantu terkait fitur sistem atau cara menyampaikan aduan Anda di platform SuaraKita?";
    }

    // Guardrail Off-Topic Rejection Fallback
    return "🤖 <strong>Batas Topik AI Assistant:</strong><br/>" .
           "Mohon maaf, sebagai asisten resmi <strong>SuaraKita</strong>, saya hanya dapat membantu menjawab pertanyaan seputar <strong>sistem pengaduan kampus, alur laporan, mode anonim, dan fitur platform SuaraKita</strong>. <br/><br/>" .
           "Silakan tanyakan seputar cara membuat laporan, melacak aduan, atau fitur sistem kami!";
}
