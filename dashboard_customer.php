<?php
session_start();
require_once 'config/koneksi.php';

// Wajib login
$name = $_SESSION['name'] ?? null;
if (!$name) {
  header('Location: login_register.php');
  exit;
}

// Ambil semua order milik customer berdasarkan nama atau WA
// Cek apakah user punya kolom user_id — kalau belum, pakai nama session
$orders = [];
try {
  // Coba ambil berdasarkan user_id dulu (kalau ada relasi)
  // Fallback: ambil semua order yang no_wa sesuai session
  $stmt = $pdo->prepare("
        SELECT * FROM orders
        WHERE status_order != 'batal'
        ORDER BY created_at DESC
    ");
  $stmt->execute();
  $all_orders = $stmt->fetchAll();

  // Filter berdasarkan nama (sementara, sebelum ada user_id)
  // Nanti bisa diganti dengan: WHERE user_id = $_SESSION['user_id']
  $orders = $all_orders; // tampilkan semua untuk sekarang

} catch (PDOException $e) {
  $orders = [];
}

// Statistik
$total_order  = count($orders);
$aktif_count  = count(array_filter($orders, fn($o) => $o['status_order'] === 'aktif'));
$pending_count = count(array_filter($orders, fn($o) => in_array($o['status_order'], ['baru', 'diproses'])));

// Format helper
function fmtTgl($tgl = null)
{
  if (!$tgl) return '-';
  $obj = new DateTime($tgl);
  $bl  = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
  return $obj->format('d') . ' ' . $bl[(int)$obj->format('n')] . ' ' . $obj->format('Y');
}
function sisaHari($expired = null)
{
  if (!$expired) return null;
  $diff = strtotime($expired) - time();
  return max(0, (int)ceil($diff / 86400));
}

$o = $orders[0] ?? null; // order terbaru untuk quick action
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Saya – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/footer_header_sec.css" />
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
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--light);
      color: var(--dark)
    }

    /* ── HERO STRIP ── */
    .account-hero {
      background: linear-gradient(135deg, #1a0505 0%, var(--rd) 50%, var(--r) 100%);
      padding: 3rem 8% 5rem;
      position: relative;
      overflow: hidden;
    }

    .account-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .hero-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
      flex-wrap: wrap;
      position: relative;
      padding-top: 2.1rem;   
    }

    .hero-left {
      display: flex;
      align-items: center;
      gap: 1.25rem
    }

    .avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .15);
      border: 2px solid rgba(255, 255, 255, .3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: #fff;
      flex-shrink: 0;
    }

    .hero-greeting p {
      font-size: 13px;
      color: rgba(255, 255, 255, .6);
      margin-bottom: 3px
    }

    .hero-greeting h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.3rem, 3vw, 1.8rem);
      color: #fff
    }

    .hero-stats {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap
    }

    .hstat {
      text-align: center
    }

    .hstat .num {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: #fff;
      font-weight: 700;
      line-height: 1
    }
    .hstat .lbl {
      font-size: 11px;
      color: rgba(255, 255, 255, .55);
      margin-top: 3px;
      white-space: nowrap
    }
    /* ── MAIN LAYOUT ── */
    .main-wrap {
      max-width: 1000px;
      margin: -2.5rem auto 0;
      padding: 0 1.5rem 4rem;
      position: relative;
      z-index: 1;
    }
    /* ── QUICK ACTIONS ── */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 10px;
      margin-bottom: 2rem;
    }
    .qa-btn {
      background: var(--white);
      border: 0.5px solid var(--border);
      border-radius: 14px;
      padding: 1.1rem 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .6rem;
      text-decoration: none;
      color: var(--dark);
      transition: all .2s;
      text-align: center;
      cursor: pointer;
    }
    .qa-btn:hover {
      border-color: var(--r);
      box-shadow: 0 4px 20px rgba(192, 57, 59, .1);
      transform: translateY(-2px)
    }
    .qa-icon {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }
    .qa-icon.red {
      background: var(--rl);
      color: var(--r)
    }
    .qa-icon.green {
      background: #e8f7f0;
      color: #2e9e5b
    }
    .qa-icon.blue {
      background: #e8f0ff;
      color: #2d7dd2
    }
    .qa-icon.amber {
      background: #fff8e0;
      color: #c9a227
    }
    .qa-label {
      font-size: 12px;
      font-weight: 500
    }
    /* ── SECTION HEADER ── */
    .sec-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1rem;
      gap: 1rem;
    }
    .sec-head h2 {
      font-size: 16px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px
    }
    .sec-head h2 i {
      color: var(--r)
    }
    .sec-head a {
      font-size: 13px;
      color: var(--r);
      text-decoration: none
    }
    .sec-head a:hover {
      text-decoration: underline
    }
    /* ── ORDER CARDS ── */
    .order-card {
      background: var(--white);
      border: 0.5px solid var(--border);
      border-radius: 16px;
      margin-bottom: 1rem;
      overflow: hidden;
      transition: box-shadow .2s;
    }
    .order-card:hover {
      box-shadow: 0 4px 24px rgba(0, 0, 0, .06)
    }
    .order-card-top {
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .order-meta {
      flex: 1;
      min-width: 0
    }
    .order-kode {
      font-family: monospace;
      font-size: 11px;
      background: var(--rl);
      color: var(--r);
      padding: 2px 8px;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: .5rem;
      font-weight: 600;
    }
    .order-nama {
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem;
      color: var(--dark);
      margin-bottom: .25rem;
    }
    .order-tema {
      font-size: 12px;
      color: var(--gray);
      display: flex;
      align-items: center;
      gap: 5px
    }
    .tema-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      flex-shrink: 0
    }
    .order-badges {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
      align-items: flex-start
    }
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 20px
    }
    .badge-aktif {
      background: #e8f7f0;
      color: #2e9e5b
    }
    .badge-baru {
      background: #e8f0ff;
      color: #2d7dd2
    }
    .badge-diproses {
      background: #fff8e0;
      color: #c9a227
    }
    .badge-nonaktif {
      background: #f5f5f5;
      color: #888
    }
    .badge-silver {
      background: #f0f0f0;
      color: #666
    }
    .badge-gold {
      background: #fff8e0;
      color: #a06000
    }
    .badge-platinum {
      background: #f0e8f8;
      color: #6a2d9e
    }
    /* Progress masa aktif */
    .order-card-mid {
      padding: .75rem 1.5rem;
      border-top: 0.5px solid var(--border);
      background: var(--light);
    }
    .masa-aktif-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: .4rem;
      font-size: 12px
    }
    .masa-aktif-row .lbl {
      color: var(--gray);
      display: flex;
      align-items: center;
      gap: 5px
    }
    .masa-aktif-row .val {
      font-weight: 600;
      color: var(--dark)
    }
    .progress-bar-wrap {
      height: 5px;
      background: #e8e0e0;
      border-radius: 99px;
      overflow: hidden
    }
    .progress-bar {
      height: 100%;
      border-radius: 99px;
      transition: width .5s;
      background: var(--r)
    }
    .progress-bar.green {
      background: #2e9e5b
    }
    .progress-bar.amber {
      background: #c9a227
    }
    .progress-bar.red {
      background: var(--r)
    }
    .expired-tag {
      font-size: 11px;
      font-weight: 600;
      color: var(--r);
      background: var(--rl);
      padding: 2px 8px;
      border-radius: 6px;
    }
    /* Actions bottom */
    .order-card-bot {
      padding: 1rem 1.5rem;
      border-top: 0.5px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
      flex-wrap: wrap;
    }
    .order-info-mini {
      display: flex;
      gap: 1.25rem;
      flex-wrap: wrap
    }
    .oi {
      font-size: 12px;
      color: var(--gray);
      display: flex;
      align-items: center;
      gap: 5px
    }
    .oi i {
      font-size: 14px
    }
    .order-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap
    }
    .act-btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-family: inherit;
      text-decoration: none;
      transition: all .2s;
    }
    .act-buka {
      background: var(--r);
      color: #fff
    }
    .act-buka:hover {
      background: var(--rd)
    }
    .act-salin {
      background: var(--white);
      color: var(--r);
      border: 1px solid var(--rm)
    }
    .act-salin:hover {
      background: var(--rl)
    }
    .act-wa {
      background: #25D366;
      color: #fff
    }
    .act-wa:hover {
      background: #1da851
    }
    .act-perpanjang {
      background: #fff8e0;
      color: #a06000;
      border: 1px solid #f0d070
    }
    .act-perpanjang:hover {
      background: #a06000;
      color: #fff
    }
    .act-detail {
      background: #e8f0ff;
      color: #2d7dd2
    }
    .act-detail:hover {
      background: #2d7dd2;
      color: #fff
    }
    /* Link undangan box */
    .link-box {
      margin: .75rem 1.5rem;
      padding: .75rem 1rem;
      background: #f0f7ff;
      border: 0.5px solid #b8d4f8;
      border-radius: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .link-box i {
      color: #2d7dd2;
      font-size: 16px;
      flex-shrink: 0
    }
    .link-url {
      font-family: monospace;
      font-size: 12px;
      color: var(--r);
      flex: 1;
      min-width: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    /* Pending payment box */
    .pending-box {
      margin: .75rem 1.5rem;
      padding: .75rem 1rem;
      background: #fff8e0;
      border: 0.5px solid #f0d070;
      border-radius: 10px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 13px;
      color: #7a4f00;
    }
    .pending-box i {
      font-size: 18px;
      flex-shrink: 0;
      margin-top: 1px
    }
    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      background: var(--white);
      border: 0.5px solid var(--border);
      border-radius: 16px;
    }
    .empty-state .icon {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      opacity: .4
    }
    .empty-state h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      margin-bottom: .5rem
    }
    .empty-state p {
      font-size: 14px;
      color: var(--gray);
      margin-bottom: 1.5rem
    }
    .btn-buat {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 12px 24px;
      background: var(--r);
      color: #fff;
      border-radius: 50px;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: background .2s;
    }
    .btn-buat:hover {
      background: var(--rd)
    }
    /* Modal perpanjang */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 999;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .modal-overlay.show {
      display: flex
    }
    .modal-box {
      background: var(--white);
      border-radius: 16px;
      padding: 2rem;
      max-width: 460px;
      width: 100%;
      animation: slideUp .3s ease;
    }
    @keyframes slideUp {
      from {
        transform: translateY(20px);
        opacity: 0
      }
      to {
        transform: translateY(0);
        opacity: 1
      }
    }
    .modal-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.25rem;
      margin-bottom: .4rem
    }
    .modal-sub {
      font-size: 13px;
      color: var(--gray);
      margin-bottom: 1.5rem
    }
    .modal-paket-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-bottom: 1.25rem
    }
    .modal-paket {
      border: 1.5px solid #e0e0e0;
      border-radius: 10px;
      padding: .85rem .6rem;
      text-align: center;
      cursor: pointer;
      transition: all .2s;
    }
    .modal-paket:hover {
      border-color: var(--r)
    }
    .modal-paket.selected {
      border-color: var(--r);
      background: var(--rl)
    }
    .modal-paket .mp-name {
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 3px
    }
    .modal-paket .mp-price {
      font-size: 14px;
      font-weight: 700;
      color: var(--r)
    }
    .modal-paket .mp-hari {
      font-size: 11px;
      color: var(--gray);
      margin-top: 2px
    }
    .modal-info {
      background: #fff8e0;
      border: 0.5px solid #f0d070;
      border-radius: 10px;
      padding: .85rem 1rem;
      font-size: 13px;
      color: #7a4f00;
      margin-bottom: 1.25rem;
      line-height: 1.7;
    }
    .modal-btns {
      display: flex;
      gap: 10px
    }
    .modal-btn-cancel {
      flex: 1;
      padding: 11px;
      border-radius: 9px;
      background: var(--white);
      color: var(--gray);
      border: 1px solid #e0e0e0;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      font-family: inherit;
    }
    .modal-btn-ok {
      flex: 2;
      padding: 11px;
      border-radius: 9px;
      background: var(--r);
      color: #fff;
      border: none;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      transition: background .2s;
    }
    .modal-btn-ok:hover {
      background: var(--rd)
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
      white-space: nowrap;
    }
    .toast.show {
      opacity: 1;
      transform: translateX(-50%) translateY(0)
    }
    @media(max-width:640px) {
      .account-hero {
        padding: 2rem 5% 4rem
      }
      .main-wrap {
        padding: 0 1rem 3rem
      }
      .order-card-top {
        flex-direction: column
      }
      .order-card-bot {
        flex-direction: column;
        align-items: flex-start
      }
      .modal-paket-grid {
        grid-template-columns: 1fr
      }
    }
  </style>
</head>

<body>
  <?php include("./header/inc_header_second.php") ?>

  <!-- HERO -->
  <div class="account-hero">
    <div class="hero-inner">
      <div class="hero-left">
        <div class="avatar"><?= strtoupper(substr($name, 0, 1)) ?></div>
        <div class="hero-greeting">
          <p>Selamat datang kembali</p>
          <h1><?= htmlspecialchars($name) ?></h1>
        </div>
      </div>
      <div class="hero-stats">
        <div class="hstat">
          <div class="num"><?= $total_order ?></div>
          <div class="lbl">Total Order</div>
        </div>
        <div class="hstat">
          <div class="num"><?= $aktif_count ?></div>
          <div class="lbl">Aktif</div>
        </div>
        <div class="hstat">
          <div class="num"><?= $pending_count ?></div>
          <div class="lbl">Diproses</div>
        </div>
      </div>
    </div>
  </div>

  <!-- MAIN -->
  <div class="main-wrap">

    <!-- QUICK ACTIONS -->
    <div class="quick-actions">
      <a href="buat_undangan.php" class="qa-btn">
        <div class="qa-icon red"><i class='bx bx-plus-circle'></i></div>
        <span class="qa-label">Buat Undangan</span>
      </a>
      <div class="qa-btn" onclick="scrollToOrders()">
        <div class="qa-icon green"><i class='bx bx-list-ul'></i></div>
        <span class="qa-label">Order Saya</span>
      </div>
      <a href="faq.php" class="qa-btn">
        <div class="qa-icon blue"><i class='bx bx-help-circle'></i></div>
        <span class="qa-label">FAQ & Bantuan</span>
      </a>
      <a href="https://wa.me/6281939195110" target="_blank" class="qa-btn">
        <div class="qa-icon amber"><i class='bx bxl-whatsapp'></i></div>
        <span class="qa-label">Chat Admin</span>
      </a>
      <a href="./admin/galery.php?order=<?= $o['kode_order'] ?>" class="qa-btn">
        <div class="qa-icon amber"><i class='bx bx-image-add'></i></div>
        <span class="qa-label">Kelola Foto</span>
      </a>
    </div>

    <!-- ORDER LIST -->
    <div id="orderSection">
      <div class="sec-head">
        <h2><i class='bx bx-receipt'></i> Undangan Saya</h2>
        <a href="buat_undangan.php"><i class='bx bx-plus'></i> Buat Baru</a>
      </div>

      <?php if (empty($orders)): ?>
        <div class="empty-state">
          <div class="icon"><i class='bx bx-envelope' ></i></div>
          <h3>Belum Ada Undangan</h3>
          <p>Kamu belum pernah membuat undangan digital.<br>Yuk mulai sekarang!</p>
          <a href="buat_undangan.php" class="btn-buat"><i class='bx bx-plus'></i> Buat Undangan Pertamamu</a>
        </div>

      <?php else: ?>
        <?php foreach ($orders as $o):
          $tgl_nikah   = fmtTgl($o['tanggal_nikah']);
          $tgl_expired = $o['tgl_expired'];
          $sisa        = sisaHari($tgl_expired);
          $is_aktif    = $o['status_order'] === 'aktif';
          $is_expired  = $is_aktif && $sisa === 0;
          $tema_label  = ucwords(str_replace('-', ' ', $o['tema']));
          $base_url    = 'http://localhost/WebDev';
          $link        = $o['kode_undangan'] ? "{$base_url}/undangan/undangan_index.php?kode={$o['kode_undangan']}&to=" . urlencode($o['nama_pria']) : null;

          // Warna progress bar
          $bar_color = 'green';
          $bar_pct   = 100;
          if ($sisa !== null) {
            $total_hari = $o['paket'] === 'platinum' ? 365 : 30;
            $bar_pct    = min(100, round($sisa / $total_hari * 100));
            if ($sisa <= 3)  $bar_color = 'red';
            elseif ($sisa <= 7) $bar_color = 'amber';
            else $bar_color = 'green';
          }

          // Warna dot tema
          $tema_colors = [
            'merah-klasik' => '#C0393B',
            'navy-elegant' => '#1a2e4a',
            'blush-pink' => '#d4687e',
            'sage-garden' => '#3d6b44',
            'rustic-brown' => '#5c3d1e'
          ];
          $dot_color = $tema_colors[$o['tema']] ?? '#888';
        ?>

          <div class="order-card">

            <!-- TOP -->
            <div class="order-card-top">
              <div class="order-meta">
                <span class="order-kode"><?= $o['kode_order'] ?></span>
                <div class="order-nama"><?= htmlspecialchars($o['nama_pria']) ?> &amp; <?= htmlspecialchars($o['nama_wanita']) ?></div>
                <div class="order-tema">
                  <span class="tema-dot" style="background:<?= $dot_color ?>"></span>
                  <?= $tema_label ?> &nbsp;·&nbsp; <?= $tgl_nikah ?>
                </div>
              </div>
              <div class="order-badges">
                <?php
                $so = $o['status_order'];
                $badge_map = ['baru' => 'baru', 'diproses' => 'diproses', 'aktif' => 'aktif', 'nonaktif' => 'nonaktif'];
                $badge_icon = ['baru' => 'bx-time', 'diproses' => 'bx-loader-alt', 'aktif' => 'bxs-check-circle', 'nonaktif' => 'bx-pause-circle'];
                $badge_lbl = ['baru' => 'Order Baru', 'diproses' => 'Diproses', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'];
                ?>
                <span class="badge badge-<?= $badge_map[$so] ?? 'baru' ?>">
                  <i class='bx <?= $badge_icon[$so] ?? 'bx-time' ?>'></i>
                  <?= $badge_lbl[$so] ?? ucfirst($so) ?>
                </span>
                <span class="badge badge-<?= $o['paket'] ?>"><?= strtoupper($o['paket']) ?></span>
                <?php if ($is_expired): ?>
                  <span class="badge" style="background:#fdeaea;color:#a32d2d"><i class='bx bx-error-circle'></i> Expired</span>
                <?php endif ?>
              </div>
            </div>

            <!-- PENDING PAYMENT -->
            <?php if (in_array($o['status_bayar'], ['pending', 'menunggu_konfirmasi']) && $o['paket'] !== 'silver'): ?>
              <div class="pending-box">
                <i class='bx bx-credit-card'></i>
                <div>
                  <strong style="display:block;margin-bottom:3px">
                    <?= $o['status_bayar'] === 'menunggu_konfirmasi' ? '<i class="bx bx-loader" ></i> Menunggu Konfirmasi Pembayaran' : '<i class="bx bx-credit-card" ></i> Belum Bayar' ?>
                  </strong>
                  <?php if ($o['status_bayar'] === 'menunggu_konfirmasi'): ?>
                    Bukti transfer sudah diterima. Admin sedang memverifikasi, link undangan akan dikirim via WhatsApp setelah konfirmasi.
                  <?php else: ?>
                    Transfer <strong>Rp <?= number_format($o['harga'], 0, ',', '.') ?></strong> ke BCA 1234567890 a.n. Bernada ID, lalu kirim bukti ke admin.
                    <a href="https://wa.me/6281939195110?text=Halo+admin,+saya+ingin+konfirmasi+pembayaran+order+<?= $o['kode_order'] ?>" target="_blank" style="color:var(--r);font-weight:600">Kirim via WA →</a>
                  <?php endif ?>
                </div>
              </div>
            <?php endif ?>

            <!-- LINK UNDANGAN -->
            <?php if ($link && $is_aktif && !$is_expired): ?>
              <div class="link-box">
                <i class='bx bx-link'></i>
                <span class="link-url" id="link-<?= $o['id'] ?>"><?= $link ?></span>
                <button class="act-btn act-salin" style="padding:5px 10px;font-size:11px" onclick="salinLink('<?= $link ?>','link-<?= $o['id'] ?>')">
                  <i class='bx bx-copy'></i>
                </button>
              </div>
            <?php endif ?>

            <!-- MASA AKTIF -->
            <?php if ($is_aktif && $tgl_expired): ?>
              <div class="order-card-mid">
                <div class="masa-aktif-row">
                  <span class="lbl"><i class='bx bx-calendar' style="font-size:13px"></i> Masa Aktif</span>
                  <span class="val">
                    <?php if ($is_expired): ?>
                      <span class="expired-tag">Sudah Expired</span>
                    <?php else: ?>
                      <?= $sisa ?> hari lagi &nbsp;·&nbsp; s/d <?= fmtTgl($tgl_expired) ?>
                    <?php endif ?>
                  </span>
                </div>
                <div class="progress-bar-wrap">
                  <div class="progress-bar <?= $bar_color ?>" style="width:<?= $bar_pct ?>%"></div>
                </div>
              </div>
            <?php endif ?>

            <!-- BOTTOM ACTIONS -->
            <div class="order-card-bot">
              <div class="order-info-mini">
                <span class="oi"><i class='bx bx-map'></i><?= htmlspecialchars(mb_strimwidth($o['lokasi'], 0, 35, '...')) ?></span>
                <span class="oi"><i class='bx bx-time'></i><?= $o['mulai_akad'] ? substr($o['mulai_akad'], 0, 5) : '-' ?> WIB</span>
              </div>
              <div class="order-actions">
                <?php if ($link && $is_aktif && !$is_expired): ?>
                  <a href="<?= $link ?>" target="_blank" class="act-btn act-buka">
                    <i class='bx bx-link-external'></i> Buka
                  </a>
                  <a href="https://wa.me/?text=<?= urlencode("Hai! Ini undangan pernikahan {$o['nama_pria']} & {$o['nama_wanita']} 💌\n{$link}") ?>" target="_blank" class="act-btn act-wa">
                    <i class='bx bxl-whatsapp'></i> Share
                  </a>
                <?php endif ?>

                <?php if ($is_aktif && ($sisa !== null && $sisa <= 14 || $is_expired)): ?>
                  <button class="act-btn act-perpanjang" onclick="bukaModalPerpanjang('<?= $o['kode_order'] ?>','<?= htmlspecialchars($o['nama_pria']) ?>','<?= htmlspecialchars($o['nama_wanita']) ?>')">
                    <i class='bx bx-refresh'></i> Perpanjang
                  </button>
                <?php elseif ($is_aktif): ?>
                  <button class="act-btn act-perpanjang" onclick="bukaModalPerpanjang('<?= $o['kode_order'] ?>','<?= htmlspecialchars($o['nama_pria']) ?>','<?= htmlspecialchars($o['nama_wanita']) ?>')">
                    <i class='bx bx-refresh'></i> Perpanjang
                  </button>
                <?php endif ?>

                <?php if ($o['status_order'] === 'baru' && in_array($o['status_bayar'], ['pending'])): ?>
                  <a href="https://wa.me/6281939195110?text=Halo+admin,+saya+ingin+konfirmasi+pembayaran+order+<?= $o['kode_order'] ?>" target="_blank" class="act-btn act-wa">
                    <i class='bx bxl-whatsapp'></i> Konfirmasi Bayar
                  </a>
                <?php endif ?>
              </div>
            </div>

          </div>
        <?php endforeach ?>
      <?php endif ?>

    </div><!-- end orderSection -->

    <!-- TIPS -->
    <?php if ($aktif_count > 0): ?>
      <div style="background:var(--white);border:0.5px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;margin-top:1rem">
        <div style="font-size:13px;font-weight:600;color:var(--dark);margin-bottom:.75rem;display:flex;align-items:center;gap:7px">
          <i class='bx bx-bulb' style="color:#c9a227;font-size:16px"></i> Tips Bagikan Undangan
        </div>
        <div style="font-size:13px;color:var(--gray);line-height:1.9">
          Personalisasi link untuk setiap tamu dengan menambahkan <code style="background:var(--rl);color:var(--r);padding:1px 6px;border-radius:4px">&to=Nama+Tamu</code> di akhir link.<br>
          Contoh: <code style="font-size:12px;color:var(--r)">...?kode=BRN-XXXXX&to=Bapak+Hendra</code>
        </div>
      </div>
    <?php endif ?>

  </div>

  <!-- MODAL PERPANJANG -->
  <div class="modal-overlay" id="modalPerpanjang">
    <div class="modal-box">
      <div class="modal-title">Perpanjang Masa Aktif</div>
      <div class="modal-sub" id="modalSubtitle">Pilih paket perpanjangan untuk undangan kamu</div>
      <input type="hidden" id="modalKodeOrder" value="" />

      <div class="modal-paket-grid">
        <div class="modal-paket selected" onclick="pilihPaketModal(this,30,'Rp 30K')">
          <div class="mp-name">Basic</div>
          <div class="mp-price">Rp 30K</div>
          <div class="mp-hari">+30 hari</div>
        </div>
        <div class="modal-paket" onclick="pilihPaketModal(this,90,'Rp 75K')">
          <div class="mp-name">Extended</div>
          <div class="mp-price">Rp 75K</div>
          <div class="mp-hari">+90 hari</div>
        </div>
        <div class="modal-paket" onclick="pilihPaketModal(this,365,'Rp 150K')">
          <div class="mp-name">Annual</div>
          <div class="mp-price">Rp 150K</div>
          <div class="mp-hari">+1 tahun</div>
        </div>
      </div>

      <div class="modal-info">
        <i class='bx bx-credit-card' ></i> Transfer ke <strong>BCA 1234567890</strong> a.n. Bernada ID<br>
        <i class='bx bxs-balloon' ></i> atau <strong>GoPay/OVO: 0819-3919-5110</strong><br>
        Setelah transfer, kirim bukti + kode order ke WhatsApp admin untuk konfirmasi.
      </div>

      <div class="modal-btns">
        <button class="modal-btn-cancel" onclick="tutupModal()">Batal</button>
        <button class="modal-btn-ok" onclick="lanjutPerpanjang()">
          <i class='bx bxl-whatsapp'></i> Lanjut via WhatsApp
        </button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"></div>

  <?php include("footer/inc_footer_second.php") ?>

  <script>
    // ── Scroll ke orders ────────────────────────
    function scrollToOrders() {
      document.getElementById('orderSection').scrollIntoView({
        behavior: 'smooth'
      });
    }

    // ── Salin link ──────────────────────────────
    function salinLink(link, elId) {
      navigator.clipboard.writeText(link).then(() => {
        showToast('✅ Link berhasil disalin!');
        const el = document.getElementById(elId);
        if (el) {
          el.style.color = '#2e9e5b';
          setTimeout(() => el.style.color = '', 2000);
        }
      }).catch(() => {
        // Fallback
        const input = document.createElement('input');
        input.value = link;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        showToast('✅ Link berhasil disalin!');
      });
    }

    // ── Modal perpanjang ────────────────────────
    let selectedHari = 30;
    let selectedHarga = 'Rp 30K';

    function bukaModalPerpanjang(kode, pria, wanita) {
      document.getElementById('modalKodeOrder').value = kode;
      document.getElementById('modalSubtitle').textContent = `Perpanjang undangan ${pria} & ${wanita}`;
      document.getElementById('modalPerpanjang').classList.add('show');
    }

    function tutupModal() {
      document.getElementById('modalPerpanjang').classList.remove('show');
    }

    function pilihPaketModal(el, hari, harga) {
      document.querySelectorAll('.modal-paket').forEach(p => p.classList.remove('selected'));
      el.classList.add('selected');
      selectedHari = hari;
      selectedHarga = harga;
    }

    function lanjutPerpanjang() {
      const kode = document.getElementById('modalKodeOrder').value;
      const pesan = encodeURIComponent(
        `Halo Admin Bernada.ID! 🌿\n\nSaya ingin perpanjang masa aktif undangan.\n\n` +
        `Kode Order : ${kode}\n` +
        `Durasi     : +${selectedHari} hari\n` +
        `Total      : ${selectedHarga}\n\n` +
        `Mohon konfirmasi pembayaran setelah saya transfer. Terima kasih!`
      );
      window.open(`https://wa.me/6281939195110?text=${pesan}`, '_blank');
      tutupModal();
      showToast('💚 Pesan WA sudah disiapkan!');
    }

    // Tutup modal klik overlay
    document.getElementById('modalPerpanjang').addEventListener('click', function(e) {
      if (e.target === this) tutupModal();
    });

    // ── Toast ────────────────────────────────────
    function showToast(msg) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2500);
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