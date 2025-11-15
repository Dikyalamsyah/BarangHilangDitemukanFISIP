<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/helpers.php';
require_admin();
include __DIR__ . '/_header.php';
global $mysqli;
?>
<h2 class="h4 mb-3">Panel Admin</h2>
<p><a class="btn btn-sm btn-outline-dark me-2" href="admin_password.php">Ganti Password</a> <a class="btn btn-sm btn-outline-dark" href="logout.php">Logout</a></p>
<?php
$q = trim($_GET['q'] ?? '');
$cat = trim($_GET['cat'] ?? '');
$st  = trim($_GET['status'] ?? '');
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 20;
$offset = ($page-1) * $perPage;

$where = " WHERE status IN ('pending','published','claimed','archived') ";
$params = []; $types = "";
if($q !== ''){ $where .= " AND (title LIKE CONCAT('%',?,'%') OR location LIKE CONCAT('%',?,'%')) "; $params[]=$q; $params[]=$q; $types.="ss"; }
if($cat !== ''){ $where .= " AND category = ? "; $params[]=$cat; $types.="s"; }
if($st  !== ''){ $where .= " AND status = ? "; $params[]=$st; $types.="s"; }

// Count
$sqlCount = "SELECT COUNT(*) c FROM items " . $where;
$stmt = $mysqli->prepare($sqlCount);
if($types!==""){ $stmt->bind_param($types, ...$params); }
$stmt->execute();
$total = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
$pages = max(1, (int)ceil($total / $perPage));

// Data with safe binding (no unpacking after unpacking)
$sql = "SELECT id, type, title, category, location, image_path, status, created_at FROM items " . $where . " ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $mysqli->prepare($sql);
if($types!==""){
  $types2 = $types . 'ii';
  $params2 = $params;
  $params2[] = $perPage;
  $params2[] = $offset;
  $bind = [];
  $bind[] = & $types2;
  foreach($params2 as $k => $v){ $bind[] = & $params2[$k]; }
  call_user_func_array([$stmt, 'bind_param'], $bind);
} else {
  $stmt->bind_param('ii', $perPage, $offset);
}
$stmt->execute();
$res = $stmt->get_result();
?>
<form class="row g-2 mb-3" method="post">
  <div class="col-md-4"><input class="form-control" name="q" placeholder="Cari judul/lokasi" value="<?= e($q) ?>"></div>
  <div class="col-md-3">
    <select class="form-select" name="cat">
      <option value="">Semua Kategori</option>
      <?php foreach(['Gawai','Earphone','Botol/Tupperware','Kartu/ID','Lainnya'] as $c): ?>
      <option value="<?= e($c) ?>" <?= $cat===$c?'selected':'' ?>><?= e($c) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <select class="form-select" name="status">
      <option value="">Semua Status</option>
      <?php foreach(['pending','published','claimed','archived'] as $s): ?>
      <option value="<?= e($s) ?>" <?= $st===$s?'selected':'' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div>
</form>
<p><a class="btn btn-sm btn-outline-secondary" href="export_csv.php?q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>&status=<?= urlencode($st) ?>">Export CSV</a></p>
<table class="table table-sm align-middle">
  <thead>
    <tr><th>ID</th><th>Foto</th><th>Jenis</th><th>Judul</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr>
  </thead>
  <tbody>
    <?php while($row=$res->fetch_assoc()): ?>
    <tr>
      <td><?= (int)$row['id'] ?></td>
      <td><?php if($row['image_path'] && is_file(UPLOAD_DIR.$row['image_path'])): ?><img src="<?= e(uploads_url($row['image_path'])) ?>" alt="thumb" style="width:50px;height:50px;object-fit:cover;border-radius:6px;"><?php endif; ?></td>
      <td><?= e($row['type']) ?></td>
      <td><?= e($row['title']) ?></td>
      <td><?= e($row['category']) ?></td>
      <td><?= e($row['location']) ?></td>
      <td><span class="badge <?= $row['status']=='published'?'text-bg-primary':($row['status']=='pending'?'text-bg-secondary':($row['status']=='claimed'?'text-bg-success':'text-bg-dark')) ?>"><?= e($row['status']) ?></span></td>
      <td>
        <a class="btn btn-sm btn-outline-primary" href="admin_update.php?id=<?= (int)$row['id'] ?>&to=published">Tayang</a>
        <a class="btn btn-sm btn-outline-success" href="claim_create.php?id=<?= (int)$row['id'] ?>">Klaim</a>
        <a class="btn btn-sm btn-outline-danger" href="admin_update.php?id=<?= (int)$row['id'] ?>&to=archived">Arsip</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<nav aria-label="Page nav"><ul class="pagination">
<?php for($i=1;$i<=$pages;$i++): ?>
<li class="page-item <?= $i==$page?'active':'' ?>"><a class="page-link" href="?q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>&status=<?= urlencode($st) ?>&page=<?= $i ?>"><?= $i ?></a></li>
<?php endfor; ?>
</ul></nav>
<?php include __DIR__ . '/_footer.php'; ?>
