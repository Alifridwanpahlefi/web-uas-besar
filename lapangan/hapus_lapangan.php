<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id_lapangan = $_GET['id'] ?? null;
if ($id_lapangan === null) {
    header("Location: lapangan_admin.php");
    exit();
}

$query_hapus = mysqli_query($conn, "DELETE FROM alif_lapangan WHERE id_lapangan = '$id_lapangan'");
if ($query_hapus) {
    header("Location: lapangan_admin.php");
    exit();
} else {
    echo "Gagal menghapus lapangan!";
}
