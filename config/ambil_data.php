
<?php
// ============================================
// FILE: config/ambil_data.php
// Helper: ambil data order dari tabel orders
// Di-include di semua file tema undangan
// ============================================

// Jangan include langsung, hanya via tema
if (!defined('TEMA_INCLUDE')) {
  http_response_code(403);
  die('Forbidden');
}

// Pastikan koneksi sudah ada
if (!isset($pdo)) {
  require_once __DIR__ . '/koneksi.php';
}

$kode = trim($_GET['kode'] ?? '');
$tamu = trim($_GET['to']   ?? 'Tamu Undangan');

// Ambil data dari tabel orders
$data = [];
if ($kode) {
  $stmt = $pdo->prepare("
        SELECT * FROM orders
        WHERE kode_undangan = ?
        AND status_order = 'aktif'
    ");
  $stmt->execute([$kode]);
  $data = $stmt->fetch() ?: [];
}

// ── Data pengantin ──────────────────────────
$pria        = $data['nama_pria']      ?? 'Nama Pria';
$wanita      = $data['nama_wanita']    ?? 'Nama Wanita';
$ayah_pria   = $data['ayah_pria']      ?? 'Ayah Pria';
$ibu_pria    = $data['ibu_pria']       ?? 'Ibu Pria';
$ayah_wanita = $data['ayah_wanita']    ?? 'Ayah Wanita';
$ibu_wanita  = $data['ibu_wanita']     ?? 'Ibu Wanita';

// ── Data acara ──────────────────────────────
$tgl_raw       = $data['tanggal_nikah'] ?? date('Y-m-d', strtotime('+30 days'));
$jam_acara     = !empty($ma) ? $ma : '00:00';
$ma            = $data['mulai_akad']   ?? '09:00';
$sa            = $data['selesai_akad'] ?? '13:00';
$mr            = $data['mulai_resepsi']   ?? '09:00';
$sr            = $data['selesai_resepsi'] ?? '13:00';
$lokasi        = $data['lokasi']        ?? 'Lokasi Acara';
$maps          = $data['link_maps']     ?? 'https://maps.google.com';
$catatan       = $data['catatan']       ?? '';

// ── Format tanggal Indonesia ────────────────
$tgl_obj  = new DateTime($tgl_raw);
$bulan_id = [
  '',
  'Januari',
  'Februari',
  'Maret',
  'April',
  'Mei',
  'Juni',
  'Juli',
  'Agustus',
  'September',
  'Oktober',
  'November',
  'Desember'
];
$hari_id  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

$tgl_full      = $hari_id[(int)$tgl_obj->format('w')] . ', ' .
  $tgl_obj->format('j') . ' ' .
  $bulan_id[(int)$tgl_obj->format('n')] . ' ' .
  $tgl_obj->format('Y');

$tgl_countdown = $tgl_obj->format('Y-m-d') . 'T' . $jam_acara . ':00+07:00';
$tgl_short     = $tgl_obj->format('d M Y');

// ── Paket & tema info ────────────────────────
$paket         = $data['paket']         ?? 'silver';
$tema          = $data['tema']          ?? 'merah-klasik';
$kode_order    = $data['kode_order']    ?? '';

// ── Waktu format ────────────────────────────
// Ubah format 09:00:00 → 09:00
$ma = substr($ma, 0, 5);
$sa = substr($sa, 0, 5);
$mr = substr($mr, 0, 5);
$sr = substr($sr, 0, 5);

// ── Expired info ────────────────────────────
$tgl_expired = $data['tgl_expired'] ?? '';
$is_expired  = $tgl_expired && strtotime($tgl_expired) < time();

// Kalau expired, redirect ke halaman error
if ($is_expired && $kode) {
  http_response_code(410);
  die('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Undangan Expired</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@400;500&display=swap" rel="stylesheet"/>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:"Plus Jakarta Sans",sans-serif;background:#f8f5f5;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}.box{background:#fff;border-radius:16px;padding:3rem 2rem;text-align:center;max-width:420px;width:100%;border:1px solid #eedede}.icon{font-size:3.5rem;margin-bottom:1.25rem}h1{font-family:"Playfair Display",serif;font-size:1.6rem;color:#1a1a1a;margin-bottom:.75rem}p{font-size:14px;color:#666;line-height:1.8;margin-bottom:1.5rem}a{display:inline-flex;align-items:center;gap:6px;padding:11px 24px;background:#C0393B;color:#fff;border-radius:50px;text-decoration:none;font-size:13px;font-weight:600}.brand{margin-top:2rem;font-size:12px;color:#aaa}.brand span{color:#C0393B;font-weight:600}</style>
    </head><body><div class="box"><div class="icon"><i class="bx bx-timer"></i></div>
    <h1>Undangan Sudah Expired</h1>
    <p>Masa aktif undangan <strong>' . $pria . ' &amp; ' . $wanita . '</strong> telah berakhir pada ' . ($tgl_expired ? date('d M Y', strtotime($tgl_expired)) : '-') . '.</p>
    <a href="/">← Beranda</a>
    <div class="brand">Bernada<span>.ID</span></div></div></body></html>');
}

// ── AMBIL FOTO GALERI ────────────────────────
$galeri_fotos = [];
if (!empty($data['kode_undangan'])) {
  try {
    $gstmt = $pdo->prepare("
            SELECT * FROM galeri_foto
            WHERE kode_undangan = ? AND status = 'aktif'
            ORDER BY urutan ASC, id ASC
        ");
    $gstmt->execute([$data['kode_undangan']]);
    $galeri_fotos = $gstmt->fetchAll();
  } catch (Exception $e) {
    $galeri_fotos = []; // tabel belum ada, skip
  }
}
$ada_galeri = count($galeri_fotos) > 0;

?>