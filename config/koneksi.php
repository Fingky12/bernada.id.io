<?php
// ============================================
// FILE: config/koneksi.php
// ✅ SUDAH DIPERBAIKI
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bernada'); // ← sesuai database kamu

// Konfigurasi Fonnte WhatsApp API
define('FONNTE_TOKEN', 'DK3J8xh2pyetxgj1dpC8');
define('ADMIN_WA',     '6281939195110');

// Konfigurasi Email (Gmail SMTP)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'bernada.id811@gmail.com');
define('MAIL_PASS', 'qkhl rwcs oocv zgin');
define('MAIL_FROM', 'bernada.id811@gmail.com');
define('MAIL_NAME', 'Bernada.ID');

// Koneksi mysqli (untuk auth)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Koneksi PDO (untuk proses undangan)
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