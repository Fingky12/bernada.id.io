<?php
// ============================================
// FILE: buat-undangan/proses_undangan.php
// Proses form: simpan DB + kirim WA + kirim Email
// ============================================

header('Content-Type: application/json');
require_once __DIR__ . '/../config/koneksi.php';

// Validasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method tidak valid.']);
    exit;
}

// Ambil & sanitasi input
function clean($val) {
    return htmlspecialchars(trim($val ?? ''), ENT_QUOTES, 'UTF-8');
}
 
$nama_pria     = clean($_POST['nama_pria']     ?? '');
$nama_wanita   = clean($_POST['nama_wanita']   ?? '');
$ayah_pria     = clean($_POST['ayah_pria']     ?? '');
$ayah_wanita   = clean($_POST['ayah_wanita']   ?? '');
$tanggal_nikah = clean($_POST['tanggal_nikah'] ?? '');
$waktu_mulai   = clean($_POST['waktu_mulai']   ?? '');
$waktu_selesai = clean($_POST['waktu_selesai'] ?? '');
$lokasi        = clean($_POST['lokasi']        ?? '');
$link_maps     = clean($_POST['link_maps']     ?? '');
$tema          = clean($_POST['tema']          ?? 'Merah Klasik');
$no_whatsapp   = preg_replace('/[^0-9]/', '', $_POST['no_whatsapp'] ?? '');
$email         = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$catatan       = clean($_POST['catatan']       ?? '');
 
// Validasi wajib
if (!$nama_pria || !$nama_wanita || !$tanggal_nikah || !$waktu_mulai || !$waktu_selesai || !$lokasi || !$no_whatsapp) {
    echo json_encode(['status' => 'error', 'message' => 'Field wajib belum lengkap.']);
    exit;
}
 
// Normalisasi nomor WA (08xxx → 628xxx)
if (substr($no_whatsapp, 0, 1) === '0') {
    $no_whatsapp = '62' . substr($no_whatsapp, 1);
}
 
// Generate kode unik
function generateKode($pdo) {
    do {
        $kode = 'BRN-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        $cek  = $pdo->prepare("SELECT id FROM undangan WHERE kode_undangan = ?");
        $cek->execute([$kode]);
    } while ($cek->rowCount() > 0);
    return $kode;
}
$kode = generateKode($pdo);
 
// Format tanggal Indonesia
$tgl_obj  = new DateTime($tanggal_nikah);
$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];
$hari_id  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$tgl_format = $hari_id[(int)$tgl_obj->format('w')] . ', ' .
              $tgl_obj->format('j') . ' ' .
              $bulan_id[(int)$tgl_obj->format('n')] . ' ' .
              $tgl_obj->format('Y');
 
// Simpan ke database
try {
    $stmt = $pdo->prepare("
        INSERT INTO undangan
        (kode_undangan, nama_pria, nama_wanita, tanggal_nikah,
         waktu_mulai, waktu_selesai, lokasi, link_maps, tema,
         no_whatsapp, email, catatan)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $kode, $nama_pria, $nama_wanita, $tanggal_nikah,
        $waktu_mulai, $waktu_selesai, $lokasi, $link_maps, $tema,
        $no_whatsapp, $email ?: null, $catatan ?: null
    ]);
    $insertId = $pdo->lastInsertId();
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    exit;
}
 
// Kirim WhatsApp via Fonnte
$wa_status = 'belum';
$pesan_wa  = "✨ *Halo, {$nama_pria}!*\n\n"
           . "Terima kasih telah memesan undangan digital di *Bernada.ID* 🎉\n\n"
           . "📋 *Detail Pesananmu:*\n"
           . "━━━━━━━━━━━━━━━━\n"
           . "👰 Pengantin : {$nama_pria} & {$nama_wanita}\n"
           . "📅 Tanggal   : {$tgl_format}\n"
           . "🕐 Waktu     : {$waktu_mulai} – {$waktu_selesai} WIB\n"
           . "📍 Lokasi    : {$lokasi}\n"
           . "🎨 Tema      : {$tema}\n"
           . "━━━━━━━━━━━━━━━━\n"
           . "🔖 *Kode Undangan: {$kode}*\n\n"
           . "Tim kami akan segera memprosesnya. Jika ada pertanyaan, balas pesan ini ya! 😊";
 
$fonnte_response = kirimWA($no_whatsapp, $pesan_wa);
if ($fonnte_response) {
    $wa_status = 'terkirim';
    $pesan_admin = "🔔 *Pesanan Baru Masuk!*\n\n"
                 . "Kode   : {$kode}\n"
                 . "Nama   : {$nama_pria} & {$nama_wanita}\n"
                 . "Tanggal: {$tgl_format}\n"
                 . "Lokasi : {$lokasi}\n"
                 . "Tema   : {$tema}\n"
                 . "WA     : {$no_whatsapp}\n"
                 . "Email  : " . ($email ?: '-');
    kirimWA(ADMIN_WA, $pesan_admin);
} else {
    $wa_status = 'gagal';
}
 
// ✅ FIX 2: path vendor - naik 1 folder dari config/ ke root project
$email_status = 'belum';
if ($email) {
    $vendor_path = __DIR__ . '/../vendor/autoload.php';
    if (file_exists($vendor_path)) {
        require_once $vendor_path;
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USER;
            $mail->Password   = MAIL_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom(MAIL_FROM, MAIL_NAME);
            $mail->addAddress($email, $nama_pria);
            $mail->isHTML(true);
            $mail->Subject = "Kode Undangan Kamu - {$kode} | Bernada.ID";
            $mail->Body    = emailTemplate($nama_pria, $nama_wanita, $tgl_format, $waktu_mulai, $waktu_selesai, $lokasi, $tema, $kode);
            $mail->AltBody = "Halo {$nama_pria}, kode undanganmu adalah {$kode}.";
            $mail->send();
            $email_status = 'terkirim';
        } catch (Exception $e) {
            $email_status = 'gagal';
        }
    } else {
        $email_status = 'gagal'; // vendor belum diinstall, skip saja
    }
}
 
// Update status notifikasi
$pdo->prepare("UPDATE undangan SET notif_wa=?, notif_email=? WHERE id=?")
    ->execute([$wa_status, $email_status, $insertId]);
 
// Response sukses
echo json_encode([
    'status'      => 'success',
    'kode'        => $kode,
    'notif_wa'    => $wa_status,
    'notif_email' => $email_status,
    'message'     => 'Undangan berhasil dibuat!'
]);


// ════════════════════════════════════════════
// FUNGSI HELPER
// ════════════════════════════════════════════

function kirimWA($nomor, $pesan) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.fonnte.com/send',
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => ['target' => $nomor, 'message' => $pesan, 'countryCode' => '62'],
        CURLOPT_HTTPHEADER     => ['Authorization: ' . FONNTE_TOKEN],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => false, // ✅ FIX 3: penting untuk XAMPP localhost
    ]);
    $result = curl_exec($ch);
    $err    = curl_errno($ch);
    curl_close($ch);
    if ($err) return false;
    $data = json_decode($result, true);
    return isset($data['status']) && $data['status'] === true;
}

function emailTemplate($pria, $wanita, $tgl, $wm, $ws, $lokasi, $tema, $kode) {
    return "
    <div style='font-family:Arial,sans-serif;max-width:560px;margin:0 auto;'>
      <div style='background:#C0393B;padding:28px 24px;text-align:center;border-radius:12px 12px 0 0'>
        <h1 style='color:#fff;margin:0;font-size:24px'>BERNADA.ID</h1>
        <p style='color:rgba(255,255,255,.8);margin:4px 0 0;font-size:14px'>Undangan Digital</p>
      </div>
      <div style='background:#fff;padding:28px 24px;border:1px solid #eee;'>
        <p style='font-size:16px;color:#333'>Halo, <strong>{$pria}</strong>!</p>
        <p style='color:#555;font-size:14px;line-height:1.6'>Terima kasih telah mempercayakan undangan digitalmu kepada Bernada.ID.</p>
        <div style='background:#fff5f5;border:1.5px dashed #C0393B;border-radius:10px;padding:16px 20px;margin:20px 0;text-align:center'>
          <p style='margin:0;font-size:12px;color:#888'>Kode Undangan Kamu</p>
          <p style='margin:6px 0 0;font-size:28px;font-weight:700;color:#C0393B;letter-spacing:.1em'>{$kode}</p>
        </div>
        <table style='width:100%;font-size:14px;border-collapse:collapse;'>
          <tr><td style='padding:8px 0;color:#888;width:130px'>Pengantin</td><td style='color:#333;font-weight:600'>{$pria} &amp; {$wanita}</td></tr>
          <tr><td style='padding:8px 0;color:#888'>Tanggal</td><td style='color:#333'>{$tgl}</td></tr>
          <tr><td style='padding:8px 0;color:#888'>Waktu</td><td style='color:#333'>{$wm} - {$ws} WIB</td></tr>
          <tr><td style='padding:8px 0;color:#888'>Lokasi</td><td style='color:#333'>{$lokasi}</td></tr>
          <tr><td style='padding:8px 0;color:#888'>Tema</td><td style='color:#333'>{$tema}</td></tr>
        </table>
      </div>
      <div style='background:#f9f9f9;padding:16px 24px;text-align:center;border-radius:0 0 12px 12px;border:1px solid #eee;border-top:none'>
        <p style='margin:0;font-size:12px;color:#aaa'>&copy; " . date('Y') . " Bernada.ID</p>
      </div>
    </div>";
}

?>