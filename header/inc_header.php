
  <header>
    <a href="halaman.php" class="logo">BERNADA<span>.ID</span></a>
    <nav class="navbar">
      <a href="#beranda">Beranda</a>
      <a href="#fitur">Fitur</a>
      <a href="#tema">Tema</a>
      <a href="#harga">Harga</a>
      <a href="#contact">Kontak</a>
      <a href="#tentang">Tentang</a>
    </nav>

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
      <a href="dashboard.php">
        <button class="btn-login">Masuk</button>
      </a>
    </div>
    <?php endif; ?>
  </header>
