<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_booking_futsal";

$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
