<?php 
  session_start();
  require_once 'config/koneksi.php';

  $name = $_SESSION['name'] ?? null;
  $alerts = $_SESSION['alerts'] ?? [];
  $active_form = $_SESSION['active_form'] ?? '';

  session_unset();

  if ($name !== null) $_SESSION['name'] = $name; 



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/dashboard_auth.css" />
    <link rel="stylesheet" href="css/footer_header.css">
    <title>Dashboard Login | BERNADA.ID</title>
  </head>
  <body>
  <section>
    <div class="container">
      <div class="title">
        <h1>Hey! <span style="font-family: 'Playfair Display', serif;"><?= $name ? ' ' . $name : ''; ?></span> Welcome to <a href="halaman.php" class="logo">BERNADA<span>.ID</span></a></h1>
        <p>Selamat datang kembali! Silakan masuk ke akun Anda.</p>
      </div>

      
        <?php if ($name): ?>
          <!-- ✅ SUDAH LOGIN: tampilkan info akun & tombol logout, sembunyikan form -->
          <div class="already-login-box">
            <div class="user-ready"><i class='bx bx-user-check' ></i></div>
            <h3>Kamu sudah login sebagai</h3>
            <p style="font-size: 20px; font-weight: 700; color: #C0393B; margin-bottom: 16px;">
              <?= htmlspecialchars($name) ?>
            </p>
            <p style="font-size: 13px; color: #888; margin-bottom: 20px;">
              Untuk masuk dengan akun lain, logout dulu ya!
            </p>
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
              <a href="halaman.php" style="
                padding: 10px 22px;
                background: #C0393B;
                color: #fff;
                border-radius: 8px;
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
              ">Ke Halaman Utama</a>
              <a href="logout.php" style="
                padding: 10px 22px;
                background: #fff;
                color: #C0393B;
                border: 2px solid #C0393B;
                border-radius: 8px;
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
              ">Logout</a>
            </div>
          </div>


          <?php else: ?>
      <div class="img-dashboard">
        <img src="./img/login-image.png" alt="Dashboard" />
      </div>

      <div class="wrapper <?= $active_form === 'register' ? 'active' : ''; ?>">
        <?php if (!empty($alerts)): ?>
        <div class="alert-box" >
          <?php foreach ($alerts as $alert): ?>
            <div class="alert <?= $alert['type']; ?>">  
              <i class='bx <?= $alert['type'] === 'success' ? 'bxs-check-circle' : 'bxs-error-circle'; ?>'></i>
              <div><?= $alert['message']; ?></div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="form login" id="login-form">
          <h2>Sign <span>In</span> Account</h2>
          <form action="auth_proses.php" method="post">
            <div class="input-box">
              <div class="icon"><i class="bx bx-envelope"></i></div>
              <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-box">
              <div class="icon"><i class="bx bx-lock"></i></div>
              <input type="password" name="password" placeholder="Password" required />
            </div> 
            <div class="remember-forgot">
              <label><input type="checkbox" /> Remember Me</label>
              <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" name="login_btn" class="btn">Sign In</button>
            <div class="login-register">Don't have an account? <a href="#" class="register-link">Register</a></div>
            <div class="social-icons">
              <a href="#" title="Facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" title="Twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" title="Google"><i class="bx bxl-google"></i></a>
              <a href="#" title="GitHub"><i class="bx bxl-github"></i></a>
            </form>
          </div>
        </div>

        <div class="form register" id="register-form">  <!--  -->
          <h2>Sign <span>Up</span> Account</h2>
          <form action="auth_proses.php" method="post">
            <div class="input-box">
              <div class="icon"><i class='bx bx-user'></i></div>
              <input type="text" name="name" placeholder="Username" required />
            </div>
            <div class="input-box">
              <div class="icon"><i class="bx bx-envelope"></i></div>
              <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-box">
              <div class="icon"><i class="bx bx-lock"></i></div>
              <input type="password" name="password" placeholder="Password" required />
            </div>
            <div class="remember-forgot">
              <label><input type="checkbox" /> I agree to the Terms & Conditions</label>
            </div>
            <button type="submit" name="register_btn" class="btn">Sign Up</button>
            <div class="login-register">Already have an account? <a href="#" class="login-link">Login</a></div>
            <div class="social-icons">
              <a href="#" title="Facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" title="Twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" title="Google"><i class="bx bxl-google"></i></a>
              <a href="#" title="GitHub"><i class="bx bxl-github"></i></a>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </section>
    <?php include("./footer/inc_footer_second.php") ?>
  </body>
  <script src="./scripts/dashboard.js"></script>
</html>
