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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
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
    <button class="btn btn-success">Kirim Konfirmasi</button>
    <a href="../booking/riwayat_user.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
</body>
</html>
