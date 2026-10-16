<?php
// admin/upload_foto.php - Modular & Secure Upload Admin

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

require_admin();

$admin_id = $_SESSION['admin_id'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $upload = upload_secure_file(
        $_FILES['foto'],
        __DIR__ . '/../assets/img',
        'profil_admin_' . $admin_id,
        ['jpg', 'jpeg', 'png', 'webp'],
        ['image/jpeg', 'image/png', 'image/webp']
    );

    if ($upload['success']) {
        db_execute("UPDATE users SET foto = ? WHERE id = ? AND level = 'admin'", [$upload['filename'], $admin_id], "si");
    }
}

header("Location: profil_admin.php");
exit;