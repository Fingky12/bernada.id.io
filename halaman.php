<?php
session_start();
require_once 'config/koneksi.php';

$name        = $_SESSION['name'] ?? null;
$alerts      = $_SESSION['alerts'] ?? [];
$active_form = $_SESSION['active_form'] ?? '';

session_unset();
if ($name !== null) $_SESSION['name'] = $name;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/inc_footer_header.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="./favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./favicon_io/favicon-16x16.png">
  <link rel="apple-touch-icon" href="./favicon_io/apple-touch-icon.png">
  <link rel="icon" href="./favicon_io/favicon.ico">
  <title>Bernada.ID – Undangan Digital Modern</title>
  <style>
  </style>
</head>

<body>

  <?php include("./header/inc_header.php") ?>

  <!-- ALERT -->
  <?php if (!empty($alerts)): ?>
    <div class="alert-box" id="alertBox">
      <?php foreach ($alerts as $alert): ?>
        <div class="alert <?= $alert['type'] ?>">
          <i class='bx <?= $alert['type'] === 'success' ? 'bxs-check-circle' : 'bxs-error-circle' ?>'></i>
          <span><?= $alert['message'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- ══════════════════════════════════════
    BERANDA / HERO
    ══════════════════════════════════════ -->
  <section class="beranda" id="beranda">
    <div class="hero-left reveal-left">
      <div class="hero-eyebrow"><i class='bx bxs-crown'></i> Platform Undangan Digital #1</div>
      <h1 class="hero-title">BERNADA<span>.ID</span></h1>
      <p class="hero-sub">Kami membantu Anda membuat undangan digital berbasis website yang modern, responsif, dan mudah dibagikan ke siapa saja, kapan saja.</p>
      <div class="search-wrapper">
        <input type="text" placeholder="Cari tema undangan..." />
        <button class="search-btn"><i class='bx bx-search'></i></button>
      </div>
      <div class="hero-btns">
        <a href="buat-undangan.php" class="btn-primary"><i class='bx bx-plus'></i> Buat Undangan Sekarang</a>
        <a href="#tema" class="btn-secondary"><i class='bx bx-palette'></i> Lihat Tema</a>
      </div>
      <div class="hero-stats">
        <div class="stat-item">
          <div class="num">500+</div>
          <div class="label">Undangan dibuat</div>
        </div>
        <div class="stat-item">
          <div class="num">6+</div>
          <div class="label">Tema tersedia</div>
        </div>
        <div class="stat-item">
          <div class="num">4.9★</div>
          <div class="label">Rating pengguna</div>
        </div>
      </div>
    </div>
    <div class="hero-right reveal-right">
      <img src="./img/Brand1.png" alt="Bernada.ID Preview" />
    </div>
  </section>

  <!-- ══════════════════════════════════════
    FITUR
    ══════════════════════════════════════ -->
  <section class="fitur" id="fitur">
    <div class="sec-header">
      <div class="sec-tag">Fitur Unggulan</div>
      <h2>Semua yang Kamu Butuhkan</h2>
      <p>Solusi lengkap untuk membuat undangan digital yang kekinian, simple, dan elegan</p>
    </div>
    <div class="fitur-grid reveal">
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bx-globe'></i></div>
        <h3>Domain Eksklusif</h3>
        <p>Gunakan subdomain unik atau custom domain sesuai kebutuhanmu.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-user-detail'></i></div>
        <h3>Custom Nama Tamu</h3>
        <p>Satu tamu, satu undangan personal dengan nama otomatis tampil.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-edit-alt'></i></div>
        <h3>Full Custom Teks</h3>
        <p>Semua teks dapat diubah sesuai konsep acaramu.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-bolt-circle'></i></div>
        <h3>Share Instan</h3>
        <p>Bagikan undangan ke WhatsApp hanya dengan satu klik.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-image-add'></i></div>
        <h3>Konten Lengkap</h3>
        <p>Dukung teks, gambar, video, Google Maps & musik latar.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-user-pin'></i></div>
        <h3>Manajemen Tamu</h3>
        <p>Kelola daftar tamu, grup, dan pencarian dengan mudah.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-message-rounded-dots'></i></div>
        <h3>RSVP & Buku Tamu</h3>
        <p>Konfirmasi kehadiran dan kirim ucapan secara online.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-gift'></i></div>
        <h3>Amplop Digital</h3>
        <p>Kirim hadiah atau transfer langsung via rekening / QRIS.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-timer'></i></div>
        <h3>Countdown Timer</h3>
        <p>Tampilkan countdown timer menuju hari spesialmu.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bx-credit-card'></i></div>
        <h3>Pembayaran Mudah</h3>
        <p>Metode pembayaran lengkap — transfer, e-wallet, QRIS.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bxs-quote-right'></i></div>
        <h3>Story & Quote</h3>
        <p>Ceritakan perjalanan cintamu dengan cerita dan kutipan indah.</p>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon"><i class='bx bx-category'></i></div>
        <h3>Tema Instan</h3>
        <p>Berbagai tema siap pakai yang bisa diganti kapan saja tanpa batas.</p>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════
    TEMA UNDANGAN
    ══════════════════════════════════════ -->
  <section class="tema" id="tema">
    <div class="sec-header">
      <div class="sec-tag">Tema Undangan</div>
      <h2>Pilih Tema yang Paling Cocok</h2>
      <p>Temukan tema yang sesuai dengan konsep pernikahanmu — dari elegan, maskulin, hingga romantis</p>
    </div>
    <div class="tema-grid reveal">

      <!-- Tema 1: Merah Klasik -->
      <a href="undangan/merah-klasik.php" target="_blank" class="tema-card">
        <div class="tema-card-preview">
          <div class="tema-preview-merah">
            <div class="preview-name">Surya & Sofi</div>
            <div class="preview-sub preview-gold">The Wedding Of</div>
            <div style="margin-top:1rem;font-size:11px;letter-spacing:.1em;opacity:.5;text-transform:uppercase">Merah Klasik</div>
          </div>
          <div class="tema-hover-overlay">
            <span><i class='bx bx-show' style="font-size:16px"></i> Lihat Preview</span>
          </div>
        </div>
        <div class="tema-info">
          <h3>Merah Klasik</h3>
          <span class="badge baru">Baru</span>
        </div>
      </a>

      <!-- Tema 2: Navy Elegant -->
      <a href="undangan/navy-elegant.php" target="_blank" class="tema-card">
        <div class="tema-card-preview">
          <div class="tema-preview-navy">
            <div class="preview-name" style="font-family:'Playfair Display',serif;letter-spacing:.05em">Rizal & Hana</div>
            <div class="preview-sub preview-gold">THE WEDDING OF</div>
            <div style="margin-top:1rem;font-size:11px;letter-spacing:.15em;opacity:.4;text-transform:uppercase;color:var(--gold)">Navy Elegant</div>
          </div>
          <div class="tema-hover-overlay">
            <span><i class='bx bx-show' style="font-size:16px"></i> Lihat Preview</span>
          </div>
        </div>
        <div class="tema-info">
          <h3>Navy Elegant</h3>
          <span class="badge baru">Baru</span>
        </div>
      </a>

      <!-- Tema 3: Blush Pink -->
      <a href="undangan/blush-pink.php" target="_blank" class="tema-card">
        <div class="tema-card-preview">
          <div class="tema-preview-pink">
            <div class="preview-name" style="font-family:'Playfair Display',serif;font-style:italic">Daffa & Rania</div>
            <div class="preview-sub" style="color:rgba(255,255,255,.6)">The Wedding Of</div>
            <div style="margin-top:1rem;font-size:11px;letter-spacing:.1em;opacity:.5;text-transform:uppercase">Blush Pink</div>
          </div>
          <div class="tema-hover-overlay">
            <span><i class='bx bx-show' style="font-size:16px"></i> Lihat Preview</span>
          </div>
        </div>
        <div class="tema-info">
          <h3>Blush Pink</h3>
          <span class="badge baru">Baru</span>
        </div>
      </a>
      
        <!-- Tema 4: Sage Garden -->
      <a href="undangan/sage-garden.php" target="_blank" class="tema-card">
        <div class="tema-card-preview">
          <div class="tema-preview-sage" style="height:220px;background:linear-gradient(160deg,#1e2d1f,#2d4a3e 40%,#3d6b44);display:flex;flex-direction:column;align-items:center;justify-content:center;color:#fff;text-align:center;padding:1rem">
            <div style="font-family:'Libre Baskerville',serif;font-size:1.6rem">Hendra & Ayu</div>
            <div style="font-size:11px;letter-spacing:.15em;opacity:.5;text-transform:uppercase;margin-top:.5rem">Sage Garden</div>
          </div>
          <div class="tema-hover-overlay"><span>👁 Lihat Preview</span></div>
        </div>
        <div class="tema-info">
          <h3>Sage Garden</h3><span class="badge baru">Baru</span>
        </div>
      </a>

      <!-- Tema 4: Coming Soon -->
      <div class="tema-card" style="cursor:default">
        <div class="tema-coming">
          <i class='bx bx-time-five'></i>
          <span>Segera Hadir</span>
          <span style="font-size:11px">Tema baru dalam pengembangan</span>
        </div>
        <div class="tema-info">
          <h3>Gold Mewah</h3>
          <span class="badge premium">Premium</span>
        </div>
      </div>

    </div>
    <div class="btn-center">
      <a href="./tema_undangan/tema.php" class="btn-lihat-semua"><i class='bx bx-palette'></i> Lihat Semua Tema</a>
    </div>
  </section>

  <!-- ══════════════════════════════════════
    HARGA
  ══════════════════════════════════════ -->
  <section class="harga" id="harga">
    <div class="sec-header">
      <div class="sec-tag">Paket & Harga</div>
      <h2>Pilih Paket yang Sesuai</h2>
      <p>Harga transparan, tanpa biaya tersembunyi — sesuaikan dengan kebutuhan acaramu</p>
    </div>
    <div class="pricing-grid reveal">

      <!-- Silver -->
      <div class="pricing-card">
        <img src="./img/member/member-silver.png" alt="Silver" class="member-icon" />
        <div class="plan-name">Silver</div>
        <div class="price">Gratis</div>
        <div class="features">
          <ul>
            <li>Domain Bernada.ID</li>
            <li>1 Tema pilihan</li>
            <li>3 Foto</li>
            <li>Maks. 3 Tamu</li>
            <li>Informasi Acara</li>
            <li>Countdown Timer</li>
            <li>Amplop Digital</li>
            <li>Link Lokasi Maps</li>
            <li>RSVP Online</li>
            <li>Masa Aktif 30 hari</li>
            <li>Revisi 2×</li>
          </ul>
        </div>
        <a href="buat-undangan.php" class="btn-outline-price">Mulai Gratis</a>
      </div>

      <!-- Gold (Popular) -->
      <div class="pricing-card popular">
        <div class="popular-badge"><i class='bx bx-star'></i> PALING LARIS</div>
        <img src="./img/member/member-gold.png" alt="Gold" class="member-icon" />
        <div class="plan-name">Gold</div>
        <div class="price">Rp 95K</div>
        <div class="features">
          <ul>
            <li>Domain Bernada.ID</li>
            <li>5 Tema Premium</li>
            <li>Maks. 10 Tamu</li>
            <li>10 Foto & 2 Video</li>
            <li>Informasi Acara</li>
            <li>Countdown Timer</li>
            <li>Amplop Digital</li>
            <li>Link Lokasi Maps</li>
            <li>RSVP Online</li>
            <li>Musik Latar</li>
            <li>Story & Quote</li>
            <li>Masa Aktif 30 hari</li>
            <li>Revisi 5×</li>
          </ul>
        </div>
        <a href="buat-undangan.php" class="btn-outline-price">Pilih Gold</a>
      </div>

      <!-- Platinum -->
      <div class="pricing-card">
        <img src="./img/member/member-platinum.png" alt="Platinum" class="member-icon" />
        <div class="plan-name">Platinum</div>
        <div class="price">Rp 190K</div>
        <div class="features">
          <ul>
            <li>Domain Sendiri (.my.id)</li>
            <li>Semua Tema Premium</li>
            <li>Tamu Unlimited</li>
            <li>Foto Unlimited</li>
            <li>Informasi Acara</li>
            <li>Countdown Timer</li>
            <li>Amplop Digital</li>
            <li>Link Lokasi Maps</li>
            <li>RSVP Online</li>
            <li>Musik Latar</li>
            <li>Story & Quote</li>
            <li>Scanner QR Code</li>
            <li>Masa Aktif 12 bulan</li>
            <li>Revisi Unlimited</li>
          </ul>
        </div>
        <a href="buat-undangan.php" class="btn-outline-price">Pilih Platinum</a>
      </div>

    </div>
  </section>

  <!-- ══════════════════════════════════════
     CTA BANNER
══════════════════════════════════════ -->
  <div class="cta-banner reveal">
    <div class="cta-text">
      <h2>Siap membuat undangan impianmu?</h2>
      <p>Bergabung dengan 500+ pasangan yang telah mempercayakan undangan digital mereka kepada Bernada.ID</p>
    </div>
    <a href="buat-undangan.php" class="cta-btn"><i class='bx bx-plus' style="font-size:18px"></i> Buat Undangan Sekarang</a>
  </div>

  <?php include("./footer/inc_footer.php") ?>

  <script src="scripts/script.js"></script>
</body>

</html>