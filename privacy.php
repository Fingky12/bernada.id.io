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
  <title>Kebijakan Privasi – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/footer_header.css">
  <link rel="stylesheet" href="css/privacy.css">
</head>
<body>
<?php include("./header/inc_header_second.php") ?>

<div class="page-hero">
  <div class="page-hero-tag">Privasi</div>
  <h1>Kebijakan Privasi</h1>
  <p>Kami berkomitmen menjaga privasi dan keamanan data pribadi kamu</p>
</div>

<div class="layout">
  <aside class="toc">
    <div class="toc-title">Daftar Isi</div>
    <a href="#p1">1. Data yang Kami Kumpulkan</a>
    <a href="#p2">2. Cara Penggunaan Data</a>
    <a href="#p3">3. Penyimpanan Data</a>
    <a href="#p4">4. Berbagi Data</a>
    <a href="#p5">5. Keamanan Data</a>
    <a href="#p6">6. Hak-hak Kamu</a>
    <a href="#p7">7. Cookie</a>
    <a href="#p8">8. Perubahan Kebijakan</a>
    <a href="#p9">9. Hubungi Kami</a>
  </aside>

  <div class="content">
    <div class="meta-bar">
      <span><i class='bx bx-calendar'></i> Berlaku sejak: 1 Januari 2025</span>
      <span><i class='bx bx-revision'></i> Terakhir diperbarui: <?= date('d F Y') ?></span>
    </div>

    <div class="info-box">
      Bernada.ID sangat menghargai privasi kamu. Dokumen ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi kamu saat menggunakan layanan kami.
    </div>

    <div class="doc-section" id="p1">
      <h2><span class="num">1</span> Data yang Kami Kumpulkan</h2>
      <p>Kami mengumpulkan beberapa jenis data saat kamu menggunakan layanan Bernada.ID:</p>
      <table class="data-table">
        <tr><th>Jenis Data</th><th>Contoh</th><th>Tujuan</th></tr>
        <tr><td>Data Akun</td><td>Nama, email, password terenkripsi</td><td>Identifikasi pengguna</td></tr>
        <tr><td>Data Undangan</td><td>Nama pengantin, tanggal, lokasi</td><td>Pembuatan undangan</td></tr>
        <tr><td>Data Kontak</td><td>Nomor WhatsApp, email</td><td>Notifikasi & komunikasi</td></tr>
        <tr><td>Data Teknis</td><td>IP address, jenis browser</td><td>Keamanan & analitik</td></tr>
      </table>
    </div>

    <div class="doc-section" id="p2">
      <h2><span class="num">2</span> Cara Penggunaan Data</h2>
      <p>Data yang kamu berikan digunakan untuk:</p>
      <ul>
        <li>Membuat dan mengelola akun kamu di Bernada.ID</li>
        <li>Memproses pesanan dan membuat undangan digital</li>
        <li>Mengirimkan notifikasi pemesanan via WhatsApp dan email</li>
        <li>Merespons pertanyaan dan permintaan dukungan kamu</li>
        <li>Meningkatkan kualitas layanan dan pengalaman pengguna</li>
        <li>Memenuhi kewajiban hukum yang berlaku</li>
      </ul>
      <div class="highlight-box">Kami tidak menggunakan data kamu untuk keperluan pemasaran dari pihak ketiga tanpa persetujuan eksplisit dari kamu.</div>
    </div>

    <div class="doc-section" id="p3">
      <h2><span class="num">3</span> Penyimpanan Data</h2>
      <p>Data kamu disimpan di server yang berlokasi di Indonesia. Kami menyimpan data selama akun kamu aktif atau selama diperlukan untuk memberikan layanan. Jika kamu menghapus akun, data akan dihapus dalam 30 hari, kecuali data yang perlu dipertahankan untuk kepatuhan hukum.</p>
    </div>

    <div class="doc-section" id="p4">
      <h2><span class="num">4</span> Berbagi Data dengan Pihak Ketiga</h2>
      <p>Kami tidak menjual atau menyewakan data pribadi kamu. Data mungkin dibagikan kepada:</p>
      <ul>
        <li><strong>Penyedia layanan teknis</strong> — hanya untuk keperluan operasional (misal: layanan email, WhatsApp API)</li>
        <li><strong>Pihak berwenang</strong> — jika diwajibkan oleh hukum atau proses hukum yang sah</li>
      </ul>
      <p>Semua pihak ketiga yang bekerja sama dengan kami terikat kewajiban kerahasiaan dan tidak diizinkan menggunakan data kamu untuk tujuan lain.</p>
    </div>

    <div class="doc-section" id="p5">
      <h2><span class="num">5</span> Keamanan Data</h2>
      <p>Kami menerapkan langkah-langkah keamanan yang wajar untuk melindungi data kamu, termasuk:</p>
      <ul>
        <li>Enkripsi password menggunakan algoritma bcrypt</li>
        <li>Proteksi terhadap serangan SQL Injection dan XSS</li>
        <li>Akses data yang dibatasi hanya untuk personel yang berwenang</li>
        <li>Pemantauan aktivitas mencurigakan secara berkala</li>
      </ul>
      <p>Meskipun demikian, tidak ada sistem yang 100% aman. Harap segera hubungi kami jika kamu mencurigai adanya akses tidak sah ke akunmu.</p>
    </div>

    <div class="doc-section" id="p6">
      <h2><span class="num">6</span> Hak-hak Kamu</h2>
      <p>Sebagai pengguna, kamu memiliki hak untuk:</p>
      <ul>
        <li><strong>Akses</strong> — meminta salinan data pribadi yang kami simpan</li>
        <li><strong>Koreksi</strong> — meminta perbaikan data yang tidak akurat</li>
        <li><strong>Penghapusan</strong> — meminta penghapusan akun dan data kamu</li>
        <li><strong>Portabilitas</strong> — meminta data kamu dalam format yang dapat dibaca mesin</li>
        <li><strong>Keberatan</strong> — menolak pemrosesan data untuk tujuan tertentu</li>
      </ul>
      <p>Untuk menggunakan hak-hak di atas, hubungi kami via WhatsApp atau email.</p>
    </div>

    <div class="doc-section" id="p7">
      <h2><span class="num">7</span> Cookie</h2>
      <p>Bernada.ID menggunakan cookie sesi (session cookie) untuk menjaga status login kamu. Cookie ini bersifat sementara dan akan dihapus saat kamu menutup browser atau logout. Kami tidak menggunakan cookie pelacak pihak ketiga untuk keperluan iklan.</p>
    </div>

    <div class="doc-section" id="p8">
      <h2><span class="num">8</span> Perubahan Kebijakan Privasi</h2>
      <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan signifikan akan diberitahukan melalui email atau pengumuman di website minimal 7 hari sebelum berlaku. Penggunaan layanan setelah perubahan dianggap sebagai penerimaan kebijakan baru.</p>
    </div>

    <div class="doc-section" id="p9">
      <h2><span class="num">9</span> Hubungi Kami</h2>
      <p>Untuk pertanyaan, permintaan, atau keluhan terkait privasi data kamu, silakan hubungi kami:</p>
    </div>

    <div class="contact-box">
      <h3>Pertanyaan tentang privasi?</h3>
      <p>Kami merespons setiap pertanyaan dalam 1×24 jam</p>
      <a href="https://wa.me/6281939195110"><i class='bx bxl-whatsapp'></i> Hubungi via WhatsApp</a>
    </div>
  </div>
</div>

<?php include("./footer/inc_footer_second.php") ?>
</body>
</html>