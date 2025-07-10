<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
if (!isset($_GET['id'])) {
    echo "ID pembayaran tidak ditemukan.";
    exit();
}
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT bayar.*, p.tanggal_pesan, p.jam_mulai, p.jam_selesai, p.status_booking, l.nama_lapangan, l.harga_per_jam, u.nama_pengguna, u.email FROM alif_pembayaran bayar JOIN alif_pemesanan p ON bayar.id_pemesanan = p.id_pemesanan JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan JOIN alif_pengguna u ON p.id_pengguna = u.id_pengguna WHERE bayar.id_pembayaran = '$id'");
if (mysqli_num_rows($q) == 0) {
    echo "Data pembayaran tidak ditemukan.";
    exit();
}
$data = mysqli_fetch_assoc($q);
$start = strtotime($data['jam_mulai']);
$end = strtotime($data['jam_selesai']);
if ($end <= $start) {
    $end = strtotime($data['jam_selesai'] . ' +1 day', $start);
}
$durasi = ($end - $start) / 3600;
if ($durasi <= 0) { $durasi = 1; } // minimal 1 jam
$total = $data['total_bayar'] ? $data['total_bayar'] : ($durasi * $data['harga_per_jam']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['konfirmasi'])) {
        $total_bayar = $data['total_bayar'];
        if (!$total_bayar || $total_bayar == 0) {
            if ($end <= $start) {
                $end = strtotime($data['jam_selesai'] . ' +1 day', $start);
            }
            $durasi = ($end - $start) / 3600;
            if ($durasi <= 0) { $durasi = 1; }
            $total_bayar = $durasi * $data['harga_per_jam'];
        }
        $update = mysqli_query($conn, "UPDATE alif_pembayaran SET status_pembayaran='sudah bayar', total_bayar='$total_bayar' WHERE id_pembayaran='$id'");
        if ($update) {
            $_SESSION['pesan'] = "Pembayaran berhasil dikonfirmasi.";
            $_SESSION['jenis'] = "success";
        } else {
            $_SESSION['pesan'] = "Gagal konfirmasi pembayaran.";
            $_SESSION['jenis'] = "danger";
        }
    } elseif (isset($_POST['tolak'])) {
        $update = mysqli_query($conn, "UPDATE alif_pembayaran SET status_pembayaran='belum bayar' WHERE id_pembayaran='$id'");
        if ($update) {
            $_SESSION['pesan'] = "Pembayaran ditolak.";
            $_SESSION['jenis'] = "warning";
        } else {
            $_SESSION['pesan'] = "Gagal menolak pembayaran.";
            $_SESSION['jenis'] = "danger";
        }
    }
    header("Location: manajemen_pemesanan.php");
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
    <link rel="icon" type="image/png" href="gambar/logo.png" />
    <style>
        body { background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height: 100vh; font-family: 'Montserrat', Arial, sans-serif; }
        .glass { background: rgba(255,255,255,0.15); box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37); backdrop-filter: blur(8px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.18); padding: 32px; max-width: 600px; margin: 40px auto; color: #232526; }
        .bukti-img { max-width: 100%; border-radius: 12px; box-shadow: 0 2px 12px rgba(13,110,253,0.12); margin-top: 10px; }
        .btn-modern { background: linear-gradient(90deg, #0d6efd 60%, #6ea8fe 100%); color: #fff; border: none; border-radius: 30px; padding: 10px 28px; font-weight: 600; font-size: 1.1rem; box-shadow: 0 4px 16px rgba(13,110,253,0.15); transition: transform 0.2s, box-shadow 0.2s; }
        .btn-modern:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 8px 32px rgba(13,110,253,0.25); color: #fff; }
    </style>
</head>
<body>
<div class="glass">
    <h3 class="mb-4">Konfirmasi Pembayaran</h3>
    <table class="table table-borderless">
        <tr><th>Nama Pengguna</th><td><?= htmlspecialchars($data['nama_pengguna']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
        <tr><th>Lapangan</th><td><?= htmlspecialchars($data['nama_lapangan']) ?></td></tr>
        <tr><th>Tanggal</th><td><?= htmlspecialchars($data['tanggal_pesan']) ?></td></tr>
        <tr><th>Jam</th><td><?= htmlspecialchars($data['jam_mulai']) ?> - <?= htmlspecialchars($data['jam_selesai']) ?></td></tr>
        <tr><th>Status Booking</th><td><?= htmlspecialchars($data['status_booking']) ?></td></tr>
        <tr><th>Total Bayar</th><td>Rp<?= number_format($total,0,',','.') ?></td></tr>
        <tr><th>Status Pembayaran</th><td><?= htmlspecialchars($data['status_pembayaran']) ?></td></tr>
        <tr><th>Metode Pembayaran</th><td><?= htmlspecialchars($data['metode_pembayaran']) ?></td></tr>
        <tr><th>Bukti Transfer</th><td><?php if ($data['bukti_transfer']): ?><img src="../uploads/<?= htmlspecialchars($data['bukti_transfer']) ?>" class="bukti-img" alt="Bukti Transfer"><?php else: ?>-<?php endif; ?></td></tr>
    </table>
    <form method="post" class="mt-4 d-flex gap-3">
        <button type="submit" name="konfirmasi" class="btn btn-modern">Konfirmasi</button>
        <button type="submit" name="tolak" class="btn btn-danger">Tolak</button>
        <a href="manajemen_pemesanan.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html> 