<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'pengguna') {
    header("Location: ../auth/login.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];
$query = mysqli_query($conn, "
    SELECT p.*, l.nama_lapangan, l.harga_per_jam,
           bayar.status_pembayaran
    FROM alif_pemesanan p
    JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan
    LEFT JOIN alif_pembayaran bayar ON p.id_pemesanan = bayar.id_pemesanan
    WHERE p.id_pengguna = '$id_pengguna'
    ORDER BY p.tanggal_pesan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Riwayat Pemesanan Saya</h3>
  <table class="table table-bordered mt-4">
    <thead>
      <tr>
        <th>#</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Lapangan</th>
        <th>Total Bayar</th>
        <th>Status Pemesanan</th>
        <th>Status Pembayaran</th>
        <th>Invoice</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 1; while($d = mysqli_fetch_assoc($query)): ?>
      <?php
        $jam1 = new DateTime($d['jam_mulai']);
        $jam2 = new DateTime($d['jam_selesai']);
        $durasi = $jam1->diff($jam2)->h;
        $total = $durasi * $d['harga_per_jam'];
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $d['tanggal_pesan'] ?></td>
        <td><?= $d['jam_mulai'] ?> - <?= $d['jam_selesai'] ?></td>
        <td><?= $d['nama_lapangan'] ?></td>
        <td>Rp<?= number_format($total, 0, ',', '.') ?></td>
        <td>
          <?= ucfirst($d['status_booking']) ?>
        </td>
        <td>
          <?php if ($d['status_pembayaran'] == 'sudah bayar'): ?>
            <span class="badge bg-success">Sudah Bayar</span>
          <?php elseif ($d['status_pembayaran'] == 'belum bayar'): ?>
            <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
          <?php else: ?>
            <a href="../pembayaran/konfirmasi_form.php?id=<?= $d['id_pemesanan'] ?>" class="btn btn-sm btn-info">Bayar</a>
          <?php endif; ?>
        </td>
        <td>
          <a href="invoice.php?id=<?= $d['id_pemesanan'] ?>" class="btn btn-sm btn-outline-dark" target="_blank">
            Lihat Invoice
          </a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
