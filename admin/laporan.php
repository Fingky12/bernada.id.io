<?php
session_start();
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) {
  header('Location: admin_login.php');
  exit;
}
$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';

$tahun = (int)($_GET['tahun'] ?? date('Y'));


// Statistik
$total   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$baru    = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_order='baru'")->fetchColumn();
$aktif   = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_order='aktif'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_bayar IN ('menunggu_konfirmasi','pending') AND paket != 'silver'")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(harga) FROM orders WHERE status_bayar='lunas'")->fetchColumn() ?? 0;

// Revenue per bulan
$revenue_bulanan = [];
for ($i = 1; $i <= 12; $i++) {
  $stmt = $pdo->prepare("SELECT COALESCE(SUM(harga),0) as total, COUNT(*) as jumlah FROM orders WHERE YEAR(created_at)=? AND MONTH(created_at)=? AND status_bayar='lunas'");
  $stmt->execute([$tahun, $i]);
  $row = $stmt->fetch();
  $revenue_bulanan[$i] = ['total' => (int)$row['total'], 'jumlah' => (int)$row['jumlah']];
}

// Paket stats
$paket_stats = $pdo->prepare("SELECT paket, COUNT(*) as jumlah, COALESCE(SUM(harga),0) as revenue FROM orders WHERE YEAR(created_at)=? GROUP BY paket ORDER BY jumlah DESC");
$paket_stats->execute([$tahun]);
$paket_data = $paket_stats->fetchAll();

// Tema stats
$tema_stats = $pdo->prepare("SELECT tema, COUNT(*) as jumlah FROM orders WHERE YEAR(created_at)=? GROUP BY tema ORDER BY jumlah DESC LIMIT 10");
$tema_stats->execute([$tahun]);
$tema_data = $tema_stats->fetchAll();

// Status stats
$status_stats = $pdo->query("SELECT status_order, COUNT(*) as jumlah FROM orders GROUP BY status_order")->fetchAll();
$status_map = [];
foreach ($status_stats as $s) $status_map[$s['status_order']] = $s['jumlah'];

// KPI
$total_rev = (int)$pdo->prepare("SELECT COALESCE(SUM(harga),0) FROM orders WHERE status_bayar='lunas' AND YEAR(created_at)=?")->execute([$tahun]) ? (function () use ($pdo, $tahun) {
  $s = $pdo->prepare("SELECT COALESCE(SUM(harga),0) FROM orders WHERE status_bayar='lunas' AND YEAR(created_at)=?");
  $s->execute([$tahun]);
  return (int)$s->fetchColumn();
})() : 0;
$total_ord  = (function () use ($pdo, $tahun) {
  $s = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE YEAR(created_at)=?");
  $s->execute([$tahun]);
  return (int)$s->fetchColumn();
})();
$total_aktif = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status_order='aktif'")->fetchColumn();
$avg_order  = $total_ord > 0 ? round($total_rev / $total_ord) : 0;

// Best month
$best_month = 0;
$best_rev = 0;
foreach ($revenue_bulanan as $m => $d) {
  if ($d['total'] > $best_rev) {
    $best_rev = $d['total'];
    $best_month = $m;
  }
}
$bulan_nama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
$bulan_full = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

// Recent orders
$recent = $pdo->query("SELECT kode_order,nama_pria,nama_wanita,tema,paket,harga,status_order,created_at FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Export Excel
if (isset($_GET['export']) && $_GET['export'] === 'excel') {
  $exp = $pdo->prepare("SELECT kode_order,nama_pria,nama_wanita,paket,tema,harga,status_bayar,status_order,no_whatsapp,tanggal_nikah,created_at,tgl_aktif,tgl_expired FROM orders WHERE YEAR(created_at)=? ORDER BY created_at DESC");
  $exp->execute([$tahun]);
  $rows = $exp->fetchAll();
  header('Content-Type: application/vnd.ms-excel; charset=utf-8');
  header('Content-Disposition: attachment; filename="laporan-bernada-' . $tahun . '.xls"');
  echo "\xEF\xBB\xBF";
  echo "<table border='1'><tr style='background:#C0393B;color:#fff;font-weight:bold'><th>Kode Order</th><th>Pria</th><th>Wanita</th><th>Paket</th><th>Tema</th><th>Harga</th><th>Status Bayar</th><th>Status Order</th><th>WA</th><th>Tgl Nikah</th><th>Tgl Order</th><th>Tgl Aktif</th><th>Expired</th></tr>";
  foreach ($rows as $r) echo "<tr><td>{$r['kode_order']}</td><td>{$r['nama_pria']}</td><td>{$r['nama_wanita']}</td><td>" . strtoupper($r['paket']) . "</td><td>" . ucwords(str_replace('-', ' ', $r['tema'])) . "</td><td>{$r['harga']}</td><td>{$r['status_bayar']}</td><td>{$r['status_order']}</td><td>{$r['no_whatsapp']}</td><td>{$r['tanggal_nikah']}</td><td>{$r['created_at']}</td><td>{$r['tgl_aktif']}</td><td>{$r['tgl_expired']}</td></tr>";
  echo "</table>";
  exit;
}

$chart_labels  = array_map(fn($i) => $bulan_nama[$i], range(1, 12));
$chart_revenue = array_map(fn($d) => $d['total'],  array_values($revenue_bulanan));
$chart_orders  = array_map(fn($d) => $d['jumlah'], array_values($revenue_bulanan));
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan & Analitik – Bernada.ID Admin</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
  <link rel="manifest" href="../favicon_io/site.webmanifest">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
      --dark: #1a1a1a;
      --gray: #5a5a5a;
      --light: #f8f5f5;
      --white: #fff;
      --border: #e0e0e0;
      --green: #2e9e5b;
      --amber: #c9a227;
      --blue: #2d7dd2
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--light);
      color: var(--dark)
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px;
      background: #1a1a1a;
      padding: 1.5rem 0;
      z-index: 100;
      display: flex;
      flex-direction: column
    }

    .sidebar-brand {
      padding: 0 1.5rem 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, .08)
    }

    .sidebar-brand h2 {
      font-size: 18px;
      color: #fff;
      font-weight: 700
    }

    .sidebar-brand h2 span {
      color: var(--r)
    }

    .sidebar-brand p {
      font-size: 11px;
      color: #555;
      margin-top: 2px
    }

    .nav-label {
      font-size: 10px;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: #444;
      padding: .75rem 1.5rem .4rem
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: .7rem 1.5rem;
      font-size: 13px;
      color: #888;
      text-decoration: none;
      transition: all .2s
    }

    .nav-item:hover,
    .nav-item.active {
      color: #fff;
      background: rgba(255, 255, 255, .06)
    }

    .nav-item.active {
      border-left: 3px solid var(--r)
    }

    .nav-item i {
      font-size: 18px
    }

    .sidebar-foot {
      padding: 1rem 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, .08);
      margin-top: auto
    }

    .admin-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: var(--r);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 700;
      flex-shrink: 0
    }

    .admin-info {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .admin-name {
      font-size: 13px;
      color: #fff
    }

    .admin-role {
      font-size: 11px;
      color: #555
    }

    .main {
      margin-left: 220px;
      min-height: 100vh
    }

    .topbar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 0 2rem;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50
    }

    .topbar-title {
      font-size: 16px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .topbar-title i {
      color: var(--r)
    }

    .filter-bar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: .75rem 2rem;
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap
    }

    .filter-bar label {
      font-size: 13px;
      color: var(--gray);
      font-weight: 500
    }

    .filter-bar select {
      padding: 7px 12px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 13px;
      font-family: inherit;
      background: var(--white);
      color: var(--dark);
      outline: none;
      cursor: pointer
    }

    .filter-bar select:focus {
      border-color: var(--r)
    }

    .btn-export {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: var(--green);
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      font-family: inherit;
      transition: background .2s;
      margin-left: auto
    }

    .btn-export:hover {
      background: #1a6640
    }

    .content {
      padding: 1.75rem 2rem
    }

    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      margin-bottom: 1.75rem
    }

    .kpi-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.25rem;
      position: relative;
      overflow: hidden
    }

    .kpi-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px
    }

    .kpi-card.k-red::before {
      background: var(--r)
    }

    .kpi-card.k-green::before {
      background: var(--green)
    }

    .kpi-card.k-blue::before {
      background: var(--blue)
    }

    .kpi-card.k-amber::before {
      background: var(--amber)
    }

    .kpi-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      margin-bottom: .85rem
    }

    .kpi-card.k-red .kpi-icon {
      background: var(--rl);
      color: var(--r)
    }

    .kpi-card.k-green .kpi-icon {
      background: #e8f7f0;
      color: var(--green)
    }

    .kpi-card.k-blue .kpi-icon {
      background: #e8f0ff;
      color: var(--blue)
    }

    .kpi-card.k-amber .kpi-icon {
      background: #fff8e0;
      color: var(--amber)
    }

    .kpi-num {
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--dark);
      line-height: 1;
      margin-bottom: 4px
    }

    .kpi-label {
      font-size: 12px;
      color: var(--gray)
    }

    .kpi-sub {
      font-size: 11px;
      color: #aaa;
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 4px
    }

    .chart-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.25rem;
      margin-bottom: 1.25rem
    }

    .chart-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.5rem
    }

    .chart-card.full {
      grid-column: 1/-1
    }

    .chart-title {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: .25rem;
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .chart-sub {
      font-size: 12px;
      color: var(--gray);
      margin-bottom: 1.25rem
    }

    .chart-wrap {
      position: relative;
      height: 240px
    }

    .chart-wrap.sm {
      height: 200px
    }

    .paket-row {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: .6rem 0;
      border-bottom: 1px solid #f5f5f5
    }

    .paket-row:last-child {
      border: none
    }

    .paket-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0
    }

    .pi-silver {
      background: #f0f0f0
    }

    .pi-gold {
      background: #fff8e0
    }

    .pi-platinum {
      background: #f0e8f8
    }

    .paket-info {
      flex: 1
    }

    .paket-name {
      font-size: 13px;
      font-weight: 600
    }

    .paket-bar-wrap {
      height: 5px;
      background: #f0f0f0;
      border-radius: 99px;
      margin-top: 4px;
      overflow: hidden
    }

    .paket-bar {
      height: 100%;
      border-radius: 99px
    }

    .pb-silver {
      background: #888
    }

    .pb-gold {
      background: var(--amber)
    }

    .pb-platinum {
      background: #9c6dd1
    }

    .paket-nums {
      text-align: right;
      flex-shrink: 0
    }

    .paket-nums .n1 {
      font-size: 14px;
      font-weight: 700;
      color: var(--dark)
    }

    .paket-nums .n2 {
      font-size: 11px;
      color: var(--gray)
    }

    .tema-row {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: .55rem 0;
      border-bottom: 1px solid #f5f5f5
    }

    .tema-row:last-child {
      border: none
    }

    .tema-rank {
      width: 22px;
      font-size: 12px;
      font-weight: 700;
      color: #aaa;
      text-align: center;
      flex-shrink: 0
    }

    .tema-rank.top {
      color: var(--r)
    }

    .tema-dot-big {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      flex-shrink: 0
    }

    .tema-info-wrap {
      flex: 1;
      min-width: 0
    }

    .tema-name-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 3px
    }

    .tema-name-row .cnt {
      font-size: 12px;
      color: var(--gray)
    }

    .tema-bar-outer {
      height: 4px;
      background: #f0f0f0;
      border-radius: 99px;
      overflow: hidden
    }

    .tema-bar-inner {
      height: 100%;
      border-radius: 99px
    }

    .best-banner {
      background: linear-gradient(135deg, var(--rd), var(--r));
      border-radius: 14px;
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1.25rem;
      margin-bottom: 1.25rem
    }

    .best-banner .bb-icon {
      font-size: 2rem;
      flex-shrink: 0
    }

    .best-banner .bb-text h3 {
      font-size: 15px;
      font-weight: 600;
      color: #fff;
      margin-bottom: 3px
    }

    .best-banner .bb-text p {
      font-size: 13px;
      color: rgba(255, 255, 255, .7)
    }

    .best-banner .bb-num {
      margin-left: auto;
      text-align: right;
      flex-shrink: 0
    }

    .best-banner .bb-num .n1 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff
    }

    .best-banner .bb-num .n2 {
      font-size: 12px;
      color: rgba(255, 255, 255, .6)
    }

    .recent-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      margin-bottom: 1.25rem
    }

    .recent-head {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .recent-head h3 {
      font-size: 14px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .recent-head a {
      font-size: 12px;
      color: var(--r);
      text-decoration: none
    }

    table.rt {
      width: 100%;
      border-collapse: collapse
    }

    table.rt th {
      padding: .7rem 1.25rem;
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .06em;
      color: var(--gray);
      background: #fafafa;
      border-bottom: 1px solid var(--border)
    }

    table.rt td {
      padding: .8rem 1.25rem;
      font-size: 13px;
      border-bottom: 1px solid #f8f8f8;
      vertical-align: middle
    }

    table.rt tr:last-child td {
      border: none
    }

    table.rt tr:hover td {
      background: #fafafa
    }

    .badge {
      display: inline-block;
      font-size: 10px;
      font-weight: 600;
      padding: 3px 8px;
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

    @media(max-width:1100px) {
      .kpi-grid {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    @media(max-width:900px) {
      .chart-grid {
        grid-template-columns: 1fr
      }
    }
  </style>
</head>

<body>

  <div class="sidebar">
    <div class="sidebar-brand">
      <h2>BERNADA<span>.ID</span></h2>
      <p>Admin Panel</p>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-label">Menu</div>
      <a href="admin_dashboard.php" class="nav-item active"><i class='bx bxs-dashboard'></i> Dashboard</a>
      <a href="admin_dashboard.php?filter=baru" class="nav-item"><i class='bx bx-cart-add'></i> Order Baru <?php if ($baru > 0): ?><span style="background:var(--r);color:#fff;font-size:10px;padding:2px 7px;border-radius:20px;margin-left:auto"><?= $baru ?></span><?php endif ?></a>
      <a href="admin_dashboard.php?filter=bayar" class="nav-item"><i class='bx bx-credit-card'></i> Konfirmasi Bayar <?php if ($pending > 0): ?><span style="background:#e07820;color:#fff;font-size:10px;padding:2px 7px;border-radius:20px;margin-left:auto"><?= $pending ?></span><?php endif ?></a>
      <a href="admin_dashboard.php?filter=aktif" class="nav-item"><i class='bx bx-check-circle'></i> Undangan Aktif</a>
      <div class="nav-label">Lainnya</div>
      <a href="../halaman.php" class="nav-item" target="_blank"><i class='bx bx-globe'></i> Lihat Website</a>
      <a href="laporan.php" class="nav-item"><i class='bx bx-bar-chart'></i> Laporan & Analitik</a>
      <a href="admin_logout.php" class="nav-item"><i class='bx bx-log-out'></i> Logout</a>
    </nav>
    <div class="sidebar-foot">
      <div class="admin-info">
        <div class="admin-avatar"><?= strtoupper(substr($admin_nama, 0, 1)) ?></div>
        <div>
          <div class="admin-name"><?= $admin_nama ?></div>
          <div class="admin-role">Administrator</div>
        </div>
      </div>
    </div>
  </div>

  <div class="main">
    <div class="topbar">
      <div class="topbar-title"><i class='bx bx-bar-chart-alt-2'></i> Laporan & Analitik</div>
      <span style="font-size:13px;color:var(--gray)">Data tahun <strong><?= $tahun ?></strong></span>
    </div>

    <div class="filter-bar">
      <label>Tahun:</label>
      <form method="GET" style="display:contents">
        <select name="tahun" onchange="this.form.submit()">
          <?php for ($y = date('Y'); $y >= 2023; $y--): ?>
            <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor ?>
        </select>
      </form>
      <a href="?tahun=<?= $tahun ?>&export=excel" class="btn-export">
        <i class='bx bx-download'></i> Export Excel <?= $tahun ?>
      </a>
    </div>

    <div class="content">

      <?php if ($best_month > 0 && $best_rev > 0): ?>
        <div class="best-banner">
          <div class="bb-icon"><i class='bx bx-trophy' style="font-size:2.6rem; color:#ffd700"></i></div>
          <div class="bb-text">
            <h3>Bulan Terbaik <?= $tahun ?></h3>
            <p><?= $bulan_full[$best_month] ?> — <?= $revenue_bulanan[$best_month]['jumlah'] ?> order berhasil lunas</p>
          </div>
          <div class="bb-num">
            <div class="n1">Rp <?= number_format($best_rev / 1000, 0, ',', '.') ?> K</div>
            <div class="n2">Revenue tertinggi</div>
          </div>
        </div>
      <?php endif ?>

      <div class="kpi-grid">
        <div class="kpi-card k-red">
          <div class="kpi-icon"><i class='bx bx-money'></i></div>
          <div class="kpi-num">Rp <?= number_format($total_rev / 1000, 0, ',', '.') ?> K</div>
          <div class="kpi-label">Total Revenue <?= $tahun ?></div>
          <div class="kpi-sub"><i class='bx bx-info-circle'></i> Dari order lunas</div>
        </div>
        <div class="kpi-card k-green">
          <div class="kpi-icon"><i class='bx bx-cart'></i></div>
          <div class="kpi-num"><?= $total_ord ?></div>
          <div class="kpi-label">Total Order <?= $tahun ?></div>
          <div class="kpi-sub"><i class='bx bx-info-circle'></i> Semua status</div>
        </div>
        <div class="kpi-card k-blue">
          <div class="kpi-icon"><i class='bx bx-check-circle'></i></div>
          <div class="kpi-num"><?= $total_aktif ?></div>
          <div class="kpi-label">Undangan Aktif Saat Ini</div>
          <div class="kpi-sub"><i class='bx bx-globe'></i> Live sekarang</div>
        </div>
        <div class="kpi-card k-amber">
          <div class="kpi-icon"><i class='bx bx-trending-up'></i></div>
          <div class="kpi-num">Rp <?= number_format($avg_order / 1000, 1, ',', '.') ?> K</div>
          <div class="kpi-label">Rata-rata per Order</div>
          <div class="kpi-sub"><i class='bx bx-calculator'></i> Order lunas saja</div>
        </div>
      </div>

      <div class="chart-grid">
        <div class="chart-card full">
          <div class="chart-title">
            <span><i class='bx bx-bar-chart' style="font-size: 1.27rem; color: var(--r)"></i> Revenue &amp; Order per Bulan</span>
            <span style="font-size:12px;color:var(--gray);font-weight:400">Tahun <?= $tahun ?></span>
          </div>
          <div class="chart-sub">Total pendapatan dan jumlah order setiap bulan</div>
          <div class="chart-wrap"><canvas id="chartRevenue"></canvas></div>
        </div>

        <div class="chart-card">
          <div class="chart-title">
            <span><i class='bx bx-package' style="font-size: 1.27rem; color: var(--r)"></i> Distribusi Paket</span>
          </div>
          <div class="chart-sub">Perbandingan order per paket tahun <?= $tahun ?></div>
          <div class="chart-wrap sm"><canvas id="chartPaket"></canvas></div>
          <div style="margin-top:.75rem">
            <?php
            $total_paket = array_sum(array_column($paket_data, 'jumlah')) ?: 1;
            $paket_icons = ['silver' => '🥈', 'gold' => '🥇', 'platinum' => '💎'];
            foreach ($paket_data as $p):
              $pct = round($p['jumlah'] / $total_paket * 100);
            ?>
              <div class="paket-row">
                <div class="paket-icon pi-<?= $p['paket'] ?>"><?= $paket_icons[$p['paket']] ?? '📦' ?></div>
                <div class="paket-info">
                  <div class="paket-name"><?= strtoupper($p['paket']) ?></div>
                  <div class="paket-bar-wrap">
                    <div class="paket-bar pb-<?= $p['paket'] ?>" style="width:<?= $pct ?>%"></div>
                  </div>
                </div>
                <div class="paket-nums">
                  <div class="n1"><?= $p['jumlah'] ?></div>
                  <div class="n2"><?= $pct ?>% · Rp <?= number_format($p['revenue'] / 1000, 0, ',', '.') ?> K</div>
                </div>
              </div>
            <?php endforeach ?>
            <?php if (empty($paket_data)): ?><div style="text-align:center;padding:1rem;color:#aaa;font-size:13px">Belum ada data</div><?php endif ?>
          </div>
        </div>

        <div class="chart-card">
          <div class="chart-title">
            <span><i class='bx bx-palette' style="font-size: 1.27rem; color: var(--r)"></i> Tema Terpopuler</span>
          </div>
          <div class="chart-sub">Tema yang paling banyak dipilih tahun <?= $tahun ?></div>
          <?php
          $max_tema = max(array_column($tema_data, 'jumlah') ?: [1]);
          $tema_palette = ['#C0393B', '#1a2e4a', '#d4687e', '#3d6b44', '#5c3d1e', '#9c6dd1', '#c9a227', '#2d7dd2', '#2e9e5b', '#888'];
          foreach ($tema_data as $idx => $t):
            $pct  = round($t['jumlah'] / $max_tema * 100);
            $color = $tema_palette[$idx] ?? '#888';
          ?>
            <div class="tema-row">
              <div class="tema-rank <?= $idx === 0 ? 'top' : '' ?>"><?= $idx + 1 ?></div>
              <div class="tema-dot-big" style="background:<?= $color ?>"></div>
              <div class="tema-info-wrap">
                <div class="tema-name-row">
                  <span><?= ucwords(str_replace('-', ' ', $t['tema'])) ?></span>
                  <span class="cnt"><?= $t['jumlah'] ?> order</span>
                </div>
                <div class="tema-bar-outer">
                  <div class="tema-bar-inner" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
                </div>
              </div>
            </div>
          <?php endforeach ?>
          <?php if (empty($tema_data)): ?><div style="text-align:center;padding:2rem;color:#aaa;font-size:13px">Belum ada data tema</div><?php endif ?>
        </div>
      </div>

      <div class="chart-grid">
        <div class="chart-card">
          <div class="chart-title">
            <span><i class='bx bx-bar-chart' style="font-size: 1.27rem; color: var(--r)"></i> Status Order (All Time)</span>
          </div>
          <div class="chart-sub">Distribusi status semua order yang masuk</div>
          <div class="chart-wrap sm"><canvas id="chartStatus"></canvas></div>
        </div>
        <div class="chart-card">
          <div class="chart-title">
            <span><i class='bx bx-money' style="font-size: 1.27rem; color: var(--r)"></i> Revenue per Paket</span>
          </div>
          <div class="chart-sub">Kontribusi revenue dari setiap paket tahun <?= $tahun ?></div>
          <div class="chart-wrap sm"><canvas id="chartRevPaket"></canvas></div>
        </div>
      </div>

      <div class="recent-card">
        <div class="recent-head">
          <h3><i class='bx bx-time-five' style="font-size: 1.27rem; color: var(--r)"></i> Order Terbaru</h3>
          <a href="index.php">Lihat semua →</a>
        </div>
        <div style="overflow-x:auto">
          <table class="rt">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Pengantin</th>
                <th>Tema</th>
                <th>Paket</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent as $r): ?>
                <tr>
                  <td style="font-family:monospace;font-size:12px;color:var(--r);font-weight:600"><?= $r['kode_order'] ?></td>
                  <td style="font-weight:500"><?= $r['nama_pria'] ?> &amp; <?= $r['nama_wanita'] ?></td>
                  <td style="font-size:12px"><?= ucwords(str_replace('-', ' ', $r['tema'])) ?></td>
                  <td><span class="badge badge-<?= $r['paket'] ?>"><?= strtoupper($r['paket']) ?></span></td>
                  <td style="font-weight:600;color:var(--r)"><?= $r['harga'] > 0 ? 'Rp ' . number_format($r['harga'], 0, ',', '.') : ' Gratis' ?></td>
                  <td><span class="badge badge-<?= $r['status_order'] ?>"><?= ucfirst($r['status_order']) ?></span></td>
                  <td style="font-size:12px;color:var(--gray)"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                </tr>
              <?php endforeach ?>
              <?php if (empty($recent)): ?><tr>
                  <td colspan="7" style="text-align:center;padding:2rem;color:#aaa">Belum ada order</td>
                </tr><?php endif ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <script>
    Chart.defaults.font.family = "'Plus Jakarta Sans',sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#888';

    const labels = <?= json_encode($chart_labels) ?>;
    const revenues = <?= json_encode($chart_revenue) ?>;
    const orders = <?= json_encode($chart_orders) ?>;
    const paketNames = <?= json_encode(array_column($paket_data, 'paket')) ?>;
    const paketCounts = <?= json_encode(array_column($paket_data, 'jumlah')) ?>;
    const paketRevs = <?= json_encode(array_column($paket_data, 'revenue')) ?>;
    const statusKeys = <?= json_encode(array_keys($status_map)) ?>;
    const statusVals = <?= json_encode(array_values($status_map)) ?>;
    const paketColors = {
      silver: '#888',
      gold: '#c9a227',
      platinum: '#9c6dd1'
    };

    // Chart 1 — Revenue + Order
    new Chart(document.getElementById('chartRevenue'), {
      type: 'bar',
      data: {
        labels,
        datasets: [{
            label: 'Revenue (Rp)',
            data: revenues,
            backgroundColor: 'rgba(192,57,59,.15)',
            borderColor: '#C0393B',
            borderWidth: 2,
            borderRadius: 6,
            yAxisID: 'y'
          },
          {
            label: 'Jumlah Order',
            data: orders,
            type: 'line',
            borderColor: '#2d7dd2',
            backgroundColor: 'rgba(45,125,210,.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#2d7dd2',
            pointRadius: 4,
            tension: .4,
            fill: true,
            yAxisID: 'y1'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              boxWidth: 12,
              padding: 16
            }
          },
          tooltip: {
            callbacks: {
              label: ctx => ctx.datasetIndex === 0 ? 'Revenue: Rp ' + ctx.raw.toLocaleString('id-ID') : 'Order: ' + ctx.raw
            }
          }
        },
        scales: {
          y: {
            type: 'linear',
            position: 'left',
            grid: {
              color: 'rgba(0,0,0,.05)'
            },
            ticks: {
              callback: v => 'Rp ' + (v / 1000) + 'K'
            }
          },
          y1: {
            type: 'linear',
            position: 'right',
            grid: {
              drawOnChartArea: false
            },
            ticks: {
              stepSize: 1
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    // Chart 2 — Paket donut
    new Chart(document.getElementById('chartPaket'), {
      type: 'doughnut',
      data: {
        labels: paketNames.map(p => p.toUpperCase()),
        datasets: [{
          data: paketCounts,
          backgroundColor: paketNames.map(p => paketColors[p] || '#ccc'),
          borderWidth: 3,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 10,
              padding: 14
            }
          },
          tooltip: {
            callbacks: {
              label: ctx => ctx.label + ': ' + ctx.raw + ' order'
            }
          }
        }
      }
    });

    // Chart 3 — Status donut
    const sColors = {
      aktif: '#2e9e5b',
      baru: '#2d7dd2',
      diproses: '#c9a227',
      nonaktif: '#aaa',
      batal: '#C0393B'
    };
    new Chart(document.getElementById('chartStatus'), {
      type: 'doughnut',
      data: {
        labels: statusKeys.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{
          data: statusVals,
          backgroundColor: statusKeys.map(s => sColors[s] || '#ccc'),
          borderWidth: 3,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 10,
              padding: 14
            }
          }
        }
      }
    });

    // Chart 4 — Revenue per paket bar
    new Chart(document.getElementById('chartRevPaket'), {
      type: 'bar',
      data: {
        labels: paketNames.map(p => p.toUpperCase()),
        datasets: [{
          label: 'Revenue',
          data: paketRevs,
          backgroundColor: paketNames.map(p => ({
            silver: 'rgba(136,136,136,.25)',
            gold: 'rgba(201,162,39,.25)',
            platinum: 'rgba(156,109,209,.25)'
          } [p] || 'rgba(192,57,59,.2)')),
          borderColor: paketNames.map(p => paketColors[p] || '#ccc'),
          borderWidth: 2,
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
            }
          }
        },
        scales: {
          y: {
            grid: {
              color: 'rgba(0,0,0,.05)'
            },
            ticks: {
              callback: v => 'Rp ' + (v / 1000) + 'K'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  </script>
</body>

</html>