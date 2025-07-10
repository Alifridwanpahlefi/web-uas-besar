<?php
include '../config/koneksi.php';

$data = [];

for ($i = 1; $i <= 12; $i++) {
    $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
    $tahun = date('Y');
    $result = mysqli_query($conn, "
        SELECT COUNT(*) AS jumlah
        FROM alif_pemesanan
        WHERE MONTH(tanggal_pesan) = '$bulan' AND YEAR(tanggal_pesan) = '$tahun'
    ");
    $row = mysqli_fetch_assoc($result);
    $data[] = $row['jumlah'];
}

echo json_encode($data);
