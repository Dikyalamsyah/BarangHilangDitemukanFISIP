<?php
// --- Bootstrap & dependensi (file ini berada di /public) ---
require_once dirname(__DIR__).'/inc/session_boot.php';
require_once dirname(__DIR__).'/inc/csrf.php';
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/helpers.php';

require_admin(); // hanya admin yang boleh

global $mysqli;
$success = null;
$error   = null;

// --- Proses form (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf(); // validasi token CSRF

  $old  = $_POST['old_password']  ?? '';
  $new  = $_POST['new_password']  ?? '';
  $new2 = $_POST['new_password2'] ?? '';

  if (strlen($new) < 8) {
    $error = 'Password baru minimal 8 karakter.';
  } elseif ($new !== $new2) {
    $error = 'Konfirmasi password baru tidak sama.';
  } else {
    // Ambil hash lama
    $stmt = $mysqli->prepare("SELECT password_hash FROM admins WHERE id=? LIMIT 1");
    $stmt->bind_param('i', $_SESSION['admin_id']);
    $stmt->execute();
    $row  = $stmt->get_result()->fetch_assoc();
    $hash = $row['password_hash'] ?? null;

    if (!$hash || !password_verify($old, $hash)) {
      $error = 'Password lama salah.';
    } else {
      // Update hash baru (tanpa kolom updated_at)
      $new_hash = password_hash($new, PASSWORD_BCRYPT);
      $stmt2 = $mysqli->prepare("UPDATE admins SET password_hash=? WHERE id=?");
      $stmt2->bind_param('si', $new_hash, $_SESSION['admin_id']);
      if ($stmt2->execute()) {
        if (function_exists('session_regenerate_id')) session_regenerate_id(true);
        $success = 'Password berhasil diperbarui.';
      } else {
        $error = 'Gagal memperbarui password.';
      }
    }
  }
}

// --- Render halaman ---
include __DIR__ . '/_header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2 class="h4 mb-3">Ganti Password Admin</h2>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <?php csrf_field(); ?>
      <div class="mb-3">
        <label class="form-label" for="old_password">Password Lama</label>
        <input type="password" class="form-control" id="old_password" name="old_password" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="new_password">Password Baru</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
        <div class="form-text">Minimal 8 karakter.</div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="new_password2">Ulangi Password Baru</label>
        <input type="password" class="form-control" id="new_password2" name="new_password2" required>
      </div>
      <button class="btn btn-primary" type="submit">Simpan</button>
      <a class="btn btn-outline-secondary" href="admin.php">Kembali</a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
