<?php
// inc/i18n.php
if (session_status() === PHP_SESSION_NONE) session_start();

const LANG_DEFAULT = 'id';
const LANGS = ['id','en'];

// Terima ?lang=id|en → simpan di session + cookie (1 tahun)
if (isset($_GET['lang']) && in_array($_GET['lang'], LANGS, true)) {
  $_SESSION['lang'] = $_GET['lang'];
  setcookie('lang', $_GET['lang'], time()+31536000, '/');
}

// Inisialisasi dari cookie bila session kosong
if (empty($_SESSION['lang'])) {
  if (!empty($_COOKIE['lang']) && in_array($_COOKIE['lang'], LANGS, true)) {
    $_SESSION['lang'] = $_COOKIE['lang'];
  } else {
    $_SESSION['lang'] = LANG_DEFAULT;
  }
}

function lang(): string { return $_SESSION['lang'] ?? LANG_DEFAULT; }

/**
 * t('key', ['{var}' => 'value'])
 * Kembalikan string terjemahan; jika tidak ada → key-nya sendiri.
 */
function t(string $key, array $vars = []): string {
  static $L = null;
  if ($L === null) {
    $L = [
      'id' => [
        'nav.home'      => 'Beranda',
        'nav.report'    => 'Laporkan Barang',
        'nav.admin'     => 'Admin',
        'nav.terms'     => 'Ketentuan',

        'search.button'      => 'Cari',
        'search.placeholder' => 'Cari judul/lokasi/kategori',

        'title'    => 'Barang Hilang & Ditemukan - Gedung E FISIP UI',
        'subtitle' => 'Telusuri daftar barang terbaru. Hanya item berstatus <em>ditayangkan</em> oleh <em>admin</em> yang terlihat publik.',

        'cat.all'     => 'Semua',
        'cat.gadgets' => 'Gawai',
        'cat.earphone'=> 'Earphone',
        'cat.bottle'  => 'Botol/Tupperware',
        'cat.card'    => 'Kartu/ID',

        'badge.found' => 'Ditemukan',
        'badge.lost'  => 'Hilang',
        'admin.category' => 'Kategori',
        'admin.location' => 'Lokasi',

        'btn.view'    => 'Lihat',
        'meta.posted' => 'Diposting: {dt}',

        'page.status' => 'Halaman {page} / {pages} — Total {total} item',
        'page.prev'   => 'Sebelumnya',
        'page.next'   => 'Berikutnya',
        'login.title'        => 'Login Admin',
'login.only'         => 'Akses ini hanya untuk admin. Semua aktivitas diawasi.',
'login.username'     => 'Username',
'login.password'     => 'Password',
'login.submit'       => 'Masuk',
'login.back'         => 'Kembali ke Beranda',
'login.error'        => 'Login gagal. Periksa kembali kredensial Anda.',

'admin.title'        => 'Dasbor Admin',
'admin.items'        => 'Daftar Item',
'admin.add'          => 'Tambah Item',
'admin.edit'         => 'Ubah',
'admin.delete'       => 'Hapus',
'admin.publish'      => 'Tayang',
'admin.unpublish'    => 'Sembunyikan',
'admin.status'       => 'Status',
'admin.type'         => 'Jenis',
'admin.category'     => 'Kategori',
'admin.location'     => 'Lokasi',
'admin.created'      => 'Dibuat',
'admin.actions'      => 'Aksi',
'admin.logout'       => 'Keluar',
'admin.confirmDel'   => 'Yakin ingin menghapus item ini?',

'form.title'         => 'Laporkan Barang',
'form.subtitle'      => 'Lengkapi data di bawah untuk melaporkan barang hilang/temuan.',
'form.type'          => 'Jenis Laporan',
'form.type.found'    => 'Ditemukan',
'form.type.lost'     => 'Hilang',
'form.category'      => 'Kategori',
'form.location'      => 'Lokasi',
'form.title.label'   => 'Judul',
'form.desc'          => 'Deskripsi',
'form.photo'         => 'Foto',
'form.submit'        => 'Kirim',
'form.cancel'        => 'Batal',
'form.required'      => 'Wajib diisi.',
'form.min8'          => 'Minimal 8 karakter.',
'save.success'       => 'Berhasil disimpan.',
'save.failed'        => 'Gagal menyimpan.',
'login.locked'    => 'Akun dikunci sementara (± {min} menit) karena terlalu banyak percobaan.',
'login.remaining' => 'Username atau password salah. Sisa percobaan: {n}.',
'cat.other'              => 'Lainnya',
'cat.gadgets_note'       => 'HP, laptop, charger, dsb.',
'form.preset.location'   => 'Lokasi Preset (Gedung E)',
'form.preset.floor'      => 'Pilih Lantai',
'form.preset.room_placeholder' => 'Ruang (mis. 204)',
'form.location'          => 'Lokasi',
'form.location.auto'     => 'Lokasi otomatis terisi dari preset (boleh diedit)',
'reporter.name'          => 'Nama Pelapor',
'reporter.contact'       => 'Kontak',
'form.photo_hint'        => 'jpg/png/webp, maks 3MB',
'room.prefix'            => 'Ruang ',
'privacy.title' => 'Kebijakan Privasi & Ketentuan',

'privacy.casual.intro' => 'Halo warga Gedung E! Website ini hadir buat bantu kalian nemuin barang yang nyasar atau ngasih info kalau nemu barang misterius di sekitar Gedung E. Anggap aja kayak Lost & Found tapi versi digitalnya.',

'privacy.casual.priv.h'     => 'Privasi Itu Penting',
'privacy.casual.priv.item1' => 'Nama & kontak yang kamu masukin cuma dipake buat urusan klaim barang, bukan buat nyepam mantan.',
'privacy.casual.priv.item2' => 'Kami nggak jual data ke pihak lain, jadi tenang aja dompet digitalmu aman.',
'privacy.casual.priv.item3' => 'Foto boleh upload, tapi plis jangan unggah selfie kece pas lagi nongkrong di kantin—fokus ke barangnya, bukan ke gaya hidup.',

'privacy.casual.rules.h'     => 'Ketentuan Main',
'privacy.casual.rules.item1' => 'Laporin cuma barang asli yang beneran hilang/ketemu. Barang imajiner kayak “kesetiaan” nggak bisa diproses.',
'privacy.casual.rules.item2' => 'Konten aneh, nggak pantas, atau nggak ada hubungannya bakal langsung di-end game sama admin.',
'privacy.casual.rules.item3' => 'Admin punya kuasa sakti buat hapus/edit laporan kalau ngaco. Jadi jangan bandel, nanti kena “snap”.',

'privacy.casual.disclaimer.h' => 'Disclaimer Ala Kadarnya',
'privacy.casual.disclaimer.p' => 'Kami cuma nyediain papan pengumuman online. Kalau ada drama, kehilangan, atau salah paham—ya jangan salahin sistem ini. Anggap aja kami kayak “papan mading digital”: nempel info, selesai. Barang balik = syukur, nggak balik = tetap semangat kuliah!',

'privacy.casual.signoff' => 'Salam hangat,<br>Diky dan Noval',




      ],
      'en' => [
        'nav.home'      => 'Home',
        'nav.report'    => 'Report Item',
        'nav.admin'     => 'Admin',
        'nav.terms'     => 'Terms',

        'search.button'      => 'Search',
        'search.placeholder' => 'Search title/location/category',

        'title'    => 'Lost & Found – FISIP UI Building E',
        'subtitle' => 'Browse latest items. Only items with status <em>published</em> by <em>admin</em> are visible to the public.',

        'cat.all'     => 'All',
        'cat.gadgets' => 'Gadgets',
        'cat.earphone'=> 'Earphone',
        'cat.bottle'  => 'Bottle/Tupperware',
        'cat.card'    => 'Card/ID',

        'badge.found' => 'Found',
        'badge.lost'  => 'Lost',
        'admin.category' => 'Category',
        'admin.location' => 'Location',

        'btn.view'    => 'View',
        'meta.posted' => 'Posted: {dt}',

        'page.status' => 'Page {page} / {pages} — Total {total} items',
        'page.prev'   => 'Previous',
        'page.next'   => 'Next',

        'login.title'        => 'Admin Login',
'login.only'         => 'This area is for admins only. All activities are monitored.',
'login.username'     => 'Username',
'login.password'     => 'Password',
'login.submit'       => 'Sign in',
'login.back'         => 'Back to Home',
'login.error'        => 'Login failed. Please check your credentials.',

'admin.title'        => 'Admin Dashboard',
'admin.items'        => 'Items',
'admin.add'          => 'Add Item',
'admin.edit'         => 'Edit',
'admin.delete'       => 'Delete',
'admin.publish'      => 'Publish',
'admin.unpublish'    => 'Unpublish',
'admin.status'       => 'Status',
'admin.type'         => 'Type',
'admin.category'     => 'Category',
'admin.location'     => 'Location',
'admin.created'      => 'Created',
'admin.actions'      => 'Actions',
'admin.logout'       => 'Logout',
'admin.confirmDel'   => 'Are you sure you want to delete this item?',

'form.title'         => 'Report Item',
'form.subtitle'      => 'Fill the form below to report a lost/found item.',
'form.type'          => 'Report Type',
'form.type.found'    => 'Found',
'form.type.lost'     => 'Lost',
'form.category'      => 'Category',
'form.location'      => 'Location',
'form.title.label'   => 'Title',
'form.desc'          => 'Description',
'form.photo'         => 'Photo',
'form.submit'        => 'Submit',
'form.cancel'        => 'Cancel',
'form.required'      => 'Required.',
'form.min8'          => 'At least 8 characters.',
'save.success'       => 'Saved successfully.',
'save.failed'        => 'Failed to save.',
'login.locked'    => 'Account temporarily locked (about {min} minutes) due to too many attempts.',
'login.remaining' => 'Invalid username or password. Remaining attempts: {n}.',
'cat.other'              => 'Other',
'cat.gadgets_note'       => 'phone, laptop, charger, etc.',
'form.preset.location'   => 'Preset Location (Building E)',
'form.preset.floor'      => 'Select Floor',
'form.preset.room_placeholder' => 'Room (e.g., 204)',
'form.location'          => 'Location',
'form.location.auto'     => 'Location auto-filled from preset (editable)',
'reporter.name'          => 'Reporter Name',
'reporter.contact'       => 'Contact',
'form.photo_hint'        => 'jpg/png/webp, max 3MB',
'room.prefix'            => 'Room ',
'privacy.title' => 'Privacy Policy & Terms',

'privacy.casual.intro' => 'Hello, Building E folks! This site helps you find misplaced items or report mysterious finds around Building E. Think of it as a digital Lost & Found.',

'privacy.casual.priv.h'     => 'Privacy Matters',
'privacy.casual.priv.item1' => 'Your name and contact are used solely for claim purposes—not for spam.',
'privacy.casual.priv.item2' => 'We do not sell your data to third parties; your e-wallet can chill.',
'privacy.casual.priv.item3' => 'You may upload a photo, but please focus on the item—not your café selfie.',

'privacy.casual.rules.h'     => 'House Rules',
'privacy.casual.rules.item1' => 'Report real items only. Imaginary things like “loyalty” can’t be processed.',
'privacy.casual.rules.item2' => 'Inappropriate or irrelevant content will be removed by admins.',
'privacy.casual.rules.item3' => 'Admins may delete/edit reports that go off the rails—play nice.',

'privacy.casual.disclaimer.h' => 'Lightweight Disclaimer',
'privacy.casual.disclaimer.p' => 'We simply provide a digital bulletin board. For any drama or misunderstandings, don’t blame this system. Think of us as a campus noticeboard: we post, that’s it. If your item returns—great. If not—keep studying hard!',

'privacy.casual.signoff' => 'Warm regards,<br>Diky and Noval',



      ],
    ];
  }
  $text = $L[lang()][$key] ?? $key;
  if ($vars) $text = strtr($text, $vars);
  return $text;
}

/** Buat URL saat ganti bahasa, mempertahankan query lain */
function url_lang(string $lang): string {
  $q = $_GET; $q['lang'] = $lang;
  return '?'.http_build_query($q);
}
