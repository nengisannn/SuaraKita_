<?php
// includes/notification.php - Modul Helper Notifikasi & WhatsApp Gateway Dispatcher

if (!defined('CONFIG_LOADED')) {
    require_once __DIR__ . '/../config.php';
    define('CONFIG_LOADED', true);
}
require_once __DIR__ . '/db.php';

/**
 * Buat notifikasi baru untuk user (In-App & Simulated/Real WA Gateway Alert)
 */
function create_notification($user_id, $pengaduan_id, $judul, $pesan) {
    if (!$user_id || !$pengaduan_id) return false;
    
    // Simpan ke tabel database untuk notifikasi internal web
    $inserted = db_execute(
        "INSERT INTO notifikasi (user_id, pengaduan_id, judul, pesan, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())",
        [$user_id, $pengaduan_id, $judul, $pesan],
        "iiss"
    );

    // Ambil data user (no HP / nama) untuk pengiriman WhatsApp
    $user = db_fetch_one("SELECT nama, username, no_hp FROM users WHERE id = ?", [$user_id], "i");
    $nama_user = $user['nama'] ?? 'Pengguna';
    $no_hp     = $user['no_hp'] ?? '';
    
    // Trigger pengiriman WhatsApp (Real API & Simulation Log)
    send_external_notification($user_id, $nama_user, $no_hp, $judul, $pesan);

    return $inserted;
}

/**
 * Ambil daftar notifikasi belum dibaca untuk user tertentu
 */
function get_unread_notifications($user_id, $limit = 5) {
    if (!$user_id) return [];
    return db_fetch_all(
        "SELECT * FROM notifikasi WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
        [$user_id, $limit],
        "ii"
    );
}

/**
 * Hitung jumlah notifikasi belum dibaca
 */
function count_unread_notifications($user_id) {
    if (!$user_id) return 0;
    $row = db_fetch_one(
        "SELECT COUNT(*) as unread FROM notifikasi WHERE user_id = ? AND is_read = 0",
        [$user_id],
        "i"
    );
    return $row['unread'] ?? 0;
}

/**
 * Tandai semua notifikasi milik user sebagai dibaca
 */
function mark_notifications_as_read($user_id) {
    if (!$user_id) return false;
    return db_execute(
        "UPDATE notifikasi SET is_read = 1 WHERE user_id = ?",
        [$user_id],
        "i"
    );
}

/**
 * Helper WhatsApp API Gateway Dispatcher (Fonnte Integration + Log Audit)
 */
function send_external_notification($user_id, $nama_user, $no_hp, $judul, $pesan) {
    $timestamp = date('Y-m-d H:i:s');
    $target_phone = !empty($no_hp) ? $no_hp : 'Belum diisi';

    // Format pesan WhatsApp yang rapi
    $wa_text = "📲 *SUARAKITA NOTIFICATION*\n";
    $wa_text .= "Halo *" . $nama_user . "*,\n\n";
    $wa_text .= "📌 *" . $judul . "*\n";
    $wa_text .= $pesan . "\n\n";
    $wa_text .= "Cek rincian laporan Anda di:\nhttp://localhost:8080/siswa/riwayat_pengaduan.php";

    $api_status = "LOGGED (Simulation Mode)";

    // Jika FONNTE_TOKEN terisi dan nomor HP siswa ada, kirim WhatsApp nyata via Fonnte API
    if (defined('FONNTE_TOKEN') && !empty(FONNTE_TOKEN) && !empty($no_hp)) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $no_hp,
                'message' => $wa_text,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . FONNTE_TOKEN
            ),
        ));

        $response = curl_exec($curl);
        $curl_error = curl_error($curl);
        curl_close($curl);

        if ($curl_error) {
            $api_status = "FAILED (cURL Error: " . $curl_error . ")";
        } else {
            $api_status = "SENT VIA FONNTE API (" . $response . ")";
        }
    }

    // Logging aktivitas pengiriman WhatsApp (lengkap dengan No. HP & Status)
    $log_message = sprintf(
        "--------------------------------------------------\n" .
        "📲 [WHATSAPP DISPATCH] %s\n" .
        "TO: %s (User ID #%d)\n" .
        "NO. WA: %s\n" .
        "HEADER: %s\n" .
        "MESSAGE: %s\n" .
        "GATEWAY RESPONSE: %s\n" .
        "--------------------------------------------------\n\n",
        $timestamp,
        $nama_user,
        $user_id,
        $target_phone,
        $judul,
        $pesan,
        $api_status
    );

    $log_path = __DIR__ . '/../assets/uploads/notifications.log';
    @file_put_contents($log_path, $log_message, FILE_APPEND);
}
