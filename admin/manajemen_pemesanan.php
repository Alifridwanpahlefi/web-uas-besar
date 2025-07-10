<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
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
$query = mysqli_query($conn, "SELECT p.*, u.nama_pengguna, l.nama_lapangan, bayar.status_pembayaran FROM alif_pemesanan p JOIN alif_pengguna u ON p.id_pengguna = u.id_pengguna JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan LEFT JOIN alif_pembayaran bayar ON p.id_pemesanan = bayar.id_pemesanan ORDER BY p.tanggal_pesan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Pemesanan - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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
      margin-bottom: 10px;
    }
    .btn-admin:hover, .btn-admin:focus {
      transform: translateY(-3px) scale(1.07);
      box-shadow: 0 8px 32px rgba(3,155,229,0.25);
      color: #fff;
      background: linear-gradient(90deg, #81d4fa 60%, #039be5 100%);
      opacity: 1;
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
    }
    .stat-card-admin {
      background: linear-gradient(120deg, #e3f0ff 60%, #f8fdff 100%);
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,150,255,0.08);
      padding: 30px 0 20px 0;
      margin-bottom: 20px;
      color: #01579b;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card-admin .icon {
      font-size: 2.5rem;
      color: #039be5;
      margin-bottom: 10px;
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
    .glass-content-admin {
      background: rgba(255,255,255,0.85);
      border-radius: 20px;
      box-shadow: 0 8px 32px 0 rgba(0,150,255,0.10);
      padding: 32px 24px;
      margin-top: 24px;
      margin-bottom: 32px;
    }
    .table {
      background: rgba(255,255,255,0.95);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,150,255,0.08);
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .dashboard-title {
      color: #0077c2;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 30px;
      margin-top: 30px;
      text-shadow: 0 2px 8px rgba(0,150,255,0.10);
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
<div id="loader-overlay">
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
</div>
<nav class="navbar navbar-expand-lg navbar-light glass-admin shadow-sm sticky-top mb-4">
  <div class="container">
    <a href="../dashboard/dashboard_admin.php" class="btn btn-glass-admin me-3">← Kembali</a>
    <a class="navbar-brand" href="#">Manajemen Pemesanan</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../dashboard/dashboard_admin.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin active" href="#">Pemesanan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../lapangan/lapangan_admin.php">Lapangan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../kalender/kalender_admin.php">Kalender</a></li>
        <li class="nav-item"><a class="nav-link btn-danger" href="../auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <h2 class="dashboard-title">Manajemen Pemesanan</h2>
  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="stat-card-admin">
        <div class="icon">👤</div>
        <h5>Total Pengguna</h5>
        <p class="fs-4 fw-bold"><?= $total_user ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card-admin">
        <div class="icon">🏟️</div>
        <h5>Total Lapangan</h5>
        <p class="fs-4 fw-bold"><?= $total_lapangan ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card-admin">
        <div class="icon">📅</div>
        <h5>Total Booking</h5>
        <p class="fs-4 fw-bold"><?= $total_booking ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card-admin">
        <div class="icon">💸</div>
        <h5>Total Pembayaran</h5>
        <p class="fs-5 fw-bold">Rp<?= number_format($total_pembayaran, 0, ',', '.') ?></p>
      </div>
    </div>
  </div>
  <div class="glass-content-admin">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Daftar Pemesanan</h3>
      <a href="export_laporan.php" class="btn btn-admin">Cetak</a>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama Pemesan</th>
            <th>Lapangan</th>
            <th>Status Pemesanan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($data = mysqli_fetch_assoc($query)) : ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $data['nama_pengguna'] ?></td>
            <td><?= $data['nama_lapangan'] ?></td>
            <td>
              <?php if ($data['status_booking'] == 'pending'): ?>
                <a href="../admin/update_status_booking.php?id=<?= $data['id_pemesanan'] ?>&status=diterima" class="btn btn-success btn-sm">Konfirmasi</a>
              <?php elseif ($data['status_booking'] == 'diterima'): ?>
                <span class="badge bg-success">Diterima</span>
              <?php elseif ($data['status_booking'] == 'ditolak'): ?>
                <span class="badge bg-danger">Ditolak</span>
              <?php else: ?>
                <span class="badge bg-secondary">-</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="view_pemesanan.php?id=<?= $data['id_pemesanan'] ?>" class="btn btn-info btn-sm">Lihat</a>
              <a href="konfirmasi_pembayaran.php?id=<?= $data['id_pemesanan'] ?>" class="btn btn-success btn-sm">Konfirmasi Pembayaran</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    document.getElementById('loader-overlay').classList.add('hide');
  }, 900);
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
</script>
</body>
</html>
