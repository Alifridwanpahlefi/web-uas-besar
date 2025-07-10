<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'pengguna') {
    header("Location: ../auth/login.php");
    exit();
}
$id_pengguna = $_SESSION['id_pengguna'];
$nama = $_SESSION['nama_pengguna'];
$total_booking_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM alif_pemesanan WHERE id_pengguna = '$id_pengguna'");
$total_booking = mysqli_fetch_assoc($total_booking_query)['total'] ?? 0;
$total_pembayaran_query = mysqli_query($conn, "SELECT SUM(total_bayar) AS total FROM alif_pembayaran WHERE id_pemesanan IN (SELECT id_pemesanan FROM alif_pemesanan WHERE id_pengguna = '$id_pengguna') AND status_pembayaran = 'sudah bayar'");
$total_pembayaran = mysqli_fetch_assoc($total_pembayaran_query)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Pengguna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" type="image/png" href="../gambar/logo.png" />
  <style>
    body {
      font-family: 'Quicksand', Arial, sans-serif;
      background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }
    .glass-user {
      background: rgba(255,255,255,0.7);
      box-shadow: 0 8px 32px 0 rgba(0,150,136,0.13);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      border-radius: 18px;
      border: 1px solid rgba(0,150,136,0.10);
    }
    .navbar {
      background: rgba(0, 200, 83, 0.7) !important;
      backdrop-filter: blur(6px);
      border-bottom: 1px solid rgba(0,150,136,0.10);
    }
    .navbar-brand {
      font-weight: bold;
      letter-spacing: 2px;
      font-size: 1.4rem;
      color: #009688 !important;
    }
    .dashboard-title {
      color: #009688;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 28px;
      margin-top: 28px;
      text-shadow: 0 2px 8px rgba(0,150,136,0.10);
    }
    .stat-card-user {
      background: linear-gradient(120deg, #b2dfdb 60%, #e0f2f1 100%);
      border-radius: 14px;
      box-shadow: 0 2px 12px rgba(0,150,136,0.08);
      padding: 24px 0 16px 0;
      margin-bottom: 18px;
      color: #00695c;
      transition: transform 0.2s, box-shadow 0.2s;
      animation: fadeInUp 1.1s;
    }
    .stat-card-user:hover {
      transform: scale(1.03);
      box-shadow: 0 8px 32px rgba(0,200,83,0.13);
    }
    .stat-card-user .icon {
      font-size: 2.2rem;
      color: #26a69a;
      margin-bottom: 8px;
    }
    .btn-user {
      background: linear-gradient(90deg, #26a69a 60%, #80cbc4 100%);
      color: #fff;
      border: none;
      border-radius: 28px;
      padding: 10px 28px;
      font-weight: 600;
      font-size: 1.05rem;
      box-shadow: 0 4px 16px rgba(0,150,136,0.10);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-user:hover {
      transform: translateY(-2px) scale(1.04);
      box-shadow: 0 8px 32px rgba(0,200,83,0.18);
      color: #fff;
    }
    .glass-nav-user {
      background: rgba(255,255,255,0.18);
      border-radius: 14px;
      padding: 14px 20px;
      margin-bottom: 28px;
      box-shadow: 0 2px 12px rgba(0,150,136,0.08);
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .glass-nav-user .btn {
      min-width: 140px;
      margin-bottom: 6px;
    }
    .chart-card-user {
      background: rgba(255,255,255,0.18);
      border-radius: 14px;
      box-shadow: 0 2px 12px rgba(0,150,136,0.08);
      padding: 18px 12px 12px 12px;
      margin-bottom: 24px;
      color: #00695c;
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
      background: rgba(0,200,83,0.13);
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
      width: 70px;
      height: 70px;
    }
    .lds-ring div {
      box-sizing: border-box;
      display: block;
      position: absolute;
      width: 54px;
      height: 54px;
      margin: 8px;
      border: 7px solid #26a69a;
      border-radius: 50%;
      animation: lds-ring 1.2s cubic-bezier(0.5,0,0.5,1) infinite;
      border-color: #26a69a transparent transparent transparent;
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
<nav class="navbar navbar-expand-lg navbar-light glass-user shadow-sm sticky-top mb-4">
  <div class="container">
    <a href="../index.php" class="btn btn-outline-secondary me-3">← Kembali</a>
    <a class="navbar-brand" href="#">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="../booking/booking_form.php">Booking</a></li>
        <li class="nav-item"><a class="nav-link" href="../booking/riwayat_user.php">Riwayat</a></li>
        <li class="nav-item"><a class="nav-link" href="../kalender/kalender_user.php">Kalender</a></li>
        <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <h2 class="dashboard-title animate__animated animate__fadeInDown">Selamat datang, <?= htmlspecialchars($nama) ?></h2>
  <div class="glass-nav-user mb-4 animate__animated animate__fadeInUp">
    <a href="../booking/booking_form.php" class="btn btn-user">Pesan Lapangan</a>
    <a href="../booking/riwayat_user.php" class="btn btn-user">Riwayat Booking</a>
    <a href="../kalender/kalender_user.php" class="btn btn-user">Lihat Kalender</a>
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
  </div>
  <div class="row text-center mb-4">
    <div class="col-md-4">
      <div class="stat-card-user animate__animated animate__fadeInUp">
        <div class="icon">📅</div>
        <h5>Total Booking Saya</h5>
        <p class="fs-4 fw-bold"><?= $total_booking ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card-user animate__animated animate__fadeInUp animate__delay-1s">
        <div class="icon">💸</div>
        <h5>Total Pembayaran</h5>
        <p class="fs-5 fw-bold">Rp<?= number_format($total_pembayaran, 0, ',', '.') ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card-user animate__animated animate__fadeInUp animate__delay-2s">
        <div class="icon">🏟️</div>
        <h5>Booking Aktif</h5>
        <p class="fs-4 fw-bold">
          <?php
          $aktif_query = mysqli_query($conn, "SELECT COUNT(*) AS aktif FROM alif_pemesanan WHERE id_pengguna = '$id_pengguna' AND (status_booking = 'menunggu' OR status_booking = 'diterima') AND (tanggal_pesan > CURDATE() OR (tanggal_pesan = CURDATE() AND jam_selesai >= CURTIME()))");
          $aktif = mysqli_fetch_assoc($aktif_query)['aktif'] ?? 0;
          echo $aktif;
          ?>
        </p>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-lg-8 mx-auto">
      <div class="chart-card-user animate__animated animate__fadeInDown">
        <h5 class="mb-3">Grafik Booking Mingguan</h5>
        <canvas id="userChart" height="110"></canvas>
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
  }, 700); // Loader duration
});
const links = document.querySelectorAll('a[href]:not([target])');
links.forEach(link => {
  link.addEventListener('click', function(e) {
    const href = link.getAttribute('href');
    if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
      document.getElementById('loader-overlay').classList.remove('hide');
    }
  });
});
const ctx = document.getElementById('userChart').getContext('2d');
const userChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    datasets: [{
      label: 'Booking',
      data: [2, 1, 3, 2, 4, 1, 2],
      backgroundColor: 'rgba(38,166,154,0.5)',
      borderColor: '#26a69a',
      borderWidth: 2,
      borderRadius: 8,
      maxBarThickness: 32
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
      y: { grid: { color: 'rgba(38,166,154,0.08)' }, beginAtZero: true }
    }
  }
});
</script>
</body>
</html>
