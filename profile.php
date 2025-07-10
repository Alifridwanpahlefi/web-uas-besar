<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['peran'])) {
    header('Location: auth/login.php');
    exit();
}
$id_pengguna = $_SESSION['id_pengguna'];
$pesan = '';
$q = mysqli_query($conn, "SELECT * FROM alif_pengguna WHERE id_pengguna='$id_pengguna'");
$user = mysqli_fetch_assoc($q);
$foto = isset($user['foto']) ? $user['foto'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $update_foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['name']) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg','jpeg','png'];
        if (in_array($ext, $valid_ext)) {
            $nama_foto = 'user_' . $id_pengguna . '_' . time() . '.' . $ext;
            $tujuan = 'gambar/' . $nama_foto;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
                $update_foto = ", foto='$tujuan'";
                $_SESSION['foto'] = $tujuan;
            } else {
                $pesan = 'Gagal upload foto.';
            }
        } else {
            $pesan = 'Format foto harus JPG/PNG.';
        }
    }
    $sql = "UPDATE alif_pengguna SET nama_pengguna='$nama', email='$email' $update_foto WHERE id_pengguna='$id_pengguna'";
    $ok = mysqli_query($conn, $sql);
    if ($ok) {
        $_SESSION['nama_pengguna'] = $nama;
        $pesan = 'Profil berhasil diperbarui!';
    } else {
        $pesan = 'Gagal update profil.';
    }
    if (!empty($_POST['password'])) {
        $pass = md5($_POST['password']);
        mysqli_query($conn, "UPDATE alif_pengguna SET password='$pass' WHERE id_pengguna='$id_pengguna'");
        $pesan .= ' Password diubah.';
    }
    $q = mysqli_query($conn, "SELECT * FROM alif_pengguna WHERE id_pengguna='$id_pengguna'");
    $user = mysqli_fetch_assoc($q);
    $foto = isset($user['foto']) ? $user['foto'] : '';
    $_SESSION['foto'] = $foto; // Pastikan session foto selalu update setelah update profil
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="gambar/logo.png" />
    <style>
        body {
          font-family: 'Quicksand', Arial, sans-serif;
          background: linear-gradient(135deg, #e0ecff 0%, #c9e4ff 50%, #e0c3fc 100%);
          min-height: 100vh;
          overflow-x: hidden;
        }
        .glass-profile {
          background: rgba(255,255,255,0.80);
          box-shadow: 0 8px 32px 0 rgba(0,150,255,0.10);
          backdrop-filter: blur(8px);
          -webkit-backdrop-filter: blur(8px);
          border-radius: 20px;
          border: 1px solid rgba(0,150,255,0.10);
          padding: 40px 32px 32px 32px;
          max-width: 420px;
          margin: 48px auto 0 auto;
          color: #222;
        }
        .profile-img {
          width: 90px; height: 90px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 12px rgba(13,110,253,0.12); margin-bottom: 10px; background: #e0ecff;
        }
        .btn-modern {
          background: linear-gradient(90deg, #039be5 60%, #81d4fa 100%);
          color: #fff;
          border: none;
          border-radius: 30px;
          padding: 12px 32px;
          font-weight: 600;
          font-size: 1.1rem;
          box-shadow: 0 4px 16px rgba(3,155,229,0.15);
          transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-modern:hover, .btn-modern:focus {
          transform: translateY(-3px) scale(1.05);
          box-shadow: 0 8px 32px rgba(3,155,229,0.25);
          color: #fff;
        }
        .btn-cancel {
          background: #e0ecff;
          color: #0077c2;
          border-radius: 30px;
          font-weight: 600;
          font-size: 1.1rem;
          border: none;
          margin-top: 8px;
        }
        .btn-cancel:hover, .btn-cancel:focus {
          background: #b2ebf2;
          color: #01579b;
        }
        .alert-info {
          background: #e3f0ff;
          color: #0077c2;
          border: 1px solid #81d4fa;
          border-radius: 12px;
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
<!-- Loader overlay -->
<div id="loader-overlay">
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
</div>
<div class="glass-profile">
    <h3 class="mb-4 text-center" style="color:#0077c2;font-weight:700;">Edit Profil</h3>
    <?php if ($pesan): ?>
      <div class="alert alert-info text-center"> <?= $pesan ?> </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="text-center mb-3">
            <img src="<?= $foto ? htmlspecialchars($foto) : 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/icons/person-circle.svg' ?>" class="profile-img" alt="Foto Profil">
        </div>
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama_pengguna']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password Baru <small>(kosongkan jika tidak ingin ganti)</small></label>
            <input type="password" name="password" class="form-control" placeholder="Password baru">
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Profil <small>(JPG/PNG, opsional)</small></label>
            <input type="file" name="foto" class="form-control">
        </div>
        <button type="submit" class="btn btn-modern w-100">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-cancel w-100 mt-2">Kembali</a>
    </form>
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