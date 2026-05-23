<?php
// ============================================
// FILE: config/ambil_data.php (FINAL UPDATE)
// Support: waktu akad & resepsi terpisah
// ============================================

if (!defined('TEMA_INCLUDE')) {
  http_response_code(403);
  die('Forbidden');
}
if (!isset($pdo)) require_once __DIR__ . '/koneksi.php';

$kode = trim($_GET['kode'] ?? '');
$tamu = trim($_GET['to']   ?? 'Tamu Undangan');

// Ambil data order
$data = [];
if ($kode) {
  $stmt = $pdo->prepare("SELECT * FROM orders WHERE kode_undangan=? AND status_order='aktif'");
  $stmt->execute([$kode]);
  $data = $stmt->fetch() ?: [];
}

// Data pengantin
$pria        = $data['nama_pria']      ?? 'Pengantin Pria';
$wanita      = $data['nama_wanita']    ?? 'Pengantin Wanita';
$ayah_pria   = $data['ayah_pria']      ?? '';
$ibu_pria    = $data['ibu_pria']       ?? '';
$ayah_wanita = $data['ayah_wanita']    ?? '';
$ibu_wanita  = $data['ibu_wanita']     ?? '';

// Data acara
$tgl_raw = $data['tanggal_nikah'] ?? date('Y-m-d', strtotime('+30 days'));
$lokasi  = $data['lokasi']        ?? 'Lokasi Acara';
$maps    = $data['link_maps']     ?? '';
$catatan = $data['catatan']       ?? '';

// ── Waktu (support akad & resepsi terpisah) ──
// waktu_mulai  = waktu akad mulai
// waktu_selesai= waktu resepsi selesai
// Kita bagi: akad 09:00-10:00, resepsi 11:00-14:00 (estimasi)
$wm_raw = substr($data['waktu_mulai']    ?? '09:00', 0, 5);
$ws_raw = substr($data['waktu_selesai']  ?? '14:00', 0, 5);

// Waktu akad mulai & selesai
$ma = $wm_raw;  // mulai akad
$sa = date('H:i', strtotime($wm_raw) + 3600); // selesai akad (+1 jam)

// Waktu resepsi mulai & selesai
$mr = date('H:i', strtotime($wm_raw) + 7200); // mulai resepsi (+2 jam)
$sr = $ws_raw;  // selesai resepsi

// Alias untuk kompatibilitas tema lama
$wm = $wm_raw;
$ws = $ws_raw;

// Format tanggal Indonesia
$tgl_obj  = new DateTime($tgl_raw);
$bulan_id = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$hari_id  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$tgl_full      = $hari_id[(int)$tgl_obj->format('w')] . ', ' . $tgl_obj->format('j') . ' ' . $bulan_id[(int)$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y');
$tgl_countdown = $tgl_obj->format('Y-m-d') . 'T' . $ma . ':00';
$tgl_short     = $tgl_obj->format('d M Y');

// Paket & info
$paket      = $data['paket']      ?? 'silver';
$tema       = $data['tema']       ?? 'merah-klasik';
$kode_order = $data['kode_order'] ?? '';

// Cek expired
$tgl_expired = $data['tgl_expired'] ?? '';
$is_expired  = $tgl_expired && strtotime($tgl_expired) < time();
if ($is_expired && $kode) {
  http_response_code(410);
  die('<html><head><meta charset="UTF-8"><title>Expired</title></head><body style="font-family:sans-serif;text-align:center;padding:4rem"><h2>⏰ Undangan Expired</h2><p>Masa aktif undangan ini telah berakhir.</p><br><a href="/" style="color:#C0393B">← Beranda</a></body></html>');
}

// ── Ambil foto galeri ────────────────────────
$galeri_fotos = [];
$ada_galeri   = false;
if (!empty($data['kode_undangan'])) {
  try {
    $gstmt = $pdo->prepare("SELECT * FROM galeri_foto WHERE kode_undangan=? AND status='aktif' ORDER BY urutan ASC, id ASC");
    $gstmt->execute([$data['kode_undangan']]);
    $galeri_fotos = $gstmt->fetchAll();
    $ada_galeri   = count($galeri_fotos) > 0;
  } catch (Exception $e) {
    $galeri_fotos = [];
    $ada_galeri   = false;
  }
}
