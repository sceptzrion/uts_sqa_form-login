<?php
// reset_lock.php (dev helper — jangan biarkan ini di production)
require_once __DIR__ . '/../src/config.php';

// optional safety check: hanya aktif jika DEV flag true
// define('DEV', true); // kamu bisa aktifkan di src/config.php
if (defined('DEV') && DEV !== true) {
  http_response_code(403);
  echo "Forbidden";
  exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();

// Hapus data login/lockout di session
if (isset($_SESSION['login'])) {
  unset($_SESSION['login']);
}

// Jika ingin juga menghapus CSRF (opsional)
// unset($_SESSION['csrf']);

// Redirect kembali ke login
header('Location: ../');
exit;
