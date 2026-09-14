<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Memeriksa apakah user sudah login.
 * Jika belum, redirect otomatis ke halaman login.
 */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Mengambil nama user yang sedang login dari session.
 * Jika tidak ada, kembalikan 'Guest'.
 */
function current_user_name() {
    return $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'Guest';
}

/**
 * Mengambil ID user yang sedang login dari session.
 */
function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}
?>