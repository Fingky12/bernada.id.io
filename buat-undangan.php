<?php 
  session_start();
  require_once 'config/koneksi.php';
  
  $name = $_SESSION['name'] ?? null;
  $alerts = $_SESSION['alerts'] ?? [];
  $active_form = $_SESSION['active_form'] ?? '';

  if(!isset($_SESSION['name'])){
      header("Location: dashboard.php");
      exit;
  }
  
  session_unset();
  
  if ($name !== null) $_SESSION['name'] = $name;
  
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/footer_header.css">
  <link rel="stylesheet" href="css/buat-undangan.css">
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

  <div class="container">
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

  <!-- MAIN -->
    <!-- ALERT -->
    <div class="alert alert-error"   id="alertError"></div>
    <div class="alert alert-success" id="alertSuccess"></div>
    <!-- FORM -->
    <form id="formUndangan" novalidate>

      <!-- STEP 1 - PENGANTIN -->
      <div class="card step-section" id="sec1">
        <div class="card-title"><i class='bx bxs-book-heart' ></i> Data Pengantin</div>
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
          <button type="button" class="btn btn-primary" onclick="nextStep(1)">Lanjut <i class='bx bx-right-arrow-alt' ></i></button>
        </div>
      </div>

      <!-- STEP 2 - ACARA -->
      <div class="card step-section" id="sec2" style="display:none">
        <div class="card-title"><i class='bx bx-calendar-heart' ></i> Info Acara</div>
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
          <button type="button" class="btn btn-outline" onclick="prevStep(2)"><i class='bx bx-left-arrow-alt' ></i> Kembali</button>
          <button type="button" class="btn btn-primary" onclick="nextStep(2)">Lanjut <i class='bx bx-right-arrow-alt' ></i></button>
        </div>
      </div>

      <!-- STEP 3 - TEMA -->
      <div class="card step-section" id="sec3" style="display:none">
        <div class="card-title"><i class='bx bx-palette' ></i> Pilih Tema</div>
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
          <button type="button" class="btn btn-outline" onclick="prevStep(3)"><i class='bx bx-left-arrow-alt' ></i> Kembali</button>
          <button type="button" class="btn btn-primary" onclick="nextStep(3)">Lanjut <i class='bx bx-right-arrow-alt' ></i></i></button>
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
          <button type="button" class="btn btn-outline" onclick="prevStep(4)"><i class='bx bx-left-arrow-alt' ></i> Kembali</button>
          <button type="submit" class="btn btn-primary"><i class='bx bx-send' ></i> Buat Undangan Sekarang</button>
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
        <a href="/" class="btn btn-outline" style="text-decoration:none;text-align:center;max-width:200px">← Beranda</a>
      </div>
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
<script src="script.js"></script>
    <!-- <script>

  let currentStep = 1;

  function setStep(step) {
    for (let i = 1; i <= 4; i++) {
      const sec = document.getElementById('sec' + i);
      const tab = document.getElementById('tab' + i);
      sec.style.display = (i === step) ? 'block' : 'none';
      tab.className = 'step-item' + (i === step ? ' active' : '') + (i < step ? ' done' : '');
    }
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function nextStep(from) {
    if (from === 1) {
      const p = document.querySelector('[name=nama_pria]').value.trim();
      const w = document.querySelector('[name=nama_wanita]').value.trim();
      if (!p || !w) { showAlert('error', 'Nama pengantin pria dan wanita wajib diisi!'); return; }
    }
    if (from === 2) {
      const tgl = document.querySelector('[name=tanggal_nikah]').value;
      const lok = document.querySelector('[name=lokasi]').value.trim();
      const wm  = document.querySelector('[name=waktu_mulai]').value;
      const ws  = document.querySelector('[name=waktu_selesai]').value;
      if (!tgl || !lok || !wm || !ws) { showAlert('error', 'Tanggal, waktu, dan lokasi wajib diisi!'); return; }
    }
    hideAlert();
    setStep(from + 1);
  }

  function prevStep(from) { setStep(from - 1); }

  function pilihTema(el, nama) {
    document.querySelectorAll('.tema-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('temaValue').value = nama;
  }

  function showAlert(type, msg) {
    hideAlert();
    const el = document.getElementById(type === 'error' ? 'alertError' : 'alertSuccess');
    el.textContent = msg;
    el.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  function hideAlert() {
    document.getElementById('alertError').style.display   = 'none';
    document.getElementById('alertSuccess').style.display = 'none';
  }

  // Submit form via AJAX
  document.getElementById('formUndangan').addEventListener('submit', function(e) {
    e.preventDefault();
    const wa = document.querySelector('[name=no_whatsapp]').value.trim();
    if (!wa) { showAlert('error', 'Nomor WhatsApp wajib diisi!'); return; }

    document.getElementById('loadingOverlay').classList.add('show');
    hideAlert();

    const formData = new FormData(this);

    fetch('proses_undangan.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        document.getElementById('loadingOverlay').classList.remove('show');
        if (data.status === 'success') {
          document.getElementById('formUndangan').style.display = 'none';
          document.getElementById('successPage').style.display  = 'block';
          document.getElementById('kodeUndangan').textContent   = data.kode;
          document.getElementById('waTarget').textContent       = wa;
          document.querySelector('.steps-bar').style.display    = 'none';
          window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
          showAlert('error', data.message || 'Terjadi kesalahan, coba lagi.');
        }
      })
      .catch(() => {
        document.getElementById('loadingOverlay').classList.remove('show');
        showAlert('error', 'Gagal terhubung ke server. Periksa koneksi kamu.');
      });
  });
</script> -->
</body>
</html>
