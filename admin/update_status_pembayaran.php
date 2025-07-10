<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($conn, "UPDATE alif_pembayaran SET status_pembayaran='$status' WHERE id_pemesanan='$id'");

header("Location: manajemen_pemesanan.php");
exit();
