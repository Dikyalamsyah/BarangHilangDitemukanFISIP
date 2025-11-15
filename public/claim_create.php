<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/auth.php';
require_admin();
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/db.php';
include __DIR__ . '/_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare("SELECT id, title FROM items WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if(!$item){
  echo '<div class="alert alert-warning">Item tidak ditemukan.</div>';
  include __DIR__ . '/_footer.php';
  exit;
}
?>
<h2 class="h4 mb-3">Klaim Item #<?= (int)$item['id'] ?> - <?= e($item['title']) ?></h2>
<form method="post" action="claim_save.php" enctype="multipart/form-data" class="row g-3">
  <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
  <div class="col-md-6">
    <label class="form-label">Nama Pengeklaim</label>
    <input type="text" class="form-control" name="claimant_name" required maxlength="120">
  </div>
  <div class="col-md-6">
    <label class="form-label">Bukti (opsional, jpg/png/webp maks 3MB)</label>
    <input type="file" class="form-control" name="evidence">
  </div>
  <div class="col-12">
    <label class="form-label">Catatan (opsional)</label>
    <textarea class="form-control" name="note" rows="3" maxlength="1000"></textarea>
  </div>
  <div class="col-12">
    <button class="btn btn-success" type="submit">Simpan Klaim</button>
    <a class="btn btn-outline-secondary" href="admin.php">Batal</a>
  </div>
</form>
<?php include __DIR__ . '/_footer.php'; ?>
