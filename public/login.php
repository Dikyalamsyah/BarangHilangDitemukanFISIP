<?php
// --- bootstrap ---
require_once dirname(__DIR__).'/inc/session_boot.php';
require_once dirname(__DIR__).'/inc/csrf.php';
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/helpers.php';
require_once __DIR__.'/../inc/i18n.php'; // <-- i18n harus dimuat sebelum set error

// Sudah login? lempar ke admin
if (!empty($_SESSION['admin_id'])) { header('Location: admin.php'); exit; }

// --- rate limiting (di sesi) ---
$now   = time();
$tries = $_SESSION['login_tries'] ?? 0;
$lock  = $_SESSION['login_lock_until'] ?? 0;

$error = null;

// --- proses login ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // masih terkunci?
  if ($now < $lock) {
    $sisa = $lock - $now;
    $mnt  = max(1, (int)ceil($sisa / 60));
    $error = strtr(t('login.locked'), ['{min}' => $mnt]);
  } else {
    verify_csrf();

    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');

    if (login($u, $p)) {
      // sukses: reset limiter + amankan sesi
      $_SESSION['login_tries'] = 0;
      $_SESSION['login_lock_until'] = 0;
      if (function_exists('session_regenerate_id')) session_regenerate_id(true);
      header('Location: admin.php'); exit;
    } else {
      // gagal: tambah percobaan
      $tries++;
      $_SESSION['login_tries'] = $tries;
      if ($tries >= 5) { // batas percobaan
        $_SESSION['login_lock_until'] = time() + (5*60); // 5 menit
        $error = strtr(t('login.locked'), ['{min}' => 5]);
      } else {
        $sisa = 5 - $tries;
        $error = strtr(t('login.remaining'), ['{n}' => $sisa]);
      }
    }
  }
}

// --- tampilan ---
include __DIR__ . '/_header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h2 class="h4 mb-3"><?= e(t('login.title')) ?></h2>
    <p class="text-muted small"><?= t('login.only') ?></p>

    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

    <form method="post" action="">
      <?php csrf_field(); ?>
      <div class="mb-3">
        <label class="form-label" for="username"><?= e(t('login.username')) ?></label>
        <input class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="password"><?= e(t('login.password')) ?></label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button class="btn btn-primary" type="submit"><?= e(t('login.submit')) ?></button>
      <a href="index.php" class="btn btn-outline-secondary"><?= e(t('login.back')) ?></a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
