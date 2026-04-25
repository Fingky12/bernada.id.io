<?php 
  session_start();
  require_once '../config/koneksi.php';
  $name = $_SESSION['name'] ?? null;
  session_unset();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../css/footer_header.css">
  <link rel="stylesheet" href="../css/tema.css">
  <title>Tema Undangan - BERNADA.ID</title>
</head>
<body>
  <?php include("../header/inc_header_second.php") ?>
  
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
  
  <?php include("../footer/inc_footer_second.php") ?>
</body>
</html>