<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM alif_lapangan WHERE id_lapangan=$id");
header("Location: lapangan_admin.php");
exit();
