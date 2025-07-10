<?php
session_start();
include '../config/koneksi.php';
if (isset($_SESSION['peran'])) {
    if ($_SESSION['peran'] == 'admin') {
        header("Location: ../dashboard/dashboard_admin.php");
        exit();
    } else { // Peran 'pengguna'
        header("Location: ../dashboard/dashboard_user.php");
        exit();
    }
}
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // Enkripsi MD5
    $query = mysqli_query($conn, "SELECT * FROM alif_pengguna WHERE email='$email' AND password='$password'");
    $cek = mysqli_num_rows($query);
    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['id_pengguna'] = $data['id_pengguna'];
        $_SESSION['nama_pengguna'] = $data['nama_pengguna'];
        $_SESSION['peran'] = $data['peran'];
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit();
        } else {
            header("Location: ../index.php");
            exit();
        }
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Booking Futsal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="icon" type="image/png" href="../gambar/logo.png" />
  <style>
    body {
      font-family: 'Quicksand', Arial, sans-serif;
      background: url('../gambar/bgakun.jpeg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
    }
    .login-card {
      background: rgba(255,255,255,0.85);
      box-shadow: 0 8px 32px 0 rgba(0,150,136,0.13);
      border-radius: 18px;
      border: 1px solid rgba(0,150,136,0.10);
      padding: 36px 32px 28px 32px;
      margin-top: 60px;
      animation: fadeInUp 1.1s;
    }
    .btn-modern {
      background: linear-gradient(90deg, #26a69a 60%, #80cbc4 100%);
      color:a #fff;
      border: none;
      border-radius: 28px;
      padding: 10px 28px;
      font-weight: 600;
      font-size: 1.05rem;
      box-shadow: 0 4px 16px rgba(0,150,136,0.10);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-modern:hover {
      transform: translateY(-2px) scale(1.04);
      box-shadow: 0 8px 32px rgba(0,200,83,0.18);
      color: #fff;
    }
    .back-btn {
      margin-bottom: 18px;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    /* Ping indicator */
    .ping-indicator {
      position: fixed;
      right: 24px;
      bottom: 24px;
      z-index: 9999;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,150,136,0.10);
      padding: 10px 18px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1rem;
      font-family: 'Quicksand', Arial, sans-serif;
      color: #009688;
      border: 1px solid #b2dfdb;
      animation: fadeInUp 1.2s;
    }
    .ping-dot {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: #43ea5e;
      box-shadow: 0 0 8px #43ea5e;
      display: inline-block;
      margin-right: 4px;
      animation: ping 1.2s infinite;
    }
    @keyframes ping {
      0% { box-shadow: 0 0 0 0 #43ea5e; }
      70% { box-shadow: 0 0 0 8px rgba(67,234,94,0); }
      100% { box-shadow: 0 0 0 0 #43ea5e; }
    }
  </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="login-card col-md-5 animate__animated animate__fadeInUp">
    <a href="../index.php" class="btn btn-outline-secondary back-btn">← Kembali ke Beranda</a>
    <h3 class="text-center mb-4" style="color:#009688;font-weight:700;">Login Pengguna</h3>
    <?php if (isset($error)) : ?>
      <div class="alert alert-danger animate__animated animate__shakeX"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required autofocus>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" name="login" class="btn btn-modern w-100">Login</button>
    </form>
    <div class="text-center mt-3">
      <span>Belum punya akun?</span> <a href="register.php" class="btn btn-outline-success btn-sm ms-1">Daftar</a>
    </div>
  </div>
</div>
<div class="ping-indicator" id="ping-indicator">
  <span class="ping-dot" id="ping-dot"></span>
  <span id="ping-text">Ping: <span id="ping-value">-</span> ms</span>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.js"></script>
<script>
function updatePing() {
  const start = Date.now();
  fetch(window.location.href, { method: 'HEAD', cache: 'no-store' })
    .then(() => {
      const ping = Date.now() - start;
      document.getElementById('ping-value').textContent = ping;
      const dot = document.getElementById('ping-dot');
      if (ping < 100) dot.style.background = '#43ea5e';
      else if (ping < 300) dot.style.background = '#ffe066';
      else dot.style.background = '#ff5e57';
    })
    .catch(() => {
      document.getElementById('ping-value').textContent = 'timeout';
      document.getElementById('ping-dot').style.background = '#ff5e57';
    });
}
setInterval(updatePing, 2000);
updatePing();
</script>
</body>
</html>
