<?php
// inc/session_boot.php
// Wajib di-include PALING ATAS pada setiap file halaman/handler.
// Mengaktifkan cookie session yang aman dan menjalankan session_start().

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'secure' => $secure,
  'httponly' => true,
  'samesite' => 'Lax',
]);
if (session_status() === PHP_SESSION_NONE) session_start();
