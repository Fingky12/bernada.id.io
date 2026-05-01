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
  <title>Tentang Kami – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/footer_header_sec.css">
  <link rel="stylesheet" href="css/tentang.css">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./favicon_io/favicon-16x16.png">
  <link rel="manifest" href="./favicon_io/site.webmanifest">
</head>

<body>
  <?php include("./header/inc_header_second.php") ?>

  <!-- HERO -->
  <div class="about-hero">
    <div class="page-hero-tag">Tentang Kami</div>
    <h1>Kami hadir untuk membuat<br><em>momen istimewamu abadi</em></h1>
    <p>Bernada.ID adalah platform undangan digital yang lahir dari keinginan membuat pernikahan terasa lebih berkesan, modern, dan mudah diakses oleh semua orang.</p>
  </div>

  <!-- STATS BAR -->
  <div class="stats-bar">
    <div class="stats-inner">
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
      <div class="stat-item">
        <div class="num">24 jam</div>
        <div class="label">Waktu proses</div>
      </div>
    </div>
  </div>

  <div class="about-main">

    <!-- STORY -->
    <div class="story-grid">
      <div>
        <div class="story-tag">Cerita Kami</div>
        <h2>Berawal dari sebuah pernikahan yang hampir terlewat</h2>
        <p>Bernada.ID lahir dari pengalaman nyata — betapa sulitnya menyebarkan undangan pernikahan ke ratusan tamu yang tersebar di berbagai kota. Undangan fisik mahal, sering terlambat, dan tidak ramah lingkungan.</p>
        <p>Kami percaya bahwa setiap pasangan berhak mendapatkan undangan yang indah, mudah dibagikan, dan terjangkau. Itulah mengapa Bernada.ID hadir — membawa keindahan undangan pernikahan ke era digital.</p>
        <p>Dengan teknologi modern dan sentuhan desain yang elegan, kami membantu ratusan pasangan menyebarkan kabar bahagia mereka dengan cara yang lebih mudah dan berkesan.</p>
      </div>
      <div class="story-visual">
        <div class="big-text">"Setiap cinta layak dirayakan dengan indah."</div>
        <p>— Tim Bernada.ID</p>
      </div>
    </div>

    <!-- VALUES -->
    <div class="section-header">
      <h2>Nilai-nilai yang Kami Pegang</h2>
      <p>Prinsip yang mendasari setiap layanan yang kami berikan</p>
    </div>
    <div class="values-grid">
      <div class="value-card">
        <div class="value-icon"><i class='bx bx-heart'></i></div>
        <h3>Penuh Kasih</h3>
        <p>Setiap undangan dikerjakan dengan perhatian dan kepedulian, karena kami tahu betapa berartinya momen ini bagi kamu.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class='bx bx-check-shield'></i></div>
        <h3>Dapat Dipercaya</h3>
        <p>Kami menjaga kepercayaan pengguna dengan transparansi penuh, harga jelas, dan komitmen terhadap kualitas layanan.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class='bx bx-bulb'></i></div>
        <h3>Inovatif</h3>
        <p>Kami terus berinovasi menghadirkan fitur-fitur baru yang membuat undangan digital semakin kaya dan berkesan.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class='bx bx-accessibility'></i></div>
        <h3>Inklusif</h3>
        <p>Layanan kami dirancang agar bisa diakses dan digunakan oleh semua orang, tanpa memandang latar belakang teknis.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class='bx bx-leaf'></i></div>
        <h3>Ramah Lingkungan</h3>
        <p>Undangan digital berarti tidak ada kertas yang terbuang. Setiap undangan yang kamu buat adalah langkah kecil untuk bumi.</p>
      </div>
      <div class="value-card">
        <div class="value-icon"><i class='bx bx-support'></i></div>
        <h3>Responsif</h3>
        <p>Tim kami selalu siap membantu 7 hari seminggu. Tidak ada pertanyaan yang terlalu kecil untuk kami jawab.</p>
      </div>
    </div>

    <!-- TIMELINE -->
    <div class="section-header">
      <h2>Perjalanan Bernada.ID</h2>
      <p>Dari ide sederhana hingga platform yang dipercaya ratusan pasangan</p>
    </div>
    <div class="timeline">
      <div class="timeline-item">
        <div class="tl-left">
          <div class="tl-year">2023</div>
          <div class="tl-line"></div>
        </div>
        <div class="tl-content">
          <h3>Ide & Riset</h3>
          <p>Berawal dari frustasi melihat betapa rumitnya distribusi undangan pernikahan fisik. Mulai riset kebutuhan pasangan muda Indonesia akan undangan digital yang terjangkau.</p>
        </div>
      </div>
      <div class="timeline-item">
        <div class="tl-left">
          <div class="tl-year">2024</div>
          <div class="tl-line"></div>
        </div>
        <div class="tl-content">
          <h3>Peluncuran Beta</h3>
          <p>Bernada.ID diluncurkan dalam versi beta dengan 3 tema undangan pertama. Respons awal sangat positif — dalam 3 bulan pertama, lebih dari 50 undangan berhasil dibuat.</p>
        </div>
      </div>
      <div class="timeline-item">
        <div class="tl-left">
          <div class="tl-year">2025</div>
          <div class="tl-line"></div>
        </div>
        <div class="tl-content">
          <h3>Pengembangan Fitur</h3>
          <p>Peluncuran fitur RSVP online, amplop digital, galeri foto, dan integrasi WhatsApp. Jumlah undangan yang dibuat melampaui 500 dengan rating kepuasan 4.9/5.</p>
        </div>
      </div>
      <div class="timeline-item">
        <div class="tl-left">
          <div class="tl-year">2026</div>
          <div class="tl-line"></div>
        </div>
        <div class="tl-content">
          <h3>Terus Berkembang</h3>
          <p>Pengembangan program afiliasi, lebih banyak pilihan tema premium, dan fitur-fitur baru yang akan segera hadir untuk membuat pengalaman undangan digital semakin sempurna.</p>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="about-cta">
      <h2>Siap membuat undangan impianmu?</h2>
      <p>Bergabunglah dengan ratusan pasangan yang telah mempercayakan undangan digital mereka kepada Bernada.ID</p>
      <div class="cta-btns">
        <a href="buat-undangan.php" class="btn-red"><i class='bx bx-plus'></i> Buat Undangan Sekarang</a>
        <a href="faq.php" class="btn-ghost"><i class='bx bx-question-mark'></i> Lihat FAQ</a>
      </div>
    </div>

  </div>

  <?php include("./footer/inc_footer_second.php") ?>
  <script>
        const profileBox = document.querySelector(".profile-box");
        const avatarCircle = document.querySelector(".avatar-circle");
  
        if (avatarCircle)
          avatarCircle.addEventListener("click", () =>
            profileBox.classList.toggle("show"),
          );
  </script>
</body>

</html>