<?php
session_start();
require_once 'config/koneksi.php';
$name = $_SESSION['name'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/footer_header_sec.css">
  <link rel="stylesheet" href="css/afiliasi.css">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./favicon_io/favicon-16x16.png">
  <link rel="manifest" href="./favicon_io/site.webmanifest">
  <title>Program Afiliasi – Bernada.ID</title>
</head>

<body>
  <?php include("./header/inc_header_second.php") ?>

  <!-- HERO -->
  <div class="aff-hero">
    <div class="coming-badge">Segera Hadir</div>
    <h1>Rekomendasikan Bernada.ID,<br><em>Dapatkan Komisi!</em></h1>
    <p>Program afiliasi Bernada.ID sedang dalam pengembangan. Daftarkan dirimu sekarang dan jadilah yang pertama bergabung!</p>
    <div class="waitlist-form">
      <input type="email" id="heroEmail" placeholder="Masukkan email kamu..." />
      <button onclick="daftarWaitlist('heroEmail', 'heroMsg')">Daftar Waitlist</button>
    </div>
    <div class="waitlist-note" id="heroMsg"></div>
  </div>

  <div class="aff-main">

    <!-- HOW IT WORKS -->
    <div class="section-header">
      <h2>Cara Kerja Program Afiliasi</h2>
      <p>Sederhana, transparan, dan menguntungkan</p>
    </div>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-num">1</div>
        <h3>Daftar & Dapatkan Link</h3>
        <p>Daftar sebagai afiliasi dan dapatkan link referral unik milikmu. Setiap orang yang mendaftar lewat linkmu akan tercatat otomatis.</p>
      </div>
      <div class="step-card">
        <div class="step-num">2</div>
        <h3>Sebarkan ke Siapa Saja</h3>
        <p>Bagikan link ke teman, keluarga, atau follower-mu. Cocok untuk wedding organizer, fotografer, atau siapapun yang kenal calon pengantin.</p>
      </div>
      <div class="step-card">
        <div class="step-num">3</div>
        <h3>Dapatkan Komisi</h3>
        <p>Setiap pembelian paket berbayar yang dilakukan lewat linkmu akan menghasilkan komisi yang langsung masuk ke saldo akunmu.</p>
      </div>
    </div>

    <!-- KOMISI -->
    <div class="komisi-section">
      <div class="section-header">
        <h2>Struktur Komisi</h2>
        <p>Semakin banyak referral, semakin besar komisi yang kamu dapatkan</p>
      </div>
      <div class="komisi-grid">
        <div class="komisi-card basic">
          <div class="tier">Afiliasi Reguler</div>
          <h3>15%</h3>
          <div class="sub">Per transaksi berhasil</div>
          <ul class="komisi-list">
            <li>Berlaku untuk semua paket berbayar</li>
            <li>Pencairan minimal Rp 50.000</li>
            <li>Transfer bank / e-wallet</li>
            <li>Dashboard tracking real-time</li>
          </ul>
          <span class="soon-badge">Segera Tersedia</span>
        </div>
        <div class="komisi-card pro">
          <div class="tier">Afiliasi Premium</div>
          <h3>25%</h3>
          <div class="sub">Per transaksi berhasil</div>
          <ul class="komisi-list">
            <li>Minimal 10 referral aktif/bulan</li>
            <li>Pencairan minimal Rp 50.000</li>
            <li>Prioritas dukungan afiliasi</li>
            <li>Materi promosi eksklusif</li>
            <li>Badge verified affiliate</li>
          </ul>
          <span class="soon-badge">Segera Tersedia</span>
        </div>
      </div>
    </div>

    <!-- FAQ MINI -->
    <div class="mini-faq">
      <div class="section-header">
        <h2>Pertanyaan Umum</h2>
      </div>
      <div class="mini-faq-item">
        <h3>Siapa saja yang bisa jadi afiliasi?</h3>
        <p>Siapapun bisa mendaftar — wedding organizer, fotografer pernikahan, blogger, content creator, atau siapapun yang ingin menghasilkan pendapatan tambahan dari rekomendasi.</p>
      </div>
      <div class="mini-faq-item">
        <h3>Apakah ada biaya untuk bergabung?</h3>
        <p>Tidak ada biaya apapun! Program afiliasi Bernada.ID sepenuhnya gratis untuk diikuti.</p>
      </div>
      <div class="mini-faq-item">
        <h3>Berapa lama cookie tracking berlaku?</h3>
        <p>Cookie tracking berlaku selama 30 hari. Artinya, jika seseorang mengklik linkmu dan melakukan pembelian dalam 30 hari, kamu tetap mendapat komisi.</p>
      </div>
      <div class="mini-faq-item">
        <h3>Bagaimana cara mencairkan komisi?</h3>
        <p>Komisi bisa dicairkan ke rekening bank atau e-wallet (GoPay, OVO, Dana, ShopeePay) setelah mencapai minimal Rp 50.000. Proses pencairan 1–3 hari kerja.</p>
      </div>
      <div class="mini-faq-item">
        <h3>Kapan program afiliasi ini diluncurkan?</h3>
        <p>Program afiliasi sedang dalam pengembangan dan akan diluncurkan dalam waktu dekat. Daftarkan emailmu di atas untuk mendapat notifikasi pertama saat program ini resmi diluncurkan!</p>
      </div>
    </div>

    <!-- CTA -->
    <div class="aff-cta">
      <h2>Jadilah yang pertama bergabung!</h2>
      <p>Daftarkan email kamu dan dapatkan keuntungan eksklusif sebagai early adopter program afiliasi Bernada.ID</p>
      <div class="aff-cta-form">
        <input type="email" id="ctaEmail" placeholder="Email kamu..." />
        <button onclick="daftarWaitlist('ctaEmail', 'ctaMsg')">Daftar Sekarang</button>
      </div>
      <div class="aff-success" id="ctaMsg"></div>
    </div>

  </div>

  <?php include("./footer/inc_footer_second.php") ?>
  <script>
    function daftarWaitlist(inputId, msgId) {
      const email = document.getElementById(inputId).value.trim();
      const msg = document.getElementById(msgId);
      if (!email || !email.includes('@')) {
        msg.style.display = 'block';
        msg.style.color = '#f5c1c1';
        msg.textContent = 'Masukkan email yang valid ya!';
        return;
      }
      // Kirim ke WhatsApp admin sebagai notifikasi waitlist
      const waText = encodeURIComponent(`Halo Bernada.ID! Saya ingin daftar waitlist program afiliasi. Email: ${email}`);
      window.open(`https://wa.me/6281939195110?text=${waText}`, '_blank');
      document.getElementById(inputId).value = '';
      msg.style.display = 'block';
      msg.style.color = inputId === 'ctaEmail' ? '#fff' : '#2e9e5b';
      msg.textContent = '✓ Terima kasih! Kami akan menghubungimu saat program diluncurkan.';
    }

      const profileBox = document.querySelector(".profile-box");
      const avatarCircle = document.querySelector(".avatar-circle");

      if (avatarCircle)
        avatarCircle.addEventListener("click", () =>
          profileBox.classList.toggle("show"),
        );
  </script>
</body>

</html>