<?php require_once dirname(__DIR__).'/inc/session_boot.php'; ?>
<?php require_once __DIR__ . '/../inc/config.php'; ?>
<?php require_once __DIR__ . '/../inc/helpers.php'; ?>
<?php require_once __DIR__ . '/../inc/i18n.php'; ?>  <!-- i18n -->

<!doctype html>
<html lang="<?= e(lang()) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NAME) ?></title>
  <meta name="theme-color" content="#fd7e14">
  <meta name="color-scheme" content="light dark">

  <!-- Vendor CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Style situs lama (di luar /public) -->
  <link href="../assets/css/styles.css" rel="stylesheet">

  <!-- Style global & komponen (di dalam /public) -->
  <link rel="stylesheet" href="assets/app.css?v=10">

  <!-- Script global (back-to-top, subnav sticky, theme toggle, dll) -->
  <script src="assets/app.js?v=10" defer></script>
</head>
<body class="d-flex flex-column min-vh-100">
  <!-- NAVBAR UTAMA -->
  <nav id="mainNav" class="navbar navbar-expand-lg navbar-dark bg-orange sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">Gedung E FISIP UI</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars"
              aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbars">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="index.php"><?= e(t('nav.home')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="item_new.php"><?= e(t('nav.report')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="login.php"><?= e(t('nav.admin')) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="privacy.php"><?= e(t('nav.terms')) ?></a></li>
        </ul>

        <!-- Toggle Tema + Bahasa -->
        <div class="d-flex align-items-center gap-2">
          <button id="themeToggle" class="theme-btn" type="button" aria-pressed="false" aria-label="Toggle tema">🌙</button>

          <!-- Switch bahasa: simpan preferensi di session/cookie via i18n.php -->
          <a class="lang-btn" href="?lang=id" title="Bahasa Indonesia" aria-label="Bahasa Indonesia">
    <!-- 🇮🇩 SVG inline -->
            <svg width="20" height="14" viewBox="0 0 4 3" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect width="4" height="3" fill="#ffffff"/>
            <rect width="4" height="1.5" fill="#E70011"/>
           </svg>
          </a>
          <a class="lang-btn" href="?lang=en" title="English" aria-label="English">
    <!-- 🇬🇧 SVG inline -->
    <svg width="20" height="14" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <rect width="60" height="30" fill="#012169"/>
      <path d="M0,0 L60,30 M60,0 L0,30" stroke="#ffffff" stroke-width="6"/>
      <path d="M0,0 L60,30 M60,0 L0,30" stroke="#C8102E" stroke-width="4"/>
      <path d="M30,0 v30 M0,15 h60" stroke="#ffffff" stroke-width="10"/>
      <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/>
    </svg>
  </a>
        </div>
      </div>
    </div>
  </nav>

  <main class="container flex-grow-1 pb-5">
    <?php if(!empty($_SESSION['flash']['success'])): ?>
      <div class="alert alert-success"><?= e($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['flash']['error'])): ?>
      <div class="alert alert-danger"><?= e($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
    <?php endif; ?>
