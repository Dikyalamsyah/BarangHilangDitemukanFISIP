<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/i18n.php';
include __DIR__ . '/_header.php';
?>
<h1 class="h4 mb-3"><?= e(t('privacy.title')) ?></h1>

<p><?= e(t('privacy.casual.intro')) ?></p>

<h2 class="h5 mt-4"><?= e(t('privacy.casual.priv.h')) ?></h2>
<ul>
  <li><?= e(t('privacy.casual.priv.item1')) ?></li>
  <li><?= e(t('privacy.casual.priv.item2')) ?></li>
  <li><?= e(t('privacy.casual.priv.item3')) ?></li>
</ul>

<h2 class="h5 mt-4"><?= e(t('privacy.casual.rules.h')) ?></h2>
<ul>
  <li><?= e(t('privacy.casual.rules.item1')) ?></li>
  <li><?= e(t('privacy.casual.rules.item2')) ?></li>
  <li><?= e(t('privacy.casual.rules.item3')) ?></li>
</ul>

<h2 class="h5 mt-4"><?= e(t('privacy.casual.disclaimer.h')) ?></h2>
<p><?= e(t('privacy.casual.disclaimer.p')) ?></p>

<p class="mt-4"><?= t('privacy.casual.signoff') /* mengandung <br> */ ?></p>

<?php include __DIR__ . '/_footer.php'; ?>
