<?php
define('APP_NAME', 'Sistem Informasi Barang Hilang & Ditemukan - Gedung E FISIP UI');
define('BASE_URL', 'http://localhost/BarangHilangDitemukan/public');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'lf_gedung_e');
define('DB_USER', 'root');
define('DB_PASS', '');

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . '/../uploads/');
define('MAX_UPLOAD_BYTES', 3 * 1024 * 1024);
$ALLOWED_IMAGE_MIME = ['image/jpeg','image/png','image/gif','image/webp'];

define('MAIL_TO', '');
define('MAIL_SUBJECT', 'Laporan Baru - Lost&Found Gedung E');
define('TELEGRAM_BOT_TOKEN', '');
define('TELEGRAM_CHAT_ID', '');
