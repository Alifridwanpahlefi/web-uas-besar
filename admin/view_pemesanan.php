<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
if (!isset($_GET['id'])) {
    echo "ID pemesanan tidak ditemukan.";
    exit();
}
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT p.*, l.nama_lapangan, l.harga_per_jam, u.nama_pengguna, u.email, bayar.status_pembayaran, bayar.metode_pembayaran, bayar.bukti_transfer, bayar.total_bayar FROM alif_pemesanan p JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan JOIN alif_pengguna u ON p.id_pengguna = u.id_pengguna LEFT JOIN alif_pembayaran bayar ON bayar.id_pemesanan = p.id_pemesanan WHERE p.id_pemesanan = '$id'");
if (mysqli_num_rows($q) == 0) {
    echo "Data pemesanan tidak ditemukan.";
    exit();
}
$data = mysqli_fetch_assoc($q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pemesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="gambar/logo.png" />
    <style>
        body { background: linear-gradient(135deg, #232526 0%, #414345 100%); min-height: 100vh; font-family: 'Montserrat', Arial, sans-serif; }
        .glass { background: rgba(255,255,255,0.15); box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37); backdrop-filter: blur(8px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.18); padding: 32px; max-width: 600px; margin: 40px auto; color: #232526; }
        .bukti-img { max-width: 100%; border-radius: 12px; box-shadow: 0 2px 12px rgba(13,110,253,0.12); margin-top: 10px; }
    </style>
</head>
<body>
<div class="glass">
    <h3 class="mb-4">Detail Pemesanan</h3>
    <table class="table table-borderless">
        <tr><th>Nama Pengguna</th><td><?= htmlspecialchars($data['nama_pengguna']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
        <tr><th>Lapangan</th><td><?= htmlspecialchars($data['nama_lapangan']) ?></td></tr>
        <tr><th>Tanggal</th><td><?= htmlspecialchars($data['tanggal_pesan']) ?></td></tr>
        <tr><th>Jam</th><td><?= htmlspecialchars($data['jam_mulai']) ?> - <?= htmlspecialchars($data['jam_selesai']) ?></td></tr>
        <tr><th>Status Booking</th><td><?= htmlspecialchars($data['status_booking']) ?></td></tr>
        <tr><th>Harga/Jam</th><td>Rp<?= number_format($data['harga_per_jam'],0,',','.') ?></td></tr>
        <?php
        $start = strtotime($data['jam_mulai']);
        $end = strtotime($data['jam_selesai']);
        $durasi = ($end - $start) / 3600;
        $total = $durasi * $data['harga_per_jam'];
        ?>
        <tr><th>Total Bayar</th><td>Rp<?= number_format($total,0,',','.') ?></td></tr>
        <tr><th>Status Pembayaran</th><td><?= $data['status_pembayaran'] ? htmlspecialchars($data['status_pembayaran']) : '-' ?></td></tr>
        <tr><th>Metode Pembayaran</th><td><?= $data['metode_pembayaran'] ? htmlspecialchars($data['metode_pembayaran']) : '-' ?></td></tr>
        <tr><th>Bukti Transfer</th><td><?php if ($data['bukti_transfer']): ?><img src="../uploads/<?= htmlspecialchars($data['bukti_transfer']) ?>" class="bukti-img" alt="Bukti Transfer"><?php else: ?>-<?php endif; ?></td></tr>
    </table>
    <div class="text-end mt-3">
        <a href="manajemen_pemesanan.php" class="btn btn-secondary">Kembali</a>
    </div>
</div>
</body>
</html> 