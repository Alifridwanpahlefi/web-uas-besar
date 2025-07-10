<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pengguna'])) {
    $id = intval($_POST['id_pengguna']);
    mysqli_query($conn, "DELETE FROM alif_pengguna WHERE id_pengguna = $id AND peran = 'pengguna'");
}
header('Location: manajemen_user.php');
exit(); 