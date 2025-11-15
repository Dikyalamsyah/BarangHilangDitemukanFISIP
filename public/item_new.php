<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/i18n.php';
include __DIR__ . '/_header.php';
?>
<h2 class="h4 mb-1"><?= e(t('form.title')) ?></h2>
<p class="text-muted mb-3"><?= e(t('form.subtitle')) ?></p>

<form method="post" action="item_save.php" enctype="multipart/form-data" class="row g-3">
  <!-- Jenis -->
  <div class="col-md-6">
    <label class="form-label"><?= e(t('form.type')) ?></label>
    <select class="form-select" name="type" required>
      <option value="found"><?= e(t('form.type.found')) ?></option>
      <option value="lost"><?= e(t('form.type.lost')) ?></option>
    </select>
  </div>

  <!-- Kategori -->
  <div class="col-md-6">
    <label class="form-label"><?= e(t('form.category')) ?></label>
    <select class="form-select" name="category" required>
      <option value="Gawai"><?= e(t('cat.gadgets')) ?> (<?= e(t('cat.gadgets_note')) ?>)</option>
      <option value="Earphone"><?= e(t('cat.earphone')) ?></option>
      <option value="Botol/Tupperware"><?= e(t('cat.bottle')) ?></option>
      <option value="Kartu/ID"><?= e(t('cat.card')) ?></option>
      <option value="Lainnya"><?= e(t('cat.other')) ?></option>
    </select>
  </div>

  <!-- Judul -->
  <div class="col-md-6">
    <label class="form-label"><?= e(t('form.title.label')) ?></label>
    <input type="text" class="form-control" name="title" maxlength="120" required>
  </div>

  <!-- Preset lokasi -->
  <div class="col-md-6">
    <label class="form-label"><?= e(t('form.preset.location')) ?></label>
    <div class="d-flex gap-2">
      <?php $ROOM_PREFIX = t('room.prefix'); ?>
      <select class="form-select" id="preset_lantai"
        onchange="(function(el){
          var f=el.form, p='<?= e($ROOM_PREFIX) ?>';
          var l=(el.value||'').trim();
          var r=(document.getElementById('preset_ruang').value||'').trim();
          f.location.value=[l, r? (p+r):''].filter(Boolean).join(', ');
        })(this)">
        <option value=""><?= e(t('form.preset.floor')) ?></option>
        <option value="Lantai 1">Lantai 1</option>
        <option value="Lantai 2">Lantai 2</option>
        <option value="Lantai 3">Lantai 3</option>
      </select>

      <input type="text" class="form-control" id="preset_ruang"
        placeholder="<?= e(t('form.preset.room_placeholder')) ?>"
        oninput="(function(el){
          var f=el.form, p='<?= e($ROOM_PREFIX) ?>';
          var l=(document.getElementById('preset_lantai').value||'').trim();
          var r=(el.value||'').trim();
          f.location.value=[l, r? (p+r):''].filter(Boolean).join(', ');
        })(this)">
    </div>
  </div>

  <!-- Lokasi (hasil preset, bisa diubah) -->
  <div class="col-md-6">
    <label class="form-label"><?= e(t('form.location')) ?></label>
    <input type="text" class="form-control" name="location" id="location_input" maxlength="120"
           placeholder="<?= e(t('form.location.auto')) ?>" required>
  </div>

  <!-- Deskripsi -->
  <div class="col-12">
    <label class="form-label"><?= e(t('form.desc')) ?></label>
    <textarea class="form-control" name="description" rows="4" maxlength="1000" required></textarea>
  </div>

  <!-- Pelapor -->
  <div class="col-md-6">
    <label class="form-label"><?= e(t('reporter.name')) ?></label>
    <input type="text" class="form-control" name="reporter_name" maxlength="80" required>
  </div>
  <div class="col-md-6">
    <label class="form-label"><?= e(t('reporter.contact')) ?></label>
    <input type="text" class="form-control" name="reporter_contact" maxlength="120">
  </div>

  <!-- Foto -->
  <div class="col-md-6">
    <label class="form-label"><?= e(t('form.photo')) ?> <span class="text-muted">— <?= e(t('form.photo_hint')) ?></span></label>
    <input type="file" class="form-control" name="image">
  </div>

  <!-- Aksi -->
  <div class="col-12">
    <button class="btn btn-success" type="submit"><?= e(t('form.submit')) ?></button>
    <a href="index.php" class="btn btn-outline-secondary"><?= e(t('login.back')) ?></a>
  </div>
</form>

<!-- Inisialisasi nilai lokasi saat halaman dimuat -->
<script>
(function(){
  var p = <?= json_encode($ROOM_PREFIX) ?>;
  var l = document.getElementById('preset_lantai');
  var r = document.getElementById('preset_ruang');
  var o = document.getElementById('location_input');
  if(!l||!r||!o) return;
  var lv=(l.value||'').trim(), rv=(r.value||'').trim();
  o.value = [lv, rv? (p+rv):''].filter(Boolean).join(', ');
})();
</script>

<?php include __DIR__ . '/_footer.php'; ?>
