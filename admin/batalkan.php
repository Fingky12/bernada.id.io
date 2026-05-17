<?php
session_start();
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) {
  header('Location: admin_login.php');
  exit;
}
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare("UPDATE orders SET status_order='batal' WHERE id=? AND status_order != 'aktif'")
  ->execute([$id]);
header('Location: admin_dashboard.php');
exit;
