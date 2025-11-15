<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/auth.php';
require_admin();
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/db.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$claimant = trim($_POST['claimant_name'] ?? '');
$note = trim($_POST['note'] ?? '');
if(!$id || $claimant===''){ set_flash('error','Data klaim tidak lengkap.'); redirect('admin.php'); }

$evidence_filename = null;
if(!empty($_FILES['evidence']) && $_FILES['evidence']['error'] !== UPLOAD_ERR_NO_FILE){
  if(!is_valid_image_upload($_FILES['evidence'])){ set_flash('error','Bukti tidak valid atau melebihi batas 3MB.'); redirect('claim_create.php?id='.$id); }
  $ext = pathinfo($_FILES['evidence']['name'], PATHINFO_EXTENSION);
  $evidence_filename = 'claim_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
  $dest = UPLOAD_DIR . $evidence_filename;
  if(!move_uploaded_file($_FILES['evidence']['tmp_name'], $dest)){ set_flash('error','Gagal menyimpan bukti.'); redirect('claim_create.php?id='.$id); }
}
$stmt = $mysqli->prepare("INSERT INTO claim_logs(item_id, admin_id, claimant_name, note, evidence_path) VALUES(?,?,?,?,?)");
$stmt->bind_param('iisss', $id, $_SESSION['admin_id'], $claimant, $note, $evidence_filename);
$ok1 = $stmt->execute();
$stmt2 = $mysqli->prepare("UPDATE items SET status='claimed', updated_at=NOW() WHERE id = ?");
$stmt2->bind_param('i', $id);
$ok2 = $stmt2->execute();
if($ok1 && $ok2){ set_flash('success','Klaim tersimpan dan status item diperbarui menjadi claimed.'); redirect('admin.php'); }
else { set_flash('error','Terjadi kesalahan saat menyimpan klaim.'); redirect('claim_create.php?id='.$id); }
