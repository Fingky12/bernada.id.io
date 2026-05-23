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
  <title>Undangan <?= htmlspecialchars($pria) ?> & <?= htmlspecialchars($wanita) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Jost:ital,wght@0,300;0,400;0,500;1,300&family=Cormorant+Garamond:ital,wght@0,300;1,300;1,400&display=swap" rel="stylesheet" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
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
      --gold: #c9a227
    }

    html {
      scroll-behavior: smooth
    }

    body {
      font-family: 'Jost', sans-serif;
      background: var(--cream);
      color: var(--dark);
      overflow-x: hidden
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
      overflow: hidden
    }

    .cover::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 15% 85%, rgba(255, 255, 255, .08) 0%, transparent 40%), radial-gradient(circle at 85% 15%, rgba(255, 255, 255, .06) 0%, transparent 40%)
    }

    .petal {
      position: absolute;
      opacity: .12;
      font-size: 12rem;
      pointer-events: none;
      color : var(--p3);
    }

    .p1 {
      top: 8%;
      left: 5%;
      transform: rotate(-20deg)
    }

    .p2 {
      top: 15%;
      right: 8%;
      transform: rotate(30deg)
    }

    .p3 {
      bottom: 20%;
      left: 8%;
      transform: rotate(15deg)
    }

    .p4 {
      bottom: 12%;
      right: 6%;
      transform: rotate(-35deg)
    }

    .c-script {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(1rem, 3vw, 1.4rem);
      color: rgba(255, 255, 255, .7);
      margin-bottom: 1rem;
      position: relative
    }

    .c-name {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(3.5rem, 12vw, 7rem);
      color: #fff;
      line-height: 1;
      margin-bottom: .5rem;
      position: relative
    }

    .c-and {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: clamp(1.5rem, 5vw, 3rem);
      color: rgba(255, 255, 255, .6);
      display: block;
      margin: .25rem 0
    }

    .c-date {
      font-family: 'Jost', sans-serif;
      font-weight: 300;
      letter-spacing: .2em;
      font-size: clamp(.85rem, 2vw, 1rem);
      color: rgba(255, 255, 255, .65);
      text-transform: uppercase;
      margin: 1.25rem 0 2.5rem;
      position: relative
    }

    .c-to {
      font-size: 12px;
      color: rgba(255, 255, 255, .45);
      margin-bottom: .4rem;
      letter-spacing: .08em
    }

    .c-guest {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(1.3rem, 4vw, 2rem);
      color: #fff;
      margin-bottom: 2.5rem
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
      text-transform: uppercase
    }

    .btn-open:hover {
      background: rgba(255, 255, 255, .25)
    }

    /* CONTENT */
    .content {
      display: none
    }

    .content.show {
      display: block
    }

    section {
      padding: 5rem 1.5rem
    }

    .sec-inner {
      max-width: 680px;
      margin: 0 auto;
      text-align: center
    }

    .st {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(2.5rem, 6vw, 4rem);
      color: var(--p1);
      margin-bottom: .25rem
    }

    .jl {
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: #bbb;
      margin-bottom: 1.5rem;
      font-weight: 300
    }

    .pl {
      width: 40px;
      height: 2px;
      background: var(--p2);
      margin: .75rem auto 1.5rem
    }

    .fd {
      display: flex;
      align-items: center;
      gap: 12px;
      justify-content: center;
      margin: 1.25rem auto
    }

    .fd::before,
    .fd::after {
      content: '';
      flex: 1;
      max-width: 60px;
      height: 1px;
      background: var(--p3)
    }

    .fd span {
      color: var(--p2);
      font-size: 1rem
    }

    /* COUPLE */
    .cpl-sec {
      background: var(--white)
    }

    .cpl-grid {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 2rem;
      align-items: center;
      margin: 2.5rem 0
    }

    .cpl-av {
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
      border: 4px solid var(--p3)
    }

    .cpl-card h3 {
      font-family: 'Great Vibes', cursive;
      font-size: 1.8rem;
      color: var(--dark);
      margin-bottom: 4px
    }

    .cpl-card p {
      font-size: 13px;
      color: var(--gray);
      line-height: 1.7;
      font-weight: 300
    }

    .amp-s {
      font-family: 'Great Vibes', cursive;
      font-size: 4rem;
      color: var(--p2)
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
      margin-top: 1.5rem
    }

    .ayat-box cite {
      font-size: .9rem;
      display: block;
      margin-top: .5rem;
      color: var(--p1)
    }

    /* COUNTDOWN */
    .cd-sec {
      background: linear-gradient(135deg, #3d1520, var(--p1))
    }

    .cd-sec .st {
      color: var(--p3)
    }

    .cd-sec .jl {
      color: rgba(255, 255, 255, .45)
    }

    .cd-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      max-width: 360px;
      margin: 2rem auto 0
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
      justify-content: center
    }

    .cd-num {
      font-size: 1.5rem;
      font-weight: 500;
      color: #fff;
      line-height: 1
    }

    .cd-lbl {
      font-size: 9px;
      color: rgba(255, 255, 255, .5);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-top: 2px
    }

    /* DETAIL */
    .det-sec {
      background: var(--cream)
    }

    .d-card {
      background: var(--white);
      border-radius: 16px;
      padding: 2rem;
      border: 1px solid var(--p3);
      margin-bottom: 1.25rem;
      text-align: left;
      position: relative;
      overflow: hidden
    }

    .d-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 80px;
      height: 80px;
      background: radial-gradient(circle, var(--p4) 0%, transparent 70%)
    }

    .d-icon-wrap {
      font-size: 2rem;
      margin-bottom: .75rem;
      font-size: 2.7rem;
      color : var(--p1);
    }

    .d-card h3 {
      font-family: 'Great Vibes', cursive;
      font-size: 1.6rem;
      color: var(--p1);
      margin-bottom: .75rem
    }

    .d-row {
      display: flex;
      gap: 8px;
      font-size: 14px;
      color: var(--gray);
      margin-bottom: .5rem;
      font-weight: 300;
      align-items: flex-start
    }

    .d-row strong {
      color: var(--dark);
      min-width: 80px;
      font-weight: 500
    }

    .d-row i {
      color: var(--p1);
      font-size: 16px;
      flex-shrink: 0;
      margin-top: 1px
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
      transition: background .2s
    }

    .btn-maps:hover {
      background: #b85570
    }

    /* GALERI */
    .gal-sec {
      background: var(--p4)
    }

    .gal-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 2rem;
      max-width: 680px;
      margin-left: auto;
      margin-right: auto
    }

    .gal-grid .gf:first-child {
      grid-column: span 2;
      grid-row: span 2
    }

    .gf {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      aspect-ratio: 1;
      background: var(--p3);
      cursor: pointer
    }

    .gf:first-child {
      aspect-ratio: auto;
      min-height: 200px
    }

    .gf img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .4s
    }

    .gf:hover img {
      transform: scale(1.05)
    }

    .gf-cap {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(61, 32, 48, .7));
      padding: 1.5rem .75rem .75rem;
      opacity: 0;
      transition: opacity .3s
    }

    .gf:hover .gf-cap {
      opacity: 1
    }

    .gf-cap span {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 12px;
      color: rgba(255, 255, 255, .9)
    }

    .lb {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .92);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 1rem
    }

    .lb.show {
      display: flex
    }

    .lb img {
      max-width: 90vw;
      max-height: 80vh;
      border-radius: 10px;
      object-fit: contain
    }

    .lb-cap {
      color: rgba(255, 255, 255, .7);
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 14px;
      margin-top: 1rem;
      text-align: center
    }

    .lb-ctr {
      position: fixed;
      top: 1rem;
      left: 50%;
      transform: translateX(-50%);
      color: rgba(255, 255, 255, .5);
      font-size: 13px
    }

    .lb-close {
      position: fixed;
      top: 1rem;
      right: 1rem;
      background: rgba(255, 255, 255, .15);
      color: #fff;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 22px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .lb-nav {
      position: fixed;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, .15);
      color: #fff;
      border: none;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      font-size: 24px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s
    }

    .lb-nav:hover {
      background: rgba(255, 255, 255, .3)
    }

    .lb-prev {
      left: 1rem
    }

    .lb-next {
      right: 1rem
    }

    /* RSVP */
    .rsvp-sec {
      background: var(--white)
    }

    .rsvp-form {
      max-width: 420px;
      margin: 2rem auto 0;
      text-align: left
    }

    .rf {
      margin-bottom: 1rem
    }

    .rf label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--gray);
      margin-bottom: 5px
    }

    .rf input,
    .rf select {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid var(--p3);
      border-radius: 50px;
      font-size: 14px;
      font-family: inherit;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color .2s
    }

    .rf input:focus,
    .rf select:focus {
      border-color: var(--p1)
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
      letter-spacing: .05em
    }

    .btn-rsvp:hover {
      background: #b85570
    }

    .rsvp-ok {
      display: none;
      text-align: center;
      padding: 2rem
    }

    .rsvp-ok .heart {
      font-size: 3rem;
      animation: pulse 1s infinite;
      color: var(--p1)
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

    /* PENUTUP */
    .cls-sec {
      background: linear-gradient(160deg, #3d1520, var(--p1), var(--p2));
      text-align: center;
      padding: 6rem 1.5rem
    }

    .cls-sec h2 {
      font-family: 'Great Vibes', cursive;
      font-size: clamp(2rem, 6vw, 4rem);
      color: #fff;
      margin-bottom: 1rem
    }

    .cls-sec p {
      font-family: 'Jost', sans-serif;
      font-weight: 300;
      font-size: 15px;
      color: rgba(255, 255, 255, .7);
      line-height: 1.9;
      max-width: 440px;
      margin: 0 auto 1rem
    }

    .cls-brand {
      font-size: 11px;
      color: rgba(255, 255, 255, .3);
      letter-spacing: .1em;
      margin-top: 3rem
    }

    .cls-brand span {
      color: var(--p3)
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
      transition: transform .2s
    }

    .music-btn:hover {
      transform: scale(1.1)
    }

    @media(max-width:500px) {
      .cpl-grid {
        grid-template-columns: 1fr
      }

      .amp-s {
        display: none
      }

      .cd-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .gal-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .gal-grid .gf:first-child {
        grid-column: span 2
      }
    }
  </style>
</head>

<body>

  <div class="cover" id="cover">
    <span class="petal p1"><i class='bx bxs-florist'></i></span><span class="petal p2"><i class='bx bx-leaf' ></i></span>
    <span class="petal p3"><i class='bx bx-leaf' ></i></span><span class="petal p4"><i class='bx bxs-florist'></i></span>
    <div class="c-script">The Wedding Of</div>
    <div class="c-name"><?= htmlspecialchars($pria) ?><span class="c-and">&amp; <?= htmlspecialchars($wanita) ?></span></div>
    <div class="c-date"><?= $tgl_full ?></div>
    <div class="c-to">Kepada Yth.</div>
    <div class="c-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="buka()"><i class='bx bx-envelope'></i> Buka Undangan</button>
  </div>

  <div class="content" id="cnt">

    <section class="cpl-sec">
      <div class="sec-inner">
        <div class="st">Bismillahirrohmanirrohim</div>
        <div class="jl">Dengan Memohon Rahmat Allah SWT</div>
        <div class="pl"></div>
        <div class="cpl-grid">
          <div class="cpl-card">
            <div class="cpl-av"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= htmlspecialchars($pria) ?></h3>
            <p>Putra dari<br><?= $ayah_pria ? htmlspecialchars($ayah_pria) : 'Bpk.' ?><?php if ($ibu_pria): ?> &amp; <?= htmlspecialchars($ibu_pria) ?><?php endif ?></p>
          </div>
          <div class="amp-s">&amp;</div>
          <div class="cpl-card">
            <div class="cpl-av"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= htmlspecialchars($wanita) ?></h3>
            <p>Putri dari<br><?= $ayah_wanita ? htmlspecialchars($ayah_wanita) : 'Bpk.' ?><?php if ($ibu_wanita): ?> &amp; <?= htmlspecialchars($ibu_wanita) ?><?php endif ?></p>
          </div>
        </div>
        <div class="ayat-box">
          "Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya."
          <cite>— QS. Ar-Rum: 21</cite>
        </div>
      </div>
    </section>

    <section class="cd-sec">
      <div class="sec-inner">
        <div class="st">Menuju Hari Bahagia</div>
        <div class="jl" style="color:rgba(255,255,255,.45)"><?= $tgl_full ?></div>
        <div class="cd-grid">
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cdh">00</div>
              <div class="cd-lbl">Hari</div>
            </div>
          </div>
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cdj">00</div>
              <div class="cd-lbl">Jam</div>
            </div>
          </div>
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cdm">00</div>
              <div class="cd-lbl">Menit</div>
            </div>
          </div>
          <div>
            <div class="cd-box">
              <div class="cd-num" id="cds">00</div>
              <div class="cd-lbl">Detik</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="det-sec">
      <div class="sec-inner">
        <div class="st">Acara</div>
        <div class="jl">Informasi Pernikahan</div>
        <div class="d-card">
          <div class="d-icon-wrap"><i class='bx bx-home-heart' ></i></div>
          <h3>Akad Nikah</h3>
          <div class="d-row"><i class='bx bx-calendar'></i><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="d-row"><i class='bx bx-time'></i><strong>Waktu</strong><?= $ma ?> – <?= $sa ?> WIB</div>
          <div class="d-row"><i class='bx bx-map'></i><strong>Lokasi</strong><?= htmlspecialchars($lokasi) ?></div>
          <?php if ($maps): ?><a href="<?= htmlspecialchars($maps) ?>" target="_blank" class="btn-maps"><i class='bx bx-map-pin'></i> Lihat Maps</a><?php endif ?>
        </div>
        <div class="d-card">
          <div class="d-icon-wrap"><i class='bx bxs-party' ></i></div>
          <h3>Resepsi</h3>
          <div class="d-row"><i class='bx bx-calendar'></i><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="d-row"><i class='bx bx-time'></i><strong>Waktu</strong><?= $mr ?> – <?= $sr ?> WIB</div>
          <div class="d-row"><i class='bx bx-map'></i><strong>Lokasi</strong><?= htmlspecialchars($lokasi) ?></div>
          <?php if ($maps): ?><a href="<?= htmlspecialchars($maps) ?>" target="_blank" class="btn-maps"><i class='bx bx-map-pin'></i> Lihat Maps</a><?php endif ?>
        </div>
      </div>
    </section>

    <?php if ($ada_galeri): ?>
      <section class="gal-sec">
        <div class="sec-inner">
          <div class="st">Galeri</div>
          <div class="jl">Momen Berharga Kami</div>
          <div class="fd"><span>🌸</span></div>
          <div class="gal-grid">
            <?php foreach ($galeri_fotos as $idx => $gf): ?>
              <div class="gf" onclick="lbBuka(<?= $idx ?>)">
                <img src="../<?= htmlspecialchars($gf['path_file']) ?>" alt="Foto <?= $idx + 1 ?>" loading="lazy" />
                <?php if ($gf['caption']): ?><div class="gf-cap"><span><?= htmlspecialchars($gf['caption']) ?></span></div><?php endif ?>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </section>
    <?php endif ?>

    <section class="rsvp-sec">
      <div class="sec-inner">
        <div class="st">Konfirmasi</div>
        <div class="jl">Kehadiran Kamu</div>
        <div class="rsvp-form" id="rsvpForm">
          <div class="rf"><label>Nama</label><input type="text" id="rNama" value="<?= htmlspecialchars($tamu) ?>" /></div>
          <div class="rf"><label>Jumlah Tamu</label>
            <select id="rJml">
              <option>1 orang</option>
              <option>2 orang</option>
              <option>3 orang</option>
              <option>4+ orang</option>
            </select>
          </div>
          <div class="rf"><label>Kehadiran</label>
            <select id="rHadir">
              <option value="hadir">✔️ Insya Allah Hadir</option>
              <option value="tidak">❌ Berhalangan Hadir</option>
            </select>
          </div>
          <button class="btn-rsvp" onclick="kirimRSVP()"><i class='bx bx-send'></i> Kirim Konfirmasi</button>
        </div>
        <div class="rsvp-ok" id="rsvpOk">
          <div class="heart"><i class='bx bxs-heart'></i></div>
          <p style="font-family:'Great Vibes',cursive;font-size:1.8rem;color:var(--p1)">Terima kasih!</p>
          <p style="font-size:14px;color:var(--gray);margin-top:4px">Konfirmasi kehadiranmu sudah diterima.</p>
        </div>
      </div>
    </section>

    <section class="cls-sec">
      <div class="sec-inner">
        <div class="fd" style="margin-bottom:1.5rem"><span><i class='bx bx-heart-circle' ></i> <i class='bx bxs-florist'></i> <i class='bx bx-heart-circle' ></i></span></div>
        <h2>Sampai Jumpa di Hari Bahagia Kami</h2>
        <p>Doa dan kehadiran Bapak/Ibu/Saudara/i adalah hadiah terindah untuk kami.</p>
        <p style="font-family:'Great Vibes',cursive;font-size:2rem;color:rgba(255,255,255,.8)"><?= htmlspecialchars($pria) ?> &amp; <?= htmlspecialchars($wanita) ?></p>
        <div class="cls-brand">Dibuat dengan ♥ oleh <span>Bernada.ID</span></div>
      </div>
    </section>

  </div>

  <?php if ($ada_galeri): ?>
    <div class="lb" id="lb" onclick="lbTutup(event)">
      <span class="lb-ctr" id="lbCtr"></span>
      <img id="lbImg" src="" alt="" />
      <div class="lb-cap" id="lbCap"></div>
      <button class="lb-nav lb-prev" onclick="lbNav(-1);event.stopPropagation()">‹</button>
      <button class="lb-nav lb-next" onclick="lbNav(1);event.stopPropagation()">›</button>
      <button class="lb-close" onclick="lbTutup()">×</button>
    </div>
    <script>
      const lbData = <?= json_encode(array_map(fn($f) => ['src' => '../' . $f['path_file'], 'cap' => $f['caption'] ?? ''], $galeri_fotos)) ?>;
      let lbI = 0;

      function lbBuka(i) {
        lbI = i;
        lbShow();
        document.getElementById('lb').classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      function lbShow() {
        const f = lbData[lbI];
        document.getElementById('lbImg').src = f.src;
        document.getElementById('lbCap').textContent = f.cap;
        document.getElementById('lbCtr').textContent = (lbI + 1) + ' / ' + lbData.length;
      }

      function lbNav(d) {
        lbI = (lbI + d + lbData.length) % lbData.length;
        lbShow();
      }

      function lbTutup(e) {
        if (e && e.target !== document.getElementById('lb') && !e.target.classList.contains('lb-close')) return;
        document.getElementById('lb').classList.remove('show');
        document.body.style.overflow = '';
      }
      document.addEventListener('keydown', e => {
        if (!document.getElementById('lb').classList.contains('show')) return;
        if (e.key === 'ArrowLeft') lbNav(-1);
        if (e.key === 'ArrowRight') lbNav(1);
        if (e.key === 'Escape') lbTutup({
          target: document.getElementById('lb')
        });
      });
    </script>
  <?php endif ?>

  <button class="music-btn" id="mBtn" onclick="togMusic()"><i class='bx bx-music'></i></button>
  <audio id="bgMusic" loop>
    <source src="../audio/wedding-music.mp3" type="audio/mpeg" />
  </audio>
  <script>
    function buka() {
      document.getElementById('cover').style.display = 'none';
      document.getElementById('cnt').classList.add('show');
      document.getElementById('bgMusic').play().catch(() => {});
    }
    let mPlay = false;

    function togMusic() {
      const m = document.getElementById('bgMusic'),
        b = document.getElementById('mBtn');
      if (mPlay) {
        m.pause();
        b.innerHTML = '<i class="bx bx-music"></i>';
        mPlay = false;
      } else {
        m.play().catch(() => {});
        b.innerHTML = '<i class="bx bx-pause"></i>';
        mPlay = true;
      }
    }

    function cd() {
      const d = new Date('<?= $tgl_countdown ?>').getTime() - Date.now();
      if (d <= 0) return;
      document.getElementById('cdh').textContent = String(Math.floor(d / 864e5)).padStart(2, '0');
      document.getElementById('cdj').textContent = String(Math.floor(d % 864e5 / 36e5)).padStart(2, '0');
      document.getElementById('cdm').textContent = String(Math.floor(d % 36e5 / 6e4)).padStart(2, '0');
      document.getElementById('cds').textContent = String(Math.floor(d % 6e4 / 1e3)).padStart(2, '0');
    }
    setInterval(cd, 1000);
    cd();

    function kirimRSVP() {
      const n = document.getElementById('rNama').value.trim();
      if (!n) {
        alert('Mohon isi nama!');
        return;
      }
      const j = document.getElementById('rJml').value;
      const h = document.getElementById('rHadir');
      const s = h.options[h.selectedIndex].text.replace(/^[^\s]+\s+/, '');
      const p = encodeURIComponent(`Halo! Saya ${n} konfirmasi kehadiran di pernikahan <?= addslashes($pria) ?> & <?= addslashes($wanita) ?>.\nKehadiran: ${s}\nJumlah: ${j}`);
      window.open(`https://wa.me/6281939195110?text=${p}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpOk').style.display = 'block';
    }
  </script>
</body>

</html>