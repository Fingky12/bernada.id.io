<?php
require_once '../config/koneksi.php';
require_once '../config/ambil_data.php';

$galeri_fotos = $galeri_fotos ?? [];
$ada_galeri = $ada_galeri ?? false;
$tgl_countdown = $tgl_countdown ?? (($tgl_raw ?? date('Y-m-d')) . 'T' . ($ma ?? '09:00') . ':00+07:00');
$pria = $pria ?? 'Pengantin Pria';
$wanita = $wanita ?? 'Pengantin Wanita';
$tamu = $tamu ?? 'Tamu Undangan';
$tgl_full = $tgl_full ?? date('d F Y', strtotime($tgl_raw ?? date('Y-m-d')));
$ayah_pria = $ayah_pria ?? '';
$ibu_pria = $ibu_pria ?? '';
$ayah_wanita = $ayah_wanita ?? '';
$ibu_wanita = $ibu_wanita ?? '';
$ma = $ma ?? '09:00';
$sa = $sa ?? '11:00';
$mr = $mr ?? '12:00';
$sr = $sr ?? '14:00';
$lokasi = $lokasi ?? 'Alamat acara';
$maps = $maps ?? 'https://maps.google.com';

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Undangan Pernikahan <?= $pria ?> & <?= $wanita ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <style id="galeri-css-snippet">
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --n1: #0a1628;
      --n2: #1a2e4a;
      --n3: #2d4a6e;
      --gold: #c9a227;
      --gold-l: #f5e9c8;
      --cream: #F8F5EF;
      --white: #fff;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--cream);
      color: var(--n1);
      overflow-x: hidden;
    }

    /* COVER */
    .cover {
      min-height: 100vh;
      background: linear-gradient(160deg, var(--n1) 0%, var(--n2) 60%, var(--n3) 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3rem 1.5rem;
      position: relative;
      overflow: hidden;
    }

    .cover::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse at 20% 20%, rgba(201, 162, 39, .08) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 80%, rgba(201, 162, 39, .06) 0%, transparent 50%);
    }

    .cover-mono {
      font-family: 'Cinzel', serif;
      font-size: clamp(4rem, 15vw, 9rem);
      color: rgba(255, 255, 255, .04);
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      white-space: nowrap;
      pointer-events: none;
      letter-spacing: .3em;
    }

    .cover-tag {
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: .3em;
      color: var(--gold);
      margin-bottom: 2rem;
      text-transform: uppercase;
      position: relative;
    }

    .cover-name {
      font-family: 'Cinzel', serif;
      font-size: clamp(2.5rem, 8vw, 5.5rem);
      color: var(--white);
      line-height: 1.1;
      margin-bottom: .5rem;
      position: relative;
    }

    .cover-name .amp {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: .55em;
      color: var(--gold);
      display: block;
    }

    .cover-line {
      width: 60px;
      height: 1px;
      background: var(--gold);
      margin: .75rem auto 1.25rem;
      opacity: .6;
    }

    .cover-date {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(.9rem, 2.5vw, 1.2rem);
      color: rgba(255, 255, 255, .6);
      letter-spacing: .08em;
      margin-bottom: 2.5rem;
      position: relative;
    }

    .cover-to {
      font-size: 12px;
      color: rgba(255, 255, 255, .4);
      margin-bottom: .4rem;
      letter-spacing: .1em;
    }

    .cover-guest {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: clamp(1.2rem, 3.5vw, 1.7rem);
      color: var(--white);
      margin-bottom: 2.5rem;
    }

    .btn-open {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 14px 34px;
      background: transparent;
      color: var(--gold);
      border: 1.5px solid var(--gold);
      border-radius: 3px;
      font-family: 'Cinzel', serif;
      font-size: 12px;
      letter-spacing: .15em;
      text-transform: uppercase;
      cursor: pointer;
      transition: all .3s;
      position: relative;
    }

    .btn-open:hover {
      background: var(--gold);
      color: var(--n1);
    }

    /* CONTENT */
    .content {
      display: none;
    }

    .content.show {
      display: block;
    }

    section {
      padding: 5rem 1.5rem;
    }

    .sec-inner {
      max-width: 680px;
      margin: 0 auto;
      text-align: center;
    }

    .cinzel-title {
      font-family: 'Cinzel', serif;
      font-size: clamp(1.5rem, 4vw, 2.5rem);
      letter-spacing: .1em;
      color: var(--n2);
      margin-bottom: .5rem;
    }

    .gold-line {
      width: 50px;
      height: 2px;
      background: var(--gold);
      margin: .75rem auto 1.5rem;
    }

    .sec-sub {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 1.1rem;
      color: #888;
      margin-bottom: 2rem;
    }

    /* COUPLE */
    .couple-section {
      background: var(--white);
    }

    .couple-grid {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 2rem;
      align-items: center;
      margin: 2.5rem 0;
    }

    .couple-card h3 {
      font-family: 'Cinzel', serif;
      font-size: 1.2rem;
      letter-spacing: .08em;
      margin-bottom: 6px;
      color: var(--n1);
    }

    .couple-card p {
      font-size: 13px;
      color: #888;
      line-height: 1.7;
    }

    .couple-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--n1), var(--n3));
      margin: 0 auto 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Cinzel', serif;
      font-size: 2rem;
      color: var(--gold);
      border: 3px solid var(--gold-l);
    }

    .amp-cinzel {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 3.5rem;
      color: var(--gold);
    }

    /* COUNTDOWN */
    .countdown-section {
      background: var(--n1);
    }

    .countdown-section .cinzel-title {
      color: #fff;
    }

    .countdown-section .sec-sub {
      color: rgba(255, 255, 255, .5);
    }

    .cd-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      max-width: 380px;
      margin: 2rem auto 0;
    }

    .cd-box {
      border: 1px solid rgba(201, 162, 39, .3);
      border-radius: 4px;
      padding: 1.25rem .75rem;
    }

    .cd-num {
      font-family: 'Cinzel', serif;
      font-size: 2.2rem;
      color: var(--gold);
      line-height: 1;
    }

    .cd-lbl {
      font-size: 10px;
      color: rgba(255, 255, 255, .4);
      letter-spacing: .1em;
      text-transform: uppercase;
      margin-top: 4px;
    }

    /* DETAIL */
    .detail-section {
      background: var(--cream);
    }

    .detail-card {
      background: var(--white);
      border-radius: 4px;
      padding: 2rem;
      border: 1px solid rgba(10, 22, 40, .1);
      margin-bottom: 1.25rem;
      text-align: left;
      border-top: 3px solid var(--gold);
    }

    .detail-card h3 {
      font-family: 'Cinzel', serif;
      font-size: 14px;
      letter-spacing: .1em;
      margin-bottom: 1rem;
      color: var(--n2);
    }

    .detail-row {
      display: flex;
      gap: 8px;
      font-size: 14px;
      color: #555;
      margin-bottom: .6rem;
      line-height: 1.6;
    }

    .detail-row strong {
      color: var(--n1);
      min-width: 80px;
    }

    .btn-maps {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 1rem;
      padding: 9px 18px;
      background: var(--n2);
      color: #fff;
      border-radius: 3px;
      text-decoration: none;
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: .1em;
      text-transform: uppercase;
      transition: background .2s;
    }

    .btn-maps:hover {
      background: var(--n1);
    }

    .btn-maps i {
      font-size: 18px;
    }

    /* RSVP */
    .rsvp-section {
      background: var(--white);
    }

    .rsvp-form {
      max-width: 420px;
      margin: 2rem auto 0;
      text-align: left;
    }

    .rsvp-field {
      margin-bottom: 1rem;
    }

    .rsvp-field label {
      display: block;
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: .1em;
      color: #888;
      margin-bottom: 5px;
      text-transform: uppercase;
    }

    .rsvp-field input,
    .rsvp-field select {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #ddd;
      border-radius: 3px;
      font-size: 14px;
      font-family: inherit;
      color: var(--n1);
      background: #fafafa;
      outline: none;
      transition: border-color .2s;
    }

    .rsvp-field input:focus,
    .rsvp-field select:focus {
      border-color: var(--gold);
    }

    .btn-rsvp {
      width: 100%;
      padding: 13px;
      background: var(--n2);
      color: #fff;
      border: none;
      border-radius: 3px;
      font-family: 'Cinzel', serif;
      font-size: 12px;
      letter-spacing: .15em;
      text-transform: uppercase;
      cursor: pointer;
      transition: background .2s;
    }

    .btn-rsvp:hover {
      background: var(--n1);
    }

    .rsvp-success {
      display: none;
      text-align: center;
      padding: 1.5rem;
    }

    /* CLOSING */
    .closing-section {
      background: linear-gradient(160deg, var(--n1), var(--n2));
      text-align: center;
      padding: 6rem 1.5rem;
    }

    .closing-section h2 {
      font-family: 'Cinzel', serif;
      font-size: clamp(1.2rem, 3vw, 2rem);
      color: var(--gold);
      margin-bottom: 1rem;
      letter-spacing: .1em;
    }

    .closing-section p {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.1rem;
      color: rgba(255, 255, 255, .6);
      line-height: 1.9;
      max-width: 440px;
      margin: 0 auto 1.5rem;
    }

    .closing-brand {
      font-family: 'Cinzel', serif;
      font-size: 11px;
      color: rgba(255, 255, 255, .25);
      letter-spacing: .15em;
      margin-top: 3rem;
    }

    .closing-brand span {
      color: var(--gold);
    }

    .music-btn {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      z-index: 999;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: var(--n2);
      color: var(--gold);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      border: 1px solid var(--gold);
      box-shadow: 0 4px 16px rgba(10, 22, 40, .4);
    }

    /* ── GALERI FOTO ── */
    .galeri-section {
      padding: 5rem 1.5rem;
    }

    /* Tentukan background sesuai tema:
      Merah Klasik : background: #fff;
      Navy Elegant : background: var(--white);
      Blush Pink   : background: var(--cream);
      Sage Garden  : background: var(--white);
      Rustic Brown : background: var(--b7);
    */

    .galeri-grid-foto {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 2rem;
      max-width: 680px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Foto pertama lebih besar */
    .galeri-grid-foto .gf-item:first-child {
      grid-column: span 2;
      grid-row: span 2;
    }

    .gf-item {
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      aspect-ratio: 1;
      background: var(--white);
      cursor: pointer;
    }

    .gf-item:first-child {
      aspect-ratio: auto;
      min-height: 200px;
    }

    .gf-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.4s ease;
    }

    .gf-item:hover img {
      transform: scale(1.05);
    }

    .gf-caption-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.6));
      padding: 1.5rem .75rem .75rem;
      opacity: 0;
      transition: opacity .3s;
    }

    .gf-item:hover .gf-caption-overlay {
      opacity: 1;
    }

    .gf-caption-overlay span {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.9);
      font-style: italic;
    }

    /* Lightbox */
    .lightbox-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.92);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 1rem;
    }

    .lightbox-overlay.show {
      display: flex;
    }

    .lightbox-img {
      max-width: 90vw;
      max-height: 80vh;
      border-radius: 8px;
      object-fit: contain;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    .lightbox-caption {
      color: rgba(255, 255, 255, 0.7);
      font-size: 14px;
      font-style: italic;
      margin-top: 1rem;
      text-align: center;
    }

    .lightbox-nav {
      position: fixed;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      border: none;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s;
    }

    .lightbox-nav:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .lightbox-prev {
      left: 1rem;
    }

    .lightbox-next {
      right: 1rem;
    }

    .lightbox-close {
      position: fixed;
      top: 1rem;
      right: 1rem;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .lightbox-counter {
      position: fixed;
      top: 1rem;
      left: 50%;
      transform: translateX(-50%);
      color: rgba(255, 255, 255, 0.5);
      font-size: 13px;
    }
    @media(max-width:500px) {
      .couple-grid {
        grid-template-columns: 1fr;
      }
      .amp-cinzel {
        display: none;
      }
      .cd-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .galeri-grid-foto {
        grid-template-columns: repeat(2, 1fr);
      }
      .gf-item:first-child {
        grid-column: span 2;
      }
    }
  </style>
</head>

<body>

  <div class="cover" id="cover">
    <div class="cover-mono"><?= strtoupper(substr($pria, 0, 1) . substr($wanita, 0, 1)) ?></div>
    <div class="cover-tag">The Wedding Of</div>
    <div class="cover-name">
      <?= $pria ?>
      <span class="amp">&amp; <?= $wanita ?></span>
    </div>
    <div class="cover-line"></div>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to">KEPADA YTH.</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="bukaUndangan()"><i class='bx bx-envelope'></i> Buka Undangan</button>
  </div>

  <div class="content" id="mainContent">

    <section class="couple-section">
      <div class="sec-inner">
        <div class="cinzel-title">Bismillahirrahmanirrahim</div>
        <div class="gold-line"></div>
        <p class="sec-sub">Dengan Memohon Rahmat dan Ridho Allah SWT</p>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= strtoupper($pria) ?></h3>
            <p>Putra dari<br><?= $ayah_pria ?> &amp; <?= $ibu_pria ?></p>
          </div>
          <div class="amp-cinzel">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= strtoupper($wanita) ?></h3>
            <p>Putri dari<br><?= $ayah_wanita ?> &amp; <?= $ibu_wanita ?></p>
          </div>
        </div>
      </div>
    </section>

    <section class="countdown-section">
      <div class="sec-inner">
        <div class="cinzel-title">Menuju Hari Bahagia</div>
        <div class="gold-line"></div>
        <div class="sec-sub"><?= $tgl_full ?></div>
        <div class="cd-grid">
          <div class="cd-box">
            <div class="cd-num" id="cd-h">00</div>
            <div class="cd-lbl">Hari</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-j">00</div>
            <div class="cd-lbl">Jam</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-m">00</div>
            <div class="cd-lbl">Menit</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-s">00</div>
            <div class="cd-lbl">Detik</div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION GALERI FOTO (PHP) -->
    <?php
    // Ensure variables exist to avoid undefined variable notices when this snippet
    // is included standalone.
    if (!isset($galeri_fotos) || !is_array($galeri_fotos)) {
      $galeri_fotos = [];
    }
    if (!isset($ada_galeri)) {
      $ada_galeri = !empty($galeri_fotos);
    }
    ?>
    <?php if ($ada_galeri): ?>
    <section class="galeri-section">
      <div class="sec-inner">

        <!--
        Sesuaikan class heading dengan tema:
        - Merah Klasik : <div class="ornament"> + <div class="sec-label">
        - Navy Elegant : <div class="cinzel-title"> + <div class="gold-line">
        - Blush Pink   : <div class="script-title">
        - Sage Garden  : <div class="baskerville-heading"> + <div class="lato-label">
        - Rustic Brown : <div class="cinzel-title"> + <div class="cinzel-label">
        -->
        <div class="ornament">Galeri</div>
        <div class="sec-label">Momen Berharga Kami</div>

        <div class="galeri-grid-foto">
          <?php foreach ($galeri_fotos as $idx => $gf): ?>
          <div class="gf-item" onclick="bukaLightbox(<?= $idx ?>)">
            <img
              src="<?= htmlspecialchars($gf['path_file']) ?>"
              alt="Foto <?= $idx+1 ?>"
              loading="lazy"
            />
            <?php if ($gf['caption']): ?>
            <div class="gf-caption-overlay">
              <span><?= htmlspecialchars($gf['caption']) ?></span>
            </div>
            <?php endif ?>
          </div>
          <?php endforeach ?>
        </div>

      </div>
    </section>

    <!-- LIGHTBOX -->
    <div class="lightbox-overlay" id="lightbox" onclick="tutupLightbox(event)">
      <span class="lightbox-counter" id="lbCounter"></span>
      <img class="lightbox-img" id="lbImg" src="" alt=""/>
      <div class="lightbox-caption" id="lbCaption"></div>
      <button class="lightbox-nav lightbox-prev" onclick="lbNav(-1);event.stopPropagation()">‹</button>
      <button class="lightbox-nav lightbox-next" onclick="lbNav(1);event.stopPropagation()">›</button>
      <button class="lightbox-close" onclick="tutupLightbox()">×</button>
    </div>

    <script>
    const lbFotos = <?= json_encode(array_map(fn($f) => [
        'src'     => $f['path_file'],
        'caption' => $f['caption'] ?? ''
    ], $galeri_fotos)) ?>;
    let lbIdx = 0;

    function bukaLightbox(idx) {
      lbIdx = idx;
      tampilLb();
      document.getElementById('lightbox').classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function tampilLb() {
      const f = lbFotos[lbIdx];
      document.getElementById('lbImg').src           = f.src;
      document.getElementById('lbCaption').textContent= f.caption;
      document.getElementById('lbCounter').textContent= (lbIdx+1)+' / '+lbFotos.length;
    }

    function lbNav(dir) {
      lbIdx = (lbIdx + dir + lbFotos.length) % lbFotos.length;
      tampilLb();
    }

    function tutupLightbox(e) {
      if (e && e.target !== document.getElementById('lightbox') && !e.target.classList.contains('lightbox-close')) return;
      document.getElementById('lightbox').classList.remove('show');
      document.body.style.overflow = '';
    }

    // Keyboard navigation
    document.addEventListener('keydown', e => {
      if (!document.getElementById('lightbox').classList.contains('show')) return;
      if (e.key === 'ArrowLeft')  lbNav(-1);
      if (e.key === 'ArrowRight') lbNav(1);
      if (e.key === 'Escape')     tutupLightbox({target:document.getElementById('lightbox')});
    });
    </script>
    <?php endif ?>

    <section class="detail-section">
      <div class="sec-inner">
        <div class="cinzel-title">Informasi Acara</div>
        <div class="gold-line"></div>
        <div class="detail-card">
          <h3>AKAD NIKAH</h3>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $ma ?> – <?= $sa ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map'></i> Google Maps</a>
        </div>
        <div class="detail-card">
          <h3>RESEPSI PERNIKAHAN</h3>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $mr ?> – <?= $sr ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map'></i> Google Maps</a>
        </div>
      </div>
    </section>

    <section class="rsvp-section">
      <div class="sec-inner">
        <div class="cinzel-title">RSVP</div>
        <div class="gold-line"></div>
        <p class="sec-sub">Konfirmasi kehadiran kamu</p>
        <div class="rsvp-form" id="rsvpForm">
          <div class="rsvp-field"><label>Nama</label><input type="text" id="rsvpNama" value="<?= htmlspecialchars($tamu) ?>" /></div>
          <div class="rsvp-field"><label>Jumlah Tamu</label>
            <select id="rsvpJml">
              <option>1 orang</option>
              <option>2 orang</option>
              <option>3 orang</option>
              <option>4 orang</option>
            </select>
          </div>
          <div class="rsvp-field"><label>Kehadiran</label>
            <select id="rsvpHadir">
              <option value="hadir">✔️ Insya Allah Hadir</option>
              <option value="tidak">❌ Berhalangan Hadir</option>
              <option value="mungkin">❓ Mungkin Hadir</option>
            </select>
          </div>
          <button class="btn-rsvp" onclick="kirimRSVP()">Konfirmasi Kehadiran</button>
        </div>
        <div class="rsvp-success" id="rsvpSuccess">
          <p style="font-family:'Cinzel',serif;font-size:1rem;letter-spacing:.1em;color:var(--n2)">TERIMA KASIH</p>
          <p style="font-size:14px;color:#888;margin-top:6px">Konfirmasi kehadiranmu sudah kami terima.</p>
        </div>
      </div>
    </section>

    <section class="closing-section">
      <div class="sec-inner">
        <h2>TERIMA KASIH</h2>
        <p>Kehadiran dan doa restu Bapak/Ibu/Saudara/i merupakan kehormatan dan kebahagiaan terbesar bagi kami.</p>
        <p style="font-family:'Cinzel',serif;font-size:1rem;letter-spacing:.1em;color:var(--gold)"><?= strtoupper($pria) ?> &amp; <?= strtoupper($wanita) ?></p>
        <div class="closing-brand">Dibuat dengan ♥ oleh <span>BERNADA.ID</span></div>
      </div>
    </section>

  </div>

  <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class='bx bx-music'></i></button>
  <audio id="bgMusic" loop>
    <source src="../audio/wedding-music.mp3" type="audio/mpeg" />
  </audio>

  <script>
    function bukaUndangan() {
      document.getElementById('cover').style.display = 'none';
      document.getElementById('mainContent').classList.add('show');
      document.getElementById('bgMusic').play().catch(() => {});
    }
    let playing = false;

    function toggleMusic() {
      const m = document.getElementById('bgMusic'),
        btn = document.getElementById('musicBtn');
      if (playing) {
        m.pause();
        btn.innerHTML = "<i class='bx bx-music' ></i>";
        playing = false;
      } else {
        m.play().catch(() => {});
        btn.textContent = "⏸";
        playing = true;
      }
    }

    const target = new Date("<?= $tgl_countdown ?>").getTime();

    function updateCountdown() {
      const now = Date.now();
      const diff = target - now;

      if (diff <= 0) {
        document.getElementById('cd-h').textContent = '00';
        document.getElementById('cd-j').textContent = '00';
        document.getElementById('cd-m').textContent = '00';
        document.getElementById('cd-s').textContent = '00';
        return;
      }

      const h = Math.floor(diff / 86400000);
      const j = Math.floor((diff % 86400000) / 3600000);
      const m = Math.floor((diff % 3600000) / 60000);
      const s = Math.floor((diff % 60000) / 1000);

      document.getElementById('cd-h').textContent = String(h).padStart(2, '0');
      document.getElementById('cd-j').textContent = String(j).padStart(2, '0');
      document.getElementById('cd-m').textContent = String(m).padStart(2, '0');
      document.getElementById('cd-s').textContent = String(s).padStart(2, '0');
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();


    function kirimRSVP() {
      const nama = document.getElementById('rsvpNama').value.trim();
      if (!nama) {
        alert('Mohon isi nama!');
        return;
      }
      const jml = document.getElementById('rsvpJml').value;
      const hadir = document.getElementById('rsvpHadir').value === 'hadir' ? 'Insya Allah Hadir' : document.getElementById('rsvpHadir').value === 'mungkin' ? 'Mungkin Hadir' : 'Berhalangan Hadir';
      const p = encodeURIComponent(`Halo! Saya ${nama} konfirmasi kehadiran di pernikahan <?= $pria ?> & <?= $wanita ?>.\nKehadiran: ${hadir}\nJumlah: ${jml}`);
      window.open(`https://wa.me/6281939195110?text=${p}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpSuccess').style.display = 'block';
    }
  </script>
</body>

</html>