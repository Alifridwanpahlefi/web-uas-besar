<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id_pemesanan = $_POST['id_pemesanan'];
$metode = $_POST['metode_pembayaran'];


$cek = mysqli_query($conn, "SELECT * FROM alif_pembayaran WHERE id_pemesanan = '$id_pemesanan'");
if (mysqli_num_rows($cek) > 0) {
    $_SESSION['pesan'] = "Anda sudah pernah mengirim bukti pembayaran.";
    $_SESSION['jenis'] = "warning";
    header("Location: ../booking/riwayat_user.php");
    exit();
}

$nama_file = $_FILES['bukti']['name'];
$tmp = $_FILES['bukti']['tmp_name'];
$ukuran = $_FILES['bukti']['size'];
$ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);


$ext_valid = ['jpg', 'jpeg', 'png', 'pdf'];
if (!in_array(strtolower($ekstensi), $ext_valid)) {
    $_SESSION['pesan'] = "Format file tidak diizinkan. Hanya JPG, PNG, PDF.";
    $_SESSION['jenis'] = "danger";
    header("Location: ../booking/riwayat_user.php");
    exit();
}

if ($ukuran > 5 * 1024 * 1024) {
    $_SESSION['pesan'] = "Ukuran file maksimal 5MB.";
    $_SESSION['jenis'] = "danger";
    header("Location: ../booking/riwayat_user.php");
    exit();
}

$nama_baru = uniqid('bukti_') . '.' . $ekstensi;
$tujuan = '../uploads/' . $nama_baru;

if (!move_uploaded_file($tmp, $tujuan)) {
    $_SESSION['pesan'] = "Gagal mengupload file.";
    $_SESSION['jenis'] = "danger";
    header("Location: ../booking/riwayat_user.php");
    exit();
}

mysqli_query($conn, "INSERT INTO alif_pembayaran
    (id_pemesanan, metode_pembayaran, status_pembayaran, bukti_transfer)
    VALUES ('$id_pemesanan', '$metode', 'belum bayar', '$nama_baru')");

$_SESSION['pesan'] = "Bukti pembayaran berhasil dikirim. Tunggu konfirmasi admin.";
$_SESSION['jenis'] = "success";
header("Location: ../booking/riwayat_user.php");
exit();
