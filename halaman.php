<?php 
  session_start();
  require_once 'config/koneksi.php';
  
  $name = $_SESSION['name'] ?? null;
  $alerts = $_SESSION['alerts'] ?? [];
  $active_form = $_SESSION['active_form'] ?? '';
  
  session_unset();
  
  if ($name !== null) $_SESSION['name'] = $name;


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/halaman.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/fitur.css">
  <link rel="stylesheet" href="css/tema.css">
  <link rel="stylesheet" href="css/harga.css">
  <link rel="stylesheet" href="css/kontak.css">
  <title>Website Undangan Digital - Benada.id</title>
</head>
<body>
  <?php include("./header/inc_header.php") ?>


    <?php if (!empty($alerts)): ?>
    <div class="alert-box" >
      <?php foreach ($alerts as $alert): ?>
        <div class="alert <?= $alert['type']; ?>">
          <i class='bx <?= $alert['type'] === 'success' ? 'bxs-check-circle' : 'bxs-error-circle'; ?>'></i>
          <span><?= $alert['message']; ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  <div class="main">
    <section class="beranda" id="beranda">
      <div class="hero hero-left reveal-left">
        <h1 class="judul">BERNADA<span>.ID</span></h1>
        <p class="subjudul"> Kami membantu Anda membuat undangan digital berbasis website yang modern, responsif, dan mudah dibagikan ke siapa saja, kapan saja.</p>
        <div class="search-wrapper">
          <input type="text" placeholder="Ketik untuk mencari..." />
          <span class="search-icon">
            <i class='bx bx-search-alt'></i>
          </span>
        </div>
        <div class="btn-create">
          <a href="buat-undangan.php" class="btn1"><i class='bx bx-plus'></i>Buat Undangan Sekarang</a>
          <a href="tema.php" class="btn2">Lihat Contoh Undangan</a>
        </div>
      </div>
      <div class="hero hero-right reveal-right">
        <img src="./img/Brand1.png" alt="undangan1">
      </div>
    </section>

    <section class="fitur" id="fitur">
      <div class="fitur-title reveal">
        <h2>Fitur Undangan Digital Terbaik</h2>
        <p>Bersua.id Memberikan Solusi Semua Yang Anda Butuhkan Untuk Membuat Halaman Undangan Digital Yang Kekinian, Simple, Minimalist, Elegant.</p>
      </div>
      <div class="fitur-wrapper">
        <div class="fitur-grid reveal">
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bx-globe' ></i></div>
            <h3>Domain Eksklusif</h3>
            <p>Gunakan subdomain unik atau custom domain sesuai kebutuhan Anda.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-user-detail' ></i></div>
            <h3>Custom Nama Tamu</h3>
            <p>Satu tamu, satu undangan personal dengan nama otomatis tampil.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-edit-alt' ></i></div>
            <h3>Full Custom Teks</h3>
            <p>Semua teks dapat diubah sesuai konsep acara Anda.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-bolt-circle' ></i></div>
            <h3>Share Instan</h3>
            <p>Bagikan undangan ke WhatsApp hanya dengan satu klik.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-image-add' ></i></div>
            <h3>Konten Lengkap</h3>
            <p>Dukung teks, gambar, video, Google Maps & musik latar.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-user-pin' ></i></div>
            <h3>Manajemen Tamu</h3>
            <p>Kelola daftar tamu, grup, dan pencarian dengan mudah.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-message-rounded-dots'></i></i></div>
            <h3>RSVP & Buku Tamu</h3>
            <p>Konfirmasi kehadiran dan kirim ucapan secara online.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-gift' ></i></div>
            <h3>Amplop Digital</h3>
            <p>Kirim hadiah atau transfer langsung melalui rekening / QRIS.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-timer'></i></div>
            <h3>Countdown Timer</h3>
            <p>Tampilkan countdown timer untuk acara spesial Anda.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bx-credit-card' ></i></div>
            <h3>Pembayaran Mudah</h3>
            <p>Pembayaran dapat dilakukan kapan saja, mudah, otomatis dengan metode pembayaran yang lengkap</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bxs-quote-right' ></i></div>
            <h3>Story & Quote</h3>
            <p>Kirim hadiah atau transfer langsung melalui rekening / QRIS.</p>
          </div>
          <div class="fitur-card">
            <div class="fitur-icon"><i class='bx bx-category'></i></div>
            <h3>Tema instan</h3>
            <p>Tersedia berbagai macam tema siap pakai yang dapat diganti kapan saja tanpa batas</p>
          </div>
        </div>
      </div>
    </section>
  
    <section class="tema" id="tema">
      <div class="tema-title">
        <h2>Pilih Tema Undangan Digital</h2>
        <p>Temukan tema yang paling cocok untuk acara spesial Anda</p>
      </div>
      <div class="tema-wrapper">
        <div class="tema-container">
          <a href="https://fingky12.github.io/demo-bersua.id/?to=Arjuna" target="_blank" rel="noopener noreferrer" class="tema-card reveal">
            <img src="img/tema1.jpeg" alt="tema1">
            <div class="tema-info">
              <h3>Elegant</h3>
              <span class="badge free">Gratis</span>
            </div>
          </a>
          <a href="#" class="tema-card reveal">
            <img src="img/cs.jpg" alt="tema2">
            <div class="tema-info">
              <h3>Minimalist</h3>
              <span class="badge free">Gratis</span>
            </div>
          </a>
          <a href="#" class="tema-card reveal">
            <img src="img/cs.jpg" alt="tema3">
            <div class="tema-info">
              <h3>Modern</h3>
              <span class="badge free">Gratis</span>
            </div>
          </a>
          <a href="#" class="tema-card reveal">
            <img src="img/cs.jpg" alt="tema4">
            <div class="tema-info">
              <h3>Classic</h3>
              <span class="badge premium">Premium</span>
            </div>
          </a>
        </div>
          <a href="./tema_undangan/tema.php" rel="noopener noreferrer" class="btn-tema">Lihat Semua Tema</a>
      </div>
    </section>

    <section class="harga" id="harga">
      <div class="pricing-title reveal">
        <h2>Pilih Paket Undangan Digital</h2>
        <p>Sesuaikan dengan kebutuhan acara spesial kamu</p>
      </div>

      <div class="pricing-wrapper reveal">
        <div class="pricing-card">
          <img src="./img/member/member-silver.png" alt="silver-member" class="member-icon">
          <div class="plan-name">Silver</div>
          <div class="price">Gratis</div>
          <div class="features">
            <ul>
              <li> Domain Bernada.Id</li>
              <li> 1 Tema</li>
              <li> 3 Foto</li>
              <li> 3 Tamu</li>
              <li> Informasi Acara</li>
              <li> Countdown Timer</li>
              <li> Amplop Digital</li>
              <li> Link Lokasi</li>
              <li> Ucapan</li>
              <li> RSVP</li>
              <li> Masa Aktif : 30 hari</li>
              <li> Revisi : 2x</li>
            </ul>
          </div>
          <a href="#" class="btn-outline">Buat Sekarang</a>
        </div>
        <div class="pricing-card popular">
          <img src="./img/member/member-gold.png" alt="gold-member" class="member-icon">
          <div class="popular-badge">PALING LARIS</div>
          <div class="plan-name">Gold</div>
          <div class="price">Rp. 95K</div>
          <div class="features">
            <ul>
              <li> Domain Bernada.Id</li>
              <li> 5 Tema Premium</li>
              <li> 10 Tamu</li>
              <li> 10 Foto & 2 Vidio</li>
              <li> Informasi Acara</li>
              <li> Countdown Timer</li>
              <li> Amplop Digital</li>
              <li> Link Lokasi</li>
              <li> Ucapan</li>
              <li> RSVP</li>
              <li> Musik</li>
              <li> Story</li>
              <li> Masa Aktif : 30 Hari</li>
              <li> Revisi : 5x</li>
            </ul>
          </div>
          <a href="#" class="btn-outline">Buat Sekarang</a>
        </div>
        <div class="pricing-card">
          <img src="./img/member/member-platinum.png" alt="platinum-member" class="member-icon">
          <div class="plan-name">Platinum</div>
          <div class="price">Rp. 190K</div>
          <div class="features">
            <ul>
              <li> Domain Sendiri(my.id)</li>
              <li> All Tema Premium</li>
              <li> Unlimited Tamu</li>
              <li> Unlimited Foto</li>
              <li> Informasi Acara</li>
              <li> Countdown Timer</li>
              <li> Amplop Digital</li>
              <li> Link Lokasi</li>
              <li> Ucapan</li>
              <li> RSVP</li>
              <li> Musik</li>
              <li> Story</li>
              <li> Scaner QR</li>
              <li> Masa Aktif : 12 bulan</li>
              <li> Revisi : Bebas</li>
            </ul>
          </div>
          <a href="#" class="btn-outline">Buat Sekarang</a>
        </div>
      </div>
    </section>
  </div>
  
  <?php include("./footer/inc_footer.php") ?>
  
  <script src="./scripts/script.js"></script>
</body>
</html>