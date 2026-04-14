  
  <header>
    <a href="halaman.php" class="logo">BERNADA<span>.ID</span></a>
    <nav class="navbar">
      <a href="halaman.php#beranda">Beranda</a>
      <a href="halaman.php#fitur">Fitur</a>
      <a href="halaman.php#tema">Tema</a> 
      <a href="halaman.php#harga">Harga</a>
      <a href="halaman.php#contact">Contact</a>
      <a href="halaman.php#tentang">Tentang</a>
    </nav>
    <?php if (isset($_SESSION['name'])): ?>
    <div class="user-auth">
      <div class="profile-box">
        <div class="avatar-circle"><?= $_SESSION['name']; ?><i class='bx bx-user'></i></div>
          <!-- <img src="./img/avatar.png" alt="User Avatar" class="avatar-img" /> -->
          <div class="dropdown-content">
            <a href="#">Profile</a>
            <a href="#">Settings</a>
            <a href="logout.php">Logout</a>
          </div>
      </div>
    </div>
    <?php else: ?>
    <div class="nav-btn">
      <a href="dashboard.php">
        <button class="btn-login btn">Masuk</button>
      </a>
    </div>
    <?php endif; ?>
  </header>
    