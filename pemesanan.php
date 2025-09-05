<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $nama_pemesan = $_POST['nama_pemesan'] ?? '';
    $alamat_pemesan = $_POST['alamat_pemesan'] ?? '';
    $jenis_produk = $_POST['jenis_produk'] ?? '';
    $jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;

    // Ambil user_id jika pelanggan login
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Ambil harga produk dari database untuk kalkulasi
    $total_harga = 0;
    $sql_harga = "SELECT harga FROM produk WHERE nama = ?";
    $stmt_harga = mysqli_prepare($conn, $sql_harga);
    
    if (!$stmt_harga) {
        die("SQL error (prepare harga): " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt_harga, 's', $jenis_produk);
    mysqli_stmt_execute($stmt_harga);
    $result_harga = mysqli_stmt_get_result($stmt_harga);
    
    if($produk_data = mysqli_fetch_assoc($result_harga)){
        $harga_satuan = $produk_data['harga'];
        $total_harga = $harga_satuan * $jumlah;
    }
    mysqli_stmt_close($stmt_harga);

    // Siapkan detail pesanan
    $detail_pesanan = "Produk: " . $jenis_produk . "\nJumlah: " . $jumlah;

    // Ambil no telepon dari data user jika login
    $no_telepon = '';
    if($user_id){
        $sql_user = "SELECT no_telepon FROM users WHERE id = ?";
        $stmt_user = mysqli_prepare($conn, $sql_user);
        if($stmt_user){
            mysqli_stmt_bind_param($stmt_user, 'i', $user_id);
            mysqli_stmt_execute($stmt_user);
            $result_user = mysqli_stmt_get_result($stmt_user);
            if($user_data = mysqli_fetch_assoc($result_user)){
                $no_telepon = $user_data['no_telepon'];
            }
            mysqli_stmt_close($stmt_user);
        }
    }

    // Simpan pesanan ke database
    $sql_insert = "INSERT INTO pesanan (user_id, nama_pelanggan, no_telepon, alamat_lengkap, detail_pesanan, total_harga, status) VALUES (?, ?, ?, ?, ?, ?, 'baru')";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    
    if (!$stmt_insert) {
        die("SQL error (prepare insert): " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_insert, 'issssi', $user_id, $nama_pemesan, $no_telepon, $alamat_pemesan, $detail_pesanan, $total_harga);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "Pesanan berhasil disimpan.";
    } else {
        echo "Error: Gagal menyimpan pesanan. " . mysqli_stmt_error($stmt_insert);
    }
    mysqli_stmt_close($stmt_insert);

} else {
    echo "Akses ditolak.";
}

mysqli_close($conn);
?>