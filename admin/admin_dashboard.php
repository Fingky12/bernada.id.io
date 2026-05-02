<?php
// ============================================
// FILE: admin/admin_dashboard.php (Dashboard Admin)
// ============================================
session_start();
require_once '../config/koneksi.php';

// Auth admin
if (!isset($_SESSION['admin_id'])) {
  header('Location: admin_login.php');
  exit;
}
$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';

// Statistik
$total   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$baru    = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_order='baru'")->fetchColumn();
$aktif   = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_order='aktif'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_bayar='menunggu_konfirmasi'")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(harga) FROM orders WHERE status_bayar='lunas'")->fetchColumn() ?? 0;

// Filter
$filter = $_GET['filter'] ?? 'semua';
$search = trim($_GET['q'] ?? '');

$where = "WHERE 1=1";
if ($filter === 'baru')    $where .= " AND status_order='baru'";
if ($filter === 'aktif')   $where .= " AND status_order='aktif'";
if ($filter === 'bayar')   $where .= " AND status_bayar='menunggu_konfirmasi'";
if ($filter === 'silver')  $where .= " AND paket='silver'";
if ($search) $where .= " AND (nama_pria LIKE '%{$search}%' OR nama_wanita LIKE '%{$search}%' OR kode_order LIKE '%{$search}%' OR no_whatsapp LIKE '%{$search}%')";

$orders = $pdo->query("SELECT * FROM orders $where ORDER BY created_at DESC LIMIT 100")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin – Bernada.ID</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
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
      --border: #e0e0e0
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--light);
      color: var(--dark)
    }

    /* SIDEBAR */
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
      color: #666;
      margin-top: 2px
    }

    .sidebar-nav {
      flex: 1;
      padding: 1rem 0;
      overflow-y: auto
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
      transition: all .2s;
      cursor: pointer;
      border: none;
      background: none;
      width: 100%
    }

    .nav-item:hover,
    .nav-item.active {
      color: #fff;
      background: rgba(255, 255, 255, .06)
    }

    .nav-item.active {
      border-left: 3px solid var(--r);
      color: #fff
    }

    .nav-item i {
      font-size: 18px
    }

    .sidebar-foot {
      padding: 1rem 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, .08)
    }

    .admin-info {
      display: flex;
      align-items: center;
      gap: 10px
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

    .admin-name {
      font-size: 13px;
      color: #fff
    }

    .admin-role {
      font-size: 11px;
      color: #555
    }

    /* MAIN */
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
      font-weight: 600
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 12px
    }

    .notif-badge {
      position: relative
    }

    .badge-num {
      position: absolute;
      top: -6px;
      right: -6px;
      background: var(--r);
      color: #fff;
      font-size: 10px;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700
    }

    .logout-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      background: var(--rl);
      color: var(--r);
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      border: 1px solid #f5c1c1;
      transition: all .2s
    }

    .logout-btn:hover {
      background: var(--r);
      color: #fff
    }

    /* CONTENT */
    .content {
      padding: 2rem
    }

    /* STATS */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 1rem;
      margin-bottom: 2rem
    }

    .stat-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.25rem;
      position: relative;
      overflow: hidden
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px
    }

    .stat-card.red::before {
      background: var(--r)
    }

    .stat-card.orange::before {
      background: #e07820
    }

    .stat-card.green::before {
      background: #2e9e5b
    }

    .stat-card.blue::before {
      background: #2d7dd2
    }

    .stat-card.gold::before {
      background: #c9a227
    }

    .stat-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      margin-bottom: .75rem
    }

    .stat-card.red .stat-icon {
      background: #fff0f0;
      color: var(--r)
    }

    .stat-card.orange .stat-icon {
      background: #fff3e0;
      color: #e07820
    }

    .stat-card.green .stat-icon {
      background: #e8f7f0;
      color: #2e9e5b
    }

    .stat-card.blue .stat-icon {
      background: #e8f0ff;
      color: #2d7dd2
    }

    .stat-card.gold .stat-icon {
      background: #fdf8e0;
      color: #c9a227
    }

    .stat-num {
      font-size: 22px;
      font-weight: 700;
      color: var(--dark)
    }

    .stat-label {
      font-size: 12px;
      color: var(--gray);
      margin-top: 3px
    }

    /* FILTER BAR */
    .filter-bar {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap
    }

    .filter-search {
      flex: 1;
      min-width: 200px;
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--light);
      border-radius: 8px;
      padding: 8px 14px
    }

    .filter-search input {
      border: none;
      background: transparent;
      outline: none;
      font-size: 14px;
      font-family: inherit;
      flex: 1;
      color: var(--dark)
    }

    .filter-search i {
      color: #aaa;
      font-size: 16px
    }

    .filter-tabs {
      display: flex;
      gap: 6px
    }

    .ftab {
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      border: 1px solid var(--border);
      background: var(--white);
      color: var(--gray);
      text-decoration: none;
      transition: all .2s
    }

    .ftab:hover,
    .ftab.active {
      background: var(--r);
      color: #fff;
      border-color: var(--r)
    }

    .ftab.orange.active {
      background: #e07820;
      border-color: #e07820
    }

    /* TABLE */
    .table-wrap {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden
    }

    .table-head {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .table-head h3 {
      font-size: 14px;
      font-weight: 600
    }

    .table-count {
      font-size: 12px;
      color: var(--gray)
    }

    table {
      width: 100%;
      border-collapse: collapse
    }

    th {
      padding: .75rem 1rem;
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .06em;
      color: var(--gray);
      background: #fafafa;
      border-bottom: 1px solid var(--border)
    }

    td {
      padding: .85rem 1rem;
      font-size: 13px;
      border-bottom: 1px solid #f5f5f5;
      vertical-align: middle
    }

    tr:last-child td {
      border-bottom: none
    }

    tr:hover td {
      background: #fafafa
    }

    /* BADGES */
    .badge {
      display: inline-block;
      font-size: 10px;
      font-weight: 600;
      padding: 3px 9px;
      border-radius: 20px
    }

    .badge-baru {
      background: #e8f0ff;
      color: #2d7dd2
    }

    .badge-diproses {
      background: #fff3e0;
      color: #e07820
    }

    .badge-aktif {
      background: #e8f7f0;
      color: #2e9e5b
    }

    .badge-nonaktif {
      background: #f5f5f5;
      color: #888
    }

    .badge-batal {
      background: #fdeaea;
      color: #a32d2d
    }

    .badge-lunas {
      background: #e8f7f0;
      color: #2e9e5b
    }

    .badge-pending {
      background: #f5f5f5;
      color: #888
    }

    .badge-konfirmasi {
      background: #fff3e0;
      color: #e07820
    }

    .badge-silver {
      background: #f0f0f0;
      color: #666
    }

    .badge-gold {
      background: #fdf8e0;
      color: #a06000
    }

    .badge-platinum {
      background: #f0e8f8;
      color: #6a2d9e
    }

    /* ACTION BTNS */
    .act-btn {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 5px 10px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: all .2s
    }

    .act-approve {
      background: #e8f7f0;
      color: #2e9e5b
    }

    .act-approve:hover {
      background: #2e9e5b;
      color: #fff
    }

    .act-view {
      background: #e8f0ff;
      color: #2d7dd2
    }

    .act-view:hover {
      background: #2d7dd2;
      color: #fff
    }

    .act-cancel {
      background: #fdeaea;
      color: #a32d2d
    }

    .act-cancel:hover {
      background: #a32d2d;
      color: #fff
    }

    .act-link {
      background: var(--rl);
      color: var(--r)
    }

    .act-link:hover {
      background: var(--r);
      color: #fff
    }

    .nama-cell strong {
      display: block;
      font-size: 13px;
      font-weight: 600
    }

    .nama-cell span {
      font-size: 11px;
      color: var(--gray)
    }

    .kode-cell {
      font-family: monospace;
      font-size: 12px;
      color: var(--r);
      font-weight: 600
    }

    @media(max-width:1100px) {
      .stats-grid {
        grid-template-columns: repeat(3, 1fr)
      }
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
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

  <!-- MAIN -->
  <div class="main">
    <div class="topbar">
      <div class="topbar-title">Dashboard Order</div>
      <div class="topbar-right">
        <?php if ($pending > 0): ?>
          <a href="?filter=bayar" class="notif-badge" style="color:var(--gray);text-decoration:none">
            <i class='bx bx-bell' style="font-size:22px"></i>
            <span class="badge-num"><?= $pending ?></span>
          </a>
        <?php endif ?>
        <a href="admin_logout.php" class="logout-btn"><i class='bx bx-log-out'></i> Logout</a>
      </div>
    </div>

    <div class="content">

      <!-- STATS -->
      <div class="stats-grid">
        <div class="stat-card red">
          <div class="stat-icon"><i class='bx bx-cart'></i></div>
          <div class="stat-num"><?= $total ?></div>
          <div class="stat-label">Total Order</div>
        </div>
        <div class="stat-card blue">
          <div class="stat-icon"><i class='bx bx-time'></i></div>
          <div class="stat-num"><?= $baru ?></div>
          <div class="stat-label">Order Baru</div>
        </div>
        <div class="stat-card orange">
          <div class="stat-icon"><i class='bx bx-credit-card'></i></div>
          <div class="stat-num"><?= $pending ?></div>
          <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon"><i class='bx bx-check-circle'></i></div>
          <div class="stat-num"><?= $aktif ?></div>
          <div class="stat-label">Undangan Aktif</div>
        </div>
        <div class="stat-card gold">
          <div class="stat-icon"><i class='bx bx-money'></i></div>
          <div class="stat-num">Rp <?= number_format($revenue / 1000, 0, ',', '.') ?>K</div>
          <div class="stat-label">Total Revenue</div>
        </div>
      </div>

      <!-- FILTER -->
      <div class="filter-bar">
        <form method="GET" style="display:contents">
          <div class="filter-search">
            <i class='bx bx-search'></i>
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, kode order, WA..." />
            <?php if ($filter !== 'semua'): ?><input type="hidden" name="filter" value="<?= $filter ?>" /><?php endif ?>
          </div>
          <button type="submit" style="display:none"></button>
        </form>
        <div class="filter-tabs">
          <a href="?" class="ftab <?= $filter === 'semua' ? 'active' : '' ?>">Semua</a>
          <a href="?filter=baru" class="ftab <?= $filter === 'baru' ? 'active' : '' ?>">Baru</a>
          <a href="?filter=bayar" class="ftab orange <?= $filter === 'bayar' ? 'active' : '' ?>">Konfirmasi Bayar</a>
          <a href="?filter=aktif" class="ftab <?= $filter === 'aktif' ? 'active' : '' ?>">Aktif</a>
          <a href="?filter=silver" class="ftab <?= $filter === 'silver' ? 'active' : '' ?>">Silver</a>
        </div>
      </div>

      <!-- TABLE -->
      <div class="table-wrap">
        <div class="table-head">
          <h3>Daftar Order</h3>
          <span class="table-count"><?= count($orders) ?> order ditemukan</span>
        </div>
        <div style="overflow-x:auto">
          <table>
            <thead>
              <tr>
                <th>Kode</th>
                <th>Pengantin</th>
                <th>Tanggal Nikah</th>
                <th>Tema</th>
                <th>Paket</th>
                <th>Status Bayar</th>
                <th>Status Order</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($orders)): ?>
                <tr>
                  <td colspan="8" style="text-align:center;padding:3rem;color:#aaa">Tidak ada order ditemukan</td>
                </tr>
              <?php else: ?>
                <?php foreach ($orders as $o):
                  $tgl = new DateTime($o['tanggal_nikah']);
                  $tema_label = ucwords(str_replace('-', ' ', $o['tema']));
                ?>
                  <tr>
                    <td>
                      <div class="kode-cell"><?= $o['kode_order'] ?></div>
                      <?php if ($o['kode_undangan']): ?>
                        <div style="font-size:10px;color:#aaa;margin-top:2px"><?= $o['kode_undangan'] ?></div>
                      <?php endif ?>
                    </td>
                    <td>
                      <div class="nama-cell">
                        <strong><?= $o['nama_pria'] ?> & <?= $o['nama_wanita'] ?></strong>
                        <span><?= $o['no_whatsapp'] ?></span>
                      </div>
                    </td>
                    <td style="font-size:12px"><?= $tgl->format('d M Y') ?></td>
                    <td style="font-size:12px"><?= $tema_label ?></td>
                    <td><span class="badge badge-<?= $o['paket'] ?>"><?= strtoupper($o['paket']) ?></span></td>
                    <td>
                      <?php
                      $sb = $o['status_bayar'];
                      $sb_class = $sb === 'lunas' ? 'lunas' : ($sb === 'menunggu_konfirmasi' ? 'konfirmasi' : 'pending');
                      $sb_label = $sb === 'lunas' ? 'Lunas' : ($sb === 'menunggu_konfirmasi' ? 'Konfirmasi' : 'Pending');
                      ?>
                      <span class="badge badge-<?= $sb_class ?>"><?= $sb_label ?></span>
                      <?php if ($o['bukti_bayar']): ?>
                        <a href="../<?= $o['bukti_bayar'] ?>" target="_blank" style="font-size:10px;color:var(--r);display:block;margin-top:3px">Lihat bukti</a>
                      <?php endif ?>
                    </td>
                    <td>
                      <?php
                      $so = $o['status_order'];
                      $so_class = ['baru' => 'baru', 'diproses' => 'diproses', 'aktif' => 'aktif', 'nonaktif' => 'nonaktif', 'batal' => 'batal'][$so] ?? 'baru';
                      ?>
                      <span class="badge badge-<?= $so_class ?>"><?= ucfirst($so) ?></span>
                    </td>
                    <td>
                      <div style="display:flex;gap:4px;flex-wrap:wrap">
                        <!-- Detail -->
                        <a href="detail.php?id=<?= $o['id'] ?>" class="act-btn act-view"><i class='bx bx-show'></i> Detail</a>

                        <!-- Approve bayar -->
                        <?php if ($o['status_bayar'] === 'menunggu_konfirmasi' || ($o['paket'] !== 'silver' && $o['status_bayar'] === 'pending')): ?>
                          <a href="konfirmasi_bayar.php?id=<?= $o['id'] ?>" class="act-btn act-approve"><i class='bx bx-check'></i> Approve</a>
                        <?php endif ?>

                        <!-- Link undangan -->
                        <?php if ($o['status_order'] === 'aktif' && $o['kode_undangan']): ?>
                          <a href="../undangan/?kode=<?= $o['kode_undangan'] ?>" target="_blank" class="act-btn act-link"><i class='bx bx-link-external'></i> Link</a>
                        <?php endif ?>

                        <!-- Batalkan -->
                        <?php if ($o['status_order'] !== 'batal' && $o['status_order'] !== 'aktif'): ?>
                          <a href="batalkan.php?id=<?= $o['id'] ?>" class="act-btn act-cancel" onclick="return confirm('Yakin batalkan order ini?')"><i class='bx bx-x'></i></a>
                        <?php endif ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach ?>
              <?php endif ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

</body>

</html>