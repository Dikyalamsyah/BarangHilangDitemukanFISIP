<?php
require_once __DIR__.'/inc/session_boot.php';
require_once __DIR__.'/inc/csrf.php';
require_once __DIR__.'/inc/db.php';

// Pastikan hanya admin/login yang boleh
if (empty($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

verify_csrf();

$id = (int)($_POST['id'] ?? 0);
$to = $_POST['to'] ?? '';

$allowed = ['pending','published','claimed','archived'];
if (!$id || !in_array($to, $allowed, true)) {
  http_response_code(400);
  exit('Bad request');
}

$stmt = $mysqli->prepare("UPDATE items SET status=?, updated_at=NOW() WHERE id=?");
$stmt->bind_param('si', $to, $id);
$stmt->execute();

header('Location: admin.php?ok=1');
