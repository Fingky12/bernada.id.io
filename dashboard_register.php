<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="./css/footer_header.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>Registrasi Akun - Bernada.Id</title>
</head>
<body>
  <?php include('./header/inc_header_dashboard.php')?>
  <div class="container">
    <div class="gambarLeft">
      <img src="" alt="Gambar Left">
    </div>
    <div class="form-box register">
      <h2>Register</h2>
      <form action="auth_proses.php" method="POST">
        <div class="inputBox">
          <input type="text" name="username" placeholder="Username" required>
          <i class='bx bxs-user' ></i>
        </div>
        <div class="inputBox">
          <input type="email" name="email" placeholder="Email" required>
          <i class='bx bxs-envelope'></i>
        </div>
        <div class="inputBox">
          <input type="password" name="password" placeholder="Password" required>
          <i class='bx bxs-lock-alt' ></i>
        </div>
        <div class="rememberForgot">
          <div><input type="checkbox"> I agree to the terms & conditions</div>
        </div>
        <button type="submit" name="register_btn" class="btn_register">Register</button>
        <div class="login_register">Already have an account? <a href="dashboard_login.php" class="login-link">Login</a></div>
      </form>
    </div>
  </div>
</body>
</html>