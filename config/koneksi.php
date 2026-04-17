<?php
// ============================================
// FILE: config/db.php
// Konfigurasi koneksi database XAMPP
// ==
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // default XAMPP
define('DB_PASS', '');            // default XAMPP (kosong)
define('DB_NAME', 'bernada');

// Konfigurasi Fonnte WhatsApp API
define('FONNTE_TOKEN', 'DK3J8xh2pyetxgj1dpC8');
define('ADMIN_WA',    '6281939195110'); // nomor WA admin, format 628xxx

// Konfigurasi Email (Gmail SMTP
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'bernada.id811@gmail.com');
define('MAIL_PASS', 'qkhl rwcs oocv zgin'); // App Password Gmail (bukan password biasa
define('MAIL_PORT', 587);
define('MAIL_FROM', 'bernada.id811@gmail.com');
define('MAIL_NAME', 'Bernada.ID');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


if($conn->connect_error){
  die("Koneksi gagal...");
  }

// Koneksi PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . $e->getMessage()]));
}
  

?>

