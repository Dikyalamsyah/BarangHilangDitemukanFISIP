<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/auth.php';
require_admin();
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$to = isset($_GET['to']) ? $_GET['to'] : '';

$allowed = ['pending','published','claimed','archived'];
if(!$id || !in_array($to, $allowed, true)){
  set_flash('error', 'Permintaan tidak valid.');
  redirect('admin.php');
}
$stmt = $mysqli->prepare("UPDATE items SET status = ?, updated_at = NOW() WHERE id = ?");
$stmt->bind_param('si', $to, $id);
if($stmt->execute()){
  set_flash('success', 'Status item berhasil diperbarui.');
} else { set_flash('error', 'Gagal memperbarui status.'); }
redirect('admin.php');
