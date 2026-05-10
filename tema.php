<?php 
  session_start();
  require_once 'config/koneksi.php';
  $name = $_SESSION['name'] ?? null;
  session_unset();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/footer_header_sec.css">
  <link rel="stylesheet" href="css/tema.css">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
  <link rel="manifest" href="favicon_io/site.webmanifest">
  <title>Tema Undangan - BERNADA.ID</title>
</head>
<body>
  <?php include("./header/inc_header_second.php") ?>
  
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
      <a href="merah-klasik.php" target="_blank" class="tema-card">
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
      <a href="navy-elegant.php" target="_blank" class="tema-card">
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
      <a href="blush-pink.php" target="_blank" class="tema-card">
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
      <a href="sage-garden.php" target="_blank" class="tema-card">
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

      <!-- Tema 5: Rustic Brown -->
      <a href="rustic-brown.php" target="_blank" class="tema-card">
        <div class="tema-card-preview">
          <div style="height:220px;background:linear-gradient(160deg,#2c1a0e,#5c3d1e 40%,#8b6340);display:flex;flex-direction:column;align-items:center;justify-content:center;color:#fff;text-align:center;padding:1rem">
            <div style="font-family:'Cinzel',serif;font-size:1.4rem;letter-spacing:.08em">RAKA & DIRA</div>
            <div style="font-size:10px;letter-spacing:.2em;opacity:.45;text-transform:uppercase;margin-top:.75rem;font-family:'Cinzel',serif">Rustic Brown</div>
          </div>
          <div class="tema-hover-overlay"><span>👁 Lihat Preview</span></div>
        </div>
        <div class="tema-info">
          <h3>Rustic Brown</h3><span class="badge baru">Baru</span>
        </div>
      </a>

      <!-- Tema Premium: Coming Soon -->
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
      <a href="halaman.php#tema" class="btn-lihat-semua"><i class='bx bx-undo' ></i> Kembali Ke Beranda</a>
    </div>
  </section>
  
  <?php include("./footer/inc_footer_second.php") ?>
</body>
</html>                                               