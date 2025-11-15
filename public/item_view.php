<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
include __DIR__ . '/_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare("SELECT * FROM items WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if(!$item){
  echo '<div class="alert alert-warning">Item tidak ditemukan.</div>';
  include __DIR__ . '/_footer.php';
  exit;
}
?>
<div class="row">
  <div class="col-md-6">
    <?php if(!empty($item['image_path']) && is_file(UPLOAD_DIR.$item['image_path'])): ?>
      <img class="img-fluid rounded mb-3" src="<?= e(uploads_url($item['image_path'])) ?>" alt="Foto">
    <?php else: ?>
      <div class="border rounded p-5 text-center text-muted">Tidak ada foto</div>
    <?php endif; ?>
  </div>
  <div class="col-md-6">
    <span class="badge <?= $item['type']=='found' ? 'text-bg-success' : 'text-bg-warning' ?>"><?= $item['type']=='found' ? 'Ditemukan' : 'Hilang' ?></span>
    <h2 class="h4 mt-2"><?= e($item['title']) ?></h2>
    <p><strong>Kategori:</strong> <?= e($item['category']) ?></p>
    <p><strong>Lokasi:</strong> <?= e($item['location']) ?></p>
    <p><?= nl2br(e($item['description'])) ?></p>
    <hr>
    <p class="small text-muted mb-1"><strong>Pelapor:</strong> <?= e($item['reporter_name']) ?></p>
    <?php if($item['reporter_contact']): ?><p class="small text-muted"><strong>Kontak:</strong> <?= e($item['reporter_contact']) ?></p><?php endif; ?>
    <a href="index.php" class="btn btn-outline-secondary">Kembali</a>
  </div>
</div>
<?php include __DIR__ . '/_footer.php'; ?>
