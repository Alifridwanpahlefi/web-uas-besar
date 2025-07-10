<?php
session_start();
include '../config/koneksi.php';

$filter_user = isset($_GET['user']) && $_GET['user'] === 'true' ? true : false;
$id_pengguna = $_SESSION['id_pengguna'] ?? null;

$sql = "
    SELECT p.*, u.nama_pengguna, l.nama_lapangan
    FROM alif_pemesanan p
    JOIN alif_pengguna u ON p.id_pengguna = u.id_pengguna
    JOIN alif_lapangan l ON p.id_lapangan = l.id_lapangan
";

if ($filter_user && $id_pengguna) {
    $sql .= " WHERE p.id_pengguna = '$id_pengguna'";
}

$result = mysqli_query($conn, $sql);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        'title' => $row['nama_lapangan'] . " - " . $row['jam_mulai'] . " s/d " . $row['jam_selesai'],
        'start' => $row['tanggal_pesan'] . "T" . $row['jam_mulai'],
        'end'   => $row['tanggal_pesan'] . "T" . $row['jam_selesai'],
        'color' => ($row['status_booking'] == 'diterima') ? '#28a745' : '#ffc107',
    ];
}

echo json_encode($events);
