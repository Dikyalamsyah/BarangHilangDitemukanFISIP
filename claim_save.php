<?php
require_once __DIR__.'/inc/session_boot.php';
require_once __DIR__.'/inc/csrf.php';
require_once __DIR__.'/inc/db.php';

if (empty($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

verify_csrf();

$item_id = (int)($_POST['item_id'] ?? 0);
$claimer = trim($_POST['claimer'] ?? '');

if (!$item_id || $claimer === '') {
  http_response_code(400);
  exit('Data tidak lengkap');
}

$mysqli->begin_transaction();
try {
  // simpan log klaim
  $stmt = $mysqli->prepare("INSERT INTO claim_logs(item_id, claimer, created_at) VALUES(?,?,NOW())");
  $stmt->bind_param('is', $item_id, $claimer);
  $stmt->execute();

  // ubah status item
  $status = 'claimed';
  $stmt2 = $mysqli->prepare("UPDATE items SET status=?, updated_at=NOW() WHERE id=?");
  $stmt2->bind_param('si', $status, $item_id);
  $stmt2->execute();

  $mysqli->commit();
  header('Location: admin.php?claimed=1');
} catch (Throwable $e) {
  $mysqli->rollback();
  http_response_code(500);
  exit('Gagal menyimpan klaim.');
}
