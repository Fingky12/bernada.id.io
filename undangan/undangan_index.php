<?php
// ============================================
// FILE: undangan/index.php
// Router otomatis — arahkan ke tema yang dipilih
// URL: /undangan/?kode=BRN-XXXXXX&to=Nama+Tamu
// ============================================

require_once '../config/koneksi.php';

$kode = trim($_GET['kode'] ?? '');
$tamu = trim($_GET['to']   ?? 'Tamu Undangan');

// Validasi kode
if (!$kode) {
  http_response_code(404);
  die(renderError('Kode undangan tidak ditemukan.', 'Pastikan link undangan yang kamu gunakan sudah benar.'));
}

// Ambil data order berdasarkan kode undangan
$stmt = $pdo->prepare("SELECT * FROM orders WHERE kode_undangan = ? AND status_order = 'aktif'");
$stmt->execute([$kode]);
$order = $stmt->fetch();

if (!$order) {
  // Cek apakah kode ada tapi belum aktif
  $cek = $pdo->prepare("SELECT status_order, nama_pria, nama_wanita FROM orders WHERE kode_undangan = ?");
  $cek->execute([$kode]);
  $cek_data = $cek->fetch();

  if ($cek_data) {
    $status = $cek_data['status_order'];
    $nama   = $cek_data['nama_pria'] . ' & ' . $cek_data['nama_wanita'];
    if ($status === 'diproses') {
      die(renderError('Undangan Sedang Diproses', "Undangan {$nama} sedang dalam proses pembuatan. Silakan tunggu notifikasi WhatsApp dari kami."));
    } elseif ($status === 'baru') {
      die(renderError('Menunggu Pembayaran', "Undangan {$nama} menunggu konfirmasi pembayaran. Segera setelah pembayaran dikonfirmasi, undangan akan aktif."));
    } else {
      die(renderError('Undangan Tidak Aktif', "Undangan ini sudah tidak aktif atau telah dibatalkan."));
    }
  }
  http_response_code(404);
  die(renderError('Undangan Tidak Ditemukan', 'Kode undangan tidak valid atau sudah expired.'));
}

// Cek masa aktif
if ($order['tgl_expired'] && strtotime($order['tgl_expired']) < time()) {
  die(renderError('Undangan Sudah Expired', "Masa aktif undangan {$order['nama_pria']} & {$order['nama_wanita']} telah berakhir pada " . date('d M Y', strtotime($order['tgl_expired'])) . "."));
}

// Daftar tema yang tersedia
$tema_list = [
  'merah-klasik' => 'merah-klasik.php',
  'navy-elegant' => 'navy-elegant.php',
  'blush-pink'   => 'blush-pink.php',
  'sage-garden'  => 'sage-garden.php',
  'rustic-brown' => 'rustic-brown.php',
];

$tema  = $order['tema'];
$file  = $tema_list[$tema] ?? 'merah-klasik.php';
$path  = __DIR__ . '/' . $file;

if (!file_exists($path)) {
  die(renderError('Tema Tidak Ditemukan', "Tema '{$tema}' tidak tersedia. Hubungi admin Bernada.ID."));
}

// Inject data order ke $_GET supaya tema PHP bisa baca
$_GET['kode'] = $kode;
$_GET['to']   = $tamu;

// Catat view (opsional, untuk analitik)
// $pdo->prepare("UPDATE orders SET views = views + 1 WHERE kode_undangan = ?")->execute([$kode]);

// Include file tema
include $path;
exit;


// ── Render halaman error ──────────────────────
function renderError($title, $msg)
{
  return '<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>' . $title . ' – Bernada.ID</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:"Plus Jakarta Sans",sans-serif;background:#f8f5f5;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
    .box{background:#fff;border-radius:16px;padding:3rem 2rem;text-align:center;max-width:440px;width:100%;border:1px solid #eedede;box-shadow:0 8px 32px rgba(192,57,59,.08)}
    .icon{font-size:3.5rem;margin-bottom:1.25rem}
    h1{font-family:"Playfair Display",serif;font-size:1.6rem;color:#1a1a1a;margin-bottom:.75rem}
    p{font-size:14px;color:#666;line-height:1.8;margin-bottom:1.5rem}
    a{display:inline-flex;align-items:center;gap:6px;padding:11px 24px;background:#C0393B;color:#fff;border-radius:50px;text-decoration:none;font-size:13px;font-weight:600;transition:background .2s}
    a:hover{background:#8a2020}
    .brand{margin-top:2rem;font-size:12px;color:#aaa}
    .brand span{color:#C0393B;font-weight:600}
  </style>
</head>
<body>
  <div class="box">
    <div class="icon">🔒</div>
    <h1>' . $title . '</h1>
    <p>' . $msg . '</p>
    <a href="/">← Kembali ke Beranda</a>
    <div class="brand">Bernada<span>.ID</span></div>
  </div>
</body>
</html>';
}
