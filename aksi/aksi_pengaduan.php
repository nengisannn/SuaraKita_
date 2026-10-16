
<?php
/**
 * File: aksi/aksi_pengaduan.php
 * Fungsi: Memproses pengajuan pengaduan siswa
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/upload.php';

// =====================================================
// 1. AUTENTIKASI DAN VALIDASI REQUEST
// =====================================================

require_siswa();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../siswa/buat_pengaduan.php');
    exit;
}

// =====================================================
// 2. AMBIL DATA SISWA DAN INPUT FORMULIR
// =====================================================

$siswa_id = (int) ($_SESSION['siswa_id'] ?? 0);
$sekolah_id = (int) ($_SESSION['sekolah_id'] ?? 0);

$judul = sanitize_input($_POST['judul'] ?? '');
$kategori = sanitize_input($_POST['kategori'] ?? 'Fasilitas');
$urgensi = sanitize_input($_POST['urgensi'] ?? 'sedang');
$isi = sanitize_input($_POST['isi'] ?? '');

$is_anonim = (
    isset($_POST['is_anonim']) &&
    $_POST['is_anonim'] == '1'
) ? 1 : 0;

// =====================================================
// 3. VALIDASI INPUT
// =====================================================

$allowed_categories = [
    'Fasilitas',
    'Akademik',
    'Keuangan',
    'Kebersihan',
    'Keamanan',
    'Lainnya'
];

$allowed_urgencies = [
    'rendah',
    'sedang',
    'tinggi',
    'darurat'
];

if (!in_array($kategori, $allowed_categories, true)) {
    $kategori = 'Fasilitas';
}

if (!in_array($urgensi, $allowed_urgencies, true)) {
    $urgensi = 'sedang';
}

if (
    $siswa_id <= 0 ||
    $sekolah_id <= 0 ||
    $judul === '' ||
    $isi === ''
) {
    header('Location: ../siswa/buat_pengaduan.php?pesan=gagal');
    exit;
}

// =====================================================
// 4. PROSES UPLOAD LAMPIRAN
// =====================================================

$lampiran_filename = null;

if (isset($_FILES['lampiran'])) {
    $file_error = $_FILES['lampiran']['error'];

    if ($file_error === UPLOAD_ERR_OK) {
        $upload = upload_secure_file(
            $_FILES['lampiran'],
            __DIR__ . '/../assets/uploads',
            'bukti_' . $siswa_id,
            ['jpg', 'jpeg', 'png', 'webp', 'pdf'],
            [
                'image/jpeg',
                'image/png',
                'image/webp',
                'application/pdf'
            ]
        );

        if (!empty($upload['success'])) {
            $lampiran_filename = $upload['filename'];
        } else {
            header(
                'Location: ../siswa/buat_pengaduan.php?pesan=gagal_upload'
            );
            exit;
        }
    } elseif ($file_error !== UPLOAD_ERR_NO_FILE) {
        header(
            'Location: ../siswa/buat_pengaduan.php?pesan=gagal_upload'
        );
        exit;
    }
}

// =====================================================
// 5. HITUNG DEADLINE SLA
// =====================================================

$days_to_add = match ($urgensi) {
    'darurat' => 1,
    'tinggi'  => 2,
    'sedang'  => 5,
    'rendah'  => 7,
    default   => 5
};

$deadline_sla = date(
    'Y-m-d H:i:s',
    strtotime("+{$days_to_add} days")
);

// =====================================================
// 6. SIMPAN DATA PENGADUAN
// =====================================================

try {
    $success = db_execute(
        "INSERT INTO pengaduan (
            siswa_id,
            sekolah_id,
            kategori,
            urgensi,
            deadline_sla,
            judul,
            isi,
            lampiran,
            is_anonim,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'terkirim')",
        [
            $siswa_id,
            $sekolah_id,
            $kategori,
            $urgensi,
            $deadline_sla,
            $judul,
            $isi,
            $lampiran_filename,
            $is_anonim
        ],
        'iissssssi'
    );

    if (!$success) {
        header(
            'Location: ../siswa/buat_pengaduan.php?pesan=gagal'
        );
        exit;
    }

    // Ambil ID pengaduan yang baru disimpan.
    $result = db_fetch_one(
        "SELECT LAST_INSERT_ID() AS id"
    );

    $inserted_id = (int) ($result['id'] ?? 0);

    // Catat aktivitas pengguna.
    log_activity(
        $sekolah_id,
        $siswa_id,
        'siswa',
        'Buat Pengaduan',
        "Membuat pengaduan #{$inserted_id} ({$judul}) dengan urgensi {$urgensi}"
    );

    header(
        'Location: ../siswa/dashboard_siswa.php?pesan=berhasil'
    );
    exit;

} catch (Throwable $e) {
    error_log(
        '[SuaraKita] Gagal menyimpan pengaduan: ' . $e->getMessage()
    );

    header(
        'Location: ../siswa/buat_pengaduan.php?pesan=gagal'
    );
    exit;
}
