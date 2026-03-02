<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/footer_header.css">
  <link rel="stylesheet" href="css/main.css">
  <title>Login Akun - Bernada.Id</title>
</head>
<body>
  <?php include('./header/inc_header_dashboard.php')?>
  <div class="container">
    <div class="gambarLeft">
      <img src="" alt="Gambar Left">
    </div>
    <div class="form-box login">
      <h2>Login</h2>
      <form action="auth_proses.php" method="POST">
        <div class="inputBox">
          <input type="email" name="email" placeholder="Email" required>
          <i class='bx bxs-envelope'></i>
        </div>
        <div class="inputBox">
          <input type="password" name="password" placeholder="Password" required>
          <i class='bx bxs-lock-alt' ></i>
        </div>
        <div class="rememberForgot">
          <div><input type="checkbox"> Remember Me</div>
          <a href="#">Forgot Password?</a>
        </div>
        <button type="submit" name="login_btn" class="btn_login">Login</button>
        <div class="login_register">Don't have an account? <a href="dashboard_register.php" class="register_link">Register</a></div>
      </form>
    </div>
  </div>
</body>
</html>