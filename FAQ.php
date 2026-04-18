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
  <link rel="stylesheet" href="css/faq.css">
  <link rel="stylesheet" href="css/footer_header.css">
  <title>FAQ - BERNADA.ID</title>
</head>
<body>
  <?php include("./header/inc_header_second.php") ?>
  <section class="faq" id="faq">
  <div class="faq-header">
    <h2>Frequently Asked Questions</h2>
    <p>Pertanyaan yang sering ditanyakan seputar layanan Bernada.ID</p>
  </div>

  <div class="faq-container">

    <div class="faq-item">
      <button class="faq-question">Bagaimana cara membuat undangan digital di Bernada.ID?</button>
      <div class="faq-answer">
        <p>Jika kamu baru pertama kali mencoba Bernada.ID, kamu bisa register terlebih dahulu melalui halaman daftar akun.</p>
        <p>Setelah membuat akun, kamu akan dibawa menuju dashboard Bernada.ID untuk mulai membuat undanganmu. Pilih paket lalu klik <b>Buat Undangan</b>.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Bagaimana cara melakukan pembayaran setelah saya memesan paket?</button>
      <div class="faq-answer">
        <p>Setelah memesan paket, kamu otomatis akan dibawa ke menu <b>List Pesanan</b>. Di sana kamu bisa melakukan pembayaran menggunakan Virtual Account, Transfer Bank, E-Money, atau QRIS seperti GoPay, OVO, Dana, dan LinkAja.</p>
        <p>Jika pembayaran selesai, maka paket akan aktif otomatis dan siap digunakan.</p>
        <p>Jika memilih paket <b>GRATIS</b>, kamu tidak perlu melakukan pembayaran sama sekali.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Jika saya mau undangan saya dibuatkan, apakah bisa?</button>
      <div class="faq-answer">
        <p>Ya, tentu bisa. Silakan hubungi Customer Service melalui WhatsApp, informasikan paket yang ingin dibeli, lalu selesaikan pembayaran sesuai instruksi admin.</p>
        <p>Tim kami akan membantu membuatkan undanganmu sampai selesai.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Jika saya memesan paket undangan video, bagaimana prosesnya?</button>
      <div class="faq-answer">
        <p>Prosesnya sama seperti memesan paket biasa. Setelah memesan, tim kami akan menghubungi kamu maksimal 2x24 jam via WhatsApp.</p>
        <p>Setelah memilih tema, kami akan mengedit video sesuai data undanganmu. Format video yang disediakan adalah <b>MP4</b>.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Saya menemukan kode referral di profil saya, apa kegunaannya?</button>
      <div class="faq-answer">
        <p>Kode referral bisa kamu bagikan kepada teman yang ingin memesan paket di Bernada.ID.</p>
        <p>Jika digunakan, temanmu akan mendapat diskon <b>5%</b> dan kamu juga mendapat bonus <b>5%</b>.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Apakah tersedia layanan WhatsApp Blast untuk mengirim undangan?</button>
      <div class="faq-answer">
        <p>Ya, kami menyediakan fitur <b>WhatsApp Blast</b> untuk membagikan undangan ke banyak kontak sekaligus dengan mudah.</p>
        <p>Fitur ini tersedia di dashboard setelah undangan selesai dibuat.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Apakah Bernada.ID menyediakan layanan Wedding Planner?</button>
      <div class="faq-answer">
        <p>Ya, kami juga menyediakan layanan <b>Wedding Planner</b> untuk membantu merencanakan acara pernikahanmu.</p>
        <p>Kamu dapat mengelola timeline, checklist, dokumen, dan kebutuhan acara lainnya.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Apakah ada fitur AI Studio untuk generate foto di Bernada.ID?</button>
      <div class="faq-answer">
        <p>Ya, tersedia fitur <b>AI Studio</b> yang memungkinkan kamu membuat foto otomatis dengan teknologi AI.</p>
        <p>Cukup upload foto sesuai instruksi, lalu sistem akan menghasilkan beberapa pilihan foto yang siap digunakan untuk undangan digitalmu.</p>
      </div>
    </div>

  </div>
</section>

  <?php include("./footer/inc_footer_second.php") ?> 
</body>
<script src="/js/script.js"></script>
</html>