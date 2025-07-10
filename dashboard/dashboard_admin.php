<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
// Statistik
$total_user_query = mysqli_query($conn, "SELECT * FROM alif_pengguna WHERE peran = 'pengguna'");
$total_user = mysqli_num_rows($total_user_query);
$total_lapangan_query = mysqli_query($conn, "SELECT * FROM alif_lapangan");
$total_lapangan = mysqli_num_rows($total_lapangan_query);
$total_booking_query = mysqli_query($conn, "SELECT * FROM alif_pemesanan");
$total_booking = mysqli_num_rows($total_booking_query);
$total_pembayaran_query = mysqli_query($conn, "SELECT SUM(total_bayar) AS total FROM alif_pembayaran WHERE status_pembayaran = 'sudah bayar'");
$total_pembayaran = mysqli_fetch_assoc($total_pembayaran_query)['total'] ?? 0;
$notif_bukti_query = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM alif_pembayaran WHERE status_pembayaran = 'belum bayar' AND bukti_transfer IS NOT NULL");
$notif_bukti = mysqli_fetch_assoc($notif_bukti_query)['jumlah'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" type="image/png" href="gambar/logo.png" />
  <style>
    body {
      font-family: 'Quicksand', Arial, sans-serif;
      background: linear-gradient(135deg, #e3f0ff 0%, #f8fdff 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }
    .glass-admin {
      background: rgba(255,255,255,0.85);
      box-shadow: 0 8px 32px 0 rgba(0,150,255,0.10);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-radius: 20px;
      border: 1px solid rgba(0,150,255,0.10);
    }
    .navbar {
      background: rgba(0, 150, 255, 0.10) !important;
      backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(0,150,255,0.10);
    }
    .navbar-brand {
      font-weight: bold;
      letter-spacing: 2px;
      font-size: 1.5rem;
      color: #0077c2 !important;
    }
    .dashboard-title {
      color: #0077c2;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 30px;
      margin-top: 30px;
      text-shadow: 0 2px 8px rgba(0,150,255,0.10);
    }
    .stat-card-admin {
      background: linear-gradient(120deg, #e3f0ff 60%, #f8fdff 100%);
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,150,255,0.08);
      padding: 30px 0 20px 0;
      margin-bottom: 20px;
      color: #01579b;
      transition: transform 0.2s, box-shadow 0.2s;
      animation: fadeInUp 1.1s;
    }
    .stat-card-admin:hover {
      transform: scale(1.04);
      box-shadow: 0 8px 32px rgba(0,150,255,0.13);
    }
    .stat-card-admin .icon {
      font-size: 2.5rem;
      color: #039be5;
      margin-bottom: 10px;
    }
    .btn-admin {
      background: linear-gradient(90deg, #039be5 60%, #81d4fa 100%);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 12px 32px;
      font-weight: 600;
      font-size: 1.1rem;
      box-shadow: 0 4px 16px rgba(3,155,229,0.15);
      transition: transform 0.2s, box-shadow 0.2s, background 0.3s, color 0.3s;
      backdrop-filter: blur(4px);
      opacity: 0.97;
      position: relative;
      overflow: hidden;
    }
    .btn-admin:hover, .btn-admin:focus {
      transform: translateY(-3px) scale(1.07);
      box-shadow: 0 8px 32px rgba(3,155,229,0.25);
      color: #fff;
      background: linear-gradient(90deg, #81d4fa 60%, #039be5 100%);
      opacity: 1;
      animation: pulseBtn 0.7s;
    }
    @keyframes pulseBtn {
      0% { box-shadow: 0 0 0 0 rgba(3,155,229,0.25); }
      70% { box-shadow: 0 0 0 12px rgba(3,155,229,0.08); }
      100% { box-shadow: 0 0 0 0 rgba(3,155,229,0.25); }
    }
    .btn-glass-admin {
      background: rgba(255,255,255,0.18) !important;
      color: #039be5 !important;
      border: 1.5px solid #81d4fa !important;
      border-radius: 30px;
      font-weight: 600;
      font-size: 1.05rem;
      box-shadow: 0 2px 12px rgba(3,155,229,0.10);
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
      backdrop-filter: blur(4px);
      opacity: 0.92;
    }
    .btn-glass-admin:hover, .btn-glass-admin:focus {
      background: rgba(3,155,229,0.12) !important;
      color: #fff !important;
      box-shadow: 0 6px 24px rgba(3,155,229,0.18);
      opacity: 1;
      animation: pulseBtn 0.7s;
    }
    .btn-danger {
      transition: transform 0.2s, box-shadow 0.2s;
      font-weight: 600;
      border-radius: 30px;
      opacity: 0.95;
    }
    .btn-danger:hover, .btn-danger:focus {
      transform: translateY(-2px) scale(1.05);
      box-shadow: 0 8px 32px rgba(220,53,69,0.18);
      opacity: 1;
      animation: pulseBtn 0.7s;
    }
    .notif-badge {
      position: absolute;
      top: 8px;
      right: 18px;
      font-size: 0.9rem;
      background: #dc3545;
      color: #fff;
      border-radius: 50%;
      padding: 4px 10px;
      font-weight: bold;
      box-shadow: 0 2px 8px rgba(220,53,69,0.15);
      z-index: 2;
    }
    .glass-nav-admin {
      background: rgba(255,255,255,0.10);
      border-radius: 16px;
      padding: 16px 24px;
      margin-bottom: 30px;
      box-shadow: 0 2px 12px rgba(0,150,255,0.08);
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .glass-nav-admin .btn {
      min-width: 160px;
      margin-bottom: 8px;
      margin-right: 8px;
    }
    .chart-card-admin {
      background: rgba(255,255,255,0.18);
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,150,255,0.08);
      padding: 24px 16px 16px 16px;
      margin-bottom: 30px;
      color: #01579b;
      animation: fadeInDown 1.1s;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    /* Loader overlay */
    #loader-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,150,255,0.10);
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
      border-color: #039be5 transparent transparent transparent;
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
<nav class="navbar navbar-expand-lg navbar-light glass-admin shadow-sm sticky-top mb-4">
  <div class="container">
    <a href="../index.php" class="btn btn-glass-admin me-3">← Kembali</a>
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active btn-glass-admin" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../admin/manajemen_pemesanan.php">Pemesanan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../admin/manajemen_user.php" class="btn btn-admin animate__animated animate__pulse animate__delay-1-5s">Manajemen User</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../lapangan/lapangan_admin.php">Lapangan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../kalender/kalender_admin.php">Kalender</a></li>
        <li class="nav-item"><a class="nav-link btn-danger" href="../auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <h2 class="dashboard-title animate__animated animate__fadeInDown">Dashboard Admin</h2>
  <div class="glass-nav-admin mb-4 animate__animated animate__fadeInUp">
    <a href="../admin/manajemen_pemesanan.php" class="btn btn-admin position-relative animate__animated animate__pulse animate__delay-1s">
      Kelola Pemesanan
      <?php if ($notif_bukti > 0): ?>
        <span class="notif-badge"><?= $notif_bukti ?></span>
      <?php endif; ?>
    </a>
    <a href="../lapangan/lapangan_admin.php" class="btn btn-admin animate__animated animate__pulse animate__delay-2s">Kelola Lapangan</a>
    <a href="../kalender/kalender_admin.php" class="btn btn-admin animate__animated animate__pulse animate__delay-3s">Kalender Booking</a>
    <a href="../auth/logout.php" class="btn btn-danger animate__animated animate__pulse animate__delay-4s">Logout</a>
  </div>
  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="stat-card-admin animate__animated animate__fadeInUp">
        <div class="icon">👤</div>
        <h5>Total Pengguna</h5>
        <p class="fs-4 fw-bold"><?= $total_user ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card-admin animate__animated animate__fadeInUp animate__delay-1s">
        <div class="icon">🏟️</div>
        <h5>Total Lapangan</h5>
        <p class="fs-4 fw-bold"><?= $total_lapangan ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card-admin animate__animated animate__fadeInUp animate__delay-2s">
        <div class="icon">📅</div>
        <h5>Total Booking</h5>
        <p class="fs-4 fw-bold"><?= $total_booking ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card-admin animate__animated animate__fadeInUp animate__delay-3s">
        <div class="icon">💸</div>
        <h5>Total Pembayaran</h5>
        <p class="fs-5 fw-bold">Rp<?= number_format($total_pembayaran, 0, ',', '.') ?></p>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-lg-8 mx-auto">
      <div class="chart-card-admin animate__animated animate__fadeInDown">
        <h5 class="mb-3">Grafik Jumlah Pengunjung Website</h5>
        <canvas id="visitorChart" height="120"></canvas>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Loader overlay logic
window.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    document.getElementById('loader-overlay').classList.add('hide');
  }, 900); // Loader duration
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
// Grafik pengunjung (dummy data)
const ctx = document.getElementById('visitorChart').getContext('2d');
const visitorChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    datasets: [{
      label: 'Pengunjung',
      data: [120, 150, 180, 200, 170, 220, 250],
      fill: true,
      backgroundColor: 'rgba(3,155,229,0.12)',
      borderColor: '#039be5',
      tension: 0.4,
      pointBackgroundColor: '#fff',
      pointBorderColor: '#039be5',
      pointRadius: 5,
      pointHoverRadius: 8
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: { enabled: true }
    },
    scales: {
      x: { grid: { display: false } },
      y: { grid: { color: 'rgba(3,155,229,0.08)' }, beginAtZero: true }
    }
  }
});
</script>
</body>
</html>
