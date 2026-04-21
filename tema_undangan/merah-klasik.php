<?php
// ══════════════════════════════════════
// TEMA: Merah Klasik
// URL: /undangan/merah-klasik.php?kode=BRN-XXXXXX
// Atau preview: /undangan/merah-klasik.php (pakai data default)
// ══════════════════════════════════════

// Ambil data dari database jika ada kode
$data = [];
$kode = $_GET['kode'] ?? '';
$tamu = $_GET['to'] ?? 'Tamu Undangan';

if ($kode) {
  require_once '../config/koneksi.php';
  $stmt = $pdo->prepare("SELECT * FROM undangan WHERE kode_undangan = ?");
  $stmt->execute([$kode]);
  $data = $stmt->fetch() ?: [];
}

// Fallback ke data default (untuk preview / static)
$pria    = $data['nama_pria']     ?? 'Surya';
$wanita  = $data['nama_wanita']   ?? 'Sofi';
$tgl_raw = $data['tanggal_nikah'] ?? '2026-06-14';
$wm      = $data['waktu_mulai']   ?? '10:00';
$ws      = $data['waktu_selesai'] ?? '14:00';
$lokasi  = $data['lokasi']        ?? 'Gedung Graha Sabha Permana, Surabaya';
$maps    = $data['link_maps']     ?? 'https://maps.google.com';

// Format tanggal
$tgl_obj  = new DateTime($tgl_raw);
$bulan_id = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$hari_id  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$tgl_full = $hari_id[(int)$tgl_obj->format('w')] . ', ' . $tgl_obj->format('j') . ' ' . $bulan_id[(int)$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y');
$tgl_countdown = $tgl_obj->format('Y-m-d') . 'T' . $wm . ':00';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Undangan Pernikahan <?= $pria ?> & <?= $wanita ?></title>
  <meta property="og:title" content="Undangan Pernikahan <?= $pria ?> & <?= $wanita ?>" />
  <meta property="og:description" content="<?= $tgl_full ?> · <?= $lokasi ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <style>
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
      overflow: hidden;
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
      cursor: pointer;
      border: none;
      font-family: inherit;
      z-index: 100;
    }

    .btn-open:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(201, 162, 39, .4);
    }

    .scroll-hint {
      position: absolute;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%);
      color: rgba(255, 255, 255, .4);
      font-size: 12px;
      letter-spacing: .1em;
      text-transform: uppercase;
      animation: bounce 2s infinite;
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateX(-50%) translateY(0)
      }

      50% {
        transform: translateX(-50%) translateY(8px)
      }
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

    .couple-names {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 7vw, 4rem);
      line-height: 1.2;
      margin-bottom: .5rem;
      color: var(--dark);
    }

    .couple-names em {
      color: var(--r2);
      font-style: italic;
    }

    .couple-names .amp {
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: .6em;
      color: var(--gold);
      display: block;
      margin: .25rem 0;
    }

    .couple-sub {
      font-size: 14px;
      color: #888;
      line-height: 1.8;
    }

    .couple-grid {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 1.5rem;
      align-items: center;
      margin: 2rem 0;
    }

    .couple-card {
      text-align: center;
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

    .couple-card p {
      font-size: 13px;
      color: #888;
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

    /* DETAIL ACARA */
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
      border-radius: 10px;
      background: linear-gradient(135deg, var(--r1), var(--r2));
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
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
      font-weight: 600;
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

    .rsvp-success .big-check {
      font-size: 48px;
      margin-bottom: 8px;
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

    /* MUSIC BTN */
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
    }
  </style>
</head>

<body>

  <!-- COVER -->
  <div class="cover" id="cover">
    <div class="cover-ornament">The Wedding Of</div>
    <h1><?= $pria ?><em>& <?= $wanita ?></em></h1>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to">Kepada Yth.</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="bukaUndangan()">✉ Buka Undangan</button>
    <div class="scroll-hint">↓ scroll</div>
  </div>

  <!-- CONTENT -->
  <div class="content" id="mainContent">

    <!-- COUPLE -->
    <section class="couple-section">
      <div class="sec-inner">
        <div class="ornament">Bismillah</div>
        <div class="sec-label">Dengan Memohon Rahmat dan Ridho Allah SWT</div>
        <div class="divider">
          <div class="divider-diamond"></div>
        </div>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= $pria ?></h3>
            <p>Putra dari<br>Bpk. &amp; Ibu</p>
          </div>
          <div class="amp-big">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar" style="background:linear-gradient(135deg,#8a2020,#C0393B)"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= $wanita ?></h3>
            <p>Putri dari<br>Bpk. &amp; Ibu</p>
          </div>
        </div>
        <p class="couple-sub">Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan.<br><em style="color:#aaa">— QS. Yasin: 36</em></p>
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

    <!-- DETAIL -->
    <section class="detail-section">
      <div class="sec-inner">
        <div class="ornament">Acara</div>
        <div class="sec-label">Informasi Pernikahan</div>
        <div class="detail-card">
          <div class="detail-card-header">
            <div class="detail-icon">💍</div>
            <div>
              <h3>Akad Nikah</h3>
              <p>Prosesi sakral pernikahan</p>
            </div>
          </div>
          <div class="detail-row"><strong>Tanggal</strong> <?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong> <?= $wm ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong> <?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps">📍 Lihat di Google Maps</a>
        </div>
        <div class="detail-card">
          <div class="detail-card-header">
            <div class="detail-icon">🎊</div>
            <div>
              <h3>Resepsi Pernikahan</h3>
              <p>Syukuran & jamuan tamu</p>
            </div>
          </div>
          <div class="detail-row"><strong>Tanggal</strong> <?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong> <?= $wm ?> – <?= $ws ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong> <?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps">📍 Lihat di Google Maps</a>
        </div>
      </div>
    </section>

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
          <div class="rsvp-field">
            <label>Nama Lengkap</label>
            <input type="text" id="rsvpNama" placeholder="Nama kamu..." value="<?= htmlspecialchars($tamu) ?>" />
          </div>
          <div class="rsvp-field">
            <label>Jumlah Tamu</label>
            <select id="rsvpJml">
              <option value="1">1 orang</option>
              <option value="2">2 orang</option>
              <option value="3">3 orang</option>
              <option value="4">4 orang</option>
            </select>
          </div>
          <div class="rsvp-field">
            <label>Konfirmasi Kehadiran</label>
            <select id="rsvpHadir">
              <option value="hadir">✅ Insya Allah Hadir</option>
              <option value="tidak">❌ Berhalangan Hadir</option>
              <option value="mungkin">🤔 Mungkin Hadir</option>
            </select>
          </div>
          <button class="btn-rsvp" onclick="kirimRSVP()">Kirim Konfirmasi</button>
        </div>
        <div class="rsvp-success" id="rsvpSuccess">
          <div class="big-check">🎉</div>
          <p style="font-size:16px;font-weight:600">Terima kasih!</p>
          <p style="font-size:14px;color:#888">Konfirmasi kehadiranmu sudah kami terima.</p>
        </div>
      </div>
    </section>

    <!-- CLOSING -->
    <section class="closing-section">
      <div class="sec-inner">
        <div class="ornament" style="color:var(--gold)">♥</div>
        <h2>Merupakan suatu kehormatan dan kebahagiaan bagi kami</h2>
        <p>apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.</p>
        <p style="font-family:'Playfair Display',serif;font-style:italic;font-size:1.2rem;color:rgba(255,255,255,.8);margin-top:1rem">
          <?= $pria ?> &amp; <?= $wanita ?>
        </p>
        <div style="margin-top:3rem" class="closing-brand">
          Dibuat dengan ♥ oleh <span>Bernada.ID</span>
        </div>
      </div>
    </section>

  </div><!-- end content -->

  <button class="music-btn" id="musicBtn" onclick="toggleMusic()" title="Musik">🎵</button>
  <audio id="bgMusic" loop>
    <source src="../audio/wedding-music.mp3" type="audio/mpeg" />
  </audio>

  <script>
    // Buka undangan
    function bukaUndangan() {
      document.getElementById('cover').style.display = 'none';
      document.getElementById('mainContent').classList.add('show');
      // Auto play music
      const music = document.getElementById('bgMusic');
      music.play().catch(() => {});
    }

    // Music toggle
    let playing = false;

    function toggleMusic() {
      const m = document.getElementById('bgMusic');
      const btn = document.getElementById('musicBtn');
      if (playing) {
        m.pause();
        btn.textContent = '🎵';
        playing = false;
      } else {
        m.play().catch(() => {});
        btn.textContent = '⏸';
        playing = true;
      }
    }

    // Countdown
    function updateCountdown() {
      const target = new Date('<?= $tgl_countdown ?>').getTime();
      const now = new Date().getTime();
      const diff = target - now;
      if (diff <= 0) {
        document.getElementById('cd-h').textContent = '00';
        document.getElementById('cd-j').textContent = '00';
        document.getElementById('cd-m').textContent = '00';
        document.getElementById('cd-s').textContent = '00';
        return;
      }
      const h = Math.floor(diff / (1000 * 60 * 60 * 24));
      const j = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const s = Math.floor((diff % (1000 * 60)) / 1000);
      document.getElementById('cd-h').textContent = String(h).padStart(2, '0');
      document.getElementById('cd-j').textContent = String(j).padStart(2, '0');
      document.getElementById('cd-m').textContent = String(m).padStart(2, '0');
      document.getElementById('cd-s').textContent = String(s).padStart(2, '0');
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();

    // RSVP
    function kirimRSVP() {
      const nama = document.getElementById('rsvpNama').value.trim();
      const jml = document.getElementById('rsvpJml').value;
      const hadir = document.getElementById('rsvpHadir').value;
      if (!nama) {
        alert('Mohon isi nama kamu!');
        return;
      }
      // Kirim via WhatsApp
      const status = hadir === 'hadir' ? 'Insya Allah Hadir' : hadir === 'tidak' ? 'Berhalangan Hadir' : 'Mungkin Hadir';
      const pesan = encodeURIComponent(`Halo! Saya ${nama} ingin konfirmasi kehadiran di pernikahan <?= $pria ?> & <?= $wanita ?>.\n\nKehadiran: ${status}\nJumlah tamu: ${jml} orang`);
      window.open(`https://wa.me/6281939195110?text=${pesan}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpSuccess').style.display = 'block';
    }
  </script>
</body>

</html>