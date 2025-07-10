<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'pengguna') {
    header("Location: ../auth/login.php");
    exit();
}
$lapangan = mysqli_query($conn, "SELECT * FROM alif_lapangan");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Lapangan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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
      padding: 32px 28px;
      margin-top: 40px;
      animation: fadeInUp 1.1s;
    }
    .form-label {
      color: #009688;
      font-weight: 600;
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
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
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
<div class="container">
  <div class="glass-user mx-auto" style="max-width: 500px;">
    <h2 class="text-center mb-4" style="color:#009688;font-weight:700;">Form Booking Lapangan</h2>
    <form method="POST" action="booking_simpan.php">
      <div class="mb-3">
        <label class="form-label">Nama Lapangan:</label>
        <select name="id_lapangan" class="form-control" required>
          <?php while($row = mysqli_fetch_assoc($lapangan)): ?>
            <option value="<?= $row['id_lapangan'] ?>"><?= $row['nama_lapangan'] ?> - Rp<?= number_format($row['harga_per_jam']) ?>/jam</option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Tanggal:</label>
        <input type="date" name="tanggal_pesan" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Jam Mulai:</label>
        <input type="time" name="jam_mulai" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Jam Selesai:</label>
        <input type="time" name="jam_selesai" class="form-control" required>
      </div>
      <div class="d-flex justify-content-between">
        <button class="btn btn-user" type="submit">Booking Sekarang</button>
        <a href="../dashboard/dashboard_user.php" class="btn btn-outline-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    document.getElementById('loader-overlay').classList.add('hide');
  }, 700);
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
