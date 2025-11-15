<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/notify.php';
session_start();

$type = $_POST['type'] ?? '';
$category = $_POST['category'] ?? '';
$title = trim($_POST['title'] ?? '');
$location = trim($_POST['location'] ?? '');
$description = trim($_POST['description'] ?? '');
$reporter_name = trim($_POST['reporter_name'] ?? '');
$reporter_contact = trim($_POST['reporter_contact'] ?? '');

if($type === '' || $category === '' || $title === '' || $location === '' || $description === '' || $reporter_name === ''){
  set_flash('error', 'Mohon lengkapi semua kolom wajib.');
  redirect('item_new.php');
}

$image_filename = null;
if(!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
  if(!is_valid_image_upload($_FILES['image'])){
    set_flash('error', 'Unggahan foto tidak valid atau melebihi batas 3MB.');
    redirect('item_new.php');
  }
  $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
  $image_filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
  $dest = UPLOAD_DIR . $image_filename;
  if(!move_uploaded_file($_FILES['image']['tmp_name'], $dest)){
    set_flash('error', 'Gagal menyimpan foto.');
    redirect('item_new.php');
  }
}

$status = 'pending';
$stmt = $mysqli->prepare("INSERT INTO items(type, title, description, category, location, reporter_name, reporter_contact, image_path, status, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,NOW(),NOW())");
$stmt->bind_param('sssssssss', $type, $title, $description, $category, $location, $reporter_name, $reporter_contact, $image_filename, $status);
if($stmt->execute()){
  $item = ['type'=>$type,'title'=>$title,'category'=>$category,'location'=>$location,'reporter_name'=>$reporter_name];
  notify_new_item($item);
  set_flash('success', 'Data barang berhasil dikirim. Tim admin akan meninjau sebelum ditayangkan ke publik.');
  redirect('index.php');
} else {
  set_flash('error', 'Terjadi kesalahan saat menyimpan data.');
  redirect('item_new.php');
}
