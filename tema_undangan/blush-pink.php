<?php
$kode = $_GET['kode'] ?? '';
$tamu = $_GET['to']   ?? 'Tamu Undangan';
$data = [];
if ($kode) {
  require_once '../config/koneksi.php';
  $stmt = $pdo->prepare("SELECT * FROM undangan WHERE kode_undangan = ?");
  $stmt->execute([$kode]);
  $data = $stmt->fetch() ?: [];
}
$pria    = $data['nama_pria']     ?? 'Daffa';
$wanita  = $data['nama_wanita']   ?? 'Rania';
$tgl_raw = $data['tanggal_nikah'] ?? '2026-08-08';
$wm      = $data['waktu_mulai']   ?? '10:00';
$ws      = $data['waktu_selesai'] ?? '14:00';
$lokasi  = $data['lokasi']        ?? 'The Westin Surabaya, Jawa Timur';
$maps    = $data['link_maps']     ?? 'https://maps.google.com';
$tgl_obj = new DateTime($tgl_raw);
$bulan_id = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$hari_id = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$tgl_full = $hari_id[(int)$tgl_obj->format('w')] . ', ' . $tgl_obj->format('j') . ' ' . $bulan_id[(int)$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y');
$tgl_countdown = $tgl_obj->format('Y-m-d') . 'T' . $wm . ':00';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Undangan Pernikahan <?= $pria ?> & <?= $wanita ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Jost:ital,wght@0,300;0,400;0,500;1,300&family=Cormorant+Garamond:ital,wght@0,300;1,300;1,400&display=swap" rel="stylesheet" />
  <style>
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
    }
  </style>
</head>

<body>

  <div class="cover" id="cover">
    <span class="petal petal-1">🌸</span>
    <span class="petal petal-2">🌷</span>
    <span class="petal petal-3">🌺</span>
    <span class="petal petal-4">🌸</span>
    <div class="cover-script">The Wedding Of</div>
    <div class="cover-name">
      <?= $pria ?>
      <span class="cover-and">&amp; <?= $wanita ?></span>
    </div>
    <div class="cover-date"><?= $tgl_full ?></div>
    <div class="cover-to">Kepada Yth.</div>
    <div class="cover-guest"><?= htmlspecialchars($tamu) ?></div>
    <button class="btn-open" onclick="bukaUndangan()">✉ Buka Undangan</button>
  </div>

  <div class="content" id="mainContent">

    <section class="couple-section">
      <div class="sec-inner">
        <div class="script-title">Bismillah</div>
        <div class="jost-label">Dengan Memohon Rahmat Allah SWT</div>
        <div class="pink-line"></div>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= $pria ?></h3>
            <p>Putra dari<br>Bpk. &amp; Ibu</p>
          </div>
          <div class="amp-script">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= $wanita ?></h3>
            <p>Putri dari<br>Bpk. &amp; Ibu</p>
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

    <section class="detail-section">
      <div class="sec-inner">
        <div class="script-title">Acara</div>
        <div class="jost-label">Informasi Pernikahan</div>
        <div class="detail-card">
          <div class="detail-icon">💍</div>
          <h3>Akad Nikah</h3>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $wm ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps">📍 Lihat Maps</a>
        </div>
        <div class="detail-card">
          <div class="detail-icon">🎊</div>
          <h3>Resepsi</h3>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $wm ?> – <?= $ws ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps">📍 Lihat Maps</a>
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
              <option value="hadir">✅ Insya Allah Hadir</option>
              <option value="tidak">❌ Berhalangan Hadir</option>
            </select>
          </div>
          <button class="btn-rsvp" onclick="kirimRSVP()">💌 Kirim Konfirmasi</button>
        </div>
        <div class="rsvp-success" id="rsvpSuccess">
          <div class="heart">💕</div>
          <p style="font-family:'Great Vibes',cursive;font-size:1.8rem;color:var(--p1)">Terima kasih!</p>
          <p style="font-size:14px;color:var(--gray);margin-top:4px">Konfirmasi kehadiranmu sudah diterima.</p>
        </div>
      </div>
    </section>

    <section class="closing-section">
      <div class="sec-inner">
        <div class="floral-divider" style="color:rgba(255,255,255,.3)">🌸 🌷 🌸</div>
        <h2>Sampai Jumpa di Hari Bahagia Kami</h2>
        <p>Doa dan kehadiran Bapak/Ibu/Saudara/i adalah hadiah terindah untuk kami.</p>
        <p style="font-family:'Great Vibes',cursive;font-size:2rem;color:rgba(255,255,255,.8)"><?= $pria ?> &amp; <?= $wanita ?></p>
        <div class="closing-brand">Dibuat dengan ♥ oleh <span>Bernada.ID</span></div>
      </div>
    </section>

  </div>

  <button class="music-btn" id="musicBtn" onclick="toggleMusic()">🎵</button>
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
        btn.textContent = '🎵';
        playing = false;
      } else {
        m.play().catch(() => {});
        btn.textContent = '⏸';
        playing = true;
      }
    }

    function updateCountdown() {
      const diff = new Date('<?= $tgl_countdown ?>').getTime() - new Date().getTime();
      if (diff <= 0) return;
      const h = Math.floor(diff / (864e5)),
        j = Math.floor(diff % 864e5 / 36e5),
        m = Math.floor(diff % 36e5 / 6e4),
        s = Math.floor(diff % 6e4 / 1e3);
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
      const hadir = document.getElementById('rsvpHadir').value === 'hadir' ? 'Insya Allah Hadir' : 'Berhalangan Hadir';
      const p = encodeURIComponent(`Halo! Saya ${nama} ingin konfirmasi kehadiran di pernikahan <?= $pria ?> & <?= $wanita ?>.\nKehadiran: ${hadir}\nJumlah: ${jml}`);
      window.open(`https://wa.me/6281939195110?text=${p}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpSuccess').style.display = 'block';
    }
  </script>
</body>

</html>