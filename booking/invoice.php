<?php
session_start();
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID pemesanan tidak ditemukan.";
    exit();
}

$id_pemesanan = intval($_GET['id']);

$data_pemesanan = mysqli_query($conn, "SELECT p.*, u.nama_pengguna, u.email, l.nama_lapangan, l.harga_per_jam FROM alif_pemesanan p JOIN alif_pengguna u ON p.id_pengguna = u.id_pengguna JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan WHERE p.id_pemesanan = '$id_pemesanan'");

if (mysqli_num_rows($data_pemesanan) == 0) {
    echo "Data pemesanan tidak ditemukan.";
    exit();
}

$p = mysqli_fetch_assoc($data_pemesanan);

// Ambil data pembayaran (jika ada)
$data_bayar = mysqli_query($conn, "SELECT * FROM alif_pembayaran WHERE id_pemesanan = '$id_pemesanan'");
$bayar = mysqli_num_rows($data_bayar) > 0 ? mysqli_fetch_assoc($data_bayar) : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pemesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../gambar/logo.png" />
    <style>
      @media print {
        .no-print { display: none !important; }
        body { background: #fff !important; }
        .invoice-box { box-shadow: none !important; border: none !important; }
      }
      body {
        background: #e0f7fa;
        min-height: 100vh;
      }
      .invoice-box {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,150,136,0.10);
        padding: 32px 28px;
        margin: 40px auto;
        max-width: 600px;
      }
      .invoice-title {
        color: #009688;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 24px;
        text-align: center;
      }
      .table th, .table td {
        vertical-align: middle;
      }
      .bukti {
        margin-top: 10px;
      }
    </style>
</head>
<body>
<div class="container">
  <div class="invoice-box">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
      <a href="../dashboard/dashboard_user.php" class="btn btn-outline-secondary">← Kembali</a>
      <button class="btn btn-success" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    </div>
    <h2 class="invoice-title">INVOICE PEMESANAN FUTSAL</h2>
    <table class="table table-borderless mb-2">
      <tr><th colspan="2" class="bg-light">Data Pemesan</th></tr>
      <tr><td>Nama</td><td><?= htmlspecialchars($p['nama_pengguna']) ?></td></tr>
      <tr><td>Email</td><td><?= htmlspecialchars($p['email']) ?></td></tr>
    </table>
    <table class="table table-borderless mb-2">
      <tr><th colspan="2" class="bg-light">Detail Pemesanan</th></tr>
      <tr><td>Lapangan</td><td><?= htmlspecialchars($p['nama_lapangan']) ?></td></tr>
      <tr><td>Tanggal</td><td><?= htmlspecialchars($p['tanggal_pesan']) ?></td></tr>
      <tr><td>Jam</td><td><?= htmlspecialchars($p['jam_mulai']) ?> - <?= htmlspecialchars($p['jam_selesai']) ?></td></tr>
      <tr><td>Status</td><td><?= !empty($p['status_booking']) ? htmlspecialchars($p['status_booking']) : (!empty($p['status_pemesanan']) ? htmlspecialchars($p['status_pemesanan']) : '-') ?></td></tr>
      <tr><td>Harga/Jam</td><td>Rp<?= number_format($p['harga_per_jam'],0,',','.') ?></td></tr>
    </table>
    <?php
    // Hitung total
    $start = strtotime($p['jam_mulai']);
    $end = strtotime($p['jam_selesai']);
    $durasi = ($end - $start) / 3600;
    $total = $durasi * $p['harga_per_jam'];
    ?>
    <table class="table table-borderless mb-2">
      <tr><th>Total Bayar</th><th class="text-end">Rp<?= number_format($total,0,',','.') ?></th></tr>
    </table>
    <table class="table table-borderless mb-2">
      <tr><th colspan="2" class="bg-light">Pembayaran</th></tr>
      <tr><td>Status</td><td><?= $bayar ? htmlspecialchars($bayar['status_pembayaran']) : 'Belum ada pembayaran' ?></td></tr>
      <tr><td>Metode</td><td><?= $bayar && isset($bayar['metode_pembayaran']) ? htmlspecialchars($bayar['metode_pembayaran']) : '-' ?></td></tr>
      <?php if ($bayar && !empty($bayar['bukti_transfer'])): ?>
      <tr><td>Bukti Transfer</td><td class="bukti"><img src="../uploads/<?= htmlspecialchars($bayar['bukti_transfer']) ?>" alt="Bukti" width="150"></td></tr>
      <?php endif; ?>
    </table>
    <div class="text-center mt-4">
      <small>Terima kasih telah melakukan pemesanan di Zaa Futsal!</small>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 