<?php
// ============================================
// FILE: admin/tema.php
// Manajemen Tema — Bernada.ID Admin
// ============================================
session_start();
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) {
  header('Location: login.php');
  exit;
}
$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';

// Statistik
$total   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$baru    = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_order='baru'")->fetchColumn();
$aktif   = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_order='aktif'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status_bayar IN ('menunggu_konfirmasi','pending') AND paket != 'silver'")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(harga) FROM orders WHERE status_bayar='lunas'")->fetchColumn() ?? 0;

// Filter
$filter = $_GET['filter'] ?? 'semua';
$search = trim($_GET['q'] ?? '');

$where = "WHERE 1=1";
if ($filter === 'baru')    $where .= " AND status_order='baru'";
if ($filter === 'aktif')   $where .= " AND status_order='aktif'";
if ($filter === 'bayar')   $where .= " AND status_bayar IN ('menunggu_konfirmasi','pending') AND paket != 'silver'";
if ($filter === 'silver')  $where .= " AND paket='silver'";
if ($search) $where .= " AND (nama_pria LIKE '%{$search}%' OR nama_wanita LIKE '%{$search}%' OR kode_order LIKE '%{$search}%' OR no_whatsapp LIKE '%{$search}%')";

$orders = $pdo->query("SELECT * FROM orders $where ORDER BY created_at DESC LIMIT 100")->fetchAll();

// ── Handle AJAX actions ──────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  header('Content-Type: application/json');
  $action = $_POST['action'];
  $id     = (int)($_POST['id'] ?? 0);

  // Toggle status aktif/nonaktif
  if ($action === 'toggle_status') {
    $curr = $pdo->prepare("SELECT status FROM tema WHERE id=?");
    $curr->execute([$id]);
    $tema = $curr->fetch();
    if ($tema) {
      $new = $tema['status'] === 'aktif' ? 'nonaktif' : 'aktif';
      $pdo->prepare("UPDATE tema SET status=? WHERE id=?")->execute([$new, $id]);
      echo json_encode(['status' => 'success', 'new_status' => $new]);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Tema tidak ditemukan']);
    }
    exit;
  }

  // Toggle tipe gratis/premium
  if ($action === 'toggle_tipe') {
    $curr = $pdo->prepare("SELECT tipe FROM tema WHERE id=?");
    $curr->execute([$id]);
    $tema = $curr->fetch();
    if ($tema) {
      $new = $tema['tipe'] === 'gratis' ? 'premium' : 'gratis';
      $pdo->prepare("UPDATE tema SET tipe=? WHERE id=?")->execute([$new, $id]);
      echo json_encode(['status' => 'success', 'new_tipe' => $new]);
    } else {
      echo json_encode(['status' => 'error']);
    }
    exit;
  }

  // Update urutan (drag & drop)
  if ($action === 'update_urutan') {
    $urutan = json_decode($_POST['urutan'] ?? '[]', true);
    foreach ($urutan as $pos => $tid) {
      $pdo->prepare("UPDATE tema SET urutan=? WHERE id=?")->execute([$pos + 1, (int)$tid]);
    }
    echo json_encode(['status' => 'success']);
    exit;
  }

  // Simpan tema baru / edit
  if ($action === 'simpan') {
    $edit_id    = (int)($_POST['edit_id']   ?? 0);
    $slug       = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($_POST['slug']   ?? '')));
    $nama       = trim($_POST['nama']        ?? '');
    $deskripsi  = trim($_POST['deskripsi']   ?? '');
    $file_php   = trim($_POST['file_php']    ?? '');
    $tipe       = in_array($_POST['tipe'] ?? '', ['gratis', 'premium']) ? $_POST['tipe'] : 'gratis';
    $status     = in_array($_POST['status'] ?? '', ['aktif', 'nonaktif']) ? $_POST['status'] : 'aktif';
    $urutan     = (int)($_POST['urutan']     ?? 0);

    if (!$slug || !$nama || !$file_php) {
      echo json_encode(['status' => 'error', 'message' => 'Slug, nama, dan file PHP wajib diisi']);
      exit;
    }

    // Handle thumbnail upload
    $thumbnail = $_POST['thumbnail_existing'] ?? null;
    if (!empty($_FILES['thumbnail']['name'])) {
      $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) && $_FILES['thumbnail']['size'] < 3 * 1024 * 1024) {
        $dir = __DIR__ . '/../img/tema/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $fname = 'tema-' . $slug . '.' . $ext;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dir . $fname);
        $thumbnail = 'img/tema/' . $fname;
      }
    }

    if ($edit_id) {
      $pdo->prepare("UPDATE tema SET slug=?,nama=?,deskripsi=?,file_php=?,tipe=?,status=?,urutan=?" . (
        $thumbnail ? ",thumbnail=?" : ""
      ) . " WHERE id=?")->execute(array_merge(
        [$slug, $nama, $deskripsi, $file_php, $tipe, $status, $urutan],
        $thumbnail ? [$thumbnail] : [],
        [$edit_id]
      ));
      echo json_encode(['status' => 'success', 'message' => 'Tema berhasil diupdate!']);
    } else {
      try {
        $pdo->prepare("INSERT INTO tema (slug,nama,deskripsi,file_php,tipe,status,urutan,thumbnail) VALUES (?,?,?,?,?,?,?,?)")
          ->execute([$slug, $nama, $deskripsi, $file_php, $tipe, $status, $urutan, $thumbnail]);
        echo json_encode(['status' => 'success', 'message' => 'Tema berhasil ditambahkan!']);
      } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Slug sudah digunakan!']);
      }
    }
    exit;
  }

  // Hapus tema
  if ($action === 'hapus') {
    // Cek apakah tema digunakan di order
    $cek = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE tema=?");
    $slug_del = $pdo->prepare("SELECT slug FROM tema WHERE id=?")->execute([$id]);
    $tema_row = $pdo->prepare("SELECT slug FROM tema WHERE id=?");
    $tema_row->execute([$id]);
    $t = $tema_row->fetch();
    if ($t) {
      $pakai = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE tema=?");
      $pakai->execute([$t['slug']]);
      if ($pakai->fetchColumn() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Tema tidak bisa dihapus karena sudah digunakan di ' . $pakai->fetchColumn() . ' order!']);
        exit;
      }
    }
    $pdo->prepare("DELETE FROM tema WHERE id=?")->execute([$id]);
    echo json_encode(['status' => 'success', 'message' => 'Tema berhasil dihapus']);
    exit;
  }

  echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenal']);
  exit;
}

// ── GET: Ambil data tema ─────────────────────
$tema_list = $pdo->query("SELECT * FROM tema ORDER BY urutan ASC, id ASC")->fetchAll();

// ── Statistik pemakaian per tema ─────────────
$stats_raw = $pdo->query("
    SELECT tema as slug,
           COUNT(*) as total_order,
           SUM(CASE WHEN status_order='aktif' THEN 1 ELSE 0 END) as aktif,
           SUM(CASE WHEN status_bayar='lunas' THEN harga ELSE 0 END) as revenue
    FROM orders
    GROUP BY tema
")->fetchAll();
$stats = [];
foreach ($stats_raw as $s) $stats[$s['slug']] = $s;

// ── Summary counts ───────────────────────────
$total_tema   = count($tema_list);
$aktif_count  = count(array_filter($tema_list, fn($t) => $t['status'] === 'aktif'));
$gratis_count = count(array_filter($tema_list, fn($t) => $t['tipe'] === 'gratis'));
$premium_count = count(array_filter($tema_list, fn($t) => $t['tipe'] === 'premium'));

// Tema paling populer
$top_slug = null;
$top_count = 0;
foreach ($stats as $slug => $s) {
  if ($s['total_order'] > $top_count) {
    $top_count = $s['total_order'];
    $top_slug = $slug;
  }
}

// Warna preview per tema
$tema_colors = [
  'merah-klasik' => ['from' => '#1a0505', 'to' => '#C0393B', 'label_color' => '#ffd080'],
  'navy-elegant' => ['from' => '#0a1628', 'to' => '#2d4a6e', 'label_color' => '#c9a227'],
  'blush-pink'   => ['from' => '#3d1520', 'to' => '#e8a0b0', 'label_color' => '#fff'],
  'sage-garden'  => ['from' => '#1e2d1f', 'to' => '#3d6b44', 'label_color' => '#b8d4bc'],
  'rustic-brown' => ['from' => '#2c1a0e', 'to' => '#8b6340', 'label_color' => '#e8d5b7'],
];
$default_colors = ['from' => '#1a1a1a', 'to' => '#555', 'label_color' => '#fff'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manajemen Tema – Bernada.ID Admin</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
  <link rel="manifest" href="../favicon_io/site.webmanifest">
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
      --border: #e0e0e0;
      --green: #2e9e5b;
      --amber: #c9a227;
      --blue: #2d7dd2;
      --purple: #7c3aed;
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
      flex-direction: column;
      overflow-y: auto
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
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .topbar-title i {
      color: var(--r);
      font-size: 20px
    }

    .btn-tambah {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 9px 18px;
      background: var(--r);
      color: #fff;
      border-radius: 9px;
      border: none;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      transition: background .2s
    }

    .btn-tambah:hover {
      background: var(--rd)
    }

    /* CONTENT */
    .content {
      padding: 1.75rem 2rem
    }

    /* KPI */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      margin-bottom: 1.75rem
    }

    .kpi-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 12px
    }

    .kpi-icon {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0
    }

    .ki-red {
      background: var(--rl);
      color: var(--r)
    }

    .ki-green {
      background: #e8f7f0;
      color: var(--green)
    }

    .ki-amber {
      background: #fff8e0;
      color: var(--amber)
    }

    .ki-purple {
      background: #f3edff;
      color: var(--purple)
    }

    .kpi-info .num {
      font-size: 22px;
      font-weight: 700;
      color: var(--dark);
      line-height: 1
    }

    .kpi-info .lbl {
      font-size: 12px;
      color: var(--gray);
      margin-top: 3px
    }

    /* TOP BANNER */
    .top-banner {
      background: linear-gradient(135deg, var(--rd), var(--r));
      border-radius: 14px;
      padding: 1.1rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .top-banner .tb-icon {
      font-size: 1.8rem
    }

    .top-banner .tb-text h3 {
      font-size: 14px;
      font-weight: 600;
      color: #fff
    }

    .top-banner .tb-text p {
      font-size: 12px;
      color: rgba(255, 255, 255, .7);
      margin-top: 2px
    }

    .top-banner .tb-num {
      margin-left: auto;
      text-align: right
    }

    .top-banner .tb-num .n1 {
      font-size: 1.4rem;
      font-weight: 700;
      color: #fff
    }

    .top-banner .tb-num .n2 {
      font-size: 11px;
      color: rgba(255, 255, 255, .6)
    }

    /* FILTER */
    .filter-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1.25rem;
      flex-wrap: wrap
    }

    .ftab {
      padding: 7px 16px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      border: 1px solid var(--border);
      background: var(--white);
      color: var(--gray);
      transition: all .2s
    }

    .ftab:hover,
    .ftab.active {
      background: var(--r);
      color: #fff;
      border-color: var(--r)
    }

    .search-box {
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 7px 14px;
      margin-left: auto
    }

    .search-box input {
      border: none;
      outline: none;
      font-size: 13px;
      font-family: inherit;
      color: var(--dark);
      background: transparent;
      width: 180px
    }

    .search-box i {
      color: #aaa;
      font-size: 16px
    }

    /* TEMA GRID */
    .tema-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.25rem
    }

    /* TEMA CARD */
    .tema-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      transition: box-shadow .2s;
      position: relative
    }

    .tema-card:hover {
      box-shadow: 0 6px 28px rgba(0, 0, 0, .08)
    }

    .tema-card.nonaktif {
      opacity: .55
    }

    /* Preview */
    .tc-preview {
      height: 160px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-align: center;
      padding: 1rem;
      position: relative;
      overflow: hidden
    }

    .tc-preview::before {
      content: '';
      position: absolute;
      inset: 0;
      opacity: .1;
      background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E")
    }

    .tc-monogram {
      font-size: 2.5rem;
      font-weight: 700;
      opacity: .15;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      letter-spacing: .1em;
      pointer-events: none
    }

    .tc-name {
      font-size: 1.1rem;
      font-weight: 700;
      position: relative;
      margin-bottom: 4px
    }

    .tc-sub {
      font-size: 10px;
      letter-spacing: .15em;
      text-transform: uppercase;
      opacity: .6;
      position: relative
    }

    /* Badges overlay */
    .tc-badges {
      position: absolute;
      top: 10px;
      left: 10px;
      display: flex;
      gap: 5px;
      flex-wrap: wrap
    }

    .tc-badge {
      font-size: 10px;
      font-weight: 700;
      padding: 3px 9px;
      border-radius: 20px;
      backdrop-filter: blur(4px)
    }

    .tcb-gratis {
      background: rgba(46, 158, 91, .85);
      color: #fff
    }

    .tcb-premium {
      background: rgba(124, 58, 237, .85);
      color: #fff
    }

    .tcb-nonaktif {
      background: rgba(0, 0, 0, .5);
      color: #fff
    }

    .tcb-top {
      background: rgba(201, 162, 39, .9);
      color: #1a1a1a
    }

    /* Body */
    .tc-body {
      padding: 1.1rem 1.25rem
    }

    .tc-meta {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: .75rem;
      margin-bottom: .85rem
    }

    .tc-title {
      font-size: 15px;
      font-weight: 600;
      color: var(--dark)
    }

    .tc-desc {
      font-size: 12px;
      color: var(--gray);
      margin-top: 3px;
      line-height: 1.5
    }

    .tc-file {
      font-size: 11px;
      color: #bbb;
      margin-top: 4px;
      display: flex;
      align-items: center;
      gap: 4px;
      font-family: monospace
    }

    /* Stats */
    .tc-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-bottom: 1rem;
      padding: .75rem;
      background: var(--light);
      border-radius: 8px
    }

    .tc-stat .s-num {
      font-size: 16px;
      font-weight: 700;
      color: var(--dark)
    }

    .tc-stat .s-lbl {
      font-size: 10px;
      color: var(--gray);
      margin-top: 2px
    }

    /* Toggle switches */
    .tc-toggles {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding-top: .85rem;
      border-top: 1px solid #f5f5f5
    }

    .toggle-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: var(--gray)
    }

    .toggle-item span {
      font-weight: 500
    }

    .switch {
      position: relative;
      width: 38px;
      height: 20px;
      flex-shrink: 0
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0
    }

    .slider {
      position: absolute;
      inset: 0;
      background: #ddd;
      border-radius: 20px;
      cursor: pointer;
      transition: .3s
    }

    .slider::before {
      content: '';
      position: absolute;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: #fff;
      left: 3px;
      top: 3px;
      transition: .3s;
      box-shadow: 0 1px 3px rgba(0, 0, 0, .2)
    }

    input:checked+.slider {
      background: var(--r)
    }

    input:checked+.slider::before {
      transform: translateX(18px)
    }

    .switch-green input:checked+.slider {
      background: var(--green)
    }

    .switch-purple input:checked+.slider {
      background: var(--purple)
    }

    .toggle-divider {
      width: 1px;
      height: 16px;
      background: #e0e0e0;
      margin: 0 4px
    }

    /* Action buttons */
    .tc-actions {
      display: flex;
      gap: 6px;
      margin-top: .85rem;
      flex-wrap: wrap
    }

    .act-btn {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 6px 12px;
      border-radius: 7px;
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-family: inherit;
      text-decoration: none;
      transition: all .2s
    }

    .act-edit {
      background: #e8f0ff;
      color: var(--blue)
    }

    .act-edit:hover {
      background: var(--blue);
      color: #fff
    }

    .act-preview {
      background: var(--rl);
      color: var(--r)
    }

    .act-preview:hover {
      background: var(--r);
      color: #fff
    }

    .act-del {
      background: #fdeaea;
      color: var(--rd)
    }

    .act-del:hover {
      background: var(--rd);
      color: #fff
    }

    /* MODAL */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 999;
      align-items: center;
      justify-content: center;
      padding: 1rem
    }

    .modal-overlay.show {
      display: flex
    }

    .modal-box {
      background: var(--white);
      border-radius: 16px;
      padding: 2rem;
      max-width: 540px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      animation: slideUp .3s ease
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
      font-size: 17px;
      font-weight: 600;
      margin-bottom: .3rem
    }

    .modal-sub {
      font-size: 13px;
      color: var(--gray);
      margin-bottom: 1.5rem
    }

    .mfield {
      margin-bottom: 1rem
    }

    .mfield label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: var(--gray);
      margin-bottom: 5px
    }

    .mfield input,
    .mfield select,
    .mfield textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #e0e0e0;
      border-radius: 9px;
      font-size: 14px;
      font-family: inherit;
      color: var(--dark);
      background: #fafafa;
      outline: none;
      transition: border-color .2s
    }

    .mfield input:focus,
    .mfield select:focus,
    .mfield textarea:focus {
      border-color: var(--r);
      box-shadow: 0 0 0 3px rgba(192, 57, 59, .08);
      background: #fff
    }

    .mfield textarea {
      resize: vertical;
      min-height: 70px
    }

    .mfield-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px
    }

    .upload-zone {
      border: 2px dashed #e0e0e0;
      border-radius: 10px;
      padding: 1.25rem;
      text-align: center;
      cursor: pointer;
      transition: all .2s;
      position: relative
    }

    .upload-zone:hover {
      border-color: var(--r);
      background: var(--rl)
    }

    .upload-zone input {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%
    }

    .upload-zone i {
      font-size: 1.8rem;
      color: #ccc;
      margin-bottom: .4rem;
      display: block
    }

    .upload-zone p {
      font-size: 12px;
      color: #aaa
    }

    .upload-zone .fname {
      font-size: 12px;
      color: var(--r);
      font-weight: 500;
      margin-top: .4rem
    }

    .modal-btns {
      display: flex;
      gap: 10px;
      margin-top: 1.5rem
    }

    .mbtn-cancel {
      flex: 1;
      padding: 11px;
      border-radius: 9px;
      background: var(--white);
      color: var(--gray);
      border: 1px solid #e0e0e0;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      font-family: inherit
    }

    .mbtn-ok {
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
      transition: background .2s
    }

    .mbtn-ok:hover {
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

    /* Empty */
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: #aaa
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: .75rem;
      display: block
    }

    @media(max-width:900px) {
      .kpi-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .tema-grid {
        grid-template-columns: 1fr
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
    <nav style="padding:1rem 0;flex:1">
      <div class="nav-label">Menu</div>
      <a href="admin_dashboard.php" class="nav-item"><i class='bx bxs-dashboard'></i> Dashboard</a>
      <a href="admin_dashboard.php?filter=baru" class="nav-item"><i class='bx bx-cart-add'></i> Order Baru <?php if ($baru > 0): ?><span style="background:var(--r);color:#fff;font-size:10px;padding:2px 7px;border-radius:20px;margin-left:auto"><?= $baru ?></span><?php endif ?></a>
      <a href="admin_dashboard.php?filter=bayar" class="nav-item"><i class='bx bx-credit-card'></i> Konfirmasi Bayar <?php if ($pending > 0): ?><span style="background:#e07820;color:#fff;font-size:10px;padding:2px 7px;border-radius:20px;margin-left:auto"><?= $pending ?></span><?php endif ?></a>
      <a href="admin_dashboard.php?filter=aktif" class="nav-item"><i class='bx bx-check-circle'></i> Undangan Aktif</a>
      <div class="nav-label">Lainnya</div>
      <a href="admin_tema.php" class="nav-item"><i class='bx bx-palette'></i> Manajemen Tema</a>
      <a href="admin_laporan.php" class="nav-item"><i class='bx bx-bar-chart'></i> Laporan & Analitik</a>
      <a href="logout.php" class="nav-item"><i class='bx bx-log-out'></i> Logout</a>
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
      <div class="topbar-title"><i class='bx bx-palette'></i> Manajemen Tema</div>
      <button class="btn-tambah" onclick="bukaModal()"><i class='bx bx-plus'></i> Tambah Tema Baru</button>
    </div>

    <div class="content">

      <!-- KPI -->
      <div class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-icon ki-red"><i class='bx bx-palette'></i></div>
          <div class="kpi-info">
            <div class="num"><?= $total_tema ?></div>
            <div class="lbl">Total Tema</div>
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon ki-green"><i class='bx bx-check-circle'></i></div>
          <div class="kpi-info">
            <div class="num"><?= $aktif_count ?></div>
            <div class="lbl">Tema Aktif</div>
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon ki-amber"><i class='bx bx-gift'></i></div>
          <div class="kpi-info">
            <div class="num"><?= $gratis_count ?></div>
            <div class="lbl">Tema Gratis</div>
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon ki-purple"><i class='bx bx-crown'></i></div>
          <div class="kpi-info">
            <div class="num"><?= $premium_count ?></div>
            <div class="lbl">Tema Premium</div>
          </div>
        </div>
      </div>

      <!-- TOP TEMA BANNER -->
      <?php if ($top_slug && $top_count > 0):
        $tc = $tema_colors[$top_slug] ?? $default_colors;
      ?>
        <div class="top-banner">
          <div class="tb-icon">🏆</div>
          <div class="tb-text">
            <h3>Tema Terpopuler</h3>
            <p><?= ucwords(str_replace('-', ' ', $top_slug)) ?> — paling banyak dipilih customer</p>
          </div>
          <div class="tb-num">
            <div class="n1"><?= $top_count ?> order</div>
            <div class="n2">Total dipilih</div>
          </div>
        </div>
      <?php endif ?>

      <!-- FILTER & SEARCH -->
      <div class="filter-row">
        <button class="ftab active" onclick="filterTema(this,'semua')">Semua (<?= $total_tema ?>)</button>
        <button class="ftab" onclick="filterTema(this,'aktif')">Aktif (<?= $aktif_count ?>)</button>
        <button class="ftab" onclick="filterTema(this,'nonaktif')">Nonaktif (<?= $total_tema - $aktif_count ?>)</button>
        <button class="ftab" onclick="filterTema(this,'gratis')">Gratis (<?= $gratis_count ?>)</button>
        <button class="ftab" onclick="filterTema(this,'premium')">Premium (<?= $premium_count ?>)</button>
        <div class="search-box">
          <i class='bx bx-search'></i>
          <input type="text" id="searchInput" placeholder="Cari tema..." oninput="cariTema(this.value)" />
        </div>
      </div>

      <!-- TEMA GRID -->
      <div class="tema-grid" id="temaGrid">
        <?php if (empty($tema_list)): ?>
          <div class="empty-state" style="grid-column:1/-1">
            <i class='bx bx-palette'></i>
            <p>Belum ada tema. Klik "Tambah Tema Baru" untuk mulai.</p>
          </div>
        <?php else: ?>
          <?php foreach ($tema_list as $t):
            $st  = $stats[$t['slug']] ?? ['total_order' => 0, 'aktif' => 0, 'revenue' => 0];
            $tc  = $tema_colors[$t['slug']] ?? $default_colors;
            $is_top = $t['slug'] === $top_slug && $top_count > 0;
            $inisial = strtoupper(implode('', array_map(fn($w) => $w[0], explode('-', $t['slug']))));
          ?>
            <div class="tema-card <?= $t['status'] === 'nonaktif' ? 'nonaktif' : '' ?>"
              data-status="<?= $t['status'] ?>"
              data-tipe="<?= $t['tipe'] ?>"
              data-nama="<?= strtolower($t['nama']) ?>">

              <!-- Preview -->
              <div class="tc-preview" style="background:linear-gradient(160deg,<?= $tc['from'] ?>,<?= $tc['to'] ?>)">
                <div class="tc-monogram"><?= $inisial ?></div>
                <div class="tc-name" style="color:<?= $tc['label_color'] ?>"><?= $t['nama'] ?></div>
                <div class="tc-sub" style="color:<?= $tc['label_color'] ?>">
                  <?= $t['tipe'] === 'premium' ? '✦ PREMIUM' : 'GRATIS' ?>
                </div>
                <div class="tc-badges">
                  <?php if ($t['tipe'] === 'premium'): ?><span class="tc-badge tcb-premium">💎 Premium</span><?php endif ?>
                  <?php if ($t['status'] === 'nonaktif'): ?><span class="tc-badge tcb-nonaktif">⚫ Nonaktif</span><?php endif ?>
                  <?php if ($is_top): ?><span class="tc-badge tcb-top">🏆 #1</span><?php endif ?>
                </div>
              </div>

              <div class="tc-body">
                <!-- Meta -->
                <div class="tc-meta">
                  <div>
                    <div class="tc-title"><?= htmlspecialchars($t['nama']) ?></div>
                    <?php if ($t['deskripsi']): ?>
                      <div class="tc-desc"><?= htmlspecialchars($t['deskripsi']) ?></div>
                    <?php endif ?>
                    <div class="tc-file"><i class='bx bx-code-alt'></i><?= $t['file_php'] ?></div>
                  </div>
                </div>

                <!-- Statistik pemakaian -->
                <div class="tc-stats">
                  <div class="tc-stat">
                    <div class="s-num"><?= $st['total_order'] ?></div>
                    <div class="s-lbl">Total Order</div>
                  </div>
                  <div class="tc-stat">
                    <div class="s-num"><?= $st['aktif'] ?></div>
                    <div class="s-lbl">Aktif Kini</div>
                  </div>
                  <div class="tc-stat">
                    <div class="s-num">Rp <?= $st['revenue'] >= 1000 ? number_format($st['revenue'] / 1000, 0, ',', '.') . 'K' : $st['revenue'] ?></div>
                    <div class="s-lbl">Revenue</div>
                  </div>
                </div>

                <!-- Toggle switches -->
                <div class="tc-toggles">
                  <!-- Toggle Status -->
                  <div class="toggle-item">
                    <label class="switch">
                      <input type="checkbox"
                        id="sw-status-<?= $t['id'] ?>"
                        <?= $t['status'] === 'aktif' ? 'checked' : '' ?>
                        onchange="toggleStatus(<?= $t['id'] ?>,this)" />
                      <span class="slider"></span>
                    </label>
                    <span id="lbl-status-<?= $t['id'] ?>"><?= $t['status'] === 'aktif' ? 'Aktif' : 'Nonaktif' ?></span>
                  </div>

                  <div class="toggle-divider"></div>

                  <!-- Toggle Tipe -->
                  <div class="toggle-item">
                    <label class="switch switch-purple">
                      <input type="checkbox"
                        id="sw-tipe-<?= $t['id'] ?>"
                        <?= $t['tipe'] === 'premium' ? 'checked' : '' ?>
                        onchange="toggleTipe(<?= $t['id'] ?>,this)" />
                      <span class="slider"></span>
                    </label>
                    <span id="lbl-tipe-<?= $t['id'] ?>"><?= $t['tipe'] === 'premium' ? 'Premium' : 'Gratis' ?></span>
                  </div>
                </div>

                <!-- Action buttons -->
                <div class="tc-actions">
                  <button class="act-btn act-edit" onclick='editTema(<?= json_encode($t) ?>)'>
                    <i class='bx bx-edit'></i> Edit
                  </button>
                  <a href="../undangan/<?= $t['file_php'] ?>?kode=" target="_blank" class="act-btn act-preview">
                    <i class='bx bx-show'></i> Preview
                  </a>
                  <button class="act-btn act-del" onclick="hapusTema(<?= $t['id'] ?>,'<?= addslashes($t['nama']) ?>')">
                    <i class='bx bx-trash'></i> Hapus
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach ?>
        <?php endif ?>
      </div>

    </div>
  </div>

  <!-- MODAL TAMBAH / EDIT TEMA -->
  <div class="modal-overlay" id="modalTema">
    <div class="modal-box">
      <div class="modal-title" id="modalTitle">Tambah Tema Baru</div>
      <div class="modal-sub">Isi informasi tema undangan yang akan ditambahkan</div>
      <input type="hidden" id="editId" value="0" />
      <input type="hidden" id="thumbnailExisting" value="" />

      <div class="mfield-row">
        <div class="mfield">
          <label>Nama Tema <span style="color:var(--r)">*</span></label>
          <input type="text" id="mNama" placeholder="cth. Merah Klasik" oninput="autoSlug()" />
        </div>
        <div class="mfield">
          <label>Slug (URL) <span style="color:var(--r)">*</span></label>
          <input type="text" id="mSlug" placeholder="cth. merah-klasik" />
        </div>
      </div>

      <div class="mfield">
        <label>Deskripsi</label>
        <textarea id="mDeskripsi" placeholder="Deskripsi singkat tema ini..."></textarea>
      </div>

      <div class="mfield-row">
        <div class="mfield">
          <label>File PHP <span style="color:var(--r)">*</span></label>
          <input type="text" id="mFile" placeholder="cth. merah-klasik.php" />
        </div>
        <div class="mfield">
          <label>Urutan Tampil</label>
          <input type="number" id="mUrutan" placeholder="cth. 1" min="1" />
        </div>
      </div>

      <div class="mfield-row">
        <div class="mfield">
          <label>Tipe</label>
          <select id="mTipe">
            <option value="gratis">🆓 Gratis</option>
            <option value="premium">💎 Premium</option>
          </select>
        </div>
        <div class="mfield">
          <label>Status</label>
          <select id="mStatus">
            <option value="aktif">✅ Aktif</option>
            <option value="nonaktif">⚫ Nonaktif</option>
          </select>
        </div>
      </div>

      <div class="mfield">
        <label>Thumbnail Preview <span style="color:#aaa;font-weight:400">(opsional, JPG/PNG maks 3MB)</span></label>
        <div class="upload-zone" id="uploadZone">
          <input type="file" id="mThumbnail" accept="image/*" onchange="previewThumb(this)" />
          <i class='bx bx-image-add'></i>
          <p>Klik untuk upload thumbnail tema</p>
          <div class="fname" id="thumbName"></div>
        </div>
        <img id="thumbPreview" src="" style="max-width:100%;border-radius:8px;margin-top:.5rem;display:none;max-height:120px;object-fit:cover" />
      </div>

      <div class="modal-btns">
        <button class="mbtn-cancel" onclick="tutupModal()">Batal</button>
        <button class="mbtn-ok" onclick="simpanTema()"><i class='bx bx-save'></i> Simpan Tema</button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"></div>

  <script>
    // ── Filter & Search ──────────────────────────
    function filterTema(el, filter) {
      document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
      el.classList.add('active');
      document.querySelectorAll('.tema-card').forEach(card => {
        const status = card.dataset.status;
        const tipe = card.dataset.tipe;
        let show = filter === 'semua' ||
          (filter === 'aktif' && status === 'aktif') ||
          (filter === 'nonaktif' && status === 'nonaktif') ||
          (filter === 'gratis' && tipe === 'gratis') ||
          (filter === 'premium' && tipe === 'premium');
        card.style.display = show ? '' : 'none';
      });
    }

    function cariTema(q) {
      q = q.toLowerCase();
      document.querySelectorAll('.tema-card').forEach(card => {
        const nama = card.dataset.nama || '';
        card.style.display = (!q || nama.includes(q)) ? '' : 'none';
      });
    }

    // ── Toggle Status ────────────────────────────
    function toggleStatus(id, el) {
      fetch('tema.php', {
          method: 'POST',
          body: new URLSearchParams({
            action: 'toggle_status',
            id
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') {
            const lbl = document.getElementById('lbl-status-' + id);
            const card = el.closest('.tema-card');
            if (data.new_status === 'aktif') {
              lbl.textContent = 'Aktif';
              card.classList.remove('nonaktif');
              card.dataset.status = 'aktif';
            } else {
              lbl.textContent = 'Nonaktif';
              card.classList.add('nonaktif');
              card.dataset.status = 'nonaktif';
            }
            showToast('Status tema diubah ke ' + data.new_status, 'success');
          }
        });
    }

    // ── Toggle Tipe ──────────────────────────────
    function toggleTipe(id, el) {
      fetch('tema.php', {
          method: 'POST',
          body: new URLSearchParams({
            action: 'toggle_tipe',
            id
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') {
            const lbl = document.getElementById('lbl-tipe-' + id);
            const card = el.closest('.tema-card');
            lbl.textContent = data.new_tipe === 'premium' ? 'Premium' : 'Gratis';
            card.dataset.tipe = data.new_tipe;
            showToast('Tipe tema diubah ke ' + data.new_tipe, 'success');
          }
        });
    }

    // ── Modal ────────────────────────────────────
    function bukaModal() {
      document.getElementById('modalTitle').textContent = 'Tambah Tema Baru';
      document.getElementById('editId').value = '0';
      document.getElementById('mNama').value = '';
      document.getElementById('mSlug').value = '';
      document.getElementById('mDeskripsi').value = '';
      document.getElementById('mFile').value = '';
      document.getElementById('mUrutan').value = '';
      document.getElementById('mTipe').value = 'gratis';
      document.getElementById('mStatus').value = 'aktif';
      document.getElementById('thumbPreview').style.display = 'none';
      document.getElementById('thumbName').textContent = '';
      document.getElementById('thumbnailExisting').value = '';
      document.getElementById('modalTema').classList.add('show');
    }

    function editTema(t) {
      document.getElementById('modalTitle').textContent = 'Edit Tema';
      document.getElementById('editId').value = t.id;
      document.getElementById('mNama').value = t.nama;
      document.getElementById('mSlug').value = t.slug;
      document.getElementById('mDeskripsi').value = t.deskripsi || '';
      document.getElementById('mFile').value = t.file_php;
      document.getElementById('mUrutan').value = t.urutan;
      document.getElementById('mTipe').value = t.tipe;
      document.getElementById('mStatus').value = t.status;
      document.getElementById('thumbnailExisting').value = t.thumbnail || '';
      if (t.thumbnail) {
        const img = document.getElementById('thumbPreview');
        img.src = '../' + t.thumbnail;
        img.style.display = 'block';
      }
      document.getElementById('modalTema').classList.add('show');
    }

    function tutupModal() {
      document.getElementById('modalTema').classList.remove('show');
    }

    document.getElementById('modalTema').addEventListener('click', function(e) {
      if (e.target === this) tutupModal();
    });

    // Auto slug dari nama
    function autoSlug() {
      if (document.getElementById('editId').value !== '0') return;
      const nama = document.getElementById('mNama').value;
      const slug = nama.toLowerCase()
        .replace(/[^a-z0-9\s\-]/g, '')
        .trim().replace(/\s+/g, '-');
      document.getElementById('mSlug').value = slug;
      document.getElementById('mFile').value = slug + '.php';
    }

    // Preview thumbnail
    function previewThumb(input) {
      const file = input.files[0];
      if (!file) return;
      document.getElementById('thumbName').textContent = '✅ ' + file.name;
      const reader = new FileReader();
      reader.onload = e => {
        const img = document.getElementById('thumbPreview');
        img.src = e.target.result;
        img.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }

    // Simpan tema
    function simpanTema() {
      const nama = document.getElementById('mNama').value.trim();
      const slug = document.getElementById('mSlug').value.trim();
      const file = document.getElementById('mFile').value.trim();
      if (!nama || !slug || !file) {
        showToast('Nama, slug, dan file PHP wajib diisi!', 'error');
        return;
      }
      const fd = new FormData();
      fd.append('action', 'simpan');
      fd.append('edit_id', document.getElementById('editId').value);
      fd.append('nama', nama);
      fd.append('slug', slug);
      fd.append('deskripsi', document.getElementById('mDeskripsi').value);
      fd.append('file_php', file);
      fd.append('urutan', document.getElementById('mUrutan').value);
      fd.append('tipe', document.getElementById('mTipe').value);
      fd.append('status', document.getElementById('mStatus').value);
      fd.append('thumbnail_existing', document.getElementById('thumbnailExisting').value);
      const thumb = document.getElementById('mThumbnail').files[0];
      if (thumb) fd.append('thumbnail', thumb);

      fetch('admin_tema.php', {
          method: 'POST',
          body: fd
        })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') {
            showToast(data.message, 'success');
            tutupModal();
            setTimeout(() => location.reload(), 1200);
          } else {
            showToast(data.message || 'Gagal menyimpan!', 'error');
          }
        });
    }

    // Hapus tema
    function hapusTema(id, nama) {
      if (!confirm(`Yakin hapus tema "${nama}"?\nTema yang sudah dipakai order tidak bisa dihapus.`)) return;
      fetch('admin_tema.php', {
          method: 'POST',
          body: new URLSearchParams({
            action: 'hapus',
            id
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
          } else {
            showToast(data.message, 'error');
          }
        });
    }

    // Toast
    function showToast(msg, type = '') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className = 'toast show ' + (type || '');
      setTimeout(() => t.className = 'toast', 2800);
    }
  </script>
</body>

</html>