<?php
// includes/db.php - Helper Database & Prepared Statements Modular

if (!defined('CONFIG_LOADED')) {
    require_once __DIR__ . '/../config.php';
    define('CONFIG_LOADED', true);
}

/**
 * Eksekusi query dengan prepared statement
 * @param string $sql Query SQL dengan placeholder ?
 * @param array $params Parameter yang akan di-bind
 * @param string $types Tipe data (misal: 'ssi' -> string, string, int). Jika kosong, otomatis dideteksi.
 * @return mysqli_stmt|false Statement yang telah dieksekusi
 */
function db_query($sql, $params = [], $types = "") {
    global $config;

    $stmt = mysqli_prepare($config, $sql);
    if (!$stmt) {
        error_log("Database error: " . mysqli_error($config));
        return false;
    }

    if (!empty($params)) {
        if (empty($types)) {
            $types = "";
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= "i";
                } elseif (is_double($param) || is_float($param)) {
                    $types .= "d";
                } else {
                    $types .= "s";
                }
            }
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute error: " . mysqli_stmt_error($stmt));
        return false;
    }

    return $stmt;
}

/**
 * Mengambil 1 baris data sebagai array assosiatif
 */
function db_fetch_one($sql, $params = [], $types = "") {
    $stmt = db_query($sql, $params, $types);
    if (!$stmt) return null;

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row;
}

/**
 * Mengambil seluruh baris data sebagai array assosiatif
 */
function db_fetch_all($sql, $params = [], $types = "") {
    $stmt = db_query($sql, $params, $types);
    if (!$stmt) return [];

    $result = mysqli_stmt_get_result($stmt);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $data;
}

/**
 * Eksekusi query INSERT, UPDATE, DELETE dan kembalikan status/ID
 */
function db_execute($sql, $params = [], $types = "") {
    $stmt = db_query($sql, $params, $types);
    if (!$stmt) return false;

    $insert_id = mysqli_stmt_insert_id($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    return $insert_id ?: ($affected_rows >= 0);
}
