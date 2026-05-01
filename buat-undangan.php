<?php
session_start();
require_once 'config/koneksi.php';

$name = $_SESSION['name'] ?? null;
$alerts = $_SESSION['alerts'] ?? [];
$active_form = $_SESSION['active_form'] ?? '';

if (!isset($_SESSION['name'])) {
  header('Location: dashboard.php');
  exit;
}

session_unset();

if ($name !== null) $_SESSION['name'] = $name;

?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/footer_header.css">
  <link rel="stylesheet" href="css/buat-undangan.css">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
  <link rel="manifest" href="favicon_io/site.webmanifest">
  <title>Buat Undangan Digital – Bernada.ID</title>
</head>

<body>
  <?php include("./header/inc_header_second.php") ?>

  <section class="buat-undangan" id="buat-undangan">
    <!-- HERO -->
    <div class="hero-strip">
      <h1>Buat Undangan Digital</h1>
      <p>Isi data pernikahan kamu, undangan siap dalam hitungan menit!</p>
    </div>

    <!-- MAIN -->
    <!-- FORM -->
    <form id="formUndangan" action="config/proses_undangan.php" method="POST" novalidate>
      <!-- STEPS BAR -->
      <div class="steps-bar">
        <div class="step-item active" id="tab1"><span class="step-num">1</span> Data Pengantin</div>
        <span class="step-arrow">›</span>
        <div class="step-item" id="tab2"><span class="step-num">2</span> Info Acara</div>
        <span class="step-arrow">›</span>
        <div class="step-item" id="tab3"><span class="step-num">3</span> Pilih Tema</div>
        <span class="step-arrow">›</span>
        <div class="step-item" id="tab4"><span class="step-num">4</span> Kontak</div>
      </div>
      <!-- ALERT -->
      <div class="alert alert-error" id="alertError"></div>
      <div class="alert alert-success" id="alertSuccess"></div>



      <!-- STEP 1 - PENGANTIN -->
      <div class="card step-section" id="sec1">
        <div class="card-title"><i class='bx bxs-book-heart'></i> Data Pengantin</div>
        <div class="field-row">
          <div class="field">
            <label>Nama Pengantin Pria <span class="req">*</span></label>
            <input type="text" name="nama_pria" placeholder="cth. Surya" required />
          </div>
          <div class="field">
            <label>Nama Pengantin Wanita <span class="req">*</span></label>
            <input type="text" name="nama_wanita" placeholder="cth. Sofi" required />
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label>Nama Ayah Pria</label>
            <input type="text" name="ayah_pria" placeholder="cth. Bpk. Ahmad" />
          </div>
          <div class="field">
            <label>Nama Ayah Wanita</label>
            <input type="text" name="ayah_wanita" placeholder="cth. Bpk. Hendra" />
          </div>
        </div>
        <div class="btn-row">
          <button type="button" class="btn btn-primary" onclick="nextStep(1)">Lanjut <i class='bx bx-right-arrow-alt'></i></button>
        </div>
      </div>

      <!-- STEP 2 - ACARA -->
      <div class="card step-section" id="sec2" style="display:none">
        <div class="card-title"><i class='bx bx-calendar-heart'></i> Info Acara</div>
        <div class="field">
          <label>Tanggal Pernikahan <span class="req">*</span></label>
          <input type="date" name="tanggal_nikah" required />
        </div>
        <div class="field-row">
          <div class="field">
            <label>Waktu Mulai <span class="req">*</span></label>
            <input type="time" name="waktu_mulai" value="10:00" required />
          </div>
          <div class="field">
            <label>Waktu Selesai <span class="req">*</span></label>
            <input type="time" name="waktu_selesai" value="14:00" required />
          </div>
        </div>
        <div class="field">
          <label>Nama Gedung / Lokasi <span class="req">*</span></label>
          <input type="text" name="lokasi" placeholder="cth. Gedung Balai Pemuda Surabaya" required />
        </div>
        <div class="field">
          <label>Link Google Maps <small style="color:#aaa">(opsional)</small></label>
          <input type="url" name="link_maps" placeholder="https://maps.google.com/..." />
        </div>
        <div class="btn-row">
          <button type="button" class="btn btn-outline" onclick="prevStep(2)"><i class='bx bx-left-arrow-alt'></i> Kembali</button>
          <button type="button" class="btn btn-primary" onclick="nextStep(2)">Lanjut <i class='bx bx-right-arrow-alt'></i></button>
        </div>
      </div>

      <!-- STEP 3 - TEMA -->
      <div class="card step-section" id="sec3" style="display:none">
        <div class="card-title"><i class='bx bx-palette'></i> Pilih Tema</div>
        <input type="hidden" name="tema" id="temaValue" value="Merah Klasik" required />
        <div class="tema-grid">
          <div class="tema-card active" onclick="pilihTema(this,'Merah Klasik')">
            <div class="tema-dot" style="background:linear-gradient(135deg,#C0393B,#7B1113)"></div>
            <div class="tema-name">Merah Klasik</div>
            <div class="tema-desc">Elegan & formal</div>
          </div>
          <div class="tema-card" onclick="pilihTema(this,'Putih Minimalis')">
            <div class="tema-dot" style="background:linear-gradient(135deg,#f5f5f5,#ccc);border:1px solid #ccc"></div>
            <div class="tema-name">Putih Minimalis</div>
            <div class="tema-desc">Bersih & modern</div>
          </div>
          <div class="tema-card" onclick="pilihTema(this,'Gold Mewah')">
            <div class="tema-dot" style="background:linear-gradient(135deg,#D4AF37,#8B6914)"></div>
            <div class="tema-name">Gold Mewah</div>
            <div class="tema-desc">Glamor & premium</div>
          </div>
          <div class="tema-card" onclick="pilihTema(this,'Navy Elegant')">
            <div class="tema-dot" style="background:linear-gradient(135deg,#1a2e4a,#0a1628)"></div>
            <div class="tema-name">Navy Elegant</div>
            <div class="tema-desc">Maskulin & kokoh</div>
          </div>
          <div class="tema-card" onclick="pilihTema(this,'Sage Green')">
            <div class="tema-dot" style="background:linear-gradient(135deg,#7a9e7e,#3d6b44)"></div>
            <div class="tema-name">Sage Green</div>
            <div class="tema-desc">Natural & segar</div>
          </div>
          <div class="tema-card" onclick="pilihTema(this,'Blush Pink')">
            <div class="tema-dot" style="background:linear-gradient(135deg,#f4b8c8,#d4687e)"></div>
            <div class="tema-name">Blush Pink</div>
            <div class="tema-desc">Romantis & lembut</div>
          </div>
        </div>
        <div class="btn-row">
          <button type="button" class="btn btn-outline" onclick="prevStep(3)"><i class='bx bx-left-arrow-alt'></i> Kembali</button>
          <button type="button" class="btn btn-primary" onclick="nextStep(3)">Lanjut <i class='bx bx-right-arrow-alt'></i></i></button>
        </div>
      </div>

      <!-- STEP 4 - KONTAK -->
      <div class="card step-section" id="sec4" style="display:none">
        <div class="card-title"><i class='bx bxs-phone-call'></i> Kontak & Catatan</div>
        <div class="field">
          <label>Nomor WhatsApp <span class="req">*</span></label>
          <input type="tel" name="no_whatsapp" placeholder="cth. 08123456789" required />
          <small style="color:#aaa;font-size:12px">Konfirmasi akan dikirim ke nomor ini</small>
        </div>
        <div class="field">
          <label>Email <small style="color:#aaa">(opsional)</small></label>
          <input type="email" name="email" placeholder="cth. surya@gmail.com" />
        </div>
        <div class="field">
          <label>Catatan Tambahan <small style="color:#aaa">(opsional)</small></label>
          <textarea name="catatan" placeholder="cth. Mohon tambahkan foto pre-wedding kami..."></textarea>
        </div>
        <div class="btn-row">
          <button type="button" class="btn btn-outline" onclick="prevStep(4)"><i class='bx bx-left-arrow-alt'></i> Kembali</button>
          <button type="submit" class="btn btn-primary"><i class='bx bx-send'></i> Buat Undangan Sekarang</button>
        </div>
      </div>
    </form>

    <!-- SUCCESS PAGE -->
    <div class="success-page card" id="successPage">
      <div class="success-icon">🎉</div>
      <h2>Undangan Berhasil Dibuat!</h2>
      <p>Kode undangan kamu:</p>
      <div class="kode-box">
        <div class="label">Kode Undangan</div>
        <div class="kode" id="kodeUndangan">-</div>
      </div>
      <p>Notifikasi telah dikirim ke <strong id="waTarget">-</strong></p>
      <p style="color:#aaa;font-size:13px;margin-top:6px">Tim kami akan segera memproses undanganmu.</p>
      <div class="btn-row" style="margin-top:1.5rem;justify-content:center">
        <a href="halaman.php" class="btn btn-outline" style="text-decoration:none;text-align:center;max-width:200px">← Beranda</a>
      </div>
    </div>

    <!-- LOADING OVERLAY -->
    <div id="loadingOverlay">
      <div class="spinner-box">
        <div class="spinner"></div>
        <p>Sedang memproses undanganmu...</p>
      </div>
    </div>
  </section>

  <?php include("./footer/inc_footer_second.php") ?>
  <script>
      const profileBox = document.querySelector(".profile-box");
      const avatarCircle = document.querySelector(".avatar-circle");

      if (avatarCircle)
        avatarCircle.addEventListener("click", () =>
          profileBox.classList.toggle("show"),
        );

            
      // Multi Step Form
      let currentStep = 1;

      function setStep(step) {
        for (let i = 1; i <= 4; i++) {
          const sec = document.getElementById("sec" + i);
          const tab = document.getElementById("tab" + i);
          if (sec) sec.style.display = i === step ? "block" : "none";
          if (tab)
            tab.className =
              "step-item" + (i === step ? " active" : "") + (i < step ? " done" : "");
        }
        currentStep = step;
        window.scrollTo({ top: 0, behavior: "smooth" });
      }

      function nextStep(from) {
        if (from === 1) {
          const fields = ["nama_pria", "nama_wanita", "ayah_pria", "ayah_wanita"];
          const empty = fields.some(
            (name) => !document.querySelector(`[name=${name}]`)?.value.trim(),
          );
          if (empty)
            return showAlert("error", "Data pengantin & orang tua wajib diisi!");
        }

        if (from === 2) {
          const required = [
            "tanggal_nikah",
            "lokasi",
            "waktu_mulai",
            "waktu_selesai",
          ];
          const empty = required.some(
            (name) => !document.querySelector(`[name=${name}]`)?.value.trim(),
          );
          if (empty)
            return showAlert("error", "Tanggal, waktu, dan lokasi wajib diisi!");
        }

        hideAlert();
        setStep(from + 1);
      }

      function prevStep(from) {
        setStep(from - 1);
      }

      function pilihTema(el, nama) {
        document
          .querySelectorAll(".tema-card")
          .forEach((card) => card.classList.remove("active"));
        el.classList.add("active");
        const tema = document.getElementById("temaValue");
        if (tema) tema.value = nama;
      }

      function showAlert(type, message) {
        hideAlert();
        const target = document.getElementById(
          type === "error" ? "alertError" : "alertSuccess",
        );
        if (!target) return;
        target.textContent = message;
        target.style.display = "block";
        target.scrollIntoView({ behavior: "smooth", block: "center" });
      }

      function hideAlert() {
        const error = document.getElementById("alertError");
        const success = document.getElementById("alertSuccess");
        if (error) error.style.display = "none";
        if (success) success.style.display = "none";
      }

      function toggleLoading(show) {
        const overlay = document.getElementById("loadingOverlay");
        if (!overlay) return;
        overlay.classList.toggle("show", show);
      }

  </script>

</body>

</html>