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
  <section class="tema" id="tema">
    <div class="tema-title">
      <h2>Pilih Tema Undangan Digital</h2>
      <p>Temukan tema yang paling cocok untuk acara spesial Anda</p>
    </div>
    <div class="tema-wrapper">
      <div class="tema-container">
        <a href="https://fingky12.github.io/demo-bersua.id/?to=Arjuna" target="_blank" class="tema-card reveal">
          <img src="img/tema1.jpeg" alt="tema1">
          <div class="tema-info">
            <h3>Adat</h3>
            <span class="badge free">Gratis</span>
          </div>
        </a>
        <a href="navy-elegant.php" target="_blank" class="tema-card reveal">
          <img src="img/tema1.jpeg" alt="tema1">
          <div class="tema-info">
            <h3>Elegant</h3>
            <span class="badge free">Gratis</span>
          </div>
        </a>
        <a href="blush-pink.php" target="_blank" class="tema-card reveal">
          <img src="img/cs.jpg" alt="tema2">
          <div class="tema-info">
            <h3>Minimalist</h3>
            <span class="badge free">Gratis</span>
          </div>
        </a>
        <a href="#" target="_blank" class="tema-card reveal">
          <img src="img/cs.jpg" alt="tema3">
          <div class="tema-info">
            <h3>Modern</h3>
            <span class="badge free">Gratis</span>
          </div>
        </a>
        <a href="merah-klasik.php" target="_blank"  class="tema-card reveal">
          <img src="img/cs.jpg" alt="tema4">
          <div class="tema-info">
            <h3>Classic</h3>
            <span class="badge premium">Premium</span>
          </div>
        </a>
      </div>
        <a href="halaman.php#harga" class="btn-tema">Lihat Harga Paket Premium</a>
    </div>
  </section>
  
  <?php include("../footer/inc_footer_second.php") ?>
</body>
</html>