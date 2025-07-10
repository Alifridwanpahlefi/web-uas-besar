<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'admin') {
    header("Location: ../auth/login.php");
}

$result = mysqli_query($conn, "SELECT * FROM alif_lapangan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Lapangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="gambar/logo.png" />
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2>Data Lapangan</h2>
  <!-- Perbaikan: Mengarahkan ke lapangan_admin.php untuk tambah lapangan -->
  <a href="lapangan_admin.php" class="btn btn-success mb-3">+ Tambah Lapangan</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Lapangan</th>
        <th>Lokasi</th>
        <th>Jenis</th>
        <th>Harga/Jam</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_lapangan'] ?></td>
        <td><?= $row['lokasi'] ?? 'N/A' ?></td>
        <td><?= $row['jenis_lapangan'] ?? 'N/A' ?></td>
        <td>Rp<?= number_format($row['harga_per_jam']) ?></td>
        <td>
          <a href="edit_lapangan.php?id=<?= $row['id_lapangan'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="hapus_lapangan.php?id=<?= $row['id_lapangan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
