<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
include __DIR__ . '/_header.php';

/** =======================
 *  Filter & Pagination
 *  ======================= */
$keyword  = isset($_GET['q'])   ? trim($_GET['q'])   : '';
$category = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$status   = 'published';

$page = max(1, (int)($_GET['page'] ?? 1));
$per  = 12;

/** WHERE dinamis */
$where   = ["status = ?"];
$params  = [$status];
$types   = "s";

if ($keyword !== '') {
  $where[]  = "(title LIKE CONCAT('%',?,'%') OR location LIKE CONCAT('%',?,'%') OR category LIKE CONCAT('%',?,'%'))";
  $params[] = $keyword; $params[] = $keyword; $params[] = $keyword;
  $types   .= "sss";
}
if ($category !== '') {
  $where[]  = "category = ?";
  $params[] = $category;
  $types   .= "s";
}
$whereSql = implode(' AND ', $where);

/** Hitung total */
$sqlCount = "SELECT COUNT(*) AS t FROM items WHERE $whereSql";
$stmtc = $mysqli->prepare($sqlCount);
$stmtc->bind_param($types, ...$params);
$stmtc->execute();
$total = (int)($stmtc->get_result()->fetch_assoc()['t'] ?? 0);

$pages = max(1, (int)ceil($total / $per));
$page  = min($page, $pages);
$off   = ($page - 1) * $per;

/** Ambil data halaman */
$sqlData = "SELECT id, type, title, category, location, image_path, status, created_at
            FROM items
            WHERE $whereSql
            ORDER BY id DESC
            LIMIT ? OFFSET ?";
$paramsData = $params;
$typesData  = $types . "ii";
$paramsData[] = $per; $paramsData[] = $off;

$stmt = $mysqli->prepare($sqlData);
$stmt->bind_param($typesData, ...$paramsData);
$stmt->execute();
$res = $stmt->get_result();

/** Helpers URL */
function page_url(int $n): string {
  $q = $_GET; $q['page'] = $n;
  return '?'.http_build_query($q);
}
/* Pertahankan parameter lain saat ganti kategori, dan reset page=1 */
function cat_url(?string $cat): string {
  $q = $_GET;
  if ($cat === null || $cat === '') { unset($q['cat']); }
  else { $q['cat'] = $cat; }
  $q['page'] = 1; // mulai dari halaman 1 tiap ganti kategori
  return '?'.http_build_query($q);
}
?>

<!-- Sentinel untuk mendeteksi saat subnav mulai menempel -->
<div id="subnav-sentinel"></div>

<!-- ===== SUB NAVBAR lengket (judul + cari + chip) ===== -->
<div class="sticky-subnav">
  <div class="row mb-3 align-items-center g-2 py-2">
    <div class="col-12 col-md-8">
      <h1 class="h4 mb-1"><?= e(t('title')) ?></h1>
      <p class="text-muted mb-0"><?= t('subtitle') /* mengandung <em> */ ?></p>
    </div>
    <div class="col-12 col-md-4">
      <form class="d-flex" method="get">
        <input name="q" class="form-control me-2" type="search"
               placeholder="<?= e(t('search.placeholder')) ?>" value="<?= e($keyword) ?>">
        <button class="btn btn-outline-primary" type="submit"><?= e(t('search.button')) ?></button>
      </form>
    </div>
  </div>

  <div class="mb-2">
    <a href="<?= cat_url('') ?>"                 class="btn btn-sm btn-outline-secondary cat-btn"><?= e(t('cat.all')) ?></a>
    <a href="<?= cat_url('Gawai') ?>"            class="btn btn-sm btn-outline-secondary cat-btn"><?= e(t('cat.gadgets')) ?></a>
    <a href="<?= cat_url('Earphone') ?>"         class="btn btn-sm btn-outline-secondary cat-btn"><?= e(t('cat.earphone')) ?></a>
    <a href="<?= cat_url('Botol/Tupperware') ?>" class="btn btn-sm btn-outline-secondary cat-btn"><?= e(t('cat.bottle')) ?></a>
    <a href="<?= cat_url('Kartu/ID') ?>"         class="btn btn-sm btn-outline-secondary cat-btn"><?= e(t('cat.card')) ?></a>
  </div>
</div>
<!-- ===== /SUB NAVBAR ===== -->

<?php if ($total === 0): ?>
  <div class="alert alert-info mt-3"><?= e(t('empty.no_items')) ?></div>
<?php endif; ?>

<div class="row g-3 mt-1">
<?php while($row = $res->fetch_assoc()): ?>
  <div class="col-12 col-md-4">
    <div class="card h-100">
      <?php if(!empty($row['image_path']) && is_file(UPLOAD_DIR.$row['image_path'])): ?>
        <img class="card-img-top" src="<?= e(uploads_url($row['image_path'])) ?>" alt="Foto">
      <?php endif; ?>
      <div class="card-body">
        <span class="badge <?= $row['type']=='found' ? 'text-bg-success' : 'text-bg-warning' ?>">
          <?= e($row['type']=='found' ? t('badge.found') : t('badge.lost')) ?>
        </span>
        <h5 class="card-title mt-2"><?= e($row['title']) ?></h5>
        <p class="card-text small mb-1">
          <strong><?= e(t('admin.category')) ?>:</strong> <?= e($row['category']) ?>
          | <strong><?= e(t('admin.location')) ?>:</strong> <?= e($row['location']) ?>
        </p>
        <a class="btn btn-sm btn-primary" href="item_view.php?id=<?= (int)$row['id'] ?>"><?= e(t('btn.view')) ?></a>
      </div>
      <div class="card-footer text-muted small">
        <?= e(strtr(t('meta.posted'), ['{dt}' => $row['created_at']])) ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<?php
$prev = max(1, $page - 1);
$next = min($pages, $page + 1);
?>
<div class="text-center my-4">
  <div class="mb-2">
    <?= e(strtr(t('page.status'), ['{page}'=>$page,'{pages}'=>$pages,'{total}'=>$total])) ?>
  </div>
  <nav aria-label="Navigasi halaman">
    <ul class="pagination justify-content-center mb-0">
      <li class="page-item <?= $page<=1 ? 'disabled' : '' ?>">
        <a class="page-link" href="<?= page_url($prev) ?>"><?= e(t('page.prev')) ?></a>
      </li>
      <li class="page-item <?= $page>=$pages ? 'disabled' : '' ?>">
        <a class="page-link" href="<?= page_url($next) ?>"><?= e(t('page.next')) ?></a>
      </li>
    </ul>
  </nav>
</div>

<?php include __DIR__ . '/_footer.php'; ?>
