<?php
function e($str){ return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
function flash($key){
  if(!empty($_SESSION['flash'][$key])){ $msg=$_SESSION['flash'][$key]; unset($_SESSION['flash'][$key]); return $msg; }
  return null;
}
function set_flash($key,$msg){ $_SESSION['flash'][$key]=$msg; }
function redirect($url){ header('Location: ' . $url); exit; }
function is_valid_image_upload($file){
  if($file['error'] !== UPLOAD_ERR_OK) return false;
  if($file['size'] <= 0 || $file['size'] > MAX_UPLOAD_BYTES) return false;
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);
  $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
  return in_array($mime, $allowed, true);
}


function uploads_url($file){
  // Hilangkan '/public' dari BASE_URL lalu tambahkan '/uploads/'
  $root = preg_replace('#/public$#', '', BASE_URL);
  return rtrim($root, '/') . '/uploads/' . ltrim($file, '/');
}
