  
  <header>
    <a href="halaman.php" class="logo">BERNADA<span>.ID</span></a>
    <nav class="navbar">
      <a href="halaman.php#beranda">Beranda</a>
      <a href="halaman.php#fitur">Fitur</a>
      <a href="halaman.php#tema">Tema</a>
      <a href="halaman.php#harga">Harga</a>
      <a href="contact.php">Hubungi Kami</a>
      <a href="tentang.php">Tentang Kami</a>
      <?php if (isset($_SESSION['name'])): ?>
      <a href="dashboard_customer.php">Dashboard Saya</a>
      <?php endif; ?>
    </nav>
    <?php if (isset($_SESSION['name'])): ?>
    <div class="user-auth">
      <div class="profile-box">
        <div class="avatar-circle"><?= $_SESSION['name']; ?><i class='bx bx-user'></i></div>
          <div class="dropdown-content">
            <a href="#">Akun</a>
            <a href="#">Pengaturan</a>
            <a href="logout.php">Keluar</a>
          </div>
      </div>
    </div>
    <?php else: ?>
    <div class="nav-btn">
      <a href="login_register.php">
        <button class="btn-login">Masuk</button>
      </a>
      <a href="admin/admin_login.php" target="_blank">
        <button class="btn-login">Admin</button>
      </a>
    </div>
    <?php endif; ?>
  </header>