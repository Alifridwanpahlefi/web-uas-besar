<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_lapangan = $_POST['id_lapangan'];
$tanggal     = $_POST['tanggal_pesan'];
$jam_mulai   = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];

$cek = mysqli_query($conn, "SELECT * FROM alif_pemesanan
    WHERE id_lapangan = '$id_lapangan'
    AND tanggal_pesan = '$tanggal'
    AND (
        (jam_mulai < '$jam_selesai' AND jam_selesai > '$jam_mulai')
    )");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
        alert('Gagal! Lapangan sudah dibooking di jam tersebut.');
        window.location = 'booking_form.php';
    </script>";
    exit();
}

mysqli_query($conn, "INSERT INTO alif_pemesanan
    (id_pengguna, id_lapangan, tanggal_pesan, jam_mulai, jam_selesai, status_booking)
    VALUES
    ('$id_pengguna', '$id_lapangan', '$tanggal', '$jam_mulai', '$jam_selesai', 'pending')");

echo "<script>
    alert('Booking berhasil disimpan!');
    window.location = '../dashboard/dashboard_user.php';
</script>";
