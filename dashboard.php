<?php
session_start();

$alerts = $_SESSION['alerts'] ?? [];
$active_form = $_SESSION['active_form'] ?? 'login';
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
    <title>Dashboard Login | BERNADA.ID</title>
  </head>
  <body>
    <div>
      <a href="halaman.php" class="logo">BERNADA<span>.ID</span></a>
    </div>

    <div class="alert-box">
      <div class="alert-success">
        <i class='bx bx-check' ></i>
        <span>Registrasi Berhasil!!</span>
      </div>
    </div>

    <div class="container">
      <div class="img-dashboard">
        <img src="./img/gambar_dashboard.png" alt="Dashboard" />
      </div>

      <div class="wrapper">
        <div class="form login" id="login-form"> <!--  -->
          <h2>Sign In Account</h2>
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
            <div class="login-register">
              <span>Don't have an account? <a href="#" class="register-link">Register</a></span>
            </div>
            <div class="social-icons">
              <a href="#" title="Facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" title="Twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" title="Google"><i class="bx bxl-google"></i></a>
              <a href="#" title="GitHub"><i class="bx bxl-github"></i></a>
            </form>
          </div>
        </div>

        <div class="form register" id="register-form">  <!--  -->
          <h2>Sign Up Account</h2>
          <form action="auth_proses.php" method="post">
            <div class="input-box">
              <div class="icon"><i class="bx bx-envelope"></i></div>
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
            <div class="login-register">
              <span>Already have an account? <a href="#" class="login-link">Login</a></span>
            </div>
            <div class="social-icons">
              <a href="#" title="Facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" title="Twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" title="Google"><i class="bx bxl-google"></i></a>
              <a href="#" title="GitHub"><i class="bx bxl-github"></i></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
  <script src="dashboard.js"></script>
</html>
