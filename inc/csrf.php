<?php
// inc/csrf.php
// Generator dan validator token CSRF sederhana.
// Wajib dipanggil pada setiap form POST yang memodifikasi data.
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/**
 * Cetak input hidden berisi token CSRF.
 */
function csrf_field(): void {
  $t = htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8');
  echo '<input type="hidden" name="_token" value="'.$t.'">';
}

/**
 * Validasi token CSRF dari request POST.
 * Hentikan eksekusi dengan kode 419 bila tidak cocok.
 */
function verify_csrf(): void {
  $ok = isset($_POST['_token'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['_token']);
  if (!$ok) {
    http_response_code(419);
    exit('CSRF token mismatch');
  }
}
