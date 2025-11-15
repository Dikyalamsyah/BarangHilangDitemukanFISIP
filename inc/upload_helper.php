<?php
// inc/upload_helper.php
// Helper untuk menyimpan file gambar hasil upload dengan validasi MIME yang kuat.
// Pemakaian:
//   require_once __DIR__.'/upload_helper.php';
//   $image_name = save_uploaded_image('image'); // 'image' adalah nama input file di form

/**
 * Simpan gambar upload secara aman.
 * @param string $field     Nama field input file (default: 'image')
 * @param int    $max_mb    Batas ukuran file dalam MB (default: 3)
 * @param string $dest_rel  Folder tujuan relatif terhadap root proyek (default: 'uploads')
 * @return string|null      Nama file hasil simpan, atau null jika tidak ada file diunggah
 * @throws RuntimeException Bila terjadi kesalahan validasi/penyimpanan
 */
function save_uploaded_image(string $field = 'image', int $max_mb = 3, string $dest_rel = 'uploads'): ?string {
  if (empty($_FILES[$field]['tmp_name'])) {
    return null; // tidak ada file
  }

  $tmp  = $_FILES[$field]['tmp_name'];
  $size = (int)$_FILES[$field]['size'];

  if ($size > $max_mb * 1024 * 1024) {
    throw new RuntimeException('File terlalu besar (maks ' . $max_mb . 'MB).');
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  if (!$finfo) throw new RuntimeException('Gagal membuka finfo.');
  $mime = finfo_file($finfo, $tmp);
  finfo_close($finfo);

  $map = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
  ];

  if (!isset($map[$mime])) {
    throw new RuntimeException('Tipe gambar tidak didukung.');
  }

  $ext = $map[$mime];
  $filename = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

  // target: /path/to/project/uploads
  $destDir = __DIR__ . '/../' . trim($dest_rel, '/');
  if (!is_dir($destDir)) {
    if (!mkdir($destDir, 0755, true)) {
      throw new RuntimeException('Gagal membuat folder upload.');
    }
  }

  $dest = $destDir . '/' . $filename;

  if (!move_uploaded_file($tmp, $dest)) {
    throw new RuntimeException('Gagal menyimpan file.');
  }

  return $filename;
}
