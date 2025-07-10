<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM alif_lapangan WHERE id_lapangan=$id"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lapangan']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis_lapangan']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga_per_jam']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']); // Tambahkan deskripsi

    $query = "UPDATE alif_lapangan SET
                nama_lapangan='$nama',
                lokasi='$lokasi',
                jenis_lapangan='$jenis',
                harga_per_jam='$harga',
                deskripsi='$deskripsi'
              WHERE id_lapangan=$id";
    mysqli_query($conn, $query);
    header("Location: lapangan_admin.php"); // Perbaikan: Redirect ke lapangan_admin.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Lapangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2>Edit Lapangan</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Lapangan:</label>
      <input type="text" name="nama_lapangan" class="form-control" value="<?= $data['nama_lapangan'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Lokasi:</label>
      <input type="text" name="lokasi" class="form-control" value="<?= $data['lokasi'] ?? '' ?>" required>
    </div>
    <div class="mb-3">
      <label>Jenis Lapangan:</label>
      <select name="jenis_lapangan" class="form-control" required>
        <option value="rumput sintetis" <?= ($data['jenis_lapangan'] ?? '') == 'rumput sintetis' ? 'selected' : '' ?>>Rumput Sintetis</option>
        <option value="vinyl" <?= ($data['jenis_lapangan'] ?? '') == 'vinyl' ? 'selected' : '' ?>>Vinyl</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Harga per Jam:</label>
      <input type="number" name="harga_per_jam" class="form-control" value="<?= $data['harga_per_jam'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Deskripsi:</label>
      <textarea name="deskripsi" class="form-control" required><?= $data['deskripsi'] ?? '' ?></textarea>
    </div>
    <button class="btn btn-primary" type="submit">Update</button>
    <a href="lapangan_admin.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
</body>
</html>
