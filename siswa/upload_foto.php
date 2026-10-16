<?php
// siswa/upload_foto.php - Modular & Secure Upload Siswa

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

require_siswa();

$siswa_id = $_SESSION['siswa_id'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $upload = upload_secure_file(
        $_FILES['foto'],
        __DIR__ . '/../assets/img',
        'profil_siswa_' . $siswa_id,
        ['jpg', 'jpeg', 'png', 'webp'],
        ['image/jpeg', 'image/png', 'image/webp']
    );

    if ($upload['success']) {
        db_execute("UPDATE users SET foto = ? WHERE id = ? AND level = 'siswa'", [$upload['filename'], $siswa_id], "si");
    }
}

header("Location: profil_siswa.php");
exit;