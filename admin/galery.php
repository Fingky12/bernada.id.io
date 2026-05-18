<?php
// ============================================
// FILE: galery.php (di root WebDev/)
// Halaman upload & kelola foto galery customer
// ============================================
session_start();
require_once '../config/koneksi.php';

$name = $_SESSION['name'] ?? null;
if (!$name) {
  header('Location: admin_dashboard.php?redirect=galery');
  exit;
}

// Ambil kode_order dari URL
$kode_order = trim($_GET['order'] ?? '');

// Ambil semua order aktif milik customer
$orders_aktif = $pdo->query("SELECT * FROM orders WHERE status_order='aktif' ORDER BY created_at DESC")->fetchAll();

// Jika ada kode_order di URL, ambil datanya
$order_aktif = null;
$fotos       = [];
$max_foto    = 3;

if ($kode_order) {
  $stmt = $pdo->prepare("SELECT * FROM orders WHERE kode_order=? AND status_order='aktif'");
  $stmt->execute([$kode_order]);
  $order_aktif = $stmt->fetch();

  if ($order_aktif) {
    $batas   = ['silver' => 3, 'gold' => 10, 'platinum' => 999];
    $max_foto = $batas[$order_aktif['paket']] ?? 3;

    $stmt2 = $pdo->prepare("SELECT * FROM galery_foto WHERE kode_order=? AND status='aktif' ORDER BY urutan ASC");
    $stmt2->execute([$kode_order]);
    $fotos = $stmt2->fetchAll();
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>galery Foto – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/footer_header_sec.css" />
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
      --light: #f7f4f4;
      --white: #fff;
      --border: #ece4e4;
      --green: #2e9e5b;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--light);
      color: var(--dark)
    }

    /* HERO */
    .page-hero {
      background: linear-gradient(135deg, #1a0505 0%, var(--rd) 50%, var(--r) 100%);
      padding: 3.5rem 8% 4.5rem;
      position: relative;
      overflow: hidden;
    }

    .page-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")
    }

    .hero-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
      position: relative
    }

    .hero-text h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.5rem, 3.5vw, 2.2rem);
      color: #fff;
      margin-bottom: .4rem
    }

    .hero-text p {
      font-size: 13px;
      color: rgba(255, 255, 255, .65)
    }

    .hero-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: rgba(255, 255, 255, .12);
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      border: 1px solid rgba(255, 255, 255, .2);
      transition: background .2s
    }

    .hero-back:hover {
      background: rgba(255, 255, 255, .2)
    }

    /* MAIN LAYOUT */
    .main-wrap {
      max-width: 960px;
      margin: -2rem auto 0;
      padding: 0 1.5rem 4rem;
      position: relative;
      z-index: 1
    }

    /* ORDER SELECTOR */
    .order-selector {
      background: var(--white);
      border: 0.5px solid var(--border);
      border-radius: 14px;
      padding: 1.25rem 1.5rem;
      margin-bottom: 1.5rem
    }

    .order-selector h3 {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 7px;
      color: var(--dark)
    }

    .order-selector h3 i {
      color: var(--r)
    }

    .order-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 10px
    }

    .order-opt {
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: .9rem 1.1rem;
      cursor: pointer;
      text-decoration: none;
      transition: all .2s;
      display: block;
    }

    .order-opt:hover {
      border-color: var(--r);
      background: var(--rl)
    }

    .order-opt.selected {
      border-color: var(--r);
      background: var(--rl)
    }

    .order-opt .op-nama {
      font-size: 14px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 3px
    }

    .order-opt .op-meta {
      font-size: 11px;
      color: var(--gray);
      display: flex;
      align-items: center;
      gap: 6px
    }

    .op-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%
    }

    .badge-paket {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 20px;
      margin-left: auto
    }

    .bp-silver {
      background: #f0f0f0;
      color: #666
    }

    .bp-gold {
      background: #fff8e0;
      color: #a06000
    }

    .bp-platinum {
      background: #f0e8f8;
      color: #6a2d9e
    }

    /* UPLOAD SECTION */
    .galery-wrap {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 1.5rem;
      align-items: start
    }

    /* Foto grid */
    .foto-section {
      background: var(--white);
      border: 0.5px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem
    }

    .sec-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.25rem
    }

    .sec-head h3 {
      font-size: 15px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 7px
    }

    .sec-head h3 i {
      color: var(--r);
      font-size: 18px
    }

    .quota-bar-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 12px;
      color: var(--gray)
    }

    .quota-bar {
      width: 100px;
      height: 6px;
      background: #f0ece8;
      border-radius: 99px;
      overflow: hidden;
      flex-shrink: 0
    }

    .quota-fill {
      height: 100%;
      border-radius: 99px;
      background: var(--r);
      transition: width .4s
    }

    .quota-fill.ok {
      background: var(--green)
    }

    .quota-fill.warn {
      background: #c9a227
    }

    .quota-fill.full {
      background: var(--r)
    }

    /* Foto grid display */
    .foto-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      min-height: 180px
    }

    .foto-item {
      position: relative;
      border-radius: 10px;
      overflow: hidden;
      aspect-ratio: 1;
      background: var(--light);
      border: 0.5px solid var(--border);
      cursor: grab;
      transition: transform .2s;
    }

    .foto-item:active {
      cursor: grabbing
    }

    .foto-item:hover .foto-overlay {
      opacity: 1
    }

    .foto-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block
    }

    .foto-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, .55);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 8px;
      opacity: 0;
      transition: opacity .2s;
    }

    .fo-btns {
      display: flex;
      gap: 6px
    }

    .fo-btn {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      transition: transform .2s;
    }

    .fo-btn:hover {
      transform: scale(1.1)
    }

    .fo-edit {
      background: rgba(255, 255, 255, .9);
      color: var(--dark)
    }

    .fo-del {
      background: rgba(192, 57, 59, .9);
      color: #fff
    }

    .fo-caption {
      font-size: 11px;
      color: rgba(255, 255, 255, .8);
      text-align: center;
      padding: 0 8px;
      max-width: 100%;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .foto-num {
      position: absolute;
      top: 6px;
      left: 6px;
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: rgba(0, 0, 0, .5);
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .foto-drag-hint {
      position: absolute;
      top: 6px;
      right: 6px;
      color: rgba(255, 255, 255, .6);
      font-size: 14px;
    }

    /* Empty foto state */
    .foto-empty {
      grid-column: 1/-1;
      text-align: center;
      padding: 2.5rem 1rem;
      color: #bbb;
    }

    .foto-empty i {
      font-size: 3rem;
      margin-bottom: .75rem;
      display: block
    }

    .foto-empty p {
      font-size: 13px
    }

    /* Upload panel */
    .upload-panel {
      background: var(--white);
      border: 0.5px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      position: sticky;
      top: 80px
    }

    .upload-panel h3 {
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 1.1rem;
      display: flex;
      align-items: center;
      gap: 7px
    }

    .upload-panel h3 i {
      color: var(--r);
      font-size: 18px
    }

    /* Drop zone */
    .drop-zone {
      border: 2px dashed var(--border);
      border-radius: 12px;
      padding: 2rem 1.5rem;
      text-align: center;
      cursor: pointer;
      transition: all .25s;
      position: relative;
      background: var(--light);
    }

    .drop-zone.dragover {
      border-color: var(--r);
      background: var(--rl)
    }

    .drop-zone input[type=file] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%
    }

    .drop-zone .dz-icon {
      font-size: 2.5rem;
      color: #ddd;
      margin-bottom: .75rem;
      display: block;
      transition: color .2s
    }

    .drop-zone:hover .dz-icon,
    .drop-zone.dragover .dz-icon {
      color: var(--r)
    }

    .drop-zone h4 {
      font-size: 14px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: .3rem
    }

    .drop-zone p {
      font-size: 12px;
      color: #aaa;
      line-height: 1.6
    }

    .drop-zone .dz-limit {
      margin-top: .75rem;
      font-size: 11px;
      font-weight: 600;
      color: var(--r);
      background: var(--rl);
      padding: 4px 12px;
      border-radius: 20px;
      display: inline-block;
    }

    /* Preview sebelum upload */
    .preview-list {
      margin-top: 1rem
    }

    .preview-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: .6rem;
      background: var(--light);
      border-radius: 8px;
      margin-bottom: 6px;
    }

    .preview-thumb {
      width: 44px;
      height: 44px;
      border-radius: 7px;
      object-fit: cover;
      flex-shrink: 0
    }

    .preview-info {
      flex: 1;
      min-width: 0
    }

    .preview-name {
      font-size: 12px;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis
    }

    .preview-size {
      font-size: 11px;
      color: var(--gray)
    }

    .preview-del {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: none;
      background: #fdeaea;
      color: var(--rd);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0
    }

    .preview-del:hover {
      background: var(--rd);
      color: #fff
    }

    /* Btn upload */
    .btn-upload {
      width: 100%;
      padding: 12px;
      background: var(--r);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      transition: background .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 1rem;
    }

    .btn-upload:hover {
      background: var(--rd)
    }

    .btn-upload:disabled {
      background: #ccc;
      cursor: not-allowed
    }

    /* Tips */
    .tips-box {
      background: #f0f7ff;
      border: 0.5px solid #b8d4f8;
      border-radius: 10px;
      padding: .9rem 1rem;
      margin-top: 1rem;
    }

    .tips-box h4 {
      font-size: 12px;
      font-weight: 600;
      color: #1a4a7a;
      margin-bottom: .4rem;
      display: flex;
      align-items: center;
      gap: 5px
    }

    .tips-box ul {
      font-size: 11px;
      color: #2d5a8a;
      line-height: 1.8;
      padding-left: 14px
    }

    /* Locked state */
    .locked-overlay {
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, .85);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      gap: .75rem;
      padding: 1.5rem;
      text-align: center;
    }

    .locked-overlay i {
      font-size: 2.5rem;
      color: #ccc
    }

    .locked-overlay p {
      font-size: 13px;
      color: var(--gray)
    }

    /* Caption modal */
    .caption-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 999;
      align-items: center;
      justify-content: center
    }

    .caption-modal.show {
      display: flex
    }

    .caption-box {
      background: var(--white);
      border-radius: 14px;
      padding: 1.75rem;
      max-width: 400px;
      width: 100%;
      margin: 1rem
    }

    .caption-box h3 {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: .3rem
    }

    .caption-box p {
      font-size: 13px;
      color: var(--gray);
      margin-bottom: 1.1rem
    }

    .caption-box img {
      width: 100%;
      border-radius: 10px;
      object-fit: cover;
      max-height: 180px;
      margin-bottom: 1rem
    }

    .caption-box input {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #e0e0e0;
      border-radius: 9px;
      font-size: 14px;
      font-family: inherit;
      outline: none;
      background: #fafafa
    }

    .caption-box input:focus {
      border-color: var(--r)
    }

    .caption-btns {
      display: flex;
      gap: 8px;
      margin-top: 1rem
    }

    .cbtn-cancel {
      flex: 1;
      padding: 10px;
      border-radius: 8px;
      background: var(--white);
      color: var(--gray);
      border: 1px solid #e0e0e0;
      font-size: 13px;
      cursor: pointer;
      font-family: inherit
    }

    .cbtn-ok {
      flex: 2;
      padding: 10px;
      border-radius: 8px;
      background: var(--r);
      color: #fff;
      border: none;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit
    }

    /* Loading */
    .upload-progress {
      display: none;
      text-align: center;
      padding: 1rem;
    }

    .prog-bar-wrap {
      height: 6px;
      background: #f0ece8;
      border-radius: 99px;
      overflow: hidden;
      margin: 10px 0
    }

    .prog-bar {
      height: 100%;
      background: var(--r);
      border-radius: 99px;
      width: 0;
      transition: width .3s
    }

    .prog-text {
      font-size: 12px;
      color: var(--gray)
    }

    /* Toast */
    .toast {
      position: fixed;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%) translateY(20px);
      background: var(--dark);
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      padding: 10px 22px;
      border-radius: 50px;
      opacity: 0;
      transition: all .3s;
      pointer-events: none;
      z-index: 9999;
      white-space: nowrap
    }

    .toast.show {
      opacity: 1;
      transform: translateX(-50%) translateY(0)
    }

    .toast.success {
      background: var(--green)
    }

    .toast.error {
      background: var(--rd)
    }

    /* Alert box */
    .alert-info {
      background: #f0f7ff;
      border: 0.5px solid #b8d4f8;
      border-radius: 10px;
      padding: .9rem 1rem;
      font-size: 13px;
      color: #1a4a7a;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .alert-warn {
      background: #fff8e0;
      border: 0.5px solid #f0d070;
      border-radius: 10px;
      padding: .9rem 1rem;
      font-size: 13px;
      color: #7a4f00;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 8px
    }

    @media(max-width:768px) {
      .galery-wrap {
        grid-template-columns: 1fr
      }

      .upload-panel {
        position: static
      }

      .foto-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .main-wrap {
        padding: 0 1rem 3rem
      }
    }
  </style>
</head>

<body>
  <?php include("../header/inc_header_second.php") ?>

  <!-- HERO -->
  <div class="page-hero">
    <div class="hero-inner">
      <div class="hero-text">
        <h1><i class='bx bx-image-add'></i> G alery Foto Undangan</h1>
        <p>Upload foto pre-wedding kamu — langsung tampil di halaman undangan</p>
      </div>
      <a href="../dashboard_customer.php" class="hero-back"><i class='bx bx-arrow-back'></i> Kembali ke Dashboard</a>
    </div>
  </div>

  <div class="main-wrap">

    <!-- PILIH ORDER -->
    <?php if (empty($orders_aktif)): ?>
      <div class="alert-warn"><i class='bx bx-error-circle' style="font-size:18px;flex-shrink:0"></i> Kamu belum punya undangan aktif. <a href="buat-undangan.php" style="color:var(--r);font-weight:600;margin-left:4px">Buat undangan dulu →</a></div>
    <?php else: ?>

      <div class="order-selector">
        <h3><i class='bx bx-receipt'></i> Pilih Undangan</h3>
        <div class="order-list">
          <?php
          $tema_colors = ['merah-klasik' => '#C0393B', 'navy-elegant' => '#1a2e4a', 'blush-pink' => '#d4687e', 'sage-garden' => '#3d6b44', 'rustic-brown' => '#5c3d1e'];
          foreach ($orders_aktif as $ord):
            $dot_color = $tema_colors[$ord['tema']] ?? '#888';
            $batas     = ['silver' => 3, 'gold' => 10, 'platinum' => 999];
            $max_f     = $batas[$ord['paket']] ?? 3;
            $jml_f     = (function () use ($pdo, $ord) {
              $s = $pdo->prepare("SELECT COUNT(*) FROM galery_foto WHERE kode_order=? AND status='aktif'");
              $s->execute([$ord['kode_order']]);
              return (int)$s->fetchColumn();
            })();
          ?>
            <a href="galery.php?order=<?= $ord['kode_order'] ?>"
              class="order-opt <?= $kode_order === $ord['kode_order'] ? 'selected' : '' ?>">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px">
                <div class="op-nama"><?= $ord['nama_pria'] ?> &amp; <?= $ord['nama_wanita'] ?></div>
                <span class="badge-paket bp-<?= $ord['paket'] ?>"><?= strtoupper($ord['paket']) ?></span>
              </div>
              <div class="op-meta">
                <span class="op-dot" style="background:<?= $dot_color ?>"></span>
                <?= ucwords(str_replace('-', ' ', $ord['tema'])) ?>
                &nbsp;·&nbsp;
                <i class='bx bx-image' style="font-size:12px"></i>
                <?= $jml_f ?>/<?= $max_f === 999 ? '∞' : $max_f ?> foto
              </div>
            </a>
          <?php endforeach ?>
        </div>
      </div>

    <?php endif ?>

    <!-- galery UTAMA -->
    <?php if ($order_aktif): ?>
      <?php
      $jml_foto = count($fotos);
      $sisa_foto = $max_foto === 999 ? 999 : max(0, $max_foto - $jml_foto);
      $pct_quota = $max_foto === 999 ? 0 : min(100, round($jml_foto / $max_foto * 100));
      $quota_class = $pct_quota >= 100 ? 'full' : ($pct_quota >= 70 ? 'warn' : 'ok');
      ?>

      <div class="galery-wrap">

        <!-- FOTO GRID -->
        <div class="foto-section">
          <div class="sec-head">
            <h3><i class='bx bxs-image-alt'></i> Foto galery</h3>
            <div class="quota-bar-wrap">
              <div class="quota-bar">
                <div class="quota-fill <?= $quota_class ?>" style="width:<?= $pct_quota ?>%"></div>
              </div>
              <span><?= $jml_foto ?>/<?= $max_foto === 999 ? '∞' : $max_foto ?> foto</span>
            </div>
          </div>

          <?php if ($jml_foto === 0): ?>
            <div class="foto-grid">
              <div class="foto-empty">
                <i class='bx bx-image-add'></i>
                <p>Belum ada foto. Upload foto pre-wedding kamu di panel sebelah kanan!</p>
              </div>
            </div>
          <?php else: ?>
            <div class="foto-grid" id="fotoGrid">
              <?php foreach ($fotos as $idx => $f): ?>
                <div class="foto-item" data-id="<?= $f['id'] ?>" data-path="<?= $f['path_file'] ?>">
                  <div class="foto-num"><?= $idx + 1 ?></div>
                  <i class='bx bx-dots-vertical-rounded foto-drag-hint'></i>
                  <img src="<?= $f['path_file'] ?>" alt="Foto <?= $idx + 1 ?>" loading="lazy" />
                  <div class="foto-overlay">
                    <div class="fo-btns">
                      <button class="fo-btn fo-edit" onclick="editCaption(<?= $f['id'] ?>,'<?= htmlspecialchars(addslashes($f['caption'])) ?>','<?= $f['path_file'] ?>')" title="Edit caption">
                        <i class='bx bx-pencil'></i>
                      </button>
                      <button class="fo-btn fo-del" onclick="hapusFoto(<?= $f['id'] ?>)" title="Hapus foto">
                        <i class='bx bx-trash'></i>
                      </button>
                    </div>
                    <?php if ($f['caption']): ?>
                      <div class="fo-caption"><?= htmlspecialchars($f['caption']) ?></div>
                    <?php endif ?>
                  </div>
                </div>
              <?php endforeach ?>
            </div>

            <p style="font-size:11px;color:#bbb;text-align:center;margin-top:.75rem">
              <i class='bx bx-move'></i> Drag & drop foto untuk mengubah urutan
            </p>
          <?php endif ?>

          <!-- Info urutan tampil -->
          <?php if ($jml_foto > 0): ?>
            <div class="alert-info" style="margin-top:1rem">
              <i class='bx bx-info-circle' style="font-size:16px;flex-shrink:0"></i>
              Foto akan tampil di galery undangan sesuai urutan di atas. Drag foto untuk mengatur ulang urutan.
            </div>
          <?php endif ?>
        </div>

        <!-- UPLOAD PANEL -->
        <div class="upload-panel" style="position:relative">
          <h3><i class='bx bx-upload'></i> Upload Foto</h3>

          <?php if ($sisa_foto <= 0 && $max_foto !== 999): ?>
            <!-- LOCKED - kuota habis -->
            <div style="position:relative;min-height:200px">
              <div class="locked-overlay" style="position:relative;min-height:200px">
                <i class='bx bx-lock'></i>
                <p>Kuota foto paket <strong><?= strtoupper($order_aktif['paket']) ?></strong> sudah penuh (<?= $max_foto ?>/<?= $max_foto ?> foto)</p>
                <a href="../dashboard_customer.php" style="display:inline-flex;align-items:center;gap:5px;padding:9px 18px;background:var(--r);color:#fff;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600">
                  <i class='bx bx-up-arrow-alt'></i> Upgrade Paket
                </a>
              </div>
            </div>
          <?php else: ?>

            <!-- DROP ZONE -->
            <div class="drop-zone" id="dropZone">
              <input type="file" id="fileInput" multiple accept="image/jpeg,image/png,image/webp" onchange="handleFiles(this.files)" />
              <i class='bx bx-image-add dz-icon'></i>
              <h4>Drag foto ke sini</h4>
              <p>atau klik untuk pilih file<br>JPG, PNG, WEBP • Maks 5MB per foto</p>
              <div class="dz-limit">Sisa <?= $sisa_foto === 999 ? 'tak terbatas' : $sisa_foto ?> foto</div>
            </div>

            <!-- PREVIEW LIST -->
            <div class="preview-list" id="previewList"></div>

            <!-- PROGRESS -->
            <div class="upload-progress" id="uploadProgress">
              <div class="prog-bar-wrap">
                <div class="prog-bar" id="progBar"></div>
              </div>
              <div class="prog-text" id="progText">Mengupload foto...</div>
            </div>

            <!-- TOMBOL UPLOAD -->
            <button class="btn-upload" id="btnUpload" onclick="uploadFoto()" disabled>
              <i class='bx bx-upload'></i> Upload Foto
            </button>

            <!-- TIPS -->
            <div class="tips-box">
              <h4><i class='bx bx-bulb'></i> Tips Foto Bagus</h4>
              <ul>
                <li>Gunakan foto resolusi minimal 800×600px</li>
                <li>Ratio 1:1 atau 4:3 paling bagus di galery</li>
                <li>Pilih foto dengan pencahayaan baik</li>
                <li>Tambahkan caption untuk setiap foto</li>
                <li>Paket <?= strtoupper($order_aktif['paket']) ?>: maks <?= $max_foto === 999 ? 'tak terbatas' : $max_foto ?> foto</li>
              </ul>
            </div>
          <?php endif ?>
        </div>

      </div><!-- end galery-wrap -->

    <?php elseif (!empty($orders_aktif)): ?>
      <!-- Belum pilih order -->
      <div style="text-align:center;padding:3rem;background:var(--white);border:0.5px solid var(--border);border-radius:16px">
        <div style="font-size:3rem;margin-bottom:1rem">📸</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:.5rem">Pilih Undangan</h3>
        <p style="font-size:14px;color:var(--gray)">Pilih undangan di atas untuk mulai upload foto galery</p>
      </div>
    <?php endif ?>

  </div><!-- end main-wrap -->

  <!-- CAPTION MODAL -->
  <div class="caption-modal" id="captionModal">
    <div class="caption-box">
      <h3>Edit Caption Foto</h3>
      <p>Tambahkan keterangan singkat untuk foto ini</p>
      <img id="captionImg" src="" alt="" />
      <input type="text" id="captionInput" placeholder="cth. Momen pertama kami bertemu..." maxlength="100" />
      <div style="font-size:11px;color:#aaa;margin-top:4px">Maks. 100 karakter</div>
      <input type="hidden" id="captionFotoId" />
      <div class="caption-btns">
        <button class="cbtn-cancel" onclick="tutupCaption()">Batal</button>
        <button class="cbtn-ok" onclick="simpanCaption()">Simpan Caption</button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"></div>

  <?php include("../footer/inc_footer_second.php") ?>

  <script>
    const KODE_ORDER = '<?= $kode_order ?>';
    const MAX_FOTO = <?= $max_foto === 999 ? 9999 : $max_foto ?>;
    let pendingFiles = [];

    // ── Drag & Drop ──────────────────────────────
    const dropZone = document.getElementById('dropZone');
    if (dropZone) {
      dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('dragover');
      });
      dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
      dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
      });
    }

    // ── Handle pilih file ────────────────────────
    function handleFiles(files) {
      const sisa = MAX_FOTO - <?= isset($jml_foto) ? $jml_foto : 0 ?> - pendingFiles.length;
      Array.from(files).slice(0, sisa).forEach(file => {
        if (file.size > 5 * 1024 * 1024) {
          showToast(`${file.name} terlalu besar (maks 5MB)`, 'error');
          return;
        }
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
          showToast(`${file.name} bukan format valid`, 'error');
          return;
        }
        pendingFiles.push(file);
        renderPreview(file, pendingFiles.length - 1);
      });
      document.getElementById('btnUpload').disabled = pendingFiles.length === 0;
    }

    function renderPreview(file, idx) {
      const reader = new FileReader();
      reader.onload = e => {
        const item = document.createElement('div');
        item.className = 'preview-item';
        item.dataset.idx = idx;
        item.innerHTML = `
      <img class="preview-thumb" src="${e.target.result}"/>
      <div class="preview-info">
        <div class="preview-name">${file.name}</div>
        <div class="preview-size">${(file.size/1024).toFixed(0)} KB</div>
      </div>
      <button class="preview-del" onclick="hapusPending(${idx})"><i class='bx bx-x'></i></button>
    `;
        document.getElementById('previewList').appendChild(item);
      };
      reader.readAsDataURL(file);
    }

    function hapusPending(idx) {
      pendingFiles.splice(idx, 1);
      const list = document.getElementById('previewList');
      list.innerHTML = '';
      pendingFiles.forEach((f, i) => renderPreview(f, i));
      document.getElementById('btnUpload').disabled = pendingFiles.length === 0;
    }

    // ── Upload ───────────────────────────────────
    function uploadFoto() {
      if (pendingFiles.length === 0) return;
      const btn = document.getElementById('btnUpload');
      const prog = document.getElementById('uploadProgress');
      btn.disabled = true;
      prog.style.display = 'block';

      const fd = new FormData();
      fd.append('action', 'upload');
      fd.append('kode_order', KODE_ORDER);
      pendingFiles.forEach((f, i) => {
        fd.append('foto[]', f);
        fd.append('caption[]', '');
      });

      const xhr = new XMLHttpRequest();
      xhr.upload.onprogress = e => {
        const pct = Math.round(e.loaded / e.total * 100);
        document.getElementById('progBar').style.width = pct + '%';
        document.getElementById('progText').textContent = `Mengupload... ${pct}%`;
      };
      xhr.onload = () => {
        prog.style.display = 'none';
        try {
          const data = JSON.parse(xhr.responseText);
          if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
          } else {
            showToast(data.message || 'Gagal upload', 'error');
            btn.disabled = false;
          }
          if (data.errors && data.errors.length) {
            data.errors.forEach(e => showToast(e, 'error'));
          }
        } catch (e) {
          showToast('Terjadi kesalahan', 'error');
          btn.disabled = false;
        }
      };
      xhr.onerror = () => {
        prog.style.display = 'none';
        showToast('Koneksi gagal', 'error');
        btn.disabled = false;
      };
      xhr.open('POST', '../config/galery_api.php');
      xhr.send(fd);
    }

    // ── Hapus foto ───────────────────────────────
    function hapusFoto(fotoId) {
      if (!confirm('Yakin hapus foto ini? Tidak bisa dikembalikan.')) return;
      const fd = new FormData();
      fd.append('action', 'hapus');
      fd.append('kode_order', KODE_ORDER);
      fd.append('foto_id', fotoId);
      fetch('../config/galery_api.php', {
          method: 'POST',
          body: fd
        })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') {
            showToast('Foto dihapus', 'success');
            setTimeout(() => location.reload(), 800);
          } else showToast(data.message, 'error');
        });
    }

    // ── Caption ──────────────────────────────────
    function editCaption(id, caption, path) {
      document.getElementById('captionFotoId').value = id;
      document.getElementById('captionInput').value = caption;
      document.getElementById('captionImg').src = path;
      document.getElementById('captionModal').classList.add('show');
    }

    function tutupCaption() {
      document.getElementById('captionModal').classList.remove('show');
    }

    function simpanCaption() {
      const id = document.getElementById('captionFotoId').value;
      const caption = document.getElementById('captionInput').value.trim();
      const fd = new FormData();
      fd.append('action', 'caption');
      fd.append('kode_order', KODE_ORDER);
      fd.append('foto_id', id);
      fd.append('caption', caption);
      fetch('../config/galery_api.php', {
          method: 'POST',
          body: fd
        })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') {
            showToast('Caption disimpan', 'success');
            tutupCaption();
            setTimeout(() => location.reload(), 800);
          }
        });
    }
    document.getElementById('captionModal').addEventListener('click', function(e) {
      if (e.target === this) tutupCaption();
    });

    // ── Drag & drop urutan foto ──────────────────
    const grid = document.getElementById('fotoGrid');
    if (grid && typeof Sortable !== 'undefined') {
      // Sortable tidak tersedia, pakai implementasi sederhana
    }
    // Simple drag reorder
    let dragSrc = null;
    document.querySelectorAll('.foto-item').forEach(item => {
      item.draggable = true;
      item.addEventListener('dragstart', function() {
        dragSrc = this;
        this.style.opacity = '.4';
      });
      item.addEventListener('dragend', function() {
        this.style.opacity = '1';
        simpanUrutan();
      });
      item.addEventListener('dragover', function(e) {
        e.preventDefault();
      });
      item.addEventListener('drop', function(e) {
        e.preventDefault();
        if (dragSrc !== this) {
          const allItems = [...grid.querySelectorAll('.foto-item')];
          const srcIdx = allItems.indexOf(dragSrc);
          const tgtIdx = allItems.indexOf(this);
          if (srcIdx < tgtIdx) this.after(dragSrc);
          else this.before(dragSrc);
        }
      });
    });

    function simpanUrutan() {
      const ids = [...document.querySelectorAll('.foto-item')].map(el => el.dataset.id);
      const fd = new FormData();
      fd.append('action', 'urutan');
      fd.append('kode_order', KODE_ORDER);
      fd.append('urutan', JSON.stringify(ids));
      fetch('../config/galery_api.php', {
        method: 'POST',
        body: fd
      }).then(r => r.json()).then(() => {
        showToast('Urutan foto disimpan', 'success');
      });
    }

    // ── Toast ────────────────────────────────────
    function showToast(msg, type = '') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className = 'toast show ' + (type || '');
      setTimeout(() => t.className = 'toast', 2800);
    }
  </script>
</body>

</html>