<?php

$host = 'localhost';
$user = 'root';        // default XAMPP
$pass = '';            // default XAMPP (kosong
$db = 'bernada';

// Konfigurasi Fonnte WhatsApp API
$fonnteToken = 'DK3J8xh2pyetxgj1dpC8';
$adminWa =     '6281939195110'; // nomor WA admin, format 628xxx

// Konfigurasi Email (Gmail SMTP
$mailHost =     'smtp.gmail.com';
$mailPort =     587;
$mailUser =     'bernada.id811@gmail.com';
$mailPass =     'qkhl rwcs oocv zgin'; // App Password Gmail (bukan password biasa
$mailFrom =     'bernada.id811@gmail.com';
$mailName =     'Bernada.ID';

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
  die("Koneksi gagal...");
  }else {
    echo "Koneksi berhasil...";
  }
  
?>