<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
if (!isset($_GET['id'])) {
    header('Location: manajemen_user.php');
    exit();
}
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM alif_pengguna WHERE id_pengguna = $id");
if (mysqli_num_rows($q) == 0) {
    header('Location: manajemen_user.php');
    exit();
}
$user = mysqli_fetch_assoc($q);
$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);
    $update_password = '';
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $update_password = ", password='$password'";
    }
    $cek_email = mysqli_query($conn, "SELECT id_pengguna FROM alif_pengguna WHERE email='$email' AND id_pengguna != $id");
    if (mysqli_num_rows($cek_email) > 0) {
        $pesan = 'Email sudah digunakan oleh user lain!';
    } else {
        $ok = mysqli_query($conn, "UPDATE alif_pengguna SET nama_pengguna='$nama', email='$email', peran='$peran' $update_password WHERE id_pengguna=$id");
        if ($ok) {
            $pesan = 'Data user berhasil diperbarui!';
            $user['nama_pengguna'] = $nama;
            $user['email'] = $email;
            $user['peran'] = $peran;
            if (isset($_SESSION['id_pengguna']) && $_SESSION['id_pengguna'] == $id) {
                $_SESSION['peran'] = $peran;
                $_SESSION['nama_pengguna'] = $nama;
                $_SESSION['email'] = $email;
            }
        } else {
            $pesan = 'Gagal update data user.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="../gambar/logo.png" />
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
    .page-title {
      color: #0077c2;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 30px;
      margin-top: 30px;
      text-shadow: 0 2px 8px rgba(0,150,255,0.10);
    }
    .edit-user-card {
      background: linear-gradient(120deg, #e3f0ff 60%, #f8fdff 100%);
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,150,255,0.08);
      padding: 32px 24px 24px 24px;
      margin-bottom: 30px;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
      animation: fadeInUp 1.1s;
    }
    .btn-admin {
      background: linear-gradient(90deg, #039be5 60%, #81d4fa 100%);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 10px 28px;
      font-weight: 600;
      font-size: 1.05rem;
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
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
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
    <a href="manajemen_user.php" class="btn btn-glass-admin me-3">← Kembali</a>
    <a class="navbar-brand" href="#">Edit User</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../dashboard/dashboard_admin.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="manajemen_pemesanan.php">Pemesanan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../lapangan/lapangan_admin.php">Lapangan</a></li>
        <li class="nav-item"><a class="nav-link btn-glass-admin" href="../kalender/kalender_admin.php">Kalender</a></li>
        <li class="nav-item"><a class="nav-link btn-danger" href="../auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <h2 class="page-title animate__animated animate__fadeInDown">Edit User</h2>
  <div class="edit-user-card animate__animated animate__fadeInUp">
    <?php if ($pesan): ?>
      <div class="alert alert-info mb-3"> <?= $pesan ?> </div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label for="nama_pengguna" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" value="<?= htmlspecialchars($user['nama_pengguna']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password Baru <small>(kosongkan jika tidak ingin ganti)</small></label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password baru">
      </div>
      <div class="mb-3">
        <label for="peran" class="form-label">Role</label>
        <select class="form-select" id="peran" name="peran" required>
          <option value="pengguna" <?= $user['peran'] == 'pengguna' ? 'selected' : '' ?>>Pengguna</option>
          <option value="admin" <?= $user['peran'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>
      <div class="d-flex justify-content-between">
        <a href="manajemen_user.php" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-admin">Simpan Perubahan</button>
      </div>
    </form>
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