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
$pria    = $data['nama_pria']     ?? 'Rizal';
$wanita  = $data['nama_wanita']   ?? 'Hana';
$tgl_raw = $data['tanggal_nikah'] ?? '2026-07-20';
$wm      = $data['waktu_mulai']   ?? '09:00';
$ws      = $data['waktu_selesai'] ?? '13:00';
$lokasi  = $data['lokasi']        ?? 'Ballroom Hotel Majapahit, Surabaya';
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
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <style>
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
    <button class="btn-open" onclick="bukaUndangan()">✉ Buka Undangan</button>
  </div>

  <div class="content" id="mainContent">

    <section class="couple-section">
      <div class="sec-inner">
        <div class="cinzel-title">Bismillahirrahmanirrahim</div>
        <div class="gold-line"></div>
        <p class="sec-sub">Dengan memohon rahmat dan ridho Allah SWT</p>
        <div class="couple-grid">
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($pria, 0, 1)) ?></div>
            <h3><?= strtoupper($pria) ?></h3>
            <p>Putra dari<br>Bpk. &amp; Ibu</p>
          </div>
          <div class="amp-cinzel">&amp;</div>
          <div class="couple-card">
            <div class="couple-avatar"><?= strtoupper(substr($wanita, 0, 1)) ?></div>
            <h3><?= strtoupper($wanita) ?></h3>
            <p>Putri dari<br>Bpk. &amp; Ibu</p>
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

    <section class="detail-section">
      <div class="sec-inner">
        <div class="cinzel-title">Informasi Acara</div>
        <div class="gold-line"></div>
        <div class="detail-card">
          <h3>AKAD NIKAH</h3>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $wm ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps">📍 Google Maps</a>
        </div>
        <div class="detail-card">
          <h3>RESEPSI PERNIKAHAN</h3>
          <div class="detail-row"><strong>Tanggal</strong><?= $tgl_full ?></div>
          <div class="detail-row"><strong>Waktu</strong><?= $wm ?> – <?= $ws ?> WIB</div>
          <div class="detail-row"><strong>Lokasi</strong><?= $lokasi ?></div>
          <a href="<?= $maps ?>" target="_blank" class="btn-maps">📍 Google Maps</a>
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
              <option value="hadir">Insya Allah Hadir</option>
              <option value="tidak">Berhalangan Hadir</option>
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
      const p = encodeURIComponent(`Halo! Saya ${nama} konfirmasi kehadiran di pernikahan <?= $pria ?> & <?= $wanita ?>.\nKehadiran: ${hadir}\nJumlah: ${jml}`);
      window.open(`https://wa.me/6281939195110?text=${p}`, '_blank');
      document.getElementById('rsvpForm').style.display = 'none';
      document.getElementById('rsvpSuccess').style.display = 'block';
    }
  </script>
</body>

</html>