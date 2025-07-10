<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id_lapangan = $_GET['id'] ?? null;
if ($id_lapangan === null) {
    header("Location: lapangan_admin.php");
    exit();
}

$query_lapangan = mysqli_query($conn, "SELECT * FROM alif_lapangan WHERE id_lapangan = '$id_lapangan'");
$data_lapangan = mysqli_fetch_assoc($query_lapangan);

if (!$data_lapangan) {
    header("Location: lapangan_admin.php");
    exit();
}

if (isset($_POST['update_lapangan'])) {
    $nama_lapangan = mysqli_real_escape_string($conn, $_POST['nama_lapangan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga_per_jam = mysqli_real_escape_string($conn, $_POST['harga_per_jam']);

    $update_query = mysqli_query($conn, "UPDATE alif_lapangan SET nama_lapangan = '$nama_lapangan', deskripsi = '$deskripsi', harga_per_jam = '$harga_per_jam' WHERE id_lapangan = '$id_lapangan'");

    if ($update_query) {
        $success_message = "Lapangan berhasil diperbarui!";
        $query_lapangan = mysqli_query($conn, "SELECT * FROM alif_lapangan WHERE id_lapangan = '$id_lapangan'");
        $data_lapangan = mysqli_fetch_assoc($query_lapangan);
    } else {
        $error_message = "Gagal memperbarui lapangan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Lapangan - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Edit Lapangan</h2>

    <?php if (isset($success_message)) : ?>
      <div class="alert alert-success"><?= $success_message ?></div>
    <?php elseif (isset($error_message)) : ?>
      <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Nama Lapangan</label>
        <input type="text" name="nama_lapangan" class="form-control" value="<?= $data_lapangan['nama_lapangan'] ?>" required>
      </div>
      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" required><?= $data_lapangan['deskripsi'] ?></textarea>
      </div>
      <div class="mb-3">
        <label>Harga per Jam</label>
        <input type="number" name="harga_per_jam" class="form-control" value="<?= $data_lapangan['harga_per_jam'] ?>" required>
      </div>
      <button type="submit" name="update_lapangan" class="btn btn-success w-100">Update Lapangan</button>
      <a href="lapangan_admin.php" class="btn btn-secondary w-100 mt-2">Kembali ke Manajemen Lapangan</a>
    </form>
  </div>
</body>
</html>
