
  <header>
    <a href="halaman.php" class="logo">BERNADA<span>.ID</span></a>
    <nav class="navbar">
      <a href="#beranda">Beranda</a>
      <a href="#fitur">Fitur</a>
      <a href="#tema">Tema</a>
      <a href="#harga">Harga</a>
      <a href="contact.php">Hubungi Kami</a>
      <a href="tentang.php">Tentang Kami</a>
      <?php if (isset($_SESSION['name'])): ?>
      <a href="dashboard_customer.php">Dashboard Saya</a>
      <?php endif; ?>
    </nav>

    <div class="sidebar-container">
      <div class="side-btn menu">
        <i class='bx bx-menu'></i>
      </div>
      <nav class="menu-sidebar">
        <div class="side-btn close">
          <i class='bx bx-x'></i>
        </div>
        <div class="logo">BERNADA<span>.ID</span></div>
        <ul>
          <li><a href="#beranda">Beranda</a></li>
          <li><a href="#fitur">Fitur</a></li>
          <li><a href="#tema">Tema</a></li>
          <li><a href="#harga">Harga</a></li>
          <li><a href="contact.php">Hubungi Kami</a></li>
          <li><a href="tentang.php">Tentang Kami</a></li>
          <?php if (isset($_SESSION['name'])): ?>
          <li><a href="dashboard_customer.php"><i class='bx bx-bar-chart'></i> Dashboard Saya</a></li>
          <?php endif; ?>
        </ul>
        <div class="footer-sidebar">
          <p>All rights reserved &copy; 2024</p>
        </div>
      </nav>
    </div>

    <?php if (isset($_SESSION['name'])): ?>
    <div class="user-auth">                                                         
      <div class="profile-box">
        <div class="avatar-circle"><?= $_SESSION['name']; ?><i class='bx bx-user'></i></div>
          <!-- <img src="./img/avatar.png" alt="User Avatar" class="avatar-img" /> -->
          <div class="dropdown-content">
            <a href="#">Profile</a>
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
