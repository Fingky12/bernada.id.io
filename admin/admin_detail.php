<?php
// FILE: admin/detail.php
session_start();
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) {
  header('Location: admin_login.php');
  exit;
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$o = $stmt->fetch();
if (!$o) {
  header('Location: admin_dashboard.php');
  exit;
}

$tgl_obj  = new DateTime($o['tanggal_nikah']);
$bulan_id = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$hari_id  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$tgl_fmt  = $hari_id[(int)$tgl_obj->format('w')] . ', ' . $tgl_obj->format('j') . ' ' . $bulan_id[(int)$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y');
$tema_label = ucwords(str_replace('-', ' ', $o['tema']));
$base_url   = 'http://localhost/WebDev';
$link_undangan = $o['kode_undangan'] ? "{$base_url}/undangan/undangan_index.php?kode={$o['kode_undangan']}&to=" . urlencode($o['nama_pria']) : null;


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
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Order <?= $o['kode_order'] ?> – Admin Bernada.ID</title>
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

    .nav-item:hover {
      color: #fff;
      background: rgba(255, 255, 255, .06)
    }

    .nav-item i {
      font-size: 18px
    }

    .main {
      margin-left: 220px;
      padding: 2rem
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
      z-index: 50;
      margin: -2rem -2rem 2rem
    }

    .topbar-title {
      font-size: 16px;
      font-weight: 600
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--gray);
      text-decoration: none;
      font-size: 13px;
      padding: 7px 14px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--white);
      transition: all .2s
    }

    .back-btn:hover {
      color: var(--r);
      border-color: var(--r)
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem
    }

    .card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.75rem;
      margin-bottom: 1.25rem
    }

    .card-title {
      font-size: 13px;
      font-weight: 600;
      color: var(--r);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 1.25rem;
      padding-bottom: .75rem;
      border-bottom: 1px solid var(--rl);
      display: flex;
      align-items: center;
      gap: 8px
    }

    .info-row {
      display: flex;
      gap: 10px;
      font-size: 14px;
      margin-bottom: .75rem;
      align-items: flex-start
    }

    .info-row .lbl {
      min-width: 140px;
      color: var(--gray);
      font-size: 13px;
      flex-shrink: 0
    }

    .info-row .val {
      font-weight: 500;
      color: var(--dark);
      line-height: 1.5
    }

    .badge {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
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
      background: #fff3e0;
      color: #e07820
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

    /* LINK BOX */
    .link-section {
      background: linear-gradient(135deg, #e8f7f0, #d4f0e0);
      border: 1px solid #5cb88a;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem
    }

    .link-section h3 {
      font-size: 14px;
      font-weight: 600;
      color: #1a6640;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .link-url-box {
      background: #fff;
      border: 1px solid #a8e0c0;
      border-radius: 8px;
      padding: 10px 14px;
      font-family: monospace;
      font-size: 13px;
      color: var(--r);
      word-break: break-all;
      margin-bottom: .75rem
    }

    .link-btns {
      display: flex;
      gap: 8px;
      flex-wrap: wrap
    }

    .lbtn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: all .2s;
      font-family: inherit
    }

    .lbtn-open {
      background: #2e9e5b;
      color: #fff
    }

    .lbtn-open:hover {
      background: #1a6640
    }

    .lbtn-copy {
      background: var(--white);
      color: #2e9e5b;
      border: 1px solid #5cb88a
    }

    .lbtn-copy:hover {
      background: #e8f7f0
    }

    .lbtn-wa {
      background: #25D366;
      color: #fff
    }

    .lbtn-wa:hover {
      background: #1da851
    }

    /* PENDING BOX */
    .pending-section {
      background: #fff8e0;
      border: 1px solid #ffd080;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem
    }

    .pending-section h3 {
      font-size: 14px;
      font-weight: 600;
      color: #7a4f00;
      margin-bottom: .5rem;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .pending-section p {
      font-size: 13px;
      color: #8b6000;
      line-height: 1.7
    }

    .approve-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 1rem;
      padding: 10px 22px;
      background: var(--r);
      color: #fff;
      border-radius: 9px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 600;
      transition: background .2s
    }

    .approve-btn:hover {
      background: var(--rd)
    }

    .bukti-img {
      max-width: 100%;
      border-radius: 8px;
      border: 1px solid var(--border);
      margin-top: .5rem;
      max-height: 300px;
      object-fit: contain
    }

    .timeline {
      margin-top: .5rem
    }

    .tl-item {
      display: flex;
      gap: 12px;
      margin-bottom: .75rem;
      font-size: 13px
    }

    .tl-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--r);
      margin-top: 5px;
      flex-shrink: 0
    }

    .tl-item .time {
      color: #aaa;
      min-width: 140px;
      flex-shrink: 0
    }

    .tl-item .evt {
      color: var(--dark)
    }

    @media(max-width:900px) {
      .grid {
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
      <a href="admin_tema.php" class="nav-item active"><i class='bx bx-palette'></i> Manajemen Tema</a>
      <a href="laporan.php" class="nav-item"><i class='bx bx-bar-chart'></i> Laporan & Analitik</a>
      <a href="admin_logout.php" class="nav-item"><i class='bx bx-log-out'></i> Logout</a>
    </nav>
  </div>

  <div class="main">
    <div class="topbar">
      <div class="topbar-title">Detail Order — <?= $o['kode_order'] ?></div>
      <a href="admin_dashboard.php" class="back-btn"><i class='bx bx-arrow-back'></i> Kembali</a>
    </div>

    <!-- ── LINK UNDANGAN (jika aktif) ── -->
    <?php if ($o['status_order'] === 'aktif' && $o['kode_undangan']): ?>
      <div class="link-section">
        <h3><i class='bx bxs-check-circle' style="font-size:18px"></i> Undangan Aktif — Link Siap Dibagikan!</h3>
        <div style="font-size:13px;color:#2e6640;margin-bottom:.75rem">
          Kode Undangan: <strong style="font-family:monospace;color:var(--r)"><?= $o['kode_undangan'] ?></strong>
          &nbsp;|&nbsp; Aktif hingga: <strong><?= $o['tgl_expired'] ? date('d M Y', strtotime($o['tgl_expired'])) : '-' ?></strong>
        </div>
        <div class="link-url-box" id="linkUndangan"><?= $link_undangan ?></div>
        <div class="link-btns">
          <a href="<?= $link_undangan ?>" target="_blank" class="lbtn lbtn-open"><i class='bx bx-link-external'></i> Buka Undangan</a>
          <button class="lbtn lbtn-copy" onclick="salinLink()"><i class='bx bx-copy'></i> Salin Link</button>
          <a href="https://wa.me/<?= $o['no_whatsapp'] ?>?text=<?= urlencode("Halo {$o['nama_pria']}! Ini link undangan digitalmu dari Bernada.ID 🎉\n\n{$link_undangan}\n\nBagikan ke tamu dengan mengganti bagian akhir:\n{$base_url}/undangan/undangan_index.php?kode={$o['kode_undangan']}&to=Nama+Tamu") ?>" target="_blank" class="lbtn lbtn-wa"><i class='bx bxl-whatsapp'></i> Kirim ke Customer</a>
        </div>
        <div style="margin-top:1rem;padding:10px 14px;background:rgba(255,255,255,.7);border-radius:8px;font-size:12px;color:#2e6640">
          💡 <strong>Cara kirim ke tamu:</strong><br>
          Ganti bagian <code>&to=<?= urlencode($o['nama_pria']) ?></code> dengan nama tamu masing-masing.<br>
          Contoh: <code><?= $base_url ?>/undangan/undangan_index.php?kode=<?= $o['kode_undangan'] ?>&to=Bapak+Hendra</code>
        </div>
      </div>
    <?php elseif ($o['status_bayar'] === 'menunggu_konfirmasi' || $o['status_bayar'] === 'pending'): ?>
      <div class="pending-section">
        <h3><i class='bx bx-time' style="font-size:18px"></i> Menunggu Konfirmasi Pembayaran</h3>
        <p>Order ini belum diaktifkan karena pembayaran belum dikonfirmasi. Setelah kamu klik "Approve & Aktifkan", link undangan akan otomatis dikirim ke WhatsApp customer.</p>
        <a href="konfirmasi_bayar.php?id=<?= $o['id'] ?>" class="approve-btn"><i class='bx bx-check'></i> Approve & Aktifkan Undangan</a>
      </div>
    <?php endif ?>

    <div class="grid">
      <!-- Kiri -->
      <div>
        <div class="card">
          <div class="card-title"><i class='bx bxs-book-heart'></i> Data Pengantin</div>
          <div class="info-row"><span class="lbl">Pengantin Pria</span><span class="val"><?= $o['nama_pria'] ?></span></div>
          <div class="info-row"><span class="lbl">Pengantin Wanita</span><span class="val"><?= $o['nama_wanita'] ?></span></div>
          <?php if ($o['ayah_pria']): ?><div class="info-row"><span class="lbl">Ayah Pria</span><span class="val"><?= $o['ayah_pria'] ?></span></div><?php endif ?>
          <?php if ($o['ibu_pria']): ?><div class="info-row"><span class="lbl">Ibu Pria</span><span class="val"><?= $o['ibu_pria'] ?></span></div><?php endif ?>
          <?php if ($o['ayah_wanita']): ?><div class="info-row"><span class="lbl">Ayah Wanita</span><span class="val"><?= $o['ayah_wanita'] ?></span></div><?php endif ?>
          <?php if ($o['ibu_wanita']): ?><div class="info-row"><span class="lbl">Ibu Wanita</span><span class="val"><?= $o['ibu_wanita'] ?></span></div><?php endif ?>
        </div>

        <div class="card">
          <div class="card-title"><i class='bx bx-calendar-heart'></i> Info Acara</div>
          <div class="info-row"><span class="lbl">Tanggal Nikah</span><span class="val"><?= $tgl_fmt ?></span></div>
          <div class="info-row"><span class="lbl">Waktu Akad</span><span class="val"><?= $o['mulai_akad'] ?> – <?= $o['selesai_akad'] ?> WIB</span></div>
          <div class="info-row"><span class="lbl">Waktu Resepsi</span><span class="val"><?= $o['mulai_resepsi'] ?> – <?= $o['selesai_resepsi'] ?> WIB</span></div>
          <div class="info-row"><span class="lbl">Lokasi</span><span class="val"><?= $o['lokasi'] ?></span></div>
          <?php if ($o['link_maps']): ?>
            <div class="info-row"><span class="lbl">Google Maps</span><span class="val"><a href="<?= $o['link_maps'] ?>" target="_blank" style="color:var(--r)">Buka Maps</a></span></div>
          <?php endif ?>
          <?php if ($o['catatan']): ?>
            <div class="info-row"><span class="lbl">Catatan</span><span class="val"><?= $o['catatan'] ?></span></div>
          <?php endif ?>
        </div>
      </div>

      <!-- Kanan -->
      <div>
        <div class="card">
          <div class="card-title"><i class='bx bx-purchase-tag'></i> Paket & Pembayaran</div>
          <div class="info-row"><span class="lbl">Paket</span><span class="val"><span class="badge badge-<?= $o['paket'] ?>"><?= strtoupper($o['paket']) ?></span></span></div>
          <div class="info-row"><span class="lbl">Tema</span><span class="val"><?= $tema_label ?></span></div>
          <div class="info-row"><span class="lbl">Total Harga</span><span class="val" style="font-weight:700;color:var(--r)">Rp <?= number_format($o['harga'], 0, ',', '.') ?></span></div>
          <div class="info-row"><span class="lbl">Metode Bayar</span><span class="val"><?= $o['metode_bayar'] ?: '-' ?></span></div>
          <div class="info-row"><span class="lbl">Status Bayar</span><span class="val">
              <?php $sb = $o['status_bayar'];
              $sc = $sb === 'lunas' ? 'lunas' : ($sb === 'menunggu_konfirmasi' ? 'konfirmasi' : 'pending'); ?>
              <span class="badge badge-<?= $sc ?>"><?= ucfirst(str_replace('_', ' ', $sb)) ?></span>
            </span></div>
          <div class="info-row"><span class="lbl">Status Order</span><span class="val"><span class="badge badge-<?= $o['status_order'] ?>"><?= ucfirst($o['status_order']) ?></span></span></div>
          <?php if ($o['tgl_aktif']): ?>
            <div class="info-row"><span class="lbl">Tanggal Aktif</span><span class="val"><?= date('d M Y H:i', strtotime($o['tgl_aktif'])) ?></span></div>
            <div class="info-row"><span class="lbl">Expired</span><span class="val"><?= date('d M Y', strtotime($o['tgl_expired'])) ?></span></div>
          <?php endif ?>
        </div>

        <div class="card">
          <div class="card-title"><i class='bx bxl-whatsapp'></i> Kontak Customer</div>
          <div class="info-row"><span class="lbl">WhatsApp</span><span class="val">
              <a href="https://wa.me/<?= $o['no_whatsapp'] ?>" target="_blank" style="color:var(--r);font-weight:600"><?= $o['no_whatsapp'] ?></a>
            </span></div>
          <?php if ($o['email']): ?>
            <div class="info-row"><span class="lbl">Email</span><span class="val"><?= $o['email'] ?></span></div>
          <?php endif ?>
          <div class="info-row"><span class="lbl">Kode Order</span><span class="val" style="font-family:monospace;color:var(--r);font-weight:700"><?= $o['kode_order'] ?></span></div>
          <div class="info-row"><span class="lbl">Order Masuk</span><span class="val"><?= date('d M Y H:i', strtotime($o['created_at'])) ?></span></div>
          <div class="info-row"><span class="lbl">WA Order</span><span class="val"><span class="badge badge-<?= $o['notif_order_wa'] === 'terkirim' ? 'aktif' : 'baru' ?>"><?= $o['notif_order_wa'] ?></span></span></div>
          <div class="info-row"><span class="lbl">WA Aktif</span><span class="val"><span class="badge badge-<?= $o['notif_aktif_wa'] === 'terkirim' ? 'aktif' : 'baru' ?>"><?= $o['notif_aktif_wa'] ?></span></span></div>
        </div>

        <?php if ($o['bukti_bayar']): ?>
          <div class="card">
            <div class="card-title"><i class='bx bx-receipt'></i> Bukti Transfer</div>
            <?php if (str_ends_with(strtolower($o['bukti_bayar']), '.pdf')): ?>
              <a href="../<?= $o['bukti_bayar'] ?>" target="_blank" style="color:var(--r);font-size:14px"><i class='bx bxs-file-pdf' ></i> Buka PDF Bukti Transfer</a>
            <?php else: ?>
              <img src="../<?= $o['bukti_bayar'] ?>" class="bukti-img" alt="Bukti Transfer" />
            <?php endif ?>
          </div>
        <?php endif ?>
      </div>
    </div>

  </div>

  <script>
    function salinLink() {
      const link = document.getElementById('linkUndangan').textContent.trim();
      navigator.clipboard.writeText(link).then(() => {
        const btn = document.querySelector('.lbtn-copy');
        const ori = btn.innerHTML;
        btn.innerHTML = "<i class='bx bx-check'></i> Tersalin!";
        btn.style.background = '#e8f7f0';
        setTimeout(() => {
          btn.innerHTML = ori;
          btn.style.background = '';
        }, 2500);
      });
    }
  </script>
</body>

</html>