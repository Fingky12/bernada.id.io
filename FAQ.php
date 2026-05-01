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
  <title>FAQ – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/footer_header_sec.css">
  <link rel="stylesheet" href="css/faq.css">
</head>
<body>

<?php include("./header/inc_header_second.php") ?>

<!-- HERO -->
<div class="faq-hero">
  <div class="faq-hero-tag">Pusat Bantuan</div>
  <h1>Ada yang bisa kami<em> bantu?</em></h1>
  <p>Temukan jawaban atas pertanyaan seputar layanan undangan digital Bernada.ID</p>
  <div class="faq-search">
    <input type="text" id="searchInput" placeholder="Cari pertanyaan, misal: cara pesan, harga..." />
    <i class='bx bx-search'></i>
  </div>
</div>

<!-- MAIN -->
<div class="faq-main">

  <!-- STATS -->
  <div class="faq-stats">
    <div class="stat-card">
      <div class="stat-num">500+</div>
      <div class="stat-label">Undangan dibuat</div>
    </div>
    <div class="stat-card">
      <div class="stat-num">4.9★</div>
      <div class="stat-label">Rating kepuasan</div>
    </div>
    <div class="stat-card">
      <div class="stat-num">&lt;24 jam</div>
      <div class="stat-label">Waktu proses</div>
    </div>
  </div>

  <!-- TABS -->
  <div class="faq-tabs">
    <div class="faq-tab active" onclick="filterTab(this,'semua')">Semua</div>
    <div class="faq-tab" onclick="filterTab(this,'umum')">Umum</div>
    <div class="faq-tab" onclick="filterTab(this,'harga')">Harga & Paket</div>
    <div class="faq-tab" onclick="filterTab(this,'fitur')">Fitur</div>
    <div class="faq-tab" onclick="filterTab(this,'teknis')">Teknis</div>
    <div class="faq-tab" onclick="filterTab(this,'akun')">Akun</div>
  </div>

  <div id="faqList">

    <!-- ══ UMUM ══ -->
    <div class="faq-section" data-cat="umum">
      <div class="faq-section-title"><i class='bx bx-info-circle'></i> Pertanyaan Umum</div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apa itu Bernada.ID?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Bernada.ID adalah platform pembuatan undangan pernikahan digital berbasis website. Undangan kami modern, responsif di semua perangkat, dan mudah dibagikan ke siapa saja kapan saja hanya lewat link — tanpa perlu install aplikasi apapun.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Bagaimana cara memesan undangan digital di Bernada.ID?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Caranya sangat mudah! Klik tombol <strong>"+ Buat Undangan Sekarang"</strong> di halaman utama, isi data pengantin, tanggal & lokasi acara, pilih tema, lalu masukkan nomor WhatsApp kamu. Tim kami akan segera memproses dan mengirimkan link undangan kamu.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Berapa lama proses pembuatan undangan?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Undangan biasanya selesai dalam <strong>1×24 jam</strong> setelah data lengkap diterima. Untuk paket premium dengan fitur custom, proses bisa memakan waktu 2–3 hari kerja.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah undangan bisa diedit setelah dibuat?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Ya, bisa! Kamu dapat mengajukan revisi data seperti tanggal, lokasi, atau nama. Untuk paket Gratis, revisi terbatas 1 kali. Paket berbayar mendapat revisi unlimited selama masa aktif undangan.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah tamu undangan perlu install aplikasi untuk membuka undangan?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Tidak perlu! Undangan Bernada.ID berbasis website sehingga cukup dibuka lewat browser di HP manapun. Tamu tinggal klik link yang kamu kirim via WhatsApp, langsung bisa lihat undangannya.
        </div></div>
      </div>
    </div>

    <!-- ══ HARGA ══ -->
    <div class="faq-section" data-cat="harga">
      <div class="faq-section-title"><i class='bx bx-purchase-tag'></i> Harga & Paket</div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah ada paket gratis? <span class="badge badge-free">Gratis</span></span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Ada! Paket Gratis Bernada.ID mencakup 1 halaman undangan dengan tema pilihan, RSVP online, dan link undangan aktif selama 30 hari. Cocok untuk kamu yang ingin mencoba layanan kami terlebih dahulu.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apa saja perbedaan paket Gratis dan Berbayar? <span class="badge badge-pro">Pro</span></span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Perbedaan utamanya:<br><br>
          <strong>Paket Gratis:</strong> 1 tema, RSVP dasar, aktif 30 hari, revisi 2×, 3 galeri foto, DomainBernada.ID.<br><br>
          <strong>Paket Pro/Berbayar:</strong> Semua tema premium, galeri foto & video, musik latar, countdown timer, Google Maps interaktif, ucapan & doa dari tamu, aktif 30 hari - 2 bulan, revisi unlimited, tanpa watermark, dan dukungan prioritas.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Metode pembayaran apa saja yang diterima?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Kami menerima pembayaran via transfer bank (BCA, Mandiri, BNI, BRI), dompet digital (GoPay, OVO, Dana, ShopeePay), dan QRIS. Konfirmasi pembayaran bisa langsung via WhatsApp admin.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah ada biaya tambahan yang tidak tertera?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Tidak ada biaya tersembunyi! Harga yang tertera di halaman Harga sudah final dan all-in. Jika kamu ingin fitur custom di luar paket, kami akan informasikan biayanya terlebih dahulu sebelum dikerjakan.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah bisa refund jika tidak puas?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Refund dapat diajukan dalam 24 jam setelah pembayaran jika undangan belum mulai dikerjakan. Setelah undangan selesai dibuat, kami tidak menerima refund namun akan membantu revisi hingga kamu puas.
        </div></div>
      </div>
    </div>

    <!-- ══ FITUR ══ -->
    <div class="faq-section" data-cat="fitur">
      <div class="faq-section-title"><i class='bx bx-star'></i> Fitur Undangan</div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah bisa menambahkan foto pre-wedding?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Bisa! Paket berbayar mendukung galeri foto hingga 10 foto. Kamu cukup kirimkan foto-foto pre-wedding kamu ke admin via WhatsApp dan kami akan langsung pasang di undangan.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah ada fitur RSVP dan konfirmasi kehadiran tamu?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Ya! Semua paket termasuk fitur RSVP online. Tamu bisa mengisi nama, jumlah tamu, dan konfirmasi kehadiran langsung dari halaman undangan. Kamu bisa melihat rekap RSVP di dashboard akun kamu.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah undangan bisa dipersonalisasi per tamu?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Ya! Link undangan kamu bisa ditambahkan nama tamu otomatis. Contoh: <em>bernada.id/surya-sofi?to=Bapak+Hendra</em> — saat dibuka akan muncul "Kepada Yth. Bapak Hendra". Fitur ini tersedia di semua paket.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah ada fitur amplop digital / transfer hadiah?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Ada! Paket berbayar dilengkapi fitur amplop digital — kamu bisa cantumkan nomor rekening atau e-wallet sehingga tamu bisa memberikan hadiah langsung dari halaman undangan.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah ada musik latar (background music)?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Ada di paket berbayar! Kamu bisa request lagu favorit kamu atau membiarkan kami memilihkan lagu yang cocok dengan tema undangan. Tamu dapat mematikan musik sesuai preferensi mereka.
        </div></div>
      </div>
    </div>

    <!-- ══ TEKNIS ══ -->
    <div class="faq-section" data-cat="teknis">
      <div class="faq-section-title"><i class='bx bx-cog'></i> Teknis</div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah undangan tampil bagus di HP dan komputer?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Semua undangan Bernada.ID didesain <strong>responsive</strong> — tampil sempurna di HP, tablet, maupun komputer. Kami juga melakukan pengecekan tampilan di berbagai ukuran layar sebelum undangan dikirimkan.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Berapa lama link undangan aktif?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Paket Gratis: link aktif selama <strong>3 bulan</strong>. Paket berbayar: aktif selama <strong>12 bulan</strong> sejak tanggal aktivasi. Setelah masa aktif habis, link akan kadaluarsa tapi data kamu tetap tersimpan dan bisa diperpanjang.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah undangan bisa dibuka tanpa internet?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Undangan kami berbasis website sehingga membutuhkan koneksi internet untuk dibuka. Namun karena dioptimalkan dengan baik, undangan tetap bisa terbuka dengan cepat meski koneksi internet lambat sekalipun.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah link undangan bisa dibagikan ke WhatsApp, Instagram, dll?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Tentu! Link undangan berupa URL biasa yang bisa dibagikan ke mana saja — WhatsApp, Instagram, LINE, Telegram, email, bahkan dicetak sebagai QR code di kartu fisik. Kami juga menyediakan template pesan siap kirim untuk WhatsApp.
        </div></div>
      </div>
    </div>

    <!-- ══ AKUN ══ -->
    <div class="faq-section" data-cat="akun">
      <div class="faq-section-title"><i class='bx bx-user-circle'></i> Akun & Keamanan</div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah harus daftar akun untuk memesan undangan?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Ya, kamu perlu membuat akun terlebih dahulu. Ini agar kamu bisa memantau status undangan, melihat data RSVP tamu, dan mengajukan revisi kapan saja lewat dashboard akun kamu.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Apakah data pribadi saya aman?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Keamanan data kamu adalah prioritas kami. Semua password disimpan terenkripsi dan kami tidak pernah membagikan data pribadi kamu ke pihak ketiga manapun. Untuk detail lengkap, baca <a href="kebijakan-privasi.php">Kebijakan Privasi</a> kami.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Bagaimana cara logout dan ganti akun?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Klik foto profil kamu di pojok kanan atas → pilih <strong>"Logout"</strong>. Setelah logout, kamu bisa login dengan akun lain. Untuk keamanan, pastikan logout jika menggunakan perangkat bersama.
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span class="faq-q-text">Bagaimana jika lupa password?</span>
          <span class="faq-icon"><i class='bx bx-plus'></i></span>
        </div>
        <div class="faq-a"><div class="faq-a-inner">
          Klik <strong>"Forgot Password?"</strong> di halaman login, masukkan email yang terdaftar, dan kami akan mengirimkan link reset password ke email kamu. Jika mengalami kendala, hubungi admin via WhatsApp.
        </div></div>
      </div>
    </div>

    <!-- NO RESULT -->
    <div class="no-result" id="noResult">
      <i class='bx bx-search-alt'></i>
      <p style="font-size:16px;font-weight:500;color:#444;margin-bottom:6px">Pertanyaan tidak ditemukan</p>
      <p>Coba kata kunci lain atau hubungi kami langsung ya!</p>
    </div>

  </div>

  <!-- CTA -->
  <div class="faq-cta">
    <h3>Masih ada pertanyaan?</h3>
    <p>Tim kami siap membantu kamu 7 hari seminggu via WhatsApp!</p>
    <div class="faq-cta-btns">
      <a href="https://wa.me/6281939195110?text=Halo+Bernada.ID,+saya+ingin+bertanya..." class="btn-wa" target="_blank">
        <i class='bx bxl-whatsapp' style="font-size:18px"></i> Chat WhatsApp
      </a>
      <a href="buat-undangan.php" class="btn-white">
        <i class='bx bx-plus' style="font-size:16px"></i> Buat Undangan Sekarang
      </a>
    </div>
  </div>

</div>

<?php include("./footer/inc_footer_second.php") ?>

<script>
  // Accordion
  function toggleFaq(btn) {
    const item = btn.parentElement;
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  }

  // Filter tab
  function filterTab(el, cat) {
    document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.faq-section').forEach(sec => {
      sec.style.display = (cat === 'semua' || sec.dataset.cat === cat) ? 'block' : 'none';
    });
    document.getElementById('searchInput').value = '';
    checkNoResult();
  }

  // Search
  document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
    document.querySelector('.faq-tab').classList.add('active');
    document.querySelectorAll('.faq-section').forEach(sec => sec.style.display = 'block');

    let anyVisible = false;
    document.querySelectorAll('.faq-item').forEach(item => {
      const text = item.querySelector('.faq-q-text').textContent.toLowerCase()
                  + item.querySelector('.faq-a-inner').textContent.toLowerCase();
      const show = !q || text.includes(q);
      item.style.display = show ? 'block' : 'none';
      if (show) anyVisible = true;
      if (!show) item.classList.remove('open');
    });

    document.querySelectorAll('.faq-section').forEach(sec => {
      const visible = [...sec.querySelectorAll('.faq-item')].some(i => i.style.display !== 'none');
      sec.style.display = visible ? 'block' : 'none';
    });

    document.getElementById('noResult').style.display = anyVisible ? 'none' : 'block';
  });

  function checkNoResult() {
    const anyVisible = [...document.querySelectorAll('.faq-item')].some(i => i.style.display !== 'none');
    document.getElementById('noResult').style.display = anyVisible ? 'none' : 'block';
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