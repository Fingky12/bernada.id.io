<?php
session_start();
require_once 'config/koneksi.php';

$name = $_SESSION['name'] ?? null;
// Redirect ke login kalau belum login
if (!$name) {
    header('Location: dashboard.php?redirect=buat-undangan');
    exit;
}
session_unset();
?>



<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buat Undangan Digital – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/footer_header_sec.css" />
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
  <link rel="manifest" href="favicon_io/site.webmanifest">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    :root {
      --r: #C0393B;
      --rd: #8a2020;
      --rl: #fff5f5;
      --rm: #fde0e0;
      --dark: #1a1a1a;
      --gray: #5a5a5a;
      --light: #f8f5f5;
      --white: #fff;
      --border: #e8e0e0;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--light);
      color: var(--dark)
    }

    /* ── HERO ── */
    .page-hero {
      background: linear-gradient(135deg, #1a0505 0%, var(--rd) 40%, var(--r) 100%);
      padding: 4.5rem 2rem 3.5rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .page-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .page-hero::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--rd), var(--r), #e05555)
    }

    .hero-tag {
      display: inline-block;
      background: rgba(255, 255, 255, .12);
      color: rgba(255, 255, 255, .85);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .12em;
      text-transform: uppercase;
      padding: 5px 16px;
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, .2);
      margin-bottom: 1rem;
      position: relative
    }

    .page-hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 4.5vw, 3rem);
      color: #fff;
      margin-bottom: .75rem;
      position: relative
    }

    .page-hero p {
      font-size: 14px;
      color: rgba(255, 255, 255, .7);
      position: relative
    }

    /* ── STEPS BAR ── */
    .steps-bar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0;
      overflow-x: auto;
      padding: 0 1rem
    }

    .step-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 14px 20px;
      font-size: 13px;
      color: #aaa;
      white-space: nowrap;
      border-bottom: 3px solid transparent;
      transition: all .2s;
      cursor: default
    }

    .step-item.active {
      color: var(--r);
      border-color: var(--r)
    }

    .step-item.done {
      color: #2e9e5b
    }

    .step-num {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #eee;
      color: #888;
      font-size: 11px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0
    }

    .step-item.active .step-num {
      background: var(--r);
      color: #fff
    }

    .step-item.done .step-num {
      background: #2e9e5b;
      color: #fff
    }

    .step-arrow {
      color: #ddd;
      font-size: 12px;
      padding: 0 2px
    }

    /* ── LAYOUT ── */
    .layout {
      max-width: 900px;
      margin: 2.5rem auto;
      padding: 0 1.5rem 5rem;
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 2rem;
      align-items: start
    }

    /* ── CARD ── */
    .card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.75rem 2rem;
      margin-bottom: 1.25rem
    }

    .card-title {
      font-size: 13px;
      font-weight: 600;
      color: var(--r);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .card-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--rm)
    }

    .card-title i {
      font-size: 18px
    }

    /* ── FIELDS ── */
    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-bottom: 1rem
    }

    .field {
      margin-bottom: 1rem
    }
    .field-time {
      display: flex;
      flex-direction: column;
      gap: 8px
    }
    .field-time .waktu {
      margin-bottom: .5rem;
    }
    
    label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #444;
      margin-bottom: 5px
    }

    label .req {
      color: var(--r)
    }

    input[type=text],
    input[type=date],
    input[type=time],
    input[type=tel],
    input[type=email],
    input[type=url],
    select,
    textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #e0e0e0;
      border-radius: 9px;
      font-size: 14px;
      font-family: inherit;
      color: var(--dark);
      background: #fafafa;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: var(--r);
      box-shadow: 0 0 0 3px rgba(192, 57, 59, .08);
      background: #fff
    }

    textarea {
      resize: vertical;
      min-height: 80px
    }

    small.hint {
      font-size: 11px;
      color: #aaa;
      margin-top: 4px;
      display: block
    }

    /* ── TEMA GRID ── */
    .tema-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px
    }

    .tema-opt {
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      overflow: hidden;
      cursor: pointer;
      transition: all .2s;
      position: relative
    }

    .tema-opt:hover {
      border-color: var(--r)
    }

    .tema-opt.selected {
      border-color: var(--r);
      box-shadow: 0 0 0 3px rgba(192, 57, 59, .12)
    }

    .tema-opt input {
      display: none
    }

    .tema-preview {
      height: 90px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-align: center;
      padding: .5rem;
      font-size: 11px;
      gap: 3px
    }

    .tp-merah {
      background: linear-gradient(135deg, #1a0505, #C0393B)
    }

    .tp-navy {
      background: linear-gradient(135deg, #0a1628, #2d4a6e)
    }

    .tp-pink {
      background: linear-gradient(135deg, #3d1520, #e8a0b0)
    }

    .tp-sage {
      background: linear-gradient(135deg, #1e2d1f, #3d6b44)
    }

    .tp-rustic {
      background: linear-gradient(135deg, #2c1a0e, #8b6340)
    }

    .tp-name {
      font-weight: 600;
      font-size: 12px
    }

    .tp-sub {
      font-size: 10px;
      opacity: .6;
      letter-spacing: .08em
    }

    .tema-label {
      padding: 6px 8px;
      font-size: 11px;
      font-weight: 600;
      text-align: center;
      color: var(--dark)
    }

    .tema-opt.selected .tema-label {
      color: var(--r)
    }

    .tema-check {
      position: absolute;
      top: 6px;
      right: 6px;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: var(--r);
      color: #fff;
      font-size: 12px;
      display: none;
      align-items: center;
      justify-content: center
    }

    .tema-opt.selected .tema-check {
      display: flex
    }

    /* ── PAKET CARDS ── */
    .paket-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px
    }

    .paket-opt {
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      padding: 1rem;
      cursor: pointer;
      transition: all .2s;
      text-align: center;
      position: relative
    }

    .paket-opt:hover {
      border-color: var(--r)
    }

    .paket-opt.selected {
      border-color: var(--r);
      background: var(--rl)
    }

    .paket-opt input {
      display: none
    }

    .paket-icon {
      font-size: 1.8rem;
      margin-bottom: .4rem
    }

    .paket-name {
      font-size: 14px;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: .2rem
    }

    .paket-price {
      font-size: 16px;
      font-weight: 700;
      color: var(--r);
      margin-bottom: .5rem
    }

    .paket-fitur {
      font-size: 11px;
      color: var(--gray);
      line-height: 1.6;
      text-align: left
    }

    .paket-fitur li {
      list-style: none;
      padding-left: 14px;
      position: relative
    }

    .paket-fitur li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--r);
      font-weight: 700
    }

    .paket-badge {
      position: absolute;
      top: -8px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--r);
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 10px;
      border-radius: 20px;
      white-space: nowrap
    }

    /* ── UPLOAD BUKTI ── */
    .upload-area {
      border: 2px dashed #e0e0e0;
      border-radius: 10px;
      padding: 1.5rem;
      text-align: center;
      cursor: pointer;
      transition: all .2s;
      position: relative
    }

    .upload-area:hover {
      border-color: var(--r);
      background: var(--rl)
    }

    .upload-area input {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%
    }

    .upload-area i {
      font-size: 2rem;
      color: #ccc;
      margin-bottom: .5rem;
      display: block
    }

    .upload-area p {
      font-size: 13px;
      color: #aaa
    }

    .upload-area .uploaded {
      font-size: 13px;
      color: var(--r);
      font-weight: 500;
      margin-top: .5rem
    }

    #uploadPreview {
      max-width: 100%;
      border-radius: 8px;
      margin-top: .75rem;
      display: none
    }

    /* ── SIDE SUMMARY ── */
    .summary-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      position: sticky;
      top: 80px
    }

    .summary-card h3 {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 1.25rem;
      padding-bottom: .75rem;
      border-bottom: 1px solid var(--rm);
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--r)
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      font-size: 13px;
      margin-bottom: .75rem;
      gap: 8px
    }

    .summary-row .lbl {
      color: var(--gray);
      flex-shrink: 0
    }

    .summary-row .val {
      font-weight: 500;
      text-align: right;
      color: var(--dark)
    }

    .summary-divider {
      border: none;
      border-top: 1px dashed #e0e0e0;
      margin: .75rem 0
    }

    .summary-total {
      display: flex;
      justify-content: space-between;
      font-size: 16px;
      font-weight: 700;
      color: var(--r)
    }

    .summary-note {
      font-size: 11px;
      color: #aaa;
      margin-top: .5rem;
      line-height: 1.6
    }

    /* ── ALERTS ── */
    .alert {
      padding: 12px 16px;
      border-radius: 9px;
      font-size: 14px;
      margin-bottom: 1.25rem;
      display: none;
      align-items: center;
      gap: 10px
    }

    .alert-error {
      background: #fdeaea;
      color: #a32d2d;
      border: 1px solid #f5c1c1
    }

    .alert-success {
      background: #eaf7f0;
      color: #1a6640;
      border: 1px solid #5cb88a
    }

    /* ── BTNS ── */
    .btn-row {
      display: flex;
      gap: 10px;
      margin-top: 1.5rem
    }

    .btn {
      flex: 1;
      padding: 13px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all .2s;
      font-family: inherit;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px
    }

    .btn-primary {
      background: var(--r);
      color: #fff
    }

    .btn-primary:hover {
      background: var(--rd)
    }

    .btn-outline {
      background: var(--white);
      color: var(--r);
      border: 2px solid var(--r)
    }

    .btn-outline:hover {
      background: var(--rl)
    }

    /* ── LOADING OVERLAY ── */
    #loadingOverlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 999;
      align-items: center;
      justify-content: center
    }

    #loadingOverlay.show {
      display: flex
    }

    .spinner-box {
      background: #fff;
      border-radius: 14px;
      padding: 2rem 2.5rem;
      text-align: center
    }

    .spinner {
      width: 44px;
      height: 44px;
      border: 4px solid var(--rm);
      border-top-color: var(--r);
      border-radius: 50%;
      animation: spin .8s linear infinite;
      margin: 0 auto 14px
    }

    @keyframes spin {
      to {
        transform: rotate(360deg)
      }
    }

    .spinner-box p {
      font-size: 14px;
      color: #555
    }

    /* ── SUCCESS PAGE ── */
    .success-page {
      display: none;
      text-align: center;
      padding: 3rem 1.5rem
    }

    .success-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #eaf7f0;
      margin: 0 auto 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px
    }
    .success-icon img {
      width: 60px;
      height: 60px;
    }

    .success-page h2 {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      margin-bottom: .5rem
    }

    .success-page p {
      color: #666;
      font-size: 14px;
      line-height: 1.8;
      margin-bottom: .4rem
    }

    .kode-box {
      background: var(--rl);
      border: 1.5px dashed var(--r);
      border-radius: 10px;
      padding: 14px 20px;
      margin: 1.25rem auto;
      max-width: 320px
    }

    .kode-box .lbl {
      font-size: 12px;
      color: #aaa;
      margin-bottom: 4px
    }

    .kode-box .kode {
      font-size: 22px;
      font-weight: 700;
      color: var(--r);
      letter-spacing: .1em
    }

    .info-bayar {
      background: #fff3e0;
      border: 1px solid #ffd080;
      border-radius: 10px;
      padding: 1rem 1.25rem;
      margin: 1rem auto;
      max-width: 480px;
      font-size: 13px;
      color: #7a4f00;
      text-align: left;
      line-height: 1.8
    }

    .info-bayar strong {
      display: block;
      margin-bottom: .4rem;
      font-size: 14px
    }

    @media(max-width:768px) {
      .layout {
        grid-template-columns: 1fr
      }

      .field-row {
        grid-template-columns: 1fr
      }

      .tema-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .paket-grid {
        grid-template-columns: 1fr
      }

      .summary-card {
        position: static
      }
    }
  </style>
</head>

<body>
  <?php include("header/inc_header_second.php") ?>

  <!-- HERO -->
  <div class="page-hero">
    <div class="hero-tag">Buat Undangan Digital</div>
    <h1>Wujudkan Undangan Impianmu</h1>
    <p>Isi data pernikahan, pilih tema & paket, dan undangan siap dalam hitungan menit!</p>
  </div>

  <!-- STEPS BAR -->
  <div class="steps-bar">
    <div class="step-item active" id="tab1"><span class="step-num">1</span> Data Pengantin</div>
    <span class="step-arrow">›</span>
    <div class="step-item" id="tab2"><span class="step-num">2</span> Info Acara</div>
    <span class="step-arrow">›</span>
    <div class="step-item" id="tab3"><span class="step-num">3</span> Pilih Tema</div>
    <span class="step-arrow">›</span>
    <div class="step-item" id="tab4"><span class="step-num">4</span> Paket & Bayar</div>
    <span class="step-arrow">›</span>
    <div class="step-item" id="tab5"><span class="step-num">5</span> Kontak</div>
  </div>

  <div class="layout">
    <div class="form-side">

      <!-- ALERT -->
      <div class="alert alert-error" id="alertError"></div>
      <div class="alert alert-success" id="alertSuccess"></div>

      <form id="formOrder" enctype="multipart/form-data" novalidate>

        <!-- ── STEP 1: DATA PENGANTIN ── -->
        <div class="card step-section" id="sec1">
          <div class="card-title"><i class='bx bxs-book-heart'></i> Data Pengantin</div>
          <div class="field-row">
            <div class="field"><label>Nama Pengantin Pria <span class="req">*</span></label><input type="text" name="nama_pria" placeholder="cth. Raka" required /></div>
            <div class="field"><label>Nama Pengantin Wanita <span class="req">*</span></label><input type="text" name="nama_wanita" placeholder="cth. Dira" required /></div>
          </div>
          <div class="field-row">
            <div class="field"><label>Nama Ayah Pria</label><input type="text" name="ayah_pria" placeholder="cth. Bpk. Ahmad" /></div>
            <div class="field"><label>Nama Ibu Pria</label><input type="text" name="ibu_pria" placeholder="cth. Ibu Sari" /></div>
          </div>
          <div class="field-row">
            <div class="field"><label>Nama Ayah Wanita</label><input type="text" name="ayah_wanita" placeholder="cth. Bpk. Hendra" /></div>
            <div class="field"><label>Nama Ibu Wanita</label><input type="text" name="ibu_wanita" placeholder="cth. Ibu Dewi" /></div>
          </div>
          <div class="btn-row">
            <button type="button" class="btn btn-primary" onclick="nextStep(1)">Lanjut <i class='bx bx-right-arrow-alt'></i></button>
          </div>
        </div>

        <!-- ── STEP 2: INFO ACARA ── -->
        <div class="card step-section" id="sec2" style="display:none">
          <div class="card-title"><i class='bx bx-calendar-heart'></i> Info Acara</div>
          <div class="field"><label>Tanggal Pernikahan <span class="req">*</span></label><input type="date" name="tanggal_nikah" required /></div>
          <div class="field-row">
            <div class="field-time">
              <label>Acara Akad <span class="req">*</span></label>
              <label class="waktu" for="mulai_akad"><small style="color:#aaa">Mulai</small></label>
              <input type="time" name="mulai_akad" value="08:00" required />
              <label class="waktu" for="selesai_akad"><small style="color:#aaa">Selesai</small></label>
              <input type="time" name="selesai_akad" value="09:00" required />
            </div>
            <div class="field-time">
              <label>Acara Resepsi <span class="req">*</span></label>
              <label class="waktu" for="mulai_resepsi"><small style="color:#aaa">Mulai</small></label>
              <input type="time" name="mulai_resepsi" value="11:00" required />
              <label class="waktu" for="selesai_resepsi"><small style="color:#aaa">Selesai</small></label>
              <input type="time" name="selesai_resepsi" value="13:00" required />
            </div>
          </div>
          <div class="field"><label>Nama Gedung / Lokasi <span class="req">*</span></label><input type="text" name="lokasi" placeholder="cth. Villa Kebun Kopi, Batu Malang" required /></div>
          <div class="field"><label>Link Google Maps <small style="color:#aaa">(opsional)</small></label><input type="url" name="link_maps" placeholder="https://maps.google.com/..." /></div>
          <div class="field"><label>Catatan untuk Admin <small style="color:#aaa">(opsional)</small></label><textarea name="catatan" placeholder="cth. Mohon tambahkan foto pre-wedding kami..."></textarea></div>
          <div class="btn-row">
            <button type="button" class="btn btn-outline" onclick="prevStep(2)"><i class='bx bx-left-arrow-alt'></i> Kembali</button>
            <button type="button" class="btn btn-primary" onclick="nextStep(2)">Lanjut <i class='bx bx-right-arrow-alt'></i></button>
          </div>
        </div>

        <!-- ── STEP 3: PILIH TEMA ── -->
        <div class="card step-section" id="sec3" style="display:none">
          <div class="card-title"><i class='bx bx-palette'></i> Pilih Tema Undangan</div>
          <input type="hidden" name="tema" id="temaVal" value="merah-klasik" />
          <div class="tema-grid">
            <label class="tema-opt selected" onclick="pilihTema(this,'merah-klasik')">
              <div class="tema-preview tp-merah">
                <div class="tp-name">Merah Klasik</div>
                <div class="tp-sub">ELEGANT</div>
              </div>
              <div class="tema-label">Merah Klasik</div>
              <div class="tema-check">✓</div>
            </label>
            <label class="tema-opt" onclick="pilihTema(this,'navy-elegant')">
              <div class="tema-preview tp-navy">
                <div class="tp-name">Navy Elegant</div>
                <div class="tp-sub">FORMAL</div>
              </div>
              <div class="tema-label">Navy Elegant</div>
              <div class="tema-check">✓</div>
            </label>
            <label class="tema-opt" onclick="pilihTema(this,'blush-pink')">
              <div class="tema-preview tp-pink">
                <div class="tp-name">Blush Pink</div>
                <div class="tp-sub">ROMANTIC</div>
              </div>
              <div class="tema-label">Blush Pink</div>
              <div class="tema-check">✓</div>
            </label>
            <label class="tema-opt" onclick="pilihTema(this,'sage-garden')">
              <div class="tema-preview tp-sage">
                <div class="tp-name">Sage Garden</div>
                <div class="tp-sub">NATURAL</div>
              </div>
              <div class="tema-label">Sage Garden</div>
              <div class="tema-check">✓</div>
            </label>
            <label class="tema-opt" onclick="pilihTema(this,'rustic-brown')">
              <div class="tema-preview tp-rustic">
                <div class="tp-name">Rustic Brown</div>
                <div class="tp-sub">CLASSIC</div>
              </div>
              <div class="tema-label">Rustic Brown</div>
              <div class="tema-check">✓</div>
            </label>
          </div>
          <div class="btn-row" style="margin-top:1.5rem">
            <button type="button" class="btn btn-outline" onclick="prevStep(3)"><i class='bx bx-left-arrow-alt'></i> Kembali</button>
            <button type="button" class="btn btn-primary" onclick="nextStep(3)">Lanjut <i class='bx bx-right-arrow-alt'></i></button>
          </div>
        </div>

        <!-- ── STEP 4: PAKET & BAYAR ── -->
        <div class="card step-section" id="sec4" style="display:none">
          <div class="card-title"><i class='bx bx-purchase-tag'></i> Pilih Paket</div>
          <input type="hidden" name="paket" id="paketVal" value="silver" />
          <div class="paket-grid">
            <label class="paket-opt selected" onclick="pilihPaket(this,'silver',0)">
              <div class="paket-icon">🥈</div>
              <div class="paket-name">Silver</div>
              <div class="paket-price">Gratis</div>
              <ul class="paket-fitur">
                <li>1 Tema pilihan</li>
                <li>3 Foto</li>
                <li>Maks. 3 Tamu</li>
                <li>RSVP Online</li>
                <li>Aktif 30 hari</li>
                <li>Revisi 2×</li>
              </ul>
            </label>
            <label class="paket-opt" onclick="pilihPaket(this,'gold',95000)">
              <div class="paket-badge">✨ LARIS</div>
              <div class="paket-icon">🥇</div>
              <div class="paket-name">Gold</div>
              <div class="paket-price">Rp 95K</div>
              <ul class="paket-fitur">
                <li>5 Tema Premium</li>
                <li>10 Foto & 2 Video</li>
                <li>10 Tamu</li>
                <li>Musik Latar</li>
                <li>Aktif 30 hari</li>
                <li>Revisi 5×</li>
              </ul>
            </label>
            <label class="paket-opt" onclick="pilihPaket(this,'platinum',190000)">
              <div class="paket-icon">💎</div>
              <div class="paket-name">Platinum</div>
              <div class="paket-price">Rp 190K</div>
              <ul class="paket-fitur">
                <li>Semua Tema</li>
                <li>Foto Unlimited</li>
                <li>Tamu Unlimited</li>
                <li>Domain Sendiri</li>
                <li>Aktif 12 bulan</li>
                <li>Revisi Unlimited</li>
              </ul>
            </label>
          </div>

          <!-- Upload bukti (muncul kalau paket berbayar) -->
          <div id="sectionBukti" style="display:none;margin-top:1.5rem">
            <div style="background:#fff3e0;border:1px solid #ffd080;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1rem;font-size:13px;color:#7a4f00">
              <strong style="display:block;margin-bottom:.4rem"><i class='bx bx-credit-card' ></i> Info Pembayaran:</strong>
              Transfer ke rekening berikut, lalu upload bukti transfer:<br>
              <i class='bx bx-credit-card' ></i> <strong>BCA: 1234567890</strong> a.n. Bernada ID<br>
              <i class='bx bx-wallet-alt' ></i> <strong>DANA/GoPay/OVO: 0819-3919-5110</strong>
            </div>
            <div class="field" >
              <label>Metode Pembayaran</label>
              <select name="metode_bayar" id="metodeVal">
                <option value="">-- Pilih Metode --</option>
                <option value="BCA">Transfer BCA</option>
                <option value="Mandiri">Transfer Mandiri</option>
                <option value="GoPay">GoPay</option>
                <option value="OVO">OVO</option>
                <option value="Dana">Dana</option>
                <option value="QRIS">QRIS</option>
              </select>
            </div>
            <div class="field">
              <label>Upload Bukti Transfer <small style="color:#aaa">(opsional, bisa dikirim via WA)</small></label>
              <div class="upload-area" id="uploadArea">
                <input type="file" name="bukti_bayar" accept="image/*,.pdf" onchange="previewBukti(this)" />
                <i class='bx bx-cloud-upload'></i>
                <p>Klik atau drag file bukti transfer<br><small>JPG, PNG, PDF maks. 5MB</small></p>
                <div class="uploaded" id="uploadedName"></div>
              </div>
              <img id="uploadPreview" src="" alt="Preview bukti" />
            </div>
          </div>

          <div class="btn-row">
            <button type="button" class="btn btn-outline" onclick="prevStep(4)"><i class='bx bx-left-arrow-alt'></i> Kembali</button>
            <button type="button" class="btn btn-primary" onclick="nextStep(4)">Lanjut <i class='bx bx-right-arrow-alt'></i></button>
          </div>
        </div>

        <!-- ── STEP 5: KONTAK ── -->
        <div class="card step-section" id="sec5" style="display:none">
        <?php if (!$name): ?>
          <div style="background:#fff3e0;border:1px solid #ffd080;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1rem;font-size:13px;color:#7a4f00;display:flex;align-items:center;gap:10px">
            <i class='bx bx-lock' style="font-size:20px;flex-shrink:0"></i>
            <div>Kamu belum login. <a href="dashboard.php" style="color:#C0393B;font-weight:600">Login dulu</a> untuk melanjutkan pembuatan undangan.</div>
          </div>
        <?php endif ?>
          <div class="card-title"><i class='bx bxs-phone-call'></i> Kontak</div>
          <div class="field">
            <label>Nomor WhatsApp <span class="req">*</span></label>
            <input type="tel" name="no_whatsapp" placeholder="cth. 08123456789" required />
            <small class="hint">Link undangan & konfirmasi akan dikirim ke nomor ini</small>
          </div>
          <div class="field">
            <label>Email <small style="color:#aaa">(opsional)</small></label>
            <input type="email" name="email" placeholder="cth. raka@gmail.com" />
          </div>
          <div class="btn-row">
            <button type="button" class="btn btn-outline" onclick="prevStep(5)"><i class='bx bx-left-arrow-alt'></i> Kembali</button>
            <button type="submit" class="btn btn-primary"><i class='bx bx-send'></i> Buat Undangan Sekarang</button>
          </div>
        </div>

      </form>

      <!-- SUCCESS PAGE -->
      <div class="success-page card" id="successPage">
        <div class="success-icon"><img src="img/gif/confetti-ball.gif" alt="Selamat"></div>
        <h2>Pesanan Berhasil!</h2>
        <div class="kode-box">
          <div class="lbl">Kode Order Kamu</div>
          <div class="kode" id="successKode">-</div>
        </div>
        <div class="info-bayar" id="infoBayar"></div>
        <p style="color:#aaa;font-size:13px;margin-top:6px">Simpan kode order kamu untuk keperluan follow-up</p>
        <div class="btn-row" style="margin-top:1.5rem;justify-content:center;max-width:320px;margin-left:auto;margin-right:auto">
          <a href="halaman.php" class="btn btn-outline" style="text-decoration:none">← Beranda</a>
          <a href="https://wa.me/6281939195110" target="_blank" class="btn btn-primary" style="text-decoration:none"><i class='bx bxl-whatsapp'></i> Chat Admin</a>
        </div>
      </div>

    </div>

    <!-- ── SIDEBAR SUMMARY ── -->
    <div class="summary-card">
      <h3><i class='bx bx-receipt' style="font-size:18px"></i> Ringkasan Order</h3>
      <div class="summary-row"><span class="lbl">Pengantin</span><span class="val" id="sumNama">-</span></div>
      <div class="summary-row"><span class="lbl">Tanggal</span><span class="val" id="sumTgl">-</span></div>
      <div class="summary-row"><span class="lbl">Lokasi</span><span class="val" id="sumLokasi">-</span></div>
      <div class="summary-row"><span class="lbl">Mulai Akad</span><span class="val" id="sumMulaiAkad">-</span></div>
      <div class="summary-row"><span class="lbl">Selesai Akad</span><span class="val" id="sumSelesaiAkad">-</span></div>
      <div class="summary-row"><span class="lbl">Mulai Resepsi</span><span class="val" id="sumMulaiResepsi">-</span></div>
      <div class="summary-row"><span class="lbl">Selesai Resepsi</span><span class="val" id="sumSelesaiResepsi">-</span></div>
      <div class="summary-row"><span class="lbl">Tema</span><span class="val" id="sumTema">Merah Klasik</span></div>
      <div class="summary-row"><span class="lbl">Paket</span><span class="val" id="sumPaket">Silver</span></div>
      <hr class="summary-divider" />
      <div class="summary-total"><span>Total</span><span id="sumHarga">Gratis</span></div>
      <div class="summary-note" id="sumNote">Paket Silver aktif langsung setelah order — link undangan dikirim via WhatsApp.</div>
      <div style="margin-top:1rem;padding:10px;background:#f0fdf4;border-radius:8px;font-size:12px;color:#166534;display:flex;gap:6px;align-items:flex-start">
        <span><i class='bx bxs-lock'></i></span><span>Data kamu aman & terlindungi. Tidak dibagikan ke pihak manapun.</span>
      </div>
    </div>

  </div>

  <div id="loadingOverlay">
    <div class="spinner-box">
      <div class="spinner"></div>
      <p>Sedang memproses pesananmu...</p>
    </div>
  </div>

  <?php include("footer/inc_footer_second.php") ?>

  <script>
    const profileBox = document.querySelector(".profile-box");
    const avatarCircle = document.querySelector(".avatar-circle");

    if (avatarCircle)
      avatarCircle.addEventListener("click", () =>
        profileBox.classList.toggle("show"),
      );

    let currentStep = 1;
    const hargaMap = {
      silver: 0,
      gold: 95000,
      platinum: 190000
    };
    const temaMap = {
      'merah-klasik': 'Merah Klasik',
      'navy-elegant': 'Navy Elegant',
      'blush-pink': 'Blush Pink',
      'sage-garden': 'Sage Garden',
      'rustic-brown': 'Rustic Brown'
    };

    // ── Step navigation ──────────────────────────
    function setStep(step) {
      for (let i = 1; i <= 5; i++) {
        const sec = document.getElementById('sec' + i);
        const tab = document.getElementById('tab' + i);
        if (sec) sec.style.display = (i === step) ? 'block' : 'none';
        if (tab) tab.className = 'step-item' + (i === step ? ' active' : '') + (i < step ? ' done' : '');
      }
      currentStep = step;
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }

    function nextStep(from) {
      hideAlert();
      if (from === 1) {
        const p = q('[name=nama_pria]').value.trim();
        const w = q('[name=nama_wanita]').value.trim();
        const ap = q('[name=ayah_pria]').value.trim();
        const ip = q('[name=ibu_pria]').value.trim();
        const aw = q('[name=ayah_wanita]').value.trim();
        const iw = q('[name=ibu_wanita]').value.trim();
        if (!p || !w || !ap || !ip || !aw || !iw) {
          showAlert('error', 'Semua field wajib diisi!');
          return;
        }
        updateSummary('nama', p + ' & ' + w + ' (Anak dari ' + ap + ' & ' + ip + ' dan ' + aw + ' & ' + iw + ')');
      }
      if (from === 2) {
        const tgl = q('[name=tanggal_nikah]').value;
        const lok = q('[name=lokasi]').value.trim();
        const ma = q('[name=mulai_akad]').value.trim();
        const sa = q('[name=selesai_akad]').value.trim();
        const mr = q('[name=mulai_resepsi]').value.trim();
        const sr = q('[name=selesai_resepsi]').value.trim();
        if (!tgl || !lok || !ma || !sa || !mr || !sr) {
          showAlert('error', 'Tanggal, Acara dan lokasi wajib diisi!');
          return;
        }
        updateSummary('tgl', tgl);
        updateSummary('lokasi', lok);
        updateSummary('mulai_akad', 'Jam : ' + ma + ' WIB');
        updateSummary('selesai_akad', 'Jam : ' + sa + ' WIB');
        updateSummary('mulai_resepsi', 'Jam : ' + mr + ' WIB');
        updateSummary('selesai_resepsi', 'Jam : ' + sr + ' WIB');
      }
      if (from === 3) {
        if (!q('#temaVal').value) {
          showAlert('error', 'Pilih tema undangan terlebih dahulu!');
          return;
        }
      }
      setStep(from + 1);
    }

    function prevStep(from) {
      setStep(from - 1);
    }

    function q(sel) {
      return document.querySelector(sel);
    }

    // ── Tema ─────────────────────────────────────
    function pilihTema(el, val) {
      document.querySelectorAll('.tema-opt').forEach(t => t.classList.remove('selected'));
      el.classList.add('selected');
      q('#temaVal').value = val;
      updateSummary('tema', temaMap[val] || val);
    }

    // ── Paket ────────────────────────────────────
    function pilihPaket(el, val, harga) {
      document.querySelectorAll('.paket-opt').forEach(p => p.classList.remove('selected'));
      el.classList.add('selected');
      q('#paketVal').value = val;
      const hargaFmt = harga > 0 ? 'Rp ' + harga.toLocaleString('id-ID') : 'Gratis';
      updateSummary('paket', val.charAt(0).toUpperCase() + val.slice(1));
      updateSummary('harga', hargaFmt);
      // Tampilkan/sembunyikan section bukti bayar
      q('#sectionBukti').style.display = harga > 0 ? 'block' : 'none';
      const note = {
        silver: 'Paket Silver aktif langsung setelah order — link undangan dikirim via WhatsApp.',
        gold: 'Setelah transfer Rp 95.000, upload bukti dan admin akan konfirmasi dalam 1×24 jam.',
        platinum: 'Setelah transfer Rp 190.000, upload bukti dan admin akan konfirmasi dalam 1×24 jam.'
      };
      q('#sumNote').textContent = note[val] || '';
    }

    // ── Summary update ────────────────────────────
    function updateSummary(key, val) {
      const map = {
        nama: 'sumNama',
        tgl: 'sumTgl',
        lokasi: 'sumLokasi',
        mulai_akad: 'sumMulaiAkad',
        selesai_akad: 'sumSelesaiAkad',
        mulai_resepsi: 'sumMulaiResepsi',
        selesai_resepsi: 'sumSelesaiResepsi',
        tema: 'sumTema',
        paket: 'sumPaket',
        harga: 'sumHarga',
      };
      if (map[key]) {
        const el = q('#' + map[key]);
        if (el) el.textContent = val || '-';
      }
    }

    // ── Upload preview ────────────────────────────
    function previewBukti(input) {
      const file = input.files[0];
      if (!file) return;
      q('#uploadedName').textContent = '✅ ' + file.name;
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
          const img = q('#uploadPreview');
          img.src = e.target.result;
          img.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    }

    // ── Alert ─────────────────────────────────────
    function showAlert(type, msg) {
      hideAlert();
      const el = q(type === 'error' ? '#alertError' : '#alertSuccess');
      el.textContent = msg;
      el.style.display = 'flex';
      el.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });
    }

    function hideAlert() {
      q('#alertError').style.display = 'none';
      q('#alertSuccess').style.display = 'none';
    }

    // ── Submit ────────────────────────────────────
    q('#formOrder').addEventListener('submit', function(e) {
      e.preventDefault();
      const wa = q('[name=no_whatsapp]').value.trim();
      if (!wa) {
        showAlert('error', 'Nomor WhatsApp wajib diisi!');
        return;
      }

      q('#loadingOverlay').classList.add('show');
      hideAlert();

      const formData = new FormData(this);
      // Cek login sebelum submit
      <?php if (!$name): ?>
        showAlert('error', 'Kamu harus login dulu untuk membuat undangan!');
        document.getElementById('loadingOverlay').classList.remove('show');
        setTimeout(() => { window.location.href = 'dashboard.php'; }, 1500);
        return;
      <?php endif ?>

      fetch('config/proses_order.php', {
          method: 'POST',
          body: formData
        })
        .then(r => r.json())
        .then(data => {
          q('#loadingOverlay').classList.remove('show');
          if (data.status === 'success') {
            // Sembunyikan form
            q('#formOrder').style.display = 'none';
            q('.steps-bar').style.display = 'none';
            document.querySelectorAll('.card.step-section').forEach(c => c.style.display = 'none');

            // Tampilkan success
            const sp = q('#successPage');
            sp.style.display = 'block';
            q('#successKode').textContent = data.kode_order;

            // Info bayar berdasarkan paket
            const ib = q('#infoBayar');
            if (data.paket === 'silver') {
              ib.innerHTML = '<strong><i class="bx bx-donate-heart bx-tada"></i> Undangan Gratis Langsung Aktif!</strong>Link undangan sudah dikirim ke WhatsApp kamu. Cek segera!';
              ib.style.background = '#e8f7f0';
              ib.style.borderColor = '#5cb88a';
              ib.style.color = '#1a6640';
            } else {
              ib.innerHTML = '<strong><i class="bx bx-loader-circle bx-spin" ></i> Menunggu Konfirmasi Pembayaran</strong>Setelah pembayaran dikonfirmasi admin, link undangan akan dikirim ke WhatsApp kamu dalam 1×24 jam.<br><br>Kode order: <strong>' + data.kode_order + '</strong>';
            }
          } else {
            showAlert('error', data.message || 'Terjadi kesalahan, coba lagi.');
          }
        })
        .catch(() => {
          q('#loadingOverlay').classList.remove('show');
          showAlert('error', 'Gagal terhubung ke server, Sudah Login? Coba lagi.');
        });
    });
  </script>
</body>

</html>