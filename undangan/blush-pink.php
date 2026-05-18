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
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Jost:ital,wght@0,300;0,400;0,500;1,300&family=Cormorant+Garamond:ital,wght@0,300;1,300;1,400&display=swap" rel="stylesheet" />
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
      --p1: #d4687e;
      --p2: #e8a0b0;
      --p3: #f7d4db;
      --p4: #fdf0f2;
      --dark: #3d2030;
      --gray: #7a5060;
      --cream: #fdf8f9;
      --white: #fff;
      --gold: #c9a227;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Jost', sans-serif;
      background: var(--cream);
      color: var(--dark);
      overflow-x: hidden;
    }

    /* COVER */
    .cover {
      min-height: 100vh;
      background: linear-gradient(160deg, #3d1520 0%, var(--p1) 50%, var(--p2) 100%);
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
        radial-gradient(circle at 15% 85%, rgba(255, 255, 255, .08) 0%, transparent 40%),
        radial-gradient(circle at 85% 15%, rgba(255, 255, 255, .06) 0%, transparent 40%);
    }

    /* Petal decorations */
    .petal {
      position: absolute;
      opacity: .12;
      font-size: 2rem;
      pointer-events: none;
    }

    .petal i {
      color: var(--p3);
    }

    .petal-1 {
      top: 8%;
      left: 5%;
      transform: rotate(-20deg);
    }

    .petal-2 {
      top: 15%;
      right: 8%;
      transform: rotate(30deg);
    }

    .petal-3 {
      bottom: 20%;
      left: 8%;
      transform: rotate(15deg);
    }

    .petal-4 {
      bottom: 12%;
      right: 36%;
      transform: rotate(-35deg);
    }

    .petal-5 {
      bottom: 52%;
      left: 32%;
      transform: rotate(-35deg);
    }

    .petal-6 {
      bottom: 32%;
      right: 6%;
      transform: rotate(-35deg);
    }

    .cover-script {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(1rem, 3vw, 1.4rem);
      color: rgba(255, 255, 255, .7);
      margin-bottom: 1rem;
      position: relative;
    }

    .cover-name {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(3.5rem, 12vw, 7rem);
      color: #fff;
      line-height: 1;
      margin-bottom: .5rem;
      position: relative;
    }

    .cover-and {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: clamp(1.5rem, 5vw, 3rem);
      color: rgba(255, 255, 255, .6);
      display: block;
      margin: .25rem 0;
    }

    .cover-date {
      font-family: 'Jost', sans-serif;
      font-weight: 300;
      letter-spacing: .2em;
      font-size: clamp(.85rem, 2vw, 1rem);
      color: rgba(255, 255, 255, .65);
      text-transform: uppercase;
      margin: 1.25rem 0 2.5rem;
      position: relative;
    }

    .cover-to {
      font-size: 12px;
      color: rgba(255, 255, 255, .45);
      margin-bottom: .4rem;
      letter-spacing: .08em;
    }

    .cover-guest {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(1.3rem, 4vw, 2rem);
      color: #fff;
      margin-bottom: 2.5rem;
    }

    .btn-open {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 13px 32px;
      background: rgba(255, 255, 255, .15);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, .4);
      border-radius: 50px;
      font-family: 'Jost', sans-serif;
      font-weight: 500;
      font-size: 13px;
      letter-spacing: .1em;
      cursor: pointer;
      backdrop-filter: blur(8px);
      transition: all .3s;
      text-transform: uppercase;
    }

    .btn-open:hover {
      background: rgba(255, 255, 255, .25);
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

    .script-title {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(2.5rem, 6vw, 4rem);
      color: var(--p1);
      margin-bottom: .25rem;
    }

    .jost-label {
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: #bbb;
      margin-bottom: 1.5rem;
      font-weight: 300;
    }

    .pink-line {
      width: 40px;
      height: 2px;
      background: var(--p2);
      margin: .75rem auto 1.5rem;
    }

    .floral-divider {
      color: var(--p2);
      font-size: 1.2rem;
      margin: 1.25rem 0;
      letter-spacing: .5em;
      opacity: .7;
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

    .couple-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: linear-gradient(135deg, #d4687e, #e8a0b0);
      margin: 0 auto 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Great Vibes', cursive;
      font-size: 2.5rem;
      color: #fff;
      border: 4px solid var(--p3);
    }

    .couple-card h3 {
      font-family: 'Great Vibes', cursive;
      font-size: 1.8rem;
      color: var(--dark);
      margin-bottom: 4px;
    }

    .couple-card p {
      font-size: 13px;
      color: var(--gray);
      line-height: 1.7;
      font-weight: 300;
    }

    .amp-script {
      font-family: 'Great Vibes', cursive;
      font-size: 4rem;
      color: var(--p2);
    }

    .ayat-box {
      background: var(--p4);
      border-radius: 12px;
      padding: 1.5rem 2rem;
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 1.1rem;
      color: var(--gray);
      line-height: 1.8;
      margin-top: 1.5rem;
    }

    .ayat-box cite {
      font-size: .9rem;
      display: block;
      margin-top: .5rem;
      color: var(--p1);
    }

    /* COUNTDOWN */
    .countdown-section {
      background: linear-gradient(135deg, #3d1520, var(--p1));
    }

    .countdown-section .script-title {
      color: var(--p3);
    }

    .countdown-section .jost-label {
      color: rgba(255, 255, 255, .45);
    }

    .cd-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      max-width: 360px;
      margin: 2rem auto 0;
    }

    .cd-box {
      background: rgba(255, 255, 255, .1);
      border-radius: 50%;
      width: 70px;
      height: 70px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .cd-num {
      font-size: 1.5rem;
      font-weight: 500;
      color: #fff;
      line-height: 1;
    }

    .cd-lbl {
      font-size: 9px;
      color: rgba(255, 255, 255, .5);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-top: 2px;
    }

    /* DETAIL */
    .detail-section {
      background: var(--cream);
    }

    .detail-card {
      background: var(--white);
      border-radius: 16px;
      padding: 2rem;
      border: 1px solid var(--p3);
      margin-bottom: 1.25rem;
      text-align: left;
      position: relative;
      overflow: hidden;
    }

    .detail-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 80px;
      height: 80px;
      background: radial-gradient(circle, var(--p4) 0%, transparent 70%);
    }

    .detail-icon {
      font-size: 2rem;
      margin-bottom: .75rem;
    }

    .detail-icon img {
      width: 32px;
      height: 32px;
    }

    .detail-card h3 {
      font-family: 'Great Vibes', cursive;
      font-size: 1.6rem;
      color: var(--p1);
      margin-bottom: .75rem;
    }

    .detail-row {
      display: flex;
      gap: 8px;
      font-size: 14px;
      color: var(--gray);
      margin-bottom: .5rem;
      font-weight: 300;
    }

    .detail-row strong {
      color: var(--dark);
      min-width: 80px;
      font-weight: 500;
    }

    .btn-maps {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: .75rem;
      padding: 9px 18px;
      background: var(--p1);
      color: #fff;
      border-radius: 50px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: background .2s;
    }

    .btn-maps:hover {
      background: #b85570;
    }

    /* RSVP */
    .rsvp-section {
      background: var(--p4);
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
      font-size: 12px;
      font-weight: 500;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--gray);
      margin-bottom: 5px;
    }

    .rsvp-field input,
    .rsvp-field select {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid var(--p3);
      border-radius: 50px;
      font-size: 14px;
      font-family: inherit;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color .2s;
    }

    .rsvp-field input:focus,
    .rsvp-field select:focus {
      border-color: var(--p1);
    }

    .btn-rsvp {
      width: 100%;
      padding: 13px;
      background: var(--p1);
      color: #fff;
      border: none;
      border-radius: 50px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      font-family: inherit;
      transition: background .2s;
      letter-spacing: .05em;
    }

    .btn-rsvp:hover {
      background: #b85570;
    }

    .rsvp-success {
      display: none;
      text-align: center;
      padding: 2rem;
    }

    .rsvp-success .heart {
      font-size: 3rem;
      animation: pulse 1s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1)
      }

      50% {
        transform: scale(1.15)
      }
    }

    /* CLOSING */
    .closing-section {
      background: linear-gradient(160deg, #3d1520, var(--p1), var(--p2));
      text-align: center;
      padding: 6rem 1.5rem;
    }

    .closing-section h2 {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(2rem, 6vw, 4rem);
      color: #fff;
      margin-bottom: 1rem;
    }

    .closing-section p {
      font-family: 'Jost', sans-serif;
      font-weight: 300;
      font-size: 15px;
      color: rgba(255, 255, 255, .7);
      line-height: 1.9;
      max-width: 440px;
      margin: 0 auto 1rem;
    }

    .closing-brand {
      font-size: 11px;
      color: rgba(255, 255, 255, .3);
      letter-spacing: .1em;
      margin-top: 3rem;
    }

    .closing-brand span {
      color: var(--p3);
    }

    .music-btn {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      z-index: 999;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: var(--p1);
      color: #fff;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(212, 104, 126, .4);
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
      background: var(--p4);
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
      .amp-script {
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
    <span class="petal petal-1"><i class='bx bx-heart-circle'></i></span>
    <span class="petal petal-2"><i class='bx bxs-florist'></i></span>
    <span class="petal petal-3"><i class='bx bxl-mongodb'></i></span>
    <span class="petal petal-4"><i class='bx bx-heart-circle'></i></span>
    <span class="petal petal-5"><i class='bx bxl-mongodb'></i></span>
    <span class="petal petal-6"><i class='bx bxl-mongodb'></i></span>
    <div class="cover-script">The Wedding Of</div>
    <div class="cover-name">
      <?= $pria ?>
      <span class="cover-and">&amp; <?= $wanita ?></span>
    </div>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to">Kepada Yth.</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="bukaUndangan()"><i class='bx bx-envelope'></i> Buka Undangan</button>
  </div>

  <div class="content" id="mainContent">

    <section class="couple-section">
      <div class="sec-inner">
        <div class="script-title">Bismillahirrahmanirrahim</div>
        <div class="jost-label">Dengan Memohon Rahmat dan Ridho Allah SWT</div>
        <div class="pink-line"></div>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= $pria ?></h3>
            <p>Putra dari<br><?= $ayah_pria ?> &amp; <?= $ibu_pria ?></p>
          </div>
          <div class="amp-script">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= $wanita ?></h3>
            <p>Putri dari<br><?= $ayah_wanita ?> &amp; <?= $ibu_wanita ?></p>
          </div>
        </div>
        <div class="ayat-box">
          "Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya."
          <cite>— QS. Ar-Rum: 21</cite>
        </div>
      </div>
    </section>

    <section class="countdown-section">
      <div class="sec-inner">
        <div class="script-title">Menuju Hari Bahagia</div>
        <div class="jost-label" style="color:rgba(255,255,255,.45)"><?= $tgl_full ?></div>
        <div class="cd-grid">
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cd-h">00</div>
              <div class="cd-lbl">Hari</div>
            </div>
          </div>
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cd-j">00</div>
              <div class="cd-lbl">Jam</div>
            </div>
          </div>
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cd-m">00</div>
              <div class="cd-lbl">Menit</div>
            </div>
          </div>
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cd-s">00</div>
              <div class="cd-lbl">Detik</div>
            </div>
          </div>
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
          <div class="script-title">Galeri</div>
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
        <img class="lightbox-img" id="lbImg" src="" alt="" />
        <div class="lightbox-caption" id="lbCaption"></div>
        <button class="lightbox-nav lightbox-prev" onclick="lbNav(-1);event.stopPropagation()">‹</button>
        <button class="lightbox-nav lightbox-next" onclick="lbNav(1);event.stopPropagation()">›</button>
        <button class="lightbox-close" onclick="tutupLightbox()">×</button>
      </div>

      <section class="detail-section">
        <div class="sec-inner">
          <div class="script-title">Acara</div>
          <div class="jost-label">Informasi Pernikahan</div>
          <div class="detail-card">
            <div class="detail-icon" style="color: var(--p1); font-size: 24px;"><i class='bx bx-donate-heart'></i></div>
            <h3>Akad Nikah</h3>
            <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
            <div class="detail-row"><strong>Waktu</strong><?= $ma ?> - <?= $sa ?> WIB</div>
            <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
            <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map'></i> Lihat Maps</a>
          </div>
          <div class="detail-card">
            <div class="detail-icon" style="color: var(--p1); font-size: 24px;"><i class='bx bx-home-heart'></i></div>
            <h3>Resepsi</h3>
            <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
            <div class="detail-row"><strong>Waktu</strong><?= $mr ?> – <?= $sr ?> WIB</div>
            <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
            <a href="<?= $maps ?>" target="_blank" class="btn-maps"><i class='bx bx-map'></i> Lihat Maps</a>
          </div>
        </div>
      </section>

      <section class="rsvp-section">
        <div class="sec-inner">
          <div class="script-title">Konfirmasi</div>
          <div class="jost-label">Kehadiran Kamu</div>
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
            <button class="btn-rsvp" onclick="kirimRSVP()"><i class='bx bx-mail-send'></i> Kirim Konfirmasi</button>
          </div>
          <div class="rsvp-success" id="rsvpSuccess">
            <div class="heart"><i class='bx bxs-heart'></i></div>
            <p style="font-family:'Great Vibes',cursive;font-size:1.8rem;color:var(--p1)">Terima kasih!</p>
            <p style="font-size:14px;color:var(--gray);margin-top:4px">Konfirmasi kehadiranmu sudah diterima.</p>
          </div>
        </div>
      </section>

      <section class="closing-section">
        <div class="sec-inner">
          <div class="floral-divider" style="color:rgba(255,255,255,.3)"><i class='bx bxs-florist'></i> <i class='bx bxs-bookmark-heart'></i> <i class='bx bxs-florist'></i></div>
          <h2>Sampai Jumpa di Hari Bahagia Kami</h2>
          <p>Doa dan kehadiran Bapak/Ibu/Saudara/i adalah hadiah terindah untuk kami.</p>
          <p style="font-family:'Great Vibes',cursive;font-size:2rem;color:rgba(255,255,255,.8)"><?= $pria ?> &amp; <?= $wanita ?></p>
          <div class="closing-brand">Dibuat dengan ♥ oleh <span>Bernada.ID</span></div>
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
      const p = encodeURIComponent(`Halo! Saya ${nama} ingin konfirmasi kehadiran di pernikahan <?= $pria ?> & <?= $wanita ?>.\nKehadiran: ${hadir}\nJumlah: ${jml}`);
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