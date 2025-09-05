<?php
// Konfigurasi Database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "potimoon_db";

// Buat Koneksi
$conn = mysqli_connect($host, $user, $password, $dbname);

// Cek Koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>