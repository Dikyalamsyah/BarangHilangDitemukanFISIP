// Back to Top — tampil HANYA saat footer terlihat; selalu di atas footer
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('backToTop');
  if (!btn) return;

  const footer = document.querySelector('footer');

  const setOffset = () => {
    let offset = 24;
    if (footer) {
      const r = footer.getBoundingClientRect();
      const overlap = Math.max(0, window.innerHeight - r.top);
      if (overlap > 0) offset = overlap + 16;  // naik di atas footer + margin
    }
    btn.style.setProperty('--bt-offset', `${offset}px`);
  };

  const show = () => { btn.classList.add('show'); setOffset(); };
  const hide = () => { btn.classList.remove('show'); };

  if ('IntersectionObserver' in window && footer) {
    const io = new IntersectionObserver(([entry]) => {
      entry.isIntersecting ? show() : hide();
    }, { threshold: 0.01 });
    io.observe(footer);
    window.addEventListener('resize', setOffset);
  } else {
    // Fallback: tampil jika footer masuk viewport
    const update = () => {
      if (!footer) return hide();
      const r = footer.getBoundingClientRect();
      (r.top <= window.innerHeight) ? show() : hide();
    };
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    window.addEventListener('load', update);
    update();
  }

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
});

// Sub-navbar: saat sentinel keluar viewport => subnav menempel & navbar utama menciut
document.addEventListener('DOMContentLoaded', () => {
  const sub = document.querySelector('.sticky-subnav');
  const sent = document.getElementById('subnav-sentinel');
  if (!sub || !sent) return;

  const root = document.documentElement;
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(([e]) => {
      const stuck = !e.isIntersecting;
      sub.classList.toggle('is-stuck', stuck);
      root.classList.toggle('nav-collapsed', stuck);
    });
    io.observe(sent);
  }
});

// ===== THEME TOGGLER (v2, single) =====
document.addEventListener('DOMContentLoaded', () => {
  const html = document.documentElement;
  const btn  = document.getElementById('themeToggle');
  if (!btn) return;

  // Preferensi awal: localStorage -> system -> light
  const saved = localStorage.getItem('theme'); // 'dark' | 'light' | null
  const systemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  const initial = saved || (systemDark ? 'dark' : 'light');
  applyTheme(initial);

  // Klik toggle
  btn.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    localStorage.setItem('theme', next);
  });

  // Ikuti perubahan sistem hanya jika user belum memilih manual
  if (!saved && window.matchMedia) {
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    const handler = (e) => {
      if (!localStorage.getItem('theme')) {
        applyTheme(e.matches ? 'dark' : 'light');
      }
    };
    if (mq.addEventListener) mq.addEventListener('change', handler);
    else mq.addListener(handler); // Safari lama
  }

  function applyTheme(mode){
  const html = document.documentElement;
  const btn  = document.getElementById('themeToggle');

  if (mode === 'dark') html.setAttribute('data-theme','dark');
  else html.removeAttribute('data-theme');

  const isDark = (mode === 'dark');
  btn.setAttribute('aria-pressed', String(isDark));
  btn.textContent = isDark ? '🌙' : '☀️';   // tidak toggle kelas apa pun
}

});

// ===== Auto-fill Lokasi untuk item_new.php =====
document.addEventListener('DOMContentLoaded', () => {
  const lantai = document.getElementById('preset_lantai');
  const ruang  = document.getElementById('preset_ruang');
  const loc    = document.getElementById('location_input');
  if (!lantai || !ruang || !loc) return; // hanya aktif di halaman form

  // Tentukan prefix "Ruang " / "Room " dari <html lang="...">
  const htmlLang = (document.documentElement.getAttribute('lang') || 'id').toLowerCase();
  const ROOM_PREFIX = htmlLang.startsWith('en') ? 'Room ' : 'Ruang ';

  const update = () => {
    const fl = (lantai.value || '').trim();
    const rm = (ruang.value  || '').trim();
    loc.value = [fl, rm ? (ROOM_PREFIX + rm) : ''].filter(Boolean).join(', ');
  };

  // Pasang listener lengkap supaya pasti kepicu
  ['change','input','click','keyup','blur'].forEach(ev => lantai.addEventListener(ev, update));
  ['input','change','keyup','blur'].forEach(ev => ruang.addEventListener(ev, update));

  // Inisialisasi awal & saat kembali dari history (bfcache)
  update();
  window.addEventListener('pageshow', update);
});
