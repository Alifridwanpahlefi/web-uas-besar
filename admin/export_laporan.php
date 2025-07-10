<?php
include '../config/koneksi.php';

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=laporan_booking.csv");
header("Pragma: no-cache");
header("Expires: 0");

$output = fopen("php://output", "w");

fputcsv($output, [
    "Nama Pengguna",
    "Lapangan",
    "Tanggal",
    "Jam Mulai",
    "Jam Selesai",
    "Status Pemesanan",
    "Metode Pembayaran",
    "Status Pembayaran"
]);

$query = mysqli_query($conn, "
    SELECT
        u.nama_pengguna,
        l.nama_lapangan,
        p.tanggal_pesan,
        p.jam_mulai,
        p.jam_selesai,
        p.status_booking,
        bayar.metode_pembayaran,
        bayar.status_pembayaran
    FROM alif_pemesanan p
    JOIN alif_pengguna u ON p.id_pengguna = u.id_pengguna
    JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan
    LEFT JOIN alif_pembayaran bayar ON bayar.id_pemesanan = p.id_pemesanan
    ORDER BY p.tanggal_pesan DESC
");

while ($data = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $data['nama_pengguna'],
        $data['nama_lapangan'],
        $data['tanggal_pesan'],
        $data['jam_mulai'],
        $data['jam_selesai'],
        $data['status_booking'],
        $data['metode_pembayaran'],
        $data['status_pembayaran']
    ]);
}

fclose($output);
exit();
