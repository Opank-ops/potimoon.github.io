<?php 
session_start();
include 'config/db.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potimoon - Jus Timun Alami Khas Banjarnegara</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2E8B57;
            --secondary-color: #F0FFF0;
            --text-color: #333333;
            --white-color: #FFFFFF;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; line-height: 1.7; color: var(--text-color); background-color: var(--white-color); }
        .container { max-width: 1100px; margin: auto; padding: 0 20px; overflow: hidden; }
        section { padding: 80px 0; }
        h1, h2, h3 { color: var(--primary-color); margin-bottom: 20px; }
        h2 { text-align: center; font-size: 2.5rem; font-weight: 700; }
        .navbar { background-color: var(--white-color); padding: 0.5rem 0; position: sticky; top: 0; width: 100%; z-index: 1000; box-shadow: var(--shadow); }
        .navbar .container { display: flex; justify-content: space-between; align-items: center; }
        .navbar .logo-link { display: flex; align-items: center; text-decoration: none; }
        .navbar .logo-img { 
            height: 100px; 
            width: auto;
            margin-top: -15px;
            margin-bottom: -15px;
        }
        .navbar .nav-links { list-style: none; display: flex; }
        .navbar .nav-links li a { padding: 0 15px; text-decoration: none; color: var(--text-color); font-weight: 600; transition: color 0.3s ease; }
        .navbar .nav-links li a:hover { color: var(--primary-color); }
        .hamburger-menu { display: none; cursor: pointer; } /* Sembunyikan di desktop */
        .hamburger-menu .bar { display: block; width: 25px; height: 3px; margin: 5px auto; transition: all 0.3s ease-in-out; background-color: var(--text-color); }

        #home { background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('https://images.pexels.com/photos/1346347/pexels-photo-1346347.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1') no-repeat center center/cover; height: 90vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
        #home h1 { font-size: 3.2rem; margin-bottom: 1rem; max-width: 700px; }
        #home p { font-size: 1.2rem; max-width: 600px; margin-bottom: 2rem; }
        .btn { cursor: pointer; background-color: var(--primary-color); color: var(--white-color); padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border: none; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.3); }
        #produk { background-color: var(--secondary-color); }
        .slider-container { position: relative; max-width: 800px; margin: auto; }
        .slider-wrapper { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .slider-wrapper::-webkit-scrollbar { display: none; }
        .product-card { flex: 0 0 100%; scroll-snap-align: center; padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: center; }
        .product-image img { width: 100%; height: 250px; object-fit: cover; max-width: 350px; border-radius: 15px; box-shadow: var(--shadow); display: block; margin: auto; }
        .product-details ul { list-style: none; padding-left: 0; }
        .product-details li { margin-bottom: 15px; padding-left: 20px; position: relative; font-size: 1.1rem; }
        .coming-soon-card { display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; background-color: var(--white-color); border-radius: 15px; padding: 40px; grid-column: 1 / -1; min-height: 400px; }
        .coming-soon-card .icon { font-size: 4rem; margin-bottom: 20px; }
        .slider-btn { position: absolute; top: 50%; transform: translateY(-50%); background-color: rgba(255, 255, 255, 0.7); border: none; border-radius: 50%; width: 40px; height: 40px; font-size: 1.5rem; color: var(--primary-color); cursor: pointer; box-shadow: var(--shadow); z-index: 10; }
        .prev-btn { left: -20px; }
        .next-btn { right: -20px; }
        #tujuan { background-color: var(--white-color); }
        .tujuan-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; text-align: center; }
        .tujuan-item { padding: 20px; }
        .tujuan-item .icon { font-size: 3rem; color: var(--primary-color); margin-bottom: 15px; }
        .tujuan-item h3 { font-size: 1.2rem; }
        #kontak { background-color: var(--secondary-color); }
        .contact-content { max-width: 800px; margin: 0 auto; text-align: center; font-size: 1.1rem; }
        .contact-content p { margin: 15px 0; }
        .contact-content strong { color: var(--primary-color); }

        #tim-kami { background-color: var(--white-color); }
        .tim-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; text-align: center; }
        .tim-item { padding: 20px; box-shadow: var(--shadow); border-radius: 15px; text-decoration: none; color: var(--text-color); display: block; transition: transform 0.3s ease, box-shadow 0.3s ease;}
        .tim-item:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .tim-item img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 5px solid var(--secondary-color); }
        .tim-item h3 { font-size: 1.2rem; color: var(--primary-color); }

        footer { background-color: #333; color: var(--white-color); text-align: center; padding: 25px 0; }
        
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.3); position: relative; }
        .close-btn { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; }
        .close-btn:hover, .close-btn:focus { color: black; text-decoration: none; cursor: pointer; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group select, .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 1rem; }
        .form-group textarea { min-height: 80px; resize: vertical; }

        /* --- ATURAN RESPONSIVE / TAMPILAN MOBILE --- */
        @media (max-width: 768px) {
            .navbar .container {
                justify-content: space-between;
            }
            .navbar .logo-link {
                margin: 0;
            }
            .hamburger-menu {
                display: block; /* Tampilkan hamburger di mobile */
            }
            .hamburger-menu.active .bar:nth-child(2) { opacity: 0; }
            .hamburger-menu.active .bar:nth-child(1) { transform: translateY(8px) rotate(45deg); }
            .hamburger-menu.active .bar:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

            .navbar .nav-links {
                display: none; /* Sembunyikan menu utama */
                position: absolute;
                top: 80px; /* Sesuaikan dengan tinggi navbar */
                left: 0;
                background-color: var(--white-color);
                width: 100%;
                flex-direction: column;
                text-align: center;
                box-shadow: var(--shadow);
            }
            .navbar .nav-links.active {
                display: flex; /* Tampilkan menu saat aktif */
            }
            .navbar .nav-links li {
                padding: 15px 0;
                width: 100%;
            }
            .navbar .nav-links li a {
                display: block;
                width: 100%;
            }
            .navbar .nav-links li:hover {
                background-color: var(--secondary-color);
            }

            h2 { font-size: 2rem; }
            #home { height: 70vh; }
            #home h1 { font-size: 2.2rem; }
            .product-card { grid-template-columns: 1fr; text-align: center; }
            .product-details li { padding-left: 0; text-align: left; padding-left: 30px; }
            .slider-btn { top: auto; bottom: -50px; transform: translateY(0); }
            .prev-btn { left: 30%; }
            .next-btn { right: 30%; }
            #produk { padding-bottom: 120px; }
            .modal-content { width: 90%; margin: 20% auto; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo-link">
                <img src="logo-potimoon.png" alt="Logo Potimoon" class="logo-img">
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#produk">Produk</a></li>
                <li><a href="index.php#tim-kami">Tentang Kami</a></li>
                <li><a href="index.php#kontak">Kontak</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li><a href="admin/index.php" style="color: #dc3545;">Admin Panel</a></li>
                    <?php else: ?>
                        <li><a href="profil.php">Profil</a></li>
                        <li><a href="riwayat-pesanan.php">Pesanan Saya</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Akun</a></li>
                <?php endif; ?>
            </ul>
            <div class="hamburger-menu">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>
    </nav>
