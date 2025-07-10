<?php
session_start();
include 'config/koneksi.php';
$query_galeri = mysqli_query($conn, "SELECT * FROM alif_galeri");
$is_logged_in = isset($_SESSION['peran']);
$nama_user = $is_logged_in ? $_SESSION['nama_pengguna'] : '';
$foto_user = isset($_SESSION['foto']) && $_SESSION['foto'] ? $_SESSION['foto'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Zaa Futsal - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="gambar/logo.png" />
  <style>
    body {
      font-family: 'Quicksand', Arial, sans-serif;
      background: linear-gradient(135deg, #e0ecff 0%, #c9e4ff 50%, #e0c3fc 100%);
      min-height: 100vh;
      overflow-x: hidden;
      transition: background 0.3s ease, color 0.3s ease;
    }
    /* DARK MODE STYLES */
    body.darkmode {
      background: linear-gradient(135deg, #232946 0%, #1a1a2e 50%, #2d3250 100%) !important;
      color: #f4f4f4 !important;
    }
    body.darkmode .glass {
      background: rgba(30,34,60,0.85) !important;
      box-shadow: 0 8px 32px 0 rgba(30,34,60,0.18);
      border: 1px solid rgba(30,34,60,0.18);
      color: #f4f4f4 !important;
    }
    body.darkmode .navbar {
      background: rgba(30,34,60,0.18) !important;
      border-bottom: 1px solid rgba(30,34,60,0.18);
    }
    body.darkmode .navbar-brand,
    body.darkmode .navbar-nav .nav-link,
    body.darkmode .navbar-nav .nav-link.active,
    body.darkmode .navbar-nav .dropdown-toggle {
      color: #a5d7ff !important;
    }
    body.darkmode .navbar-nav .nav-link,
    body.darkmode .navbar-nav .dropdown-toggle {
      background: rgba(30,34,60,0.18) !important;
      border: 1.5px solid #3a6ea5 !important;
    }
    body.darkmode .navbar-nav .nav-link.active,
    body.darkmode .navbar-nav .nav-link:hover,
    body.darkmode .navbar-nav .nav-link:focus,
    body.darkmode .navbar-nav .dropdown-toggle:hover,
    body.darkmode .navbar-nav .dropdown-toggle:focus {
      background: rgba(90,120,200,0.18) !important;
      color: #f4f4f4 !important;
      box-shadow: 0 6px 24px rgba(90,120,200,0.18);
    }
    body.darkmode .navbar-nav .dropdown-menu {
      background: #232946 !important;
      color: #f4f4f4 !important;
    }
    body.darkmode .navbar-nav .dropdown-item {
      color: #a5d7ff !important;
    }
    body.darkmode .navbar-nav .dropdown-item:hover,
    body.darkmode .navbar-nav .dropdown-item:focus {
      background: #232946 !important;
      color: #fff !important;
    }
    body.darkmode .stat-card,
    body.darkmode .testimoni-card {
      background: rgba(30,34,60,0.22) !important;
      color: #f4f4f4 !important;
    }
    body.darkmode .galeri-img {
      filter: brightness(0.85) saturate(1.1) grayscale(0.08);
      box-shadow: 0 4px 24px rgba(90,120,200,0.10);
    }
    body.darkmode .btn-modern {
      background: linear-gradient(90deg, #3a6ea5 60%, #232946 100%) !important;
      color: #fff !important;
      box-shadow: 0 4px 16px rgba(58,110,165,0.15);
    }
    body.darkmode .btn-modern:hover {
      box-shadow: 0 8px 32px rgba(58,110,165,0.25);
      color: #fff !important;
    }
    body.darkmode .btn-outline-light {
      border-color: #a5d7ff !important;
      color: #a5d7ff !important;
      background: rgba(30,34,60,0.7) !important;
    }
    body.darkmode .btn-outline-light:hover,
    body.darkmode .btn-outline-light:focus {
      background: #3a6ea5 !important;
      color: #fff !important;
      border-color: #a5d7ff !important;
    }
    body.darkmode .testimoni-card .blockquote-footer {
      color: #a5d7ff !important;
    }
    body.darkmode footer {
      color: #a5d7ff !important;
      border-top: 2px solid #3a6ea5 !important;
      background: transparent !important;
    }
    body.darkmode .container h3,
    body.darkmode .container h4,
    body.darkmode .container p,
    body.darkmode .container span,
    body.darkmode .container a,
    body.darkmode .container {
      color: #f4f4f4 !important;
    }
    
    /* Additional dark mode text fixes */
    body.darkmode h1, body.darkmode h2, body.darkmode h3, 
    body.darkmode h4, body.darkmode h5, body.darkmode h6 {
      color: #f4f4f4 !important;
    }
    body.darkmode p, body.darkmode span, body.darkmode div {
      color: #f4f4f4 !important;
    }
    body.darkmode .text-center {
      color: #f4f4f4 !important;
    }
    body.darkmode .btn,
    body.darkmode .btn-sm {
      color: #fff !important;
    }
    body.darkmode .btn.btn-light {
      background: #232946 !important;
      color: #a5d7ff !important;
      border: 1px solid #3a6ea5 !important;
    }
    body.darkmode .btn.btn-light:hover {
      background: #3a6ea5 !important;
      color: #fff !important;
    }
    body.darkmode #loader-overlay {
      background: rgba(30,30,60,0.85) !important;
    }
    
    /* Theme toggle button styles */
    #theme-toggle {
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border: 1px solid rgba(0,0,0,0.1);
    }
    #theme-toggle:hover {
      transform: scale(1.1);
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }
    #theme-toggle i {
      transition: transform 0.3s ease;
    }
    #theme-toggle:hover i {
      transform: rotate(15deg);
    }
    body.darkmode #theme-toggle {
      background: #232946 !important;
      color: #a5d7ff !important;
      border: 1px solid #3a6ea5 !important;
    }
    body.darkmode #theme-toggle:hover {
      background: #3a6ea5 !important;
      color: #fff !important;
    }
    .glass {
      background: rgba(255,255,255,0.80);
      box-shadow: 0 8px 32px 0 rgba(0,150,255,0.10);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-radius: 20px;
      border: 1px solid rgba(0,150,255,0.10);
      transition: background 0.3s ease, box-shadow 0.3s ease, border 0.3s ease, color 0.3s ease;
    }
    .navbar {
      background: rgba(0, 150, 255, 0.10) !important;
      backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(0,150,255,0.10);
      transition: background 0.3s ease, border 0.3s ease;
    }
    .navbar-brand {
      font-weight: bold;
      letter-spacing: 2px;
      font-size: 1.5rem;
      color: #0077c2 !important;
    }
    .navbar-nav .nav-link, .navbar-nav .nav-link.active {
      color: #039be5 !important;
      font-weight: 600;
      border: 1.5px solid #81d4fa;
      border-radius: 12px;
      margin-right: 8px;
      padding: 6px 22px;
      background: rgba(255,255,255,0.18);
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 12px rgba(3,155,229,0.10);
      opacity: 0.92;
      display: inline-block;
      height: 40px;
      line-height: 28px;
      vertical-align: middle;
    }
    .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover, .navbar-nav .nav-link:focus {
      background: rgba(3,155,229,0.12);
      color: #0077c2 !important;
      box-shadow: 0 6px 24px rgba(3,155,229,0.18);
      opacity: 1;
    }
    .navbar-nav .dropdown-toggle {
      padding-right: 32px !important;
      padding-left: 22px !important;
      height: 40px;
      line-height: 28px;
      border-radius: 12px;
      border: 1.5px solid #81d4fa;
      background: rgba(255,255,255,0.18);
      color: #039be5 !important;
      font-weight: 600;
      box-shadow: 0 2px 12px rgba(3,155,229,0.10);
      opacity: 0.92;
      margin-right: 8px;
      display: inline-block;
      vertical-align: middle;
    }
    .navbar-nav .dropdown-toggle:hover, .navbar-nav .dropdown-toggle:focus {
      background: rgba(3,155,229,0.12);
      color: #0077c2 !important;
      box-shadow: 0 6px 24px rgba(3,155,229,0.18);
      opacity: 1;
    }
    .navbar-nav .dropdown-menu {
      border-radius: 12px;
      min-width: 180px;
      margin-top: 8px;
    }
    .navbar-nav .dropdown-item {
      font-weight: 500;
      color: #0077c2;
      border-radius: 8px;
      padding: 8px 18px;
    }
    .navbar-nav .dropdown-item:hover, .navbar-nav .dropdown-item:focus {
      background: #e0ecff;
      color: #039be5;
    }
    .navbar-nav .nav-link.btn, .navbar-nav .nav-link.btn-outline-warning, .navbar-nav .nav-link.btn-outline-primary {
      padding: 6px 22px !important;
      height: 40px;
      line-height: 28px;
      border-radius: 12px;
      margin-right: 8px;
      font-weight: 600;
      vertical-align: middle;
      display: inline-block;
    }
    .navbar-nav .nav-link.btn img, .navbar-nav .dropdown-toggle img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 6px;
      vertical-align: middle;
    }
    .hero {
      padding: 120px 0 80px 0;
      background: url('gambar/futsal.png') no-repeat center center;
      background-size: cover;
      color: #222;
      position: relative;
      overflow: hidden;
    }
    .hero .glass {
      padding: 40px 30px;
      animation: fadeInDown 1.2s;
      color: #222;
    }
    .hero .glass h1, .hero .glass .display-4 {
      color: #0077c2;
      font-weight: 700;
    }
    .hero .glass p.lead {
      color: #222;
      font-weight: 500;
    }
    .hero .btn-outline-light {
      border-color: #81d4fa !important;
      color: #039be5 !important;
      background: rgba(255,255,255,0.7) !important;
      font-weight: 600;
    }
    .hero .btn-outline-light:hover, .hero .btn-outline-light:focus {
      background: #81d4fa !important;
      color: #fff !important;
      border-color: #039be5 !important;
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .btn-modern {
      background: linear-gradient(90deg, #0d6efd 60%, #6ea8fe 100%);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 12px 32px;
      font-weight: 600;
      font-size: 1.1rem;
      box-shadow: 0 4px 16px rgba(13,110,253,0.15);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-modern:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 8px 32px rgba(13,110,253,0.25);
      color: #fff;
    }
    .stat-card {
      background: rgba(255,255,255,0.12);
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      padding: 30px 0 20px 0;
      margin-bottom: 20px;
      color: #222;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
      transform: scale(1.04);
      box-shadow: 0 8px 32px rgba(13,110,253,0.18);
    }
    .galeri-img {
      height: 220px;
      object-fit: cover;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(13,110,253,0.10);
      transition: transform 0.4s cubic-bezier(.4,2,.6,1), box-shadow 0.3s;
      filter: brightness(0.95) saturate(1.1);
    }
    .galeri-img:hover {
      transform: scale(1.07) rotate(-2deg);
      box-shadow: 0 12px 40px rgba(13,110,253,0.25);
      filter: brightness(1.05) saturate(1.2);
    }
    .testimoni-card {
      background: rgba(255,255,255,0.18);
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      padding: 30px 20px 20px 20px;
      margin-bottom: 20px;
      color: #232526;
      position: relative;
      overflow: hidden;
      animation: fadeInUp 1.2s;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .testimoni-card blockquote {
      font-size: 1.1rem;
      font-style: italic;
      margin-bottom: 10px;
    }
    .testimoni-card .blockquote-footer {
      color: #0d6efd;
      font-weight: 600;
    }
    footer {
      background: transparent;
      color: #0077c2;
      padding: 18px 0 8px 0;
      text-align: center;
      margin-top: 60px;
      font-size: 0.98rem;
      letter-spacing: 1px;
      border-top: 2px solid #c9e4ff;
    }
    /* Loader overlay */
    #loader-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(30,30,60,0.7);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity 0.4s;
      opacity: 1;
      pointer-events: all;
    }
    #loader-overlay.hide {
      opacity: 0;
      pointer-events: none;
    }
    .lds-ring {
      display: inline-block;
      position: relative;
      width: 80px;
      height: 80px;
    }
    .lds-ring div {
      box-sizing: border-box;
      display: block;
      position: absolute;
      width: 64px;
      height: 64px;
      margin: 8px;
      border: 8px solid #fff;
      border-radius: 50%;
      animation: lds-ring 1.2s cubic-bezier(0.5,0,0.5,1) infinite;
      border-color: #0d6efd transparent transparent transparent;
    }
    .lds-ring div:nth-child(1) { animation-delay: -0.45s; }
    .lds-ring div:nth-child(2) { animation-delay: -0.3s; }
    .lds-ring div:nth-child(3) { animation-delay: -0.15s; }
    @keyframes lds-ring {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
<!-- Loader overlay -->
<div id="loader-overlay">
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
</div>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark glass shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">Zaa Futsal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <!-- Dark/Light Mode Toggle Button -->
        <li class="nav-item d-flex align-items-center me-2">
          <button id="theme-toggle" class="btn btn-light border-0 px-3 py-2" style="border-radius:50%;font-size:1.2rem;line-height:1;transition:all 0.3s ease;" title="Toggle dark/light mode">
            <i id="theme-toggle-icon" class="bi bi-moon-fill"></i>
          </button>
        </li>
        <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="kalender/kalender_user.php">Jadwal</a></li>
        <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li>
        <?php if ($is_logged_in): ?>
          <li class="nav-item">
            <?php if ($_SESSION['peran'] === 'admin'): ?>
              <a class="nav-link btn btn-outline-warning me-2 px-3" href="dashboard/dashboard_admin.php">Dashboard</a>
            <?php else: ?>
              <a class="nav-link btn btn-outline-primary me-2 px-3" href="dashboard/dashboard_user.php">Dashboard</a>
            <?php endif; ?>
          </li>
        <?php endif; ?>
        <?php if (!$is_logged_in): ?>
          <li class="nav-item">
            <a class="nav-link" href="auth/login.php">
              <i class="bi bi-person-circle"></i> Login
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php if ($foto_user): ?>
                <img src="<?= htmlspecialchars($foto_user) ?>" alt="Akun" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
              <?php else: ?>
                <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
              <?php endif; ?>
              <span><?= htmlspecialchars($nama_user) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="profile.php">Edit Profile</a></li>
              <li><a class="dropdown-item" href="auth/logout.php">Logout</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<!-- Hero Section -->
<section class="hero text-center">
  <div class="container">
    <div class="glass mx-auto" style="max-width: 600px;">
      <h1 class="display-4 fw-bold mb-3">Welcome To Zaa Futsal!</h1>
      <p class="lead mb-4">Tempat Futsal? Zaa Futsal Aja!</p>
      <a href="auth/login.php" class="btn btn-modern btn-lg me-2">Mulai Booking</a>
      <a href="#galeri" class="btn btn-outline-light btn-lg">Lihat Galeri</a>
    </div>
  </div>
</section>
<!-- Statistik -->
<div class="container mt-5">
  <h3 class="text-center mb-4" style="color:#222;">📊 Zaa Futsal Statistik</h3>
  <div class="row text-center">
    <div class="col-md-4">
      <div class="stat-card">
        <h4>120+</h4>
        <p>Pengguna Terdaftar</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <h4>300+</h4>
        <p>Pemesanan Sukses</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <h4>5</h4>
        <p>Lapangan Tersedia</p>
      </div>
    </div>
  </div>
</div>
<!-- Galeri Lapangan -->
<div class="container mt-5" id="galeri">
  <h3 class="text-center mb-4" style="color:#222;">🏟️ Galeri Lapangan</h3>
  <div class="row">
    <?php while ($row = mysqli_fetch_assoc($query_galeri)) : ?>
      <div class="col-md-4 mb-4">
        <div class="glass p-2 h-100 d-flex align-items-center justify-content-center animate__animated animate__fadeInUp">
          <img src="<?= $row['gambar_url'] ?>" class="w-100 galeri-img" alt="Lapangan">
        </div>
      </div>
    <?php endwhile; ?>
  </div>
  <div class="text-center mt-3">
    <a href="auth/login.php" class="btn btn-modern">Booking Sekarang</a>
  </div>
</div>
<!-- Testimoni -->
<div class="container mt-5" id="testimoni">
  <h3 class="text-center mb-4" style="color:#222;">💬 Testimoni Pengguna</h3>
  <div class="row text-center">
    <div class="col-md-4">
      <div class="testimoni-card animate__animated animate__fadeInUp">
        <blockquote class="blockquote">
          <p>"Sistemnya gampang dipakai dan sangat membantu!"</p>
          <footer class="blockquote-footer">Rian</footer>
        </blockquote>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimoni-card animate__animated animate__fadeInUp animate__delay-1s">
        <blockquote class="blockquote">
          <p>"Booking lapangan sekarang jadi cepat banget, top!"</p>
          <footer class="blockquote-footer">Lina</footer>
        </blockquote>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimoni-card animate__animated animate__fadeInUp animate__delay-2s">
        <blockquote class="blockquote">
          <p>"Lapangannya Luas, Harga Terjangkau"</p>
          <footer class="blockquote-footer">Bayu</footer>
        </blockquote>
      </div>
    </div>
  </div>
</div>
<!-- Kontak WhatsApp & Instagram -->
<div class="text-center my-4">
  <span style="font-size:1.08rem;color:#222;font-weight:500;">Jika ada terkait pesanan, kritik dan saran, Hubungi:
  <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
    <a href="http://wa.me/6285658015864" target="_blank" class="btn btn-sm d-flex align-items-center gap-2" style="background:#25d366;color:#fff;font-weight:600;border-radius:20px;padding:8px 18px 8px 14px;box-shadow:0 2px 8px rgba(37,211,102,0.10);text-decoration:none;">
      <i class="bi bi-whatsapp" style="font-size:1.3rem;"></i> WhatsApp
    </a>
    <a href="https://www.instagram.com/liflyz._/" target="_blank" class="btn btn-sm d-flex align-items-center gap-2" style="background:linear-gradient(45deg,#fd5949,#d6249f,#285AEB);color:#fff;font-weight:600;border-radius:20px;padding:8px 18px 8px 14px;box-shadow:0 2px 8px rgba(220,53,69,0.10);text-decoration:none;">
      <i class="bi bi-instagram" style="font-size:1.3rem;"></i> Instagram
    </a>
  </div>
</div>
<!-- Footer minimalis -->
<footer>
  <div class="container">
    <p class="mb-0" style="text-align:center;">&copy; 2025 <strong>Alif Ridwan Pahlefi</strong>. All rights reserved.</p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Optional: Animate.css for extra animation classes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
// Loader overlay logic
function hideLoader() {
  var loader = document.getElementById('loader-overlay');
  if (loader) loader.classList.add('hide');
}
// DARK/LIGHT MODE TOGGLE LOGIC
function setTheme(mode) {
  if (mode === 'dark') {
    document.body.classList.add('darkmode');
    document.getElementById('theme-toggle-icon').className = 'bi bi-sun-fill';
  } else {
    document.body.classList.remove('darkmode');
    document.getElementById('theme-toggle-icon').className = 'bi bi-moon-fill';
  }
}

function toggleTheme() {
  const body = document.body;
  const isDark = body.classList.contains('darkmode');
  
  if (isDark) {
    body.classList.remove('darkmode');
    localStorage.setItem('theme', 'light');
    setTheme('light');
  } else {
    body.classList.add('darkmode');
    localStorage.setItem('theme', 'dark');
    setTheme('dark');
  }
}

window.addEventListener('DOMContentLoaded', function() {
  // Loader
  setTimeout(hideLoader, 900);
  
  // THEME INIT
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    document.body.classList.add('darkmode');
    document.getElementById('theme-toggle-icon').className = 'bi bi-sun-fill';
  } else {
    document.body.classList.remove('darkmode');
    document.getElementById('theme-toggle-icon').className = 'bi bi-moon-fill';
  }
  
  // Toggle event
  const themeToggle = document.getElementById('theme-toggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', toggleTheme);
  }
});
window.addEventListener('load', function() {
  setTimeout(hideLoader, 900);
});
// Animasi loading saat pindah halaman
const links = document.querySelectorAll('a[href]:not([target])');
links.forEach(link => {
  link.addEventListener('click', function(e) {
    const href = link.getAttribute('href');
    if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
      document.getElementById('loader-overlay').classList.remove('hide');
    }
  });
});
// Fallback: pastikan loader hilang setelah 3 detik apapun yang terjadi
setTimeout(hideLoader, 3000);
</script>
<!-- Tambahkan Bootstrap Icons jika belum ada -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
