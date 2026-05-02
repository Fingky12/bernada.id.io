<?php
// ============================================
// FILE: config/proses_order.php
// Proses order customer → simpan DB → notif WA
// ============================================

header('Content-Type: application/json');
require_once __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Method tidak valid.']);
    exit;
}

// ── Sanitasi input ──────────────────────────
function cl($v) { return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }

$nama_pria    = cl($_POST['nama_pria']    ?? '');
$nama_wanita  = cl($_POST['nama_wanita']  ?? '');
$ayah_pria    = cl($_POST['ayah_pria']    ?? '');
$ibu_pria     = cl($_POST['ibu_pria']     ?? '');
$ayah_wanita  = cl($_POST['ayah_wanita']  ?? '');
$ibu_wanita   = cl($_POST['ibu_wanita']   ?? '');
$tanggal      = cl($_POST['tanggal_nikah']?? '');
$waktu_mulai  = cl($_POST['waktu_mulai']  ?? '');
$waktu_selesai= cl($_POST['waktu_selesai']?? '');
$lokasi       = cl($_POST['lokasi']       ?? '');
$link_maps    = cl($_POST['link_maps']    ?? '');
$paket        = cl($_POST['paket']        ?? 'silver');
$tema         = cl($_POST['tema']         ?? 'merah-klasik');
$catatan      = cl($_POST['catatan']      ?? '');
$no_wa        = preg_replace('/[^0-9]/', '', $_POST['no_whatsapp'] ?? '');
$email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$metode_bayar = cl($_POST['metode_bayar'] ?? '');

// ── Validasi wajib ──────────────────────────
if (!$nama_pria || !$nama_wanita || !$tanggal || !$waktu_mulai || !$waktu_selesai || !$lokasi || !$no_wa || !$paket || !$tema) {
    echo json_encode(['status'=>'error','message'=>'Field wajib belum lengkap.']);
    exit;
}

// Normalisasi WA
if (substr($no_wa, 0, 1) === '0') $no_wa = '62'.substr($no_wa, 1);

// ── Ambil harga paket ───────────────────────
$stmt_harga = $pdo->prepare("SELECT harga, aktif FROM paket_harga WHERE paket = ?");
$stmt_harga->execute([$paket]);
$paket_data = $stmt_harga->fetch();
$harga = $paket_data['harga'] ?? 0;
$aktif_hari = $paket_data['aktif'] ?? 30;

// ── Generate kode order unik ────────────────
function genKodeOrder($pdo, $prefix, $col) {
    do {
        $kode = $prefix.'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        $cek  = $pdo->prepare("SELECT id FROM orders WHERE $col = ?");
        $cek->execute([$kode]);
    } while ($cek->rowCount() > 0);
    return $kode;
}
$kode_order = genKodeOrder($pdo, 'ORD', 'kode_order');

// ── Format tanggal Indonesia ────────────────
$tgl_obj  = new DateTime($tanggal);
$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$hari_id  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$tgl_fmt  = $hari_id[(int)$tgl_obj->format('w')].', '.$tgl_obj->format('j').' '.$bulan_id[(int)$tgl_obj->format('n')].' '.$tgl_obj->format('Y');

// ── Handle upload bukti bayar (jika ada) ────
$bukti_path = null;
if ($paket !== 'silver' && !empty($_FILES['bukti_bayar']['name'])) {
    $upload_dir = __DIR__.'/../uploads/bukti/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
    $ext  = strtolower(pathinfo($_FILES['bukti_bayar']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','pdf'];
    if (in_array($ext, $allowed) && $_FILES['bukti_bayar']['size'] < 5*1024*1024) {
        $filename   = 'bukti_'.$kode_order.'.'.$ext;
        $bukti_path = 'uploads/bukti/'.$filename;
        move_uploaded_file($_FILES['bukti_bayar']['tmp_name'], $upload_dir.$filename);
    }
}

// Status awal
$status_bayar = ($paket === 'silver') ? 'lunas' : ($bukti_path ? 'menunggu_konfirmasi' : 'pending');
$status_order = ($paket === 'silver') ? 'diproses' : 'baru';

// ── Simpan ke database ──────────────────────
try {
    $sql = "INSERT INTO orders
        (kode_order, nama_pria, nama_wanita, ayah_pria, ibu_pria, ayah_wanita, ibu_wanita,
         no_whatsapp, email, tanggal_nikah, waktu_mulai, waktu_selesai, lokasi, link_maps,
         paket, tema, catatan, harga, status_bayar, bukti_bayar, metode_bayar, status_order)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $pdo->prepare($sql)->execute([
        $kode_order, $nama_pria, $nama_wanita, $ayah_pria ?: null, $ibu_pria ?: null,
        $ayah_wanita ?: null, $ibu_wanita ?: null, $no_wa, $email ?: null,
        $tanggal, $waktu_mulai, $waktu_selesai, $lokasi, $link_maps ?: null,
        $paket, $tema, $catatan ?: null, $harga, $status_bayar,
        $bukti_path, $metode_bayar ?: null, $status_order
    ]);
} catch (PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Gagal menyimpan: '.$e->getMessage()]);
    exit;
}

// ── Jika silver → langsung aktifkan ────────
$kode_undangan = null;
if ($paket === 'silver') {
    $kode_undangan = genKodeOrder($pdo, 'BRN', 'kode_undangan');
    $tgl_aktif   = date('Y-m-d H:i:s');
    $tgl_expired = date('Y-m-d H:i:s', strtotime("+{$aktif_hari} days"));
    $pdo->prepare("UPDATE orders SET kode_undangan=?, status_order='aktif', tgl_aktif=?, tgl_expired=? WHERE kode_order=?")
        ->execute([$kode_undangan, $tgl_aktif, $tgl_expired, $kode_order]);
}

// ── Kirim WA ke customer ────────────────────
$nama_paket = strtoupper($paket);
$harga_fmt  = $harga > 0 ? 'Rp '.number_format($harga,0,',','.') : 'Gratis';
$tema_label = ucwords(str_replace('-',' ', $tema));

if ($paket === 'silver') {
    // Langsung kirim link
    $link = "http://localhost/WebDev/undangan/{$tema}.php?kode={$kode_undangan}&to=".urlencode($nama_pria);
    $pesan_wa = "🌿 *Halo, {$nama_pria}!*\n\n"
              . "Selamat! Undangan digitalmu dari *Bernada.ID* sudah siap! 🎉\n\n"
              . "📋 *Detail Order:*\n"
              . "━━━━━━━━━━━━━━━━\n"
              . "🔖 Kode Order   : {$kode_order}\n"
              . "🔑 Kode Undangan: {$kode_undangan}\n"
              . "👰 Pengantin    : {$nama_pria} & {$nama_wanita}\n"
              . "📅 Tanggal      : {$tgl_fmt}\n"
              . "📍 Lokasi       : {$lokasi}\n"
              . "🎨 Tema         : {$tema_label}\n"
              . "💎 Paket        : {$nama_paket} ({$harga_fmt})\n"
              . "━━━━━━━━━━━━━━━━\n\n"
              . "🔗 *Link Undangan Kamu:*\n"
              . "{$link}\n\n"
              . "Bagikan link ini ke tamu undanganmu!\n"
              . "Contoh: {$link}&to=Nama+Tamu\n\n"
              . "Tim Bernada.ID 💚";
} else {
    // Tunggu konfirmasi bayar
    $info_bayar = $bukti_path
        ? "✅ Bukti transfer sudah kami terima, sedang diverifikasi."
        : "⚠️ Mohon kirim bukti transfer ke nomor admin untuk mempercepat proses.";

    $pesan_wa = "🌿 *Halo, {$nama_pria}!*\n\n"
              . "Pesanan undangan digitalmu di *Bernada.ID* sudah kami terima! 🎉\n\n"
              . "📋 *Detail Order:*\n"
              . "━━━━━━━━━━━━━━━━\n"
              . "🔖 Kode Order: {$kode_order}\n"
              . "👰 Pengantin : {$nama_pria} & {$nama_wanita}\n"
              . "📅 Tanggal   : {$tgl_fmt}\n"
              . "🎨 Tema      : {$tema_label}\n"
              . "💎 Paket     : {$nama_paket}\n"
              . "💰 Total     : {$harga_fmt}\n"
              . "━━━━━━━━━━━━━━━━\n\n"
              . "💳 *Status Pembayaran:*\n{$info_bayar}\n\n"
              . "Setelah pembayaran terverifikasi, link undangan akan langsung kami kirimkan ke nomor ini.\n\n"
              . "_Simpan kode order kamu: *{$kode_order}*_\n\n"
              . "Tim Bernada.ID 💚";
}

$wa_status = kirimWA($no_wa, $pesan_wa) ? 'terkirim' : 'gagal';
$pdo->prepare("UPDATE orders SET notif_order_wa=? WHERE kode_order=?")
    ->execute([$wa_status, $kode_order]);

// Notif ke admin
$pesan_admin = "🔔 *ORDER BARU MASUK!*\n\n"
             . "Kode    : {$kode_order}\n"
             . "Nama    : {$nama_pria} & {$nama_wanita}\n"
             . "Tanggal : {$tgl_fmt}\n"
             . "Tema    : {$tema_label}\n"
             . "Paket   : {$nama_paket} ({$harga_fmt})\n"
             . "WA      : {$no_wa}\n"
             . "Bayar   : {$status_bayar}\n"
             . ($bukti_path ? "Bukti   : ✅ Sudah upload\n" : "")
             . "\nCek dashboard: http://localhost/WebDev/admin/";
kirimWA(ADMIN_WA, $pesan_admin);

// ── Response ────────────────────────────────
echo json_encode([
    'status'        => 'success',
    'kode_order'    => $kode_order,
    'kode_undangan' => $kode_undangan,
    'paket'         => $paket,
    'harga'         => $harga,
    'status_bayar'  => $status_bayar,
    'wa_status'     => $wa_status,
    'message'       => 'Order berhasil dibuat!'
]);

// ── Fungsi helper ────────────────────────────
function kirimWA($nomor, $pesan) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.fonnte.com/send',
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => ['target'=>$nomor,'message'=>$pesan,'countryCode'=>'62'],
        CURLOPT_HTTPHEADER     => ['Authorization: '.FONNTE_TOKEN],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $res = curl_exec($ch); $err = curl_errno($ch); curl_close($ch);
    if ($err) return false;
    $data = json_decode($res, true);
    return isset($data['status']) && $data['status'] === true;
}