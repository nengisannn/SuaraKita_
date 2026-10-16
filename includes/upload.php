<?php
// includes/upload.php - Helper File Upload Aman & Modular

/**
 * Upload file dengan proteksi keamanan tinggi
 *
 * @param array $file_array $_FILES['field_name']
 * @param string $destination_dir Path direktori tujuan
 * @param string $prefix_filename Prefix nama file baru
 * @param array $allowed_extensions Whitelist ekstensi diizinkan
 * @param array $allowed_mimes Whitelist MIME type diizinkan
 * @param int $max_size Ukuran file maksimum dalam Bytes (default: 5MB)
 * @return array ['success' => bool, 'filename' => string, 'error' => string]
 */
function upload_secure_file($file_array, $destination_dir, $prefix_filename = "file_", $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'], $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'], $max_size = 5242880) {
    if (!isset($file_array) || $file_array['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Tidak ada file yang diunggah atau terjadi kesalahan upload.'];
    }

    $fileName = $file_array['name'];
    $fileTmp  = $file_array['tmp_name'];
    $fileSize = $file_array['size'];

    // 1. Cek Ukuran File
    if ($fileSize > $max_size) {
        return ['success' => false, 'error' => 'Ukuran file melebihi batas maksimum (Maks: ' . ($max_size / 1024 / 1024) . 'MB).'];
    }

    // 2. Cek Ekstensi File (Whitelist)
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_extensions, true)) {
        return ['success' => false, 'error' => 'Format file tidak diizinkan. Ekstensi diizinkan: ' . implode(', ', $allowed_extensions)];
    }

    // 3. Cek MIME Type Asli File
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmp);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowed_mimes, true)) {
        return ['success' => false, 'error' => 'MIME type file tidak valid (' . htmlspecialchars($mimeType) . ').'];
    }

    // 4. Pastikan Folder Tujuan Ada
    if (!is_dir($destination_dir)) {
        mkdir($destination_dir, 0755, true);
    }

    // 5. Generate Nama File Unik dan Aman (Mencegah Overwrite & RCE)
    $newFileName = $prefix_filename . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $targetPath  = rtrim($destination_dir, '/') . '/' . $newFileName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        return ['success' => true, 'filename' => $newFileName, 'path' => $targetPath];
    }

    return ['success' => false, 'error' => 'Gagal memindahkan file ke direktori tujuan.'];
}
