<?php
session_start();
require_once 'config/koneksi.php';
$name = $_SESSION['name'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Syarat & Ketentuan – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/footer_header.css">
  <link rel="stylesheet" href="css/therms.css">
</head>
<body>
<?php include("./header/inc_header_second.php") ?>

<div class="page-hero">
  <div class="page-hero-tag">Legal</div>
  <h1>Syarat &amp; Ketentuan</h1>
  <p>Harap baca dokumen ini dengan seksama sebelum menggunakan layanan Bernada.ID</p>
</div>

<div class="layout">
  <aside class="toc">
    <div class="toc-title">Daftar Isi</div>
    <a href="#s1">1. Penerimaan Ketentuan</a>
    <a href="#s2">2. Layanan Kami</a>
    <a href="#s3">3. Akun Pengguna</a>
    <a href="#s4">4. Pemesanan & Pembayaran</a>
    <a href="#s5">5. Hak Kekayaan Intelektual</a>
    <a href="#s6">6. Larangan Penggunaan</a>
    <a href="#s7">7. Penolakan Garansi</a>
    <a href="#s8">8. Batasan Tanggung Jawab</a>
    <a href="#s9">9. Perubahan Layanan</a>
    <a href="#s10">10. Hukum yang Berlaku</a>
    <a href="#s11">11. Hubungi Kami</a>
  </aside>

  <div class="content">
    <div class="meta-bar">
      <span><i class='bx bx-calendar'></i> Berlaku sejak: 1 Januari 2025</span>
      <span><i class='bx bx-revision'></i> Terakhir diperbarui: <?= date('d F Y') ?></span>
    </div>

    <div class="highlight-box">
      Dengan mengakses atau menggunakan layanan Bernada.ID, kamu dianggap telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan yang berlaku.
    </div>

    <div class="doc-section" id="s1">
      <h2><span class="num">1</span> Penerimaan Ketentuan</h2>
      <p>Syarat dan Ketentuan ini mengatur penggunaan website dan layanan Bernada.ID yang dioperasikan secara perorangan. Dengan mendaftar akun atau menggunakan layanan kami, kamu menyatakan bahwa kamu berusia minimal 17 tahun dan memiliki kapasitas hukum untuk mengikat perjanjian ini.</p>
      <p>Jika kamu tidak menyetujui salah satu ketentuan dalam dokumen ini, harap hentikan penggunaan layanan kami.</p>
    </div>

    <div class="doc-section" id="s2">
      <h2><span class="num">2</span> Layanan Kami</h2>
      <p>Bernada.ID menyediakan layanan pembuatan undangan pernikahan digital berbasis website, meliputi:</p>
      <ul>
        <li>Pembuatan halaman undangan digital yang dapat diakses via link</li>
        <li>Berbagai pilihan tema dan desain undangan</li>
        <li>Fitur RSVP online untuk konfirmasi kehadiran tamu</li>
        <li>Fitur amplop digital dan galeri foto (paket berbayar)</li>
        <li>Notifikasi otomatis via WhatsApp</li>
      </ul>
      <p>Kami berhak menambah, mengubah, atau menghentikan fitur layanan sewaktu-waktu dengan pemberitahuan yang wajar kepada pengguna.</p>
    </div>

    <div class="doc-section" id="s3">
      <h2><span class="num">3</span> Akun Pengguna</h2>
      <p>Untuk menggunakan layanan Bernada.ID, kamu wajib membuat akun dengan informasi yang akurat dan lengkap. Kamu bertanggung jawab penuh atas:</p>
      <ul>
        <li>Kerahasiaan kata sandi akun kamu</li>
        <li>Semua aktivitas yang terjadi di bawah akun kamu</li>
        <li>Segera memberitahu kami jika ada akses tidak sah ke akunmu</li>
      </ul>
      <p>Satu akun hanya boleh digunakan oleh satu individu. Berbagi akun dengan pihak lain tidak diperbolehkan.</p>
    </div>

    <div class="doc-section" id="s4">
      <h2><span class="num">4</span> Pemesanan &amp; Pembayaran</h2>
      <p>Pemesanan dianggap sah setelah data undangan lengkap diterima dan (untuk paket berbayar) pembayaran telah dikonfirmasi. Ketentuan pembayaran:</p>
      <ul>
        <li>Harga yang tertera adalah harga final dalam Rupiah (IDR)</li>
        <li>Pembayaran dilakukan di muka sebelum pengerjaan dimulai</li>
        <li>Refund dapat diajukan dalam 24 jam jika pengerjaan belum dimulai</li>
        <li>Setelah undangan selesai, tidak ada refund namun tersedia revisi sesuai paket</li>
        <li>Kami berhak membatalkan pesanan yang terindikasi penipuan atau pelanggaran ketentuan</li>
      </ul>
    </div>

    <div class="doc-section" id="s5">
      <h2><span class="num">5</span> Hak Kekayaan Intelektual</h2>
      <p>Seluruh desain, template, kode, dan konten yang tersedia di Bernada.ID adalah milik Bernada.ID dan dilindungi hak cipta. Kamu dilarang:</p>
      <ul>
        <li>Menyalin, memodifikasi, atau mendistribusikan template/desain kami</li>
        <li>Menggunakan merek, logo, atau nama Bernada.ID tanpa izin tertulis</li>
        <li>Melakukan reverse engineering terhadap sistem kami</li>
      </ul>
      <p>Konten yang kamu unggah (foto, teks personal) tetap menjadi milikmu. Kamu memberikan Bernada.ID lisensi terbatas untuk menampilkan konten tersebut dalam undanganmu.</p>
    </div>

    <div class="doc-section" id="s6">
      <h2><span class="num">6</span> Larangan Penggunaan</h2>
      <p>Kamu dilarang menggunakan layanan Bernada.ID untuk:</p>
      <ul>
        <li>Kegiatan ilegal atau yang melanggar hukum Republik Indonesia</li>
        <li>Menyebarkan konten yang mengandung SARA, pornografi, atau kekerasan</li>
        <li>Melakukan spam atau pengiriman pesan massal yang tidak sah</li>
        <li>Mencoba meretas atau mengganggu sistem Bernada.ID</li>
        <li>Membuat undangan palsu atau menyesatkan</li>
      </ul>
      <div class="highlight-box">Pelanggaran ketentuan ini dapat mengakibatkan penangguhan atau penghapusan akun tanpa pengembalian dana.</div>
    </div>

    <div class="doc-section" id="s7">
      <h2><span class="num">7</span> Penolakan Garansi</h2>
      <p>Layanan Bernada.ID disediakan "sebagaimana adanya" (as-is). Meskipun kami berupaya memberikan layanan terbaik, kami tidak menjamin bahwa layanan akan selalu tersedia tanpa gangguan, bebas dari kesalahan, atau memenuhi semua ekspektasi spesifik kamu.</p>
    </div>

    <div class="doc-section" id="s8">
      <h2><span class="num">8</span> Batasan Tanggung Jawab</h2>
      <p>Bernada.ID tidak bertanggung jawab atas kerugian tidak langsung, insidental, atau konsekuensial yang timbul dari penggunaan atau ketidakmampuan menggunakan layanan kami, termasuk namun tidak terbatas pada kehilangan data atau kerugian bisnis.</p>
    </div>

    <div class="doc-section" id="s9">
      <h2><span class="num">9</span> Perubahan Layanan &amp; Ketentuan</h2>
      <p>Kami berhak memperbarui Syarat & Ketentuan ini sewaktu-waktu. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di website. Penggunaan layanan setelah perubahan dianggap sebagai penerimaan ketentuan baru.</p>
    </div>

    <div class="doc-section" id="s10">
      <h2><span class="num">10</span> Hukum yang Berlaku</h2>
      <p>Syarat & Ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Republik Indonesia. Setiap sengketa yang timbul akan diselesaikan melalui musyawarah mufakat, dan jika tidak tercapai, akan diselesaikan melalui jalur hukum yang berlaku di Indonesia.</p>
    </div>

    <div class="doc-section" id="s11">
      <h2><span class="num">11</span> Hubungi Kami</h2>
      <p>Jika ada pertanyaan terkait Syarat & Ketentuan ini, silakan hubungi kami:</p>
    </div>

    <div class="contact-box">
      <h3>Ada pertanyaan?</h3>
      <p>Tim kami siap membantu kamu memahami ketentuan layanan Bernada.ID</p>
      <a href="https://wa.me/6281939195110"><i class='bx bxl-whatsapp'></i> Hubungi via WhatsApp</a>
    </div>
  </div>
</div>

<?php include("./footer/inc_footer_second.php") ?>
</body>
</html>