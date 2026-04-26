<?php
session_start();
require_once 'config/koneksi.php';
$name = $_SESSION['name'] ?? null;

// Proses form kontak
$send_status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_pesan'])) {
  $nm   = htmlspecialchars(trim($_POST['nama']    ?? ''));
  $em   = htmlspecialchars(trim($_POST['email']   ?? ''));
  $wa   = htmlspecialchars(trim($_POST['whatsapp'] ?? ''));
  $subj = htmlspecialchars(trim($_POST['subjek']  ?? ''));
  $msg  = htmlspecialchars(trim($_POST['pesan']   ?? ''));

  if ($nm && $em && $subj && $msg) {
    // Simpan ke database
    try {
      // Buat tabel jika belum ada
      $pdo->exec("CREATE TABLE IF NOT EXISTS pesan_kontak (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama VARCHAR(100), email VARCHAR(100), whatsapp VARCHAR(20),
                subjek VARCHAR(200), pesan TEXT, status ENUM('baru','dibaca','dibalas') DEFAULT 'baru',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
      $pdo->prepare("INSERT INTO pesan_kontak (nama,email,whatsapp,subjek,pesan) VALUES (?,?,?,?,?)")
        ->execute([$nm, $em, $wa, $subj, $msg]);
      $send_status = 'success';
    } catch (PDOException $e) {
      $send_status = 'error';
    }

    // Kirim notif WA ke admin
    if ($send_status === 'success') {
      $pesan_wa = "📩 *Pesan Baru dari Website!*\n\n"
        . "Nama   : {$nm}\n"
        . "Email  : {$em}\n"
        . "WA     : " . ($wa ?: '-') . "\n"
        . "Subjek : {$subj}\n"
        . "Pesan  : {$msg}";
      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.fonnte.com/send',
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => ['target' => ADMIN_WA, 'message' => $pesan_wa, 'countryCode' => '62'],
        CURLOPT_HTTPHEADER     => ['Authorization: ' . FONNTE_TOKEN],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
      ]);
      curl_exec($ch);
      curl_close($ch);

      // Kirim email konfirmasi ke pengirim
      if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
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
          $mail->addAddress($em, $nm);
          $mail->isHTML(true);
          $mail->Subject = "Pesan Kamu Sudah Kami Terima – Bernada.ID";
          $mail->Body    = "
                    <div style='font-family:Arial,sans-serif;max-width:520px;margin:0 auto;'>
                      <div style='background:#C0393B;padding:24px;text-align:center;border-radius:12px 12px 0 0'>
                        <h2 style='color:#fff;margin:0'>BERNADA.ID</h2>
                      </div>
                      <div style='background:#fff;padding:24px;border:1px solid #eee;'>
                        <p style='font-size:15px;color:#333'>Halo, <strong>{$nm}</strong>!</p>
                        <p style='font-size:14px;color:#555;line-height:1.7'>Terima kasih telah menghubungi kami. Pesan kamu dengan subjek <strong>\"{$subj}\"</strong> sudah kami terima dan akan direspons dalam 1×24 jam.</p>
                        <div style='background:#fff5f5;border-left:3px solid #C0393B;padding:12px 16px;margin:16px 0;border-radius:0 8px 8px 0;font-size:13px;color:#666'>{$msg}</div>
                      </div>
                      <div style='background:#f9f9f9;padding:14px;text-align:center;border-radius:0 0 12px 12px;border:1px solid #eee;border-top:none;font-size:12px;color:#aaa'>
                        &copy; " . date('Y') . " Bernada.ID
                      </div>
                    </div>";
          $mail->send();
        } catch (Exception $e) { /* skip */
        }
      }
    }
  } else {
    $send_status = 'incomplete';
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/footer_header_sec.css">
  <link rel="stylesheet" href="css/contact.css">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
  <link rel="manifest" href="favicon_io/site.webmanifest">
  <title>Hubungi Kami – Bernada.ID</title>
</head>

<body>
  <?php include("./header/inc_header_second.php") ?>

  <div class="page-hero">
    <div class="page-hero-tag">Hubungi Kami</div>
    <h1>Ada yang bisa kami bantu?</h1>
    <p>Tim kami siap merespons pertanyaan kamu dalam 1×24 jam</p>
  </div>

  <div class="contact-layout">

    <!-- LEFT -->
    <div class="contact-info">
      <div class="info-card">
        <h3><i class='bx bx-info-circle'></i> Informasi Kontak</h3>
        <div class="contact-item">
          <div class="ci-icon"><i class='bx bxl-whatsapp'></i></div>
          <div>
            <div class="ci-label">WhatsApp</div>
            <div class="ci-value"><a href="https://wa.me/6281939195110" target="_blank">+62 819-3919-5110</a></div>
          </div>
        </div>
        <div class="contact-item">
          <div class="ci-icon"><i class='bx bx-envelope'></i></div>
          <div>
            <div class="ci-label">Email</div>
            <div class="ci-value"><a href="mailto:bernada.id811@gmail.com">bernada.id811@gmail.com</a></div>
          </div>
        </div>
        <div class="contact-item">
          <div class="ci-icon"><i class='bx bx-map'></i></div>
          <div>
            <div class="ci-label">Lokasi</div>
            <div class="ci-value">Sidoarjo, Jawa Timur, Indonesia</div>
          </div>
        </div>
        <a href="https://wa.me/6281939195110?text=Halo+Bernada.ID,+saya+ingin+bertanya..." class="wa-btn" target="_blank">
          <i class='bx bxl-whatsapp'></i> Chat Langsung via WhatsApp
        </a>
      </div>

      <div class="info-card">
        <h3><i class='bx bx-time-five'></i> Jam Operasional</h3>
        <div class="hours-grid">
          <div class="hour-item">
            <div class="day">Senin – Jumat</div>
            <div class="time">18.00 – 23.00</div>
          </div>
          <div class="hour-item">
            <div class="day">Sabtu</div>
            <div class="time">20.00 – 01.00</div>
          </div>
          <div class="hour-item">
            <div class="day">Minggu</div>
            <div class="time">18.00 – 23.00</div>
          </div>
          <div class="hour-item">
            <div class="day">Libur Nasional</div>
            <div class="time">18.00 – 23.00</div>
          </div>
        </div>
      </div>

      <div class="info-card">
        <h3><i class='bx bxl-instagram'></i> Ikuti Kami</h3>
        <p style="font-size:13px;color:var(--gray);margin-bottom:10px">Update terbaru, inspirasi undangan, dan promo eksklusif</p>
        <div class="social-row">
          <a href="#" class="social-btn" title="Instagram"><i class='bx bxl-instagram'></i></a>
          <a href="#" class="social-btn" title="TikTok"><i class='bx bxl-tiktok'></i></a>
          <a href="#" class="social-btn" title="WhatsApp"><i class='bx bxl-whatsapp'></i></a>
          <a href="#" class="social-btn" title="Facebook"><i class='bx bxl-facebook'></i></a>
        </div>
      </div>
    </div>

    <!-- RIGHT — FORM -->
    <div class="contact-form-wrap">
      <div class="form-header">
        <h2>Kirim Pesan</h2>
        <p>Isi form di bawah dan kami akan membalas sesegera mungkin</p>
      </div>

      <?php if ($send_status === 'success'): ?>
        <div class="alert alert-success">
          <i class='bx bxs-check-circle'></i>
          <div><strong>Pesan berhasil terkirim!</strong> Kami akan membalas dalam 1×24 jam. Cek email kamu untuk konfirmasi.</div>
        </div>
      <?php elseif ($send_status === 'error'): ?>
        <div class="alert alert-error">
          <i class='bx bxs-error-circle'></i>
          <div>Terjadi kesalahan saat mengirim pesan. Coba lagi atau hubungi via WhatsApp.</div>
        </div>
      <?php elseif ($send_status === 'incomplete'): ?>
        <div class="alert alert-error">
          <i class='bx bxs-error-circle'></i>
          <div>Harap lengkapi semua field yang wajib diisi.</div>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="field-row">
          <div class="field">
            <label>Nama Lengkap <span class="req">*</span></label>
            <input type="text" name="nama" placeholder="cth. Budi Santoso" required />
          </div>
          <div class="field">
            <label>Email <span class="req">*</span></label>
            <input type="email" name="email" placeholder="cth. budi@gmail.com" required />
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label>WhatsApp <small style="color:#aaa">(opsional)</small></label>
            <input type="tel" name="whatsapp" placeholder="cth. 08123456789" />
          </div>
          <div class="field">
            <label>Subjek <span class="req">*</span></label>
            <select name="subjek" required>
              <option value="">-- Pilih Subjek --</option>
              <option>Pertanyaan tentang layanan</option>
              <option>Pertanyaan tentang harga & paket</option>
              <option>Permintaan revisi undangan</option>
              <option>Kendala teknis</option>
              <option>Kerjasama & afiliasi</option>
              <option>Lainnya</option>
            </select>
          </div>
        </div>
        <div class="field">
          <label>Pesan <span class="req">*</span></label>
          <textarea name="pesan" placeholder="Tuliskan pertanyaan atau pesanmu di sini..." required></textarea>
        </div>
        <button type="submit" name="kirim_pesan" class="btn-submit">
          <i class='bx bx-send'></i> Kirim Pesan Sekarang
        </button>
      </form>
    </div>

  </div>

  <?php include("./footer/inc_footer_second.php") ?>

  <script src="./scripts/script.js"></script>
</body>

</html>