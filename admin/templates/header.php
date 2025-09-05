<?php
session_start();

// Keamanan: Cek apakah user sudah login dan rolenya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Tombol Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$nama_admin = htmlspecialchars($_SESSION['nama']);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Potimoon</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        .admin-panel { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #343a40; color: white; padding: 1rem; }
        .sidebar h2 { text-align: center; margin-bottom: 2rem; }
        .sidebar a { display: block; color: white; padding: 1rem; text-decoration: none; border-radius: 4px; margin-bottom: 0.5rem; }
        .sidebar a:hover, .sidebar a.active { background-color: #495057; }
        .main-content { flex-grow: 1; padding: 2rem; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 1rem; margin-bottom: 2rem; }
        .header .welcome { font-size: 1.2rem; }
        .logout-btn { color: #dc3545; text-decoration: none; }
        .logout-btn:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="admin-panel">
    <div class="sidebar">
        <h2>Potimoon Admin</h2>
        <a href="index.php" class="active">Dashboard</a>
        <a href="produk.php">Produk</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="profil.php">Profil</a>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="welcome">Selamat Datang, <strong><?php echo $nama_admin; ?></strong>!</div>
            <a href="?logout=true" class="logout-btn">Logout</a>
        </div>
        <div class="content-body">
