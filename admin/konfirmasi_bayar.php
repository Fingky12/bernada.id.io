<?php
// ============================================
// FILE: admin/konfirmasi_bayar.php
// Admin approve bayar → aktifkan undangan → kirim WA link
// ============================================
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
  header('Location: login.php');
  exit;
}

$id    = (int)($_GET['id'] ?? 0);
$order = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$order->execute([$id]);
$o = $order->fetch();

if (!$o) {
  header('Location: index.php');
  exit;
}

$success = '';
$error   = '';

// ── Proses konfirmasi ───────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])) {
  try {
    // Generate kode undangan jika belum ada
    $kode_undangan = $o['kode_undangan'];
    if (!$kode_undangan) {
      do {
        $kode_undangan = 'BRN-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        $cek = $pdo->prepare("SELECT id FROM orders WHERE kode_undangan = ?");
        $cek->execute([$kode_undangan]);
      } while ($cek->rowCount() > 0);
    }

    // Hitung masa aktif
    $paket_data = $pdo->prepare("SELECT aktif FROM paket_harga WHERE paket = ?");
    $paket_data->execute([$o['paket']]);
    $aktif_hari = $paket_data->fetchColumn() ?: 30;

    $tgl_aktif   = date('Y-m-d H:i:s');
    $tgl_expired = date('Y-m-d H:i:s', strtotime("+{$aktif_hari} days"));

    // Update database
    $pdo->prepare("
            UPDATE orders SET
                kode_undangan = ?,
                status_bayar  = 'lunas',
                status_order  = 'aktif',
                tgl_aktif     = ?,
                tgl_expired   = ?,
                tgl_bayar     = NOW(),
                metode_bayar  = ?
            WHERE id = ?
        ")->execute([$kode_undangan, $tgl_aktif, $tgl_expired, $_POST['metode_bayar'] ?? '', $id]);

    // Format tanggal
    $tgl_obj  = new DateTime($o['tanggal_nikah']);
    $bulan_id = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $hari_id  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $tgl_fmt  = $hari_id[(int)$tgl_obj->format('w')] . ', ' . $tgl_obj->format('j') . ' ' . $bulan_id[(int)$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y');

    $tema_label  = ucwords(str_replace('-', ' ', $o['tema']));
    $nama_paket  = strtoupper($o['paket']);
    $expired_fmt = date('d M Y', strtotime($tgl_expired));

    // URL undangan
    $base_url = rtrim($_POST['base_url'] ?? 'http://localhost/WebDev', '/');
    $link     = "{$base_url}/undangan/?kode={$kode_undangan}&to=" . urlencode($o['nama_pria']);
    $link_custom = "{$base_url}/undangan/?kode={$kode_undangan}&to=Nama+Tamu";

    // Kirim WA ke customer
    $pesan_wa = "🎉 *Selamat, {$o['nama_pria']}!*\n\n"
      . "Pembayaran kamu telah *TERKONFIRMASI* dan undangan digitalmu sudah *AKTIF*! ✅\n\n"
      . "📋 *Detail Undangan:*\n"
      . "━━━━━━━━━━━━━━━━\n"
      . "🔖 Kode Undangan : {$kode_undangan}\n"
      . "👰 Pengantin     : {$o['nama_pria']} & {$o['nama_wanita']}\n"
      . "📅 Tanggal       : {$tgl_fmt}\n"
      . "📍 Lokasi        : {$o['lokasi']}\n"
      . "🎨 Tema          : {$tema_label}\n"
      . "💎 Paket         : {$nama_paket}\n"
      . "⏰ Aktif hingga  : {$expired_fmt}\n"
      . "━━━━━━━━━━━━━━━━\n\n"
      . "🔗 *Link Undangan Kamu:*\n"
      . "{$link}\n\n"
      . "📤 *Cara kirim ke tamu:*\n"
      . "Ganti bagian akhir link dengan nama tamu:\n"
      . "`{$link_custom}`\n\n"
      . "Contoh:\n"
      . "• {$base_url}/undangan/?kode={$kode_undangan}&to=Bapak+Hendra\n"
      . "• {$base_url}/undangan/?kode={$kode_undangan}&to=Keluarga+Budi\n\n"
      . "Jika ada pertanyaan, balas pesan ini ya! 💚\n"
      . "Tim Bernada.ID";

    $wa_status = kirimWA($o['no_whatsapp'], $pesan_wa) ? 'terkirim' : 'gagal';
    $pdo->prepare("UPDATE orders SET notif_aktif_wa = ? WHERE id = ?")
      ->execute([$wa_status, $id]);

    $success = "Order berhasil diaktifkan! Kode undangan: <strong>{$kode_undangan}</strong>. WA notifikasi: <strong>{$wa_status}</strong>.";

    // Reload data
    $order->execute([$id]);
    $o = $order->fetch();
  } catch (Exception $e) {
    $error = 'Gagal: ' . $e->getMessage();
  }
}

$tema_label = ucwords(str_replace('-', ' ', $o['tema']));
$tgl_fmt = date('d M Y', strtotime($o['tanggal_nikah']));

function kirimWA($nomor, $pesan)
{
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL            => 'https://api.fonnte.com/send',
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => ['target' => $nomor, 'message' => $pesan, 'countryCode' => '62'],
    CURLOPT_HTTPHEADER     => ['Authorization: ' . FONNTE_TOKEN],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => false,
  ]);
  $res = curl_exec($ch);
  $err = curl_errno($ch);
  curl_close($ch);
  if ($err) return false;
  $d = json_decode($res, true);
  return isset($d['status']) && $d['status'] === true;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Konfirmasi Bayar – Bernada.ID Admin</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
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
      color: var(--dark);
      padding: 2rem
    }

    .wrap {
      max-width: 680px;
      margin: 0 auto
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--gray);
      text-decoration: none;
      font-size: 13px;
      margin-bottom: 1.5rem
    }

    .back-btn:hover {
      color: var(--r)
    }

    .card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 2rem;
      margin-bottom: 1.25rem
    }

    .card-title {
      font-size: 14px;
      font-weight: 600;
      color: var(--r);
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: 1.25rem;
      padding-bottom: .75rem;
      border-bottom: 1px solid var(--rl)
    }

    .info-row {
      display: flex;
      gap: 8px;
      font-size: 14px;
      margin-bottom: .6rem
    }

    .info-row strong {
      min-width: 130px;
      color: var(--dark);
      font-weight: 600
    }

    .info-row span {
      color: var(--gray)
    }

    .alert {
      padding: 13px 16px;
      border-radius: 9px;
      font-size: 14px;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 10px
    }

    .alert-success {
      background: #eaf7f0;
      color: #1a6640;
      border: 1px solid #5cb88a
    }

    .alert-error {
      background: #fdeaea;
      color: #a32d2d;
      border: 1px solid #f5c1c1
    }

    .badge {
      display: inline-block;
      font-size: 11px;
      padding: 3px 10px;
      border-radius: 20px;
      font-weight: 600
    }

    .badge-lunas {
      background: #e8f7f0;
      color: #2e9e5b
    }

    .badge-konfirmasi {
      background: #fff3e0;
      color: #e07820
    }

    .badge-pending {
      background: #f5f5f5;
      color: #888
    }

    .badge-aktif {
      background: #e8f7f0;
      color: #2e9e5b
    }

    .badge-baru {
      background: #e8f0ff;
      color: #2d7dd2
    }

    label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #444;
      margin-bottom: 5px
    }

    input,
    select {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      color: var(--dark);
      background: #fafafa;
      outline: none;
      margin-bottom: 1rem
    }

    input:focus,
    select:focus {
      border-color: var(--r);
      box-shadow: 0 0 0 3px rgba(192, 57, 59, .08)
    }

    .btn-approve {
      width: 100%;
      padding: 13px;
      background: var(--r);
      color: #fff;
      border: none;
      border-radius: 9px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      transition: background .2s
    }

    .btn-approve:hover {
      background: #8a2020
    }

    .link-box {
      background: #f0f7ff;
      border: 1px solid #b8d4f8;
      border-radius: 9px;
      padding: 1rem 1.25rem;
      margin-top: 1rem
    }

    .link-box p {
      font-size: 13px;
      color: #1a4a7a;
      margin-bottom: .5rem;
      font-weight: 500
    }

    .link-url {
      font-family: monospace;
      font-size: 13px;
      color: var(--r);
      word-break: break-all;
      background: #fff;
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #dde8f8
    }

    .bukti-img {
      max-width: 100%;
      border-radius: 8px;
      border: 1px solid var(--border);
      margin-top: .5rem
    }
  </style>
</head>

<body>
  <div class="wrap">
    <a href="admin_dashboard.php" class="back-btn"><i class='bx bx-arrow-back'></i> Kembali ke Dashboard</a>

    <?php if ($success): ?>
      <div class="alert alert-success"><i class='bx bxs-check-circle'></i>
        <div><?= $success ?></div>
      </div>
    <?php endif ?>
    <?php if ($error): ?>
      <div class="alert alert-error"><i class='bx bxs-error-circle'></i>
        <div><?= $error ?></div>
      </div>
    <?php endif ?>

    <!-- Detail Order -->
    <div class="card">
      <div class="card-title">📋 Detail Order</div>
      <div class="info-row"><strong>Kode Order</strong><span style="font-family:monospace;color:var(--r);font-weight:700"><?= $o['kode_order'] ?></span></div>
      <div class="info-row"><strong>Pengantin</strong><span><?= $o['nama_pria'] ?> & <?= $o['nama_wanita'] ?></span></div>
      <div class="info-row"><strong>Tanggal Nikah</strong><span><?= $tgl_fmt ?></span></div>
      <div class="info-row"><strong>Lokasi</strong><span><?= $o['lokasi'] ?></span></div>
      <div class="info-row"><strong>Tema</strong><span><?= $tema_label ?></span></div>
      <div class="info-row"><strong>Paket</strong><span><?= strtoupper($o['paket']) ?> — Rp <?= number_format($o['harga'], 0, ',', '.') ?></span></div>
      <div class="info-row"><strong>WhatsApp</strong><span><?= $o['no_whatsapp'] ?></span></div>
      <div class="info-row"><strong>Status Bayar</strong><span><span class="badge badge-<?= $o['status_bayar'] === 'lunas' ? 'lunas' : ($o['status_bayar'] === 'menunggu_konfirmasi' ? 'konfirmasi' : 'pending') ?>"><?= ucfirst(str_replace('_', ' ', $o['status_bayar'])) ?></span></span></div>
      <div class="info-row"><strong>Status Order</strong><span><span class="badge badge-<?= $o['status_order'] ?>"><?= ucfirst($o['status_order']) ?></span></span></div>
      <?php if ($o['kode_undangan']): ?>
        <div class="info-row"><strong>Kode Undangan</strong><span style="font-family:monospace;color:var(--r);font-weight:700"><?= $o['kode_undangan'] ?></span></div>
        <div class="link-box">
          <p>🔗 Link Undangan Aktif:</p>
          <div class="link-url">http://localhost/WebDev/undangan/?kode=<?= $o['kode_undangan'] ?>&to=<?= urlencode($o['nama_pria']) ?></div>
        </div>
      <?php endif ?>
      <?php if ($o['catatan']): ?>
        <div class="info-row" style="margin-top:.5rem"><strong>Catatan</strong><span><?= $o['catatan'] ?></span></div>
      <?php endif ?>
    </div>

    <!-- Bukti Bayar -->
    <?php if ($o['bukti_bayar']): ?>
      <div class="card">
        <div class="card-title">🧾 Bukti Transfer</div>
        <?php if (str_ends_with(strtolower($o['bukti_bayar']), '.pdf')): ?>
          <a href="../<?= $o['bukti_bayar'] ?>" target="_blank" style="color:var(--r)">📄 Buka PDF Bukti Transfer</a>
        <?php else: ?>
          <img src="../<?= $o['bukti_bayar'] ?>" class="bukti-img" alt="Bukti Transfer" />
        <?php endif ?>
      </div>
    <?php endif ?>

    <!-- Form Approve -->
    <?php if ($o['status_order'] !== 'aktif'): ?>
      <div class="card">
        <div class="card-title">✅ Konfirmasi Pembayaran</div>
        <form method="POST">
          <label>Metode Pembayaran</label>
          <select name="metode_bayar">
            <option value="BCA">Transfer BCA</option>
            <option value="Mandiri">Transfer Mandiri</option>
            <option value="BNI">Transfer BNI</option>
            <option value="BRI">Transfer BRI</option>
            <option value="GoPay">GoPay</option>
            <option value="OVO">OVO</option>
            <option value="Dana">Dana</option>
            <option value="QRIS">QRIS</option>
            <option value="ShopeePay">ShopeePay</option>
          </select>
          <label>Base URL Website</label>
          <input type="text" name="base_url" value="http://localhost/WebDev" placeholder="https://bernada.id" />
          <p style="font-size:12px;color:#aaa;margin-top:-8px;margin-bottom:1rem">URL ini digunakan untuk generate link undangan yang dikirim ke customer</p>
          <button type="submit" name="approve" class="btn-approve">
            ✅ Konfirmasi Bayar & Aktifkan Undangan
          </button>
        </form>
      </div>
    <?php else: ?>
      <div class="alert alert-success">
        <i class='bx bxs-check-circle'></i>
        <div>Undangan sudah aktif. Notif WA: <strong><?= $o['notif_aktif_wa'] ?></strong></div>
      </div>
    <?php endif ?>
  </div>
</body>

</html>