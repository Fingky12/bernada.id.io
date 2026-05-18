<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/ambil_data.php';
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
  <title>Undangan Pernikahan <?= htmlspecialchars($pria) ?> & <?= htmlspecialchars($wanita) ?></title>
  <meta property="og:title" content="Undangan – <?= htmlspecialchars($pria) ?> & <?= htmlspecialchars($wanita) ?>" />
  <meta property="og:description" content="<?= $tgl_full ?> · <?= htmlspecialchars($lokasi) ?>" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <style id="galeri-css-snippet">
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --r1: #7B1113;
      --r2: #C0393B;
      --r3: #e05555;
      --gold: #c9a227;
      --cream: #FDF8F0;
      --dark: #1a1a1a;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--cream);
      color: var(--dark);
      overflow-x: hidden;
    }

    /* COVER */
    .cover {
      min-height: 100vh;
      background: linear-gradient(160deg, #1a0505 0%, var(--r1) 40%, var(--r2) 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3rem 1.5rem;
      position: relative;
    }

    .cover::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M40 0 L80 40 L40 80 L0 40 Z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .cover-ornament {
      font-size: 13px;
      letter-spacing: .25em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 1.5rem;
      position: relative;
    }

    .cover-ornament::before,
    .cover-ornament::after {
      content: '—';
      margin: 0 10px;
      opacity: .6;
    }

    .cover h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(3rem, 10vw, 6rem);
      color: #fff;
      line-height: 1.1;
      margin-bottom: 1rem;
      position: relative;
    }

    .cover h1 em {
      color: var(--gold);
      display: block;
      font-style: italic;
      font-size: .65em;
    }

    .cover-date {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(1rem, 3vw, 1.4rem);
      color: rgba(255, 255, 255, .75);
      margin-bottom: 2rem;
      letter-spacing: .05em;
    }

    .cover-to {
      font-size: 13px;
      color: rgba(255, 255, 255, .5);
      margin-bottom: .5rem;
    }

    .cover-guest {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: clamp(1.2rem, 4vw, 1.8rem);
      color: #fff;
      margin-bottom: 2.5rem;
    }

    .btn-open {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 14px 32px;
      background: var(--gold);
      color: var(--dark);
      border-radius: 50px;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: .05em;
      transition: transform .2s, box-shadow .2s;
      border: none;
      cursor: pointer;
      font-family: inherit;
      z-index: 9999;
      pointer-events: auto;
    }

    .btn-open:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(201, 162, 39, .4);
    }

    /* CONTENT */
    .content {
      display: none;
    }

    .content.show {
      display: block;
    }

    /* SECTION */
    section {
      padding: 5rem 1.5rem;
    }

    .sec-inner {
      max-width: 680px;
      margin: 0 auto;
      text-align: center;
    }

    .ornament {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: clamp(2.5rem, 6vw, 4rem);
      color: var(--r2);
      margin-bottom: .25rem;
    }

    .sec-label {
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: #aaa;
      margin-bottom: 2rem;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      justify-content: center;
      margin: 1.5rem auto;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      max-width: 80px;
      height: 1px;
      background: #e0c8c8;
    }

    .divider-diamond {
      width: 8px;
      height: 8px;
      background: var(--r2);
      transform: rotate(45deg);
    }

    /* COUPLE */
    .couple-section {
      background: #fff;
    }

    .couple-grid {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 2rem;
      align-items: center;
      margin: 2.5rem 0;
    }

    .couple-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--r1), var(--r2));
      margin: 0 auto 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      color: #fff;
      border: 4px solid var(--cream);
      box-shadow: 0 4px 20px rgba(192, 57, 59, .2);
    }

    .couple-card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      margin-bottom: 4px;
    }

    .couple-card .ortu {
      font-size: 13px;
      color: #888;
      line-height: 1.8;
    }

    .amp-big {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 3rem;
      color: var(--gold);
    }

    /* COUNTDOWN */
    .countdown-section {
      background: linear-gradient(135deg, var(--r1), var(--r2));
    }

    .countdown-section .sec-label {
      color: rgba(255, 255, 255, .5);
    }

    .countdown-section .ornament {
      color: var(--gold);
    }

    .countdown-section h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.3rem, 3vw, 1.8rem);
      color: #fff;
      margin-bottom: 2rem;
    }

    .countdown-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      max-width: 400px;
      margin: 0 auto;
    }

    .cd-box {
      background: rgba(255, 255, 255, .1);
      border-radius: 12px;
      padding: 1.25rem .75rem;
    }

    .cd-num {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      color: #fff;
      line-height: 1;
    }

    .cd-label {
      font-size: 11px;
      color: rgba(255, 255, 255, .6);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-top: 4px;
    }

    /* DETAIL */
    .detail-section {
      background: var(--cream);
    }

    .detail-card {
      background: #fff;
      border-radius: 16px;
      padding: 2rem;
      border: 1px solid #eedede;
      margin-bottom: 1.25rem;
      text-align: left;
    }

    .detail-card-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 1rem;
      padding-bottom: .75rem;
      border-bottom: 1px solid #f0e0e0;
    }

    .detail-icon {
      width: 44px;
      height: 44px;
      /* border-radius: 10px; */
      /* background: linear-gradient(135deg, var(--r1), var(--r2)); */
      color: var(--r2);
      display: flex;
      align-items: center;
      font-size: 2.2rem;
      justify-content: center;
    }

    .detail-card-header h3 {
      font-size: 16px;
      font-weight: 600;
    }

    .detail-card-header p {
      font-size: 12px;
      color: #aaa;
    }

    .detail-row {
      display: flex;
      gap: 8px;
      align-items: flex-start;
      margin-bottom: .6rem;
      font-size: 14px;
      color: #555;
    }

    .detail-row strong {
      color: var(--dark);
      min-width: 80px;
    }

    .btn-maps {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 1rem;
      padding: 9px 18px;
      background: var(--r2);
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: background .2s;
    }

    .btn-maps:hover {
      background: var(--r1);
    }

    /* RSVP */
    .rsvp-section {
      background: #fff;
    }

    .rsvp-form {
      max-width: 440px;
      margin: 0 auto;
      text-align: left;
    }

    .rsvp-field {
      margin-bottom: 1rem;
    }

    .rsvp-field label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #444;
      margin-bottom: 5px;
    }

    .rsvp-field input,
    .rsvp-field select {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #e0d0d0;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      color: var(--dark);
      background: #fafafa;
      outline: none;
      transition: border-color .2s;
    }

    .rsvp-field input:focus,
    .rsvp-field select:focus {
      border-color: var(--r2);
    }

    .btn-rsvp {
      width: 100%;
      padding: 13px;
      background: var(--r2);
      color: #fff;
      border: none;
      border-radius: 9px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      transition: background .2s;
    }

    .btn-rsvp:hover {
      background: var(--r1);
    }

    .rsvp-success {
      display: none;
      text-align: center;
      padding: 1.5rem;
    }

    /* PENUTUP */
    .closing-section {
      background: linear-gradient(160deg, #1a0505, var(--r1));
      text-align: center;
      padding: 6rem 1.5rem;
    }

    .closing-section h2 {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: clamp(1.5rem, 4vw, 2.5rem);
      color: #fff;
      margin-bottom: 1rem;
    }

    .closing-section p {
      font-size: 14px;
      color: rgba(255, 255, 255, .65);
      line-height: 1.9;
      max-width: 440px;
      margin: 0 auto 2rem;
    }

    .closing-brand {
      font-family: 'Playfair Display', serif;
      font-size: 13px;
      color: rgba(255, 255, 255, .3);
      letter-spacing: .1em;
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
      background: var(--r2);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      border: none;
      box-shadow: 0 4px 16px rgba(192, 57, 59, .4);
      transition: transform .2s;
    }

    .music-btn:hover {
      transform: scale(1.1);
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
      background: var(--r3);
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

      .amp-big {
        display: none;
      }

      .countdown-grid {
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
  <!-- COVER -->
  <div class="cover" id="cover">
    <div class="cover-ornament">The Wedding Of</div>
    <h1><?= htmlspecialchars($pria) ?><em>& <?= htmlspecialchars($wanita) ?></em></h1>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to">Kepada Yth.</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="bukaUndangan()"><i class='bx bx-envelope'></i> &nbsp; Buka Undangan</button>
  </div>

  <!-- CONTENT -->
  <div class="content" id="mainContent">

    <!-- COUPLE -->
    <section class="couple-section">
      <div class="sec-inner">
        <div class="ornament">Bismillahirrahmanirrahim</div>
        <div class="sec-label">Dengan Memohon Rahmat dan Ridho Allah SWT</div>
        <div class="divider">
          <div class="divider-diamond"></div>
        </div>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= htmlspecialchars($pria) ?></h3>
            <div class="ortu">
              Putra dari<br>
              <?= $ayah_pria ? htmlspecialchars($ayah_pria) : 'Bpk.' ?>
              <?= $ibu_pria ? '&amp; ' . htmlspecialchars($ibu_pria) : 'Ibu.' ?>
            </div>
          </div>
          <div class="amp-big">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar" style="background:linear-gradient(135deg,#8a2020,#C0393B)"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= htmlspecialchars($wanita) ?></h3>
            <div class="ortu">
              Putri dari<br>
              <?= $ayah_wanita ? htmlspecialchars($ayah_wanita) : 'Bpk.' ?>
              <?= $ibu_wanita ? '&amp; ' . htmlspecialchars($ibu_wanita) : 'Ibu.' ?>
            </div>
          </div>
        </div>
        <p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:15px;color:#888;line-height:1.9">
          Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan.<br>
          <em style="color:#aaa">— QS. Yasin: 36</em>
        </p>
      </div>
    </section>

    <!-- COUNTDOWN -->
    <section class="countdown-section">
      <div class="sec-inner">
        <div class="ornament" style="color:var(--gold)">◆</div>
        <div class="sec-label" style="color:rgba(255,255,255,.5)">Menuju Hari Bahagia</div>
        <h2><?= $tgl_full ?></h2>
        <div class="countdown-grid">
          <div class="cd-box">
            <div class="cd-num" id="cd-h">00</div>
            <div class="cd-label">Hari</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-j">00</div>
            <div class="cd-label">Jam</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-m">00</div>
            <div class="cd-label">Menit</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cd-s">00</div>
            <div class="cd-label">Detik</div>
          </div>
        </div>
      </div>
    </section>

    <!-- DETAIL ACARA -->
    <section class="detail-section">
      <div class="sec-inner">
        <div class="ornament">Acara</div>
        <div class="sec-label">Informasi Pernikahan</div>
        <div class="detail-card">
          <div class="detail-card-header">
            <div class="detail-icon"><i class='bx bx-heart-circle'></i></div>
            <div>
              <h3>Akad Nikah</h3>
              <p>Prosesi sakral pernikahan</p>
            </div>
          </div>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $ma ?> - <?= $sa ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= htmlspecialchars($lokasi) ?></div>
          <a href="<?= htmlspecialchars($maps) ?>" target="_blank" class="btn-maps"><i class='bx bxs-map'></i> Lihat di Google Maps</a>
        </div>
        <div class="detail-card">
          <div class="detail-card-header">
            <div class="detail-icon"><i class='bx bx-home-heart'></i></div>
            <div>
              <h3>Resepsi Pernikahan</h3>
              <p>Syukuran &amp; jamuan tamu</p>
            </div>
          </div>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $mr ?> – <?= $sr ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= htmlspecialchars($lokasi) ?></div>
          <a href="<?= htmlspecialchars($maps) ?>" target="_blank" class="btn-maps"><i class='bx bxs-map'></i> Lihat di Google Maps</a>
        </div>
      </div>
    </section>

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
  
    <!-- SECTION GALERI FOTO (PHP) -->
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
    <?php endif ?>
    
    <!-- LIGHTBOX -->
    <div class="lightbox-overlay" id="lightbox" onclick="tutupLightbox(event)">
      <span class="lightbox-counter" id="lbCounter"></span>
      <img class="lightbox-img" id="lbImg" src="" alt=""/>
      <div class="lightbox-caption" id="lbCaption"></div>
      <button class="lightbox-nav lightbox-prev" onclick="lbNav(-1);event.stopPropagation()">‹</button>
      <button class="lightbox-nav lightbox-next" onclick="lbNav(1);event.stopPropagation()">›</button>
      <button class="lightbox-close" onclick="tutupLightbox()">×</button>
    </div>

    <!-- RSVP -->
    <section class="rsvp-section">
      <div class="sec-inner">
        <div class="ornament">RSVP</div>
        <div class="sec-label">Konfirmasi Kehadiran</div>
        <div class="divider">
          <div class="divider-diamond"></div>
        </div>
        <p style="font-size:14px;color:#888;margin-bottom:1.5rem">Mohon konfirmasi kehadiran kamu paling lambat 7 hari sebelum acara</p>
        <div class="rsvp-form" id="rsvpForm">
          <div class="rsvp-field"><label>Nama Lengkap</label><input type="text" id="rsvpNama" placeholder="Nama kamu..." value="<?= htmlspecialchars($tamu) ?>" /></div>
          <div class="rsvp-field"><label>Jumlah Tamu</label>
            <select id="rsvpJml">
              <option>1 orang</option>
              <option>2 orang</option>
              <option>3 orang</option>
              <option>4+ orang</option>
            </select>
          </div>
          <div class="rsvp-field"><label>Konfirmasi Kehadiran</label>
            <select id="rsvpHadir">
              <option value="hadir">✔️ Insya Allah Hadir</option>
              <option value="tidak">❌ Berhalangan Hadir</option>
              <option value="mungkin">❓ Mungkin Hadir</option>
            </select>
          </div>
          <button class="btn-rsvp" onclick="kirimRSVP()">Kirim Konfirmasi</button>
        </div>
        <div class="rsvp-success" id="rsvpSuccess">
          <div style="font-size:48px;margin-bottom:8px">🎉</div>
          <p style="font-size:16px;font-weight:600">Terima kasih!</p>
          <p style="font-size:14px;color:#888">Konfirmasi kehadiranmu sudah kami terima.</p>
        </div>
      </div>
    </section>

    <!-- PENUTUP -->
    <section class="closing-section">
      <div class="sec-inner">
        <div class="ornament" style="color:var(--gold)">♥</div>
        <h2>Merupakan suatu kehormatan dan kebahagiaan bagi kami</h2>
        <p>apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.</p>
        <p style="font-family:'Playfair Display',serif;font-style:italic;font-size:1.2rem;color:rgba(255,255,255,.8);margin-top:1rem">
          <?= htmlspecialchars($pria) ?> &amp; <?= htmlspecialchars($wanita) ?>
        </p>
        <div style="margin-top:3rem" class="closing-brand">
          Dibuat dengan ♥ oleh <span>Bernada.ID</span>
        </div>
      </div>
    </section>

  </div>

  <button class="music-btn" id="musicBtn" onclick="toggleMusic()" title="Musik"><i class='bx bx-music'></i></button>
  <audio id="bgMusic" loop>
    <!-- <source src="../audio/" type="audio/mpeg" /> -->
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
        btn.innerHTML = '<i class="bx bx-music"></i>';
        playing = false;
      } else {
        m.play().catch(() => {});
        btn.textContent = '⏸';
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
        alert('Mohon isi nama kamu!');
        return;
      }
      const jml = document.getElementById('rsvpJml').value;
      const hadir = document.getElementById('rsvpHadir');
      const status = hadir.options[hadir.selectedIndex].text.replace(/^[^\s]+\s+/, '');
      const p = encodeURIComponent(`Halo! Saya ${nama} ingin konfirmasi kehadiran di pernikahan <?= addslashes($pria) ?> & <?= addslashes($wanita) ?>.\nKehadiran: ${status}\nJumlah: ${jml}`);
      window.open(`https://wa.me/6281939195110?text=${p}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpSuccess').style.display = 'block';
    }

        
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
      document.getElementById('lbImg').src = f.src;
      document.getElementById('lbCaption').textContent = f.caption;
      document.getElementById('lbCounter').textContent = (lbIdx + 1) + ' / ' + lbFotos.length;
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
      if (e.key === 'ArrowLeft') lbNav(-1);
      if (e.key === 'ArrowRight') lbNav(1);
      if (e.key === 'Escape') tutupLightbox({
        target: document.getElementById('lightbox')
      });
    });

  </script>
</body>
</html>