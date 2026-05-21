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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,300;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
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
      --r1: #7B1113;
      --r2: #C0393B;
      --gold: #c9a227;
      --cream: #FDF8F0;
      --dark: #1a1a1a
    }

    html {
      scroll-behavior: smooth
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--cream);
      color: var(--dark);
      overflow-x: hidden
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
      overflow: hidden
    }

    .cover::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")
    }

    .cover-orn {
      font-size: 13px;
      letter-spacing: .25em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 1.5rem;
      position: relative
    }

    .cover-orn::before,
    .cover-orn::after {
      content: '—';
      margin: 0 10px;
      opacity: .6
    }

    .cover h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(3rem, 10vw, 6rem);
      color: #fff;
      line-height: 1.1;
      margin-bottom: 1rem;
      position: relative
    }

    .cover h1 em {
      color: var(--gold);
      display: block;
      font-style: italic;
      font-size: .65em
    }

    .cover-date {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(1rem, 3vw, 1.4rem);
      color: rgba(255, 255, 255, .75);
      margin-bottom: 2rem;
      letter-spacing: .05em
    }

    .cover-to {
      font-size: 13px;
      color: rgba(255, 255, 255, .5);
      margin-bottom: .5rem
    }

    .cover-guest {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: clamp(1.2rem, 4vw, 1.8rem);
      color: #fff;
      margin-bottom: 2.5rem
    }

    .btn-open {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 14px 32px;
      background: var(--gold);
      color: var(--dark);
      border-radius: 50px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-family: inherit;
      transition: transform .2s, box-shadow .2s;
      z-index: 1;
    }

    .btn-open:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(201, 162, 39, .4)
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

    .orn {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: clamp(2.5rem, 6vw, 4rem);
      color: var(--r2);
      margin-bottom: .25rem
    }

    .sec-lbl {
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: #aaa;
      margin-bottom: 2rem
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      justify-content: center;
      margin: 1.5rem auto
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      max-width: 80px;
      height: 1px;
      background: #e0c8c8
    }

    .diamond {
      width: 8px;
      height: 8px;
      background: var(--r2);
      transform: rotate(45deg)
    }

    /* COUPLE */
    .couple-sec {
      background: #fff
    }

    .couple-grid {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 2rem;
      align-items: center;
      margin: 2.5rem 0
    }

    .c-avatar {
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
      box-shadow: 0 4px 20px rgba(192, 57, 59, .2)
    }

    .c-card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      margin-bottom: 4px
    }

    .c-card .ortu {
      font-size: 13px;
      color: #888;
      line-height: 1.8
    }

    .amp {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 3rem;
      color: var(--gold)
    }

    /* COUNTDOWN */
    .cd-sec {
      background: linear-gradient(135deg, var(--r1), var(--r2))
    }

    .cd-sec h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.3rem, 3vw, 1.8rem);
      color: #fff;
      margin-bottom: 2rem
    }

    .cd-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      max-width: 400px;
      margin: 0 auto
    }

    .cd-box {
      background: rgba(255, 255, 255, .1);
      border-radius: 12px;
      padding: 1.25rem .75rem
    }

    .cd-num {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      color: #fff;
      line-height: 1
    }

    .cd-lbl {
      font-size: 11px;
      color: rgba(255, 255, 255, .6);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-top: 4px
    }

    /* DETAIL */
    .detail-sec {
      background: var(--cream)
    }

    .d-card {
      background: #fff;
      border-radius: 16px;
      padding: 2rem;
      border: 1px solid #eedede;
      margin-bottom: 1.25rem;
      text-align: left
    }

    .d-head {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 1rem;
      padding-bottom: .75rem;
      border-bottom: 1px solid #f0e0e0
    }

    .d-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--r1), var(--r2));
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px
    }

    .d-head h3 {
      font-size: 16px;
      font-weight: 600
    }

    .d-head p {
      font-size: 12px;
      color: #aaa
    }

    .d-row {
      display: flex;
      gap: 8px;
      align-items: flex-start;
      margin-bottom: .6rem;
      font-size: 14px;
      color: #555
    }

    .d-row strong {
      color: var(--dark);
      min-width: 80px
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
      font-weight: 600;
      transition: background .2s
    }

    .btn-maps:hover {
      background: var(--r1)
    }

    /* ═══ GALERI FOTO ═══ */
    .galeri-sec {
      background: #fff
    }

    .galeri-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 2rem;
      max-width: 680px;
      margin-left: auto;
      margin-right: auto
    }

    .galeri-grid .gf:first-child {
      grid-column: span 2;
      grid-row: span 2
    }

    .gf {
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      aspect-ratio: 1;
      background: #f0ece8;
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
      background: linear-gradient(transparent, rgba(0, 0, 0, .6));
      padding: 1.5rem .75rem .75rem;
      opacity: 0;
      transition: opacity .3s
    }

    .gf:hover .gf-cap {
      opacity: 1
    }

    .gf-cap span {
      font-size: 12px;
      color: rgba(255, 255, 255, .9);
      font-style: italic
    }

    /* Lightbox */
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
      border-radius: 8px;
      object-fit: contain
    }

    .lb-cap {
      color: rgba(255, 255, 255, .7);
      font-size: 14px;
      font-style: italic;
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
      background: #fff
    }

    .rsvp-form {
      max-width: 440px;
      margin: 0 auto;
      text-align: left
    }

    .rf {
      margin-bottom: 1rem
    }

    .rf label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #444;
      margin-bottom: 5px
    }

    .rf input,
    .rf select {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #e0d0d0;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      color: var(--dark);
      background: #fafafa;
      outline: none;
      transition: border-color .2s
    }

    .rf input:focus,
    .rf select:focus {
      border-color: var(--r2)
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
      transition: background .2s
    }

    .btn-rsvp:hover {
      background: var(--r1)
    }

    .rsvp-ok {
      display: none;
      text-align: center;
      padding: 1.5rem
    }

    /* PENUTUP */
    .closing-sec {
      background: linear-gradient(160deg, #1a0505, var(--r1));
      text-align: center;
      padding: 6rem 1.5rem
    }

    .closing-sec h2 {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: clamp(1.5rem, 4vw, 2.5rem);
      color: #fff;
      margin-bottom: 1rem
    }

    .closing-sec p {
      font-size: 14px;
      color: rgba(255, 255, 255, .65);
      line-height: 1.9;
      max-width: 440px;
      margin: 0 auto 2rem
    }

    .closing-brand {
      font-size: 13px;
      color: rgba(255, 255, 255, .3);
      letter-spacing: .1em
    }

    .closing-brand span {
      color: var(--gold)
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
      transition: transform .2s
    }

    .music-btn:hover {
      transform: scale(1.1)
    }

    @media(max-width:500px) {
      .couple-grid {
        grid-template-columns: 1fr
      }

      .amp {
        display: none
      }

      .cd-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .galeri-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .galeri-grid .gf:first-child {
        grid-column: span 2
      }
    }
  </style>
</head>

<body>

  <!-- COVER -->
  <div class="cover" id="cover">
    <div class="cover-orn">The Wedding Of</div>
    <h1><?= htmlspecialchars($pria) ?><em>&amp; <?= htmlspecialchars($wanita) ?></em></h1>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to">Kepada Yth.</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="buka()">✉ Buka Undangan</button>
  </div>

  <!-- CONTENT -->
  <div class="content" id="cnt">

    <!-- COUPLE -->
    <section class="couple-sec">
      <div class="sec-inner">
        <div class="orn">Bismillahirrohmanirrohim</div>
        <div class="sec-lbl">Dengan Memohon Rahmat dan Ridho Allah SWT</div>
        <div class="divider">
          <div class="diamond"></div>
        </div>
        <div class="couple-grid">
          <div class="c-card">
            <div class="c-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= htmlspecialchars($pria) ?></h3>
            <div class="ortu">Putra dari<br>
              <?= $ayah_pria ? htmlspecialchars($ayah_pria) : 'Bpk.' ?>
              <?php if ($ibu_pria): ?>&amp; <?= htmlspecialchars($ibu_pria) ?><?php endif ?>
            </div>
          </div>
          <div class="amp">&amp;</div>
          <div class="c-card">
            <div class="c-avatar" style="background:linear-gradient(135deg,#8a2020,#C0393B)"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= htmlspecialchars($wanita) ?></h3>
            <div class="ortu">Putri dari<br>
              <?= $ayah_wanita ? htmlspecialchars($ayah_wanita) : 'Bpk.' ?>
              <?php if ($ibu_wanita): ?>&amp; <?= htmlspecialchars($ibu_wanita) ?><?php endif ?>
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
    <section class="cd-sec">
      <div class="sec-inner">
        <div class="orn" style="color:var(--gold)">◆</div>
        <div class="sec-lbl" style="color:rgba(255,255,255,.5)">Menuju Hari Bahagia</div>
        <h2><?= $tgl_full ?></h2>
        <div class="cd-grid">
          <div class="cd-box">
            <div class="cd-num" id="cdh">00</div>
            <div class="cd-lbl">Hari</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cdj">00</div>
            <div class="cd-lbl">Jam</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cdm">00</div>
            <div class="cd-lbl">Menit</div>
          </div>
          <div class="cd-box">
            <div class="cd-num" id="cds">00</div>
            <div class="cd-lbl">Detik</div>
          </div>
        </div>
      </div>
    </section>

    <!-- DETAIL ACARA -->
    <section class="detail-sec">
      <div class="sec-inner">
        <div class="orn">Acara</div>
        <div class="sec-lbl">Informasi Pernikahan</div>
        <div class="d-card">
          <div class="d-head">
            <div class="d-icon"><i class='bx bx-book-heart' ></i></div>
            <div>
              <h3>Akad Nikah</h3>
              <p>Prosesi sakral pernikahan</p>
            </div>
          </div>
          <div class="d-row"><strong>Tanggal : </strong><?= $tgl_full ?></div>
          <div class="d-row"><strong>Waktu : </strong><?= $ma ?> - <?= $sa ?> WIB</div>
          <div class="d-row"><strong>Lokasi : </strong><?= htmlspecialchars($lokasi) ?></div>
          <?php if ($maps): ?><a href="<?= htmlspecialchars($maps) ?>" target="_blank" class="btn-maps"><i class='bx bx-map' ></i> Lihat Google Maps</a><?php endif ?>
        </div>
        <div class="d-card">
          <div class="d-head">
            <div class="d-icon"><i class='bx bxs-florist'></i></div>
            <div>
              <h3>Resepsi Pernikahan</h3>
              <p>Syukuran &amp; jamuan tamu</p>
            </div>
          </div>
          <div class="d-row"><strong>Tanggal : </strong><?= $tgl_full ?></div>
          <div class="d-row"><strong>Waktu : </strong><?= $mr ?> – <?= $sr ?> WIB</div>
          <div class="d-row"><strong>Lokasi : </strong><?= htmlspecialchars($lokasi) ?></div>
          <?php if ($maps): ?><a href="<?= htmlspecialchars($maps) ?>" target="_blank" class="btn-maps"><i class='bx bx-map' ></i> Lihat Google Maps</a><?php endif ?>
        </div>
      </div>
    </section>

    <!-- ═══ GALERI FOTO (otomatis muncul kalau ada foto) ═══ -->
    <?php if ($ada_galeri): ?>
      <section class="galeri-sec">
        <div class="sec-inner">
          <div class="orn">Galeri</div>
          <div class="sec-lbl">Momen Berharga Kami</div>
          <div class="divider">
            <div class="diamond"></div>
          </div>
          <div class="galeri-grid">
            <?php foreach ($galeri_fotos as $idx => $gf): ?>
              <div class="gf" onclick="lbBuka(<?= $idx ?>)">
                <img src="../<?= htmlspecialchars($gf['path_file']) ?>" alt="Foto <?= $idx + 1 ?>" loading="lazy" />
                <?php if ($gf['caption']): ?>
                  <div class="gf-cap"><span><?= htmlspecialchars($gf['caption']) ?></span></div>
                <?php endif ?>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </section>
    <?php endif ?>

    <!-- RSVP -->
    <section class="rsvp-sec">
      <div class="sec-inner">
        <div class="orn">RSVP</div>
        <div class="sec-lbl">Konfirmasi Kehadiran</div>
        <div class="divider">
          <div class="diamond"></div>
        </div>
        <p style="font-size:14px;color:#888;margin-bottom:1.5rem">Mohon konfirmasi kehadiran paling lambat 7 hari sebelum acara</p>
        <div class="rsvp-form" id="rsvpForm">
          <div class="rf"><label>Nama Lengkap</label><input type="text" id="rNama" value="<?= htmlspecialchars($tamu) ?>" placeholder="Nama kamu..." /></div>
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
              <option value="mungkin">❗ Mungkin Hadir</option>
            </select>
          </div>
          <button class="btn-rsvp" onclick="kirimRSVP()">Kirim Konfirmasi</button>
        </div>
        <div class="rsvp-ok" id="rsvpOk">
          <div style="font-size:48px;margin-bottom:8px"><i class='bx bx-party' ></i></div>
          <p style="font-size:16px;font-weight:600">Terima kasih!</p>
          <p style="font-size:14px;color:#888">Konfirmasi kehadiranmu sudah kami terima.</p>
        </div>
      </div>
    </section>

    <!-- PENUTUP -->
    <section class="closing-sec">
      <div class="sec-inner">
        <div class="orn" style="color:var(--gold)">♥</div>
        <h2>Merupakan suatu kehormatan dan kebahagiaan bagi kami</h2>
        <p>apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.</p>
        <p style="font-family:'Playfair Display',serif;font-style:italic;font-size:1.2rem;color:rgba(255,255,255,.8);margin-top:1rem">
          <?= htmlspecialchars($pria) ?> &amp; <?= htmlspecialchars($wanita) ?>
        </p>
        <div style="margin-top:3rem" class="closing-brand">Dibuat dengan ♥ oleh <span>Bernada.ID</span></div>
      </div>
    </section>

  </div><!-- end content -->

  <!-- Lightbox -->
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