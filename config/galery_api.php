<?php
// ============================================
// FILE: config/galery _api.php
// API: upload, hapus, urutan, ambil foto galery
// ============================================

session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/koneksi.php';

// Wajib login
if (!isset($_SESSION['name'])) {
  echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
  exit;
}

$action    = $_POST['action'] ?? $_GET['action'] ?? '';
$kode_order = trim($_POST['kode_order'] ?? $_GET['kode_order'] ?? '');

// Validasi kode order milik user yang login
function cekOrder(PDO $pdo, string $kode_order): ?array
{
  $stmt = $pdo->prepare("SELECT * FROM orders WHERE kode_order = ? AND status_order != 'batal'");
  $stmt->execute([$kode_order]);
  return $stmt->fetch();
}

// ── GET FOTO ────────────────────────────────
if ($action === 'get' || $_SERVER['REQUEST_METHOD'] === 'GET') {
  $stmt = $pdo->prepare("SELECT * FROM galery_foto WHERE kode_order = ? ORDER BY urutan ASC, id ASC");
  $stmt->execute([$kode_order]);
  $fotos = $stmt->fetchAll();
  echo json_encode(['status' => 'success', 'data' => $fotos, 'total' => count($fotos)]);
  exit;
}

// ── UPLOAD FOTO ─────────────────────────────
if ($action === 'upload') {
  $order = cekOrder($pdo, $kode_order);
  if (!$order) {
    echo json_encode(['status' => 'error', 'message' => 'Order tidak ditemukan']);
    exit;
  }

  // Cek batas foto per paket
  $batas = ['silver' => 3, 'gold' => 10, 'platinum' => 999];
  $max   = $batas[$order['paket']] ?? 3;
  $total = (int)$pdo->prepare("SELECT COUNT(*) FROM galery_foto WHERE kode_order=? AND status='aktif'")->execute([$kode_order]) ?
    (function () use ($pdo, $kode_order) {
      $s = $pdo->prepare("SELECT COUNT(*) FROM galery_foto WHERE kode_order=? AND status='aktif'");
      $s->execute([$kode_order]);
      return (int)$s->fetchColumn();
    })() : 0;

  if ($total >= $max) {
    echo json_encode(['status' => 'error', 'message' => "Batas foto paket {$order['paket']} adalah {$max} foto. Upgrade paket untuk menambah lebih banyak foto."]);
    exit;
  }

  if (empty($_FILES['foto'])) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang diupload']);
    exit;
  }

  $uploaded = [];
  $errors   = [];
  $files    = $_FILES['foto'];

  // Normalisasi single/multiple file
  if (!is_array($files['name'])) {
    foreach ($files as $k => $v) $files[$k] = [$v];
  }

  $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
  $max_size      = 5 * 1024 * 1024; // 5MB per foto
  $upload_dir    = __DIR__ . '/../uploads/galery/' . $kode_order . '/';
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

  $sisa = $max - $total;
  foreach ($files['name'] as $i => $fname) {
    if ($i >= $sisa) {
      $errors[] = "Hanya bisa upload {$sisa} foto lagi (batas paket)";
      break;
    }
    if ($files['error'][$i] !== UPLOAD_ERR_OK) {
      $errors[] = "Error upload: {$fname}";
      continue;
    }
    if ($files['size'][$i]  > $max_size) {
      $errors[] = "{$fname} terlalu besar (maks 5MB)";
      continue;
    }
    if (!in_array($files['type'][$i], $allowed_types)) {
      $errors[] = "{$fname} bukan gambar valid (JPG/PNG/WEBP)";
      continue;
    }

    // Generate nama unik
    $ext      = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
    $newname  = $kode_order . '_' . uniqid() . '.' . $ext;
    $filepath = $upload_dir . $newname;
    $relpath  = 'uploads/galery/' . $kode_order . '/' . $newname;

    if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
      // Kompres gambar jika terlalu besar (> 1MB)
      if ($files['size'][$i] > 1024 * 1024 && function_exists('imagecreatefromjpeg')) {
        kompresGambar($filepath, $ext);
      }

      $caption = trim($_POST['caption'][$i] ?? '');
      $urutan  = $total + $i + 1;
      $pdo->prepare("INSERT INTO galery_foto (kode_order, kode_undangan, nama_file, path_file, caption, urutan, ukuran) VALUES (?,?,?,?,?,?,?)")
        ->execute([$kode_order, $order['kode_undangan'], $newname, $relpath, $caption, $urutan, $files['size'][$i]]);

      $uploaded[] = [
        'id'       => $pdo->lastInsertId(),
        'nama'     => $newname,
        'path'     => $relpath,
        'caption'  => $caption,
        'url'      => '../' . $relpath,
      ];
    } else {
      $errors[] = "Gagal menyimpan: {$fname}";
    }
  }

  echo json_encode([
    'status'   => count($uploaded) > 0 ? 'success' : 'error',
    'uploaded' => $uploaded,
    'errors'   => $errors,
    'message'  => count($uploaded) > 0 ? count($uploaded) . ' foto berhasil diupload!' : 'Gagal upload foto',
    'total'    => $total + count($uploaded),
    'max'      => $max,
  ]);
  exit;
}

// ── HAPUS FOTO ──────────────────────────────
if ($action === 'hapus') {
  $foto_id = (int)($_POST['foto_id'] ?? 0);
  $stmt = $pdo->prepare("SELECT * FROM galery_foto WHERE id = ? AND kode_order = ?");
  $stmt->execute([$foto_id, $kode_order]);
  $foto = $stmt->fetch();

  if (!$foto) {
    echo json_encode(['status' => 'error', 'message' => 'Foto tidak ditemukan']);
    exit;
  }

  // Hapus file fisik
  $file_path = __DIR__ . '/../' . $foto['path_file'];
  if (file_exists($file_path)) unlink($file_path);

  // Hapus dari DB
  $pdo->prepare("DELETE FROM galery_foto WHERE id=?")->execute([$foto_id]);

  echo json_encode(['status' => 'success', 'message' => 'Foto berhasil dihapus']);
  exit;
}

// ── UPDATE CAPTION ───────────────────────────
if ($action === 'caption') {
  $foto_id = (int)($_POST['foto_id'] ?? 0);
  $caption = trim($_POST['caption']  ?? '');
  $pdo->prepare("UPDATE galery_foto SET caption=? WHERE id=? AND kode_order=?")
    ->execute([$caption, $foto_id, $kode_order]);
  echo json_encode(['status' => 'success', 'message' => 'Caption diperbarui']);
  exit;
}

// ── UPDATE URUTAN ────────────────────────────
if ($action === 'urutan') {
  $urutan = json_decode($_POST['urutan'] ?? '[]', true);
  foreach ($urutan as $pos => $fid) {
    $pdo->prepare("UPDATE galery_foto SET urutan=? WHERE id=? AND kode_order=?")
      ->execute([$pos + 1, (int)$fid, $kode_order]);
  }
  echo json_encode(['status' => 'success']);
  exit;
}

// ── GET FOTO untuk TEMA (publik, via kode undangan) ──
if ($action === 'get_publik') {
  $kode_undangan = trim($_GET['kode_undangan'] ?? '');
  if (!$kode_undangan) {
    echo json_encode(['status' => 'error']);
    exit;
  }
  $stmt = $pdo->prepare("SELECT * FROM galery_foto WHERE kode_undangan=? AND status='aktif' ORDER BY urutan ASC");
  $stmt->execute([$kode_undangan]);
  echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
  exit;
}

echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenal']);

// ── Helper kompres gambar ────────────────────
function kompresGambar(string $path, string $ext)
{
  try {
    if (in_array($ext, ['jpg', 'jpeg'])) {
      $img = imagecreatefromjpeg($path);
      if ($img) {
        imagejpeg($img, $path, 80);
        imagedestroy($img);
      }
    } elseif ($ext === 'png') {
      $img = imagecreatefrompng($path);
      if ($img) {
        imagepng($img, $path, 7);
        imagedestroy($img);
      }
    }
  } catch (Exception $e) { /* skip */
  }
}
