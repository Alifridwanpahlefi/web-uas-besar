<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_pemesanan = $_GET['id'];
$query = mysqli_query($conn, "
    SELECT p.*, l.nama_lapangan, l.harga_per_jam
    FROM alif_pemesanan p
    JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan
    WHERE p.id_pemesanan = '$id_pemesanan'
    AND p.id_pengguna = '{$_SESSION['id_pengguna']}'
");

$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "Data booking tidak ditemukan atau Anda tidak berhak mengakses.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', Arial, sans-serif;
      background: linear-gradient(135deg, #e3f0ff 0%, #b2ebf2 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }
    .glass-user {
      background: rgba(255,255,255,0.8);
      box-shadow: 0 8px 32px 0 rgba(0,150,136,0.13);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-radius: 18px;
      border: 1px solid rgba(0,150,136,0.10);
      padding: 36px 32px;
      margin-top: 60px;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
      animation: fadeInUp 1.1s;
    }
    h2 {
      color: #0077c2;
      font-weight: 700;
      margin-bottom: 24px;
      text-align: center;
    }
    label, strong {
      color: #009688;
      font-weight: 600;
    }
    .btn-user {
      background: linear-gradient(90deg, #26a69a 60%, #81d4fa 100%);
      color: #fff;
      border: none;
      border-radius: 28px;
      padding: 10px 28px;
      font-weight: 600;
      font-size: 1.05rem;
      box-shadow: 0 4px 16px rgba(0,150,136,0.10);
      transition: transform 0.2s, box-shadow 0.2s;
      margin-right: 8px;
    }
    .btn-user:hover {
      transform: translateY(-2px) scale(1.04);
      box-shadow: 0 8px 32px rgba(0,200,83,0.18);
      color: #fff;
    }
    .btn-secondary {
      border-radius: 28px;
      font-weight: 600;
      padding: 10px 28px;
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
<div class="glass-user">
  <h2>Konfirmasi Pembayaran</h2>
  <div class="mb-3">
    <strong>Lapangan:</strong> <?= $data['nama_lapangan'] ?><br>
    <strong>Tanggal:</strong> <?= $data['tanggal_pesan'] ?><br>
    <strong>Jam:</strong> <?= $data['jam_mulai'] ?> - <?= $data['jam_selesai'] ?><br>
    <strong>Harga/jam:</strong> Rp<?= number_format($data['harga_per_jam']) ?><br>
  </div>
  <form method="POST" action="konfirmasi_simpan.php" enctype="multipart/form-data">
    <input type="hidden" name="id_pemesanan" value="<?= $id_pemesanan ?>">
    <div class="mb-3">
      <label>Metode Pembayaran:</label>
      <select name="metode_pembayaran" class="form-control" required>
        <option value="">-- Pilih --</option>
        <option value="transfer">Transfer Bank</option>
        <option value="e-wallet">E-Wallet</option>
        <option value="tunai">Tunai</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Bukti Pembayaran (jpg, png, pdf):</label>
      <input type="file" name="bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>
    <button class="btn btn-user" type="submit">Kirim Konfirmasi</button>
    <a href="../booking/riwayat_user.php" class="btn btn-secondary">Kembali</a>
  </form>
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
