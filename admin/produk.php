<?php
include 'templates/header.php';
include '../config/db.php';

$message = '';

// Proses Hapus Produk
if (isset($_GET['hapus'])) {
    $id_produk = $_GET['hapus'];

    // Ambil nama file foto untuk dihapus
    $sql_foto = "SELECT foto FROM produk WHERE id = ?";
    $stmt_foto = mysqli_prepare($conn, $sql_foto);
    mysqli_stmt_bind_param($stmt_foto, 'i', $id_produk);
    mysqli_stmt_execute($stmt_foto);
    $result_foto = mysqli_stmt_get_result($stmt_foto);
    if ($row_foto = mysqli_fetch_assoc($result_foto)) {
        if (!empty($row_foto['foto']) && file_exists("../uploads/" . $row_foto['foto'])) {
            unlink("../uploads/" . $row_foto['foto']);
        }
    }

    // Hapus data dari DB
    $sql = "DELETE FROM produk WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_produk);
    if(mysqli_stmt_execute($stmt)) $message = "<p style='color:green;'>Produk berhasil dihapus.</p>";
}

// Ambil semua produk untuk ditampilkan
$sql_select = "SELECT p.id, p.nama, p.harga, p.ketersediaan_stok, k.nama AS nama_kategori 
               FROM produk p 
               JOIN kategori k ON p.kategori_id = k.id 
               ORDER BY p.nama ASC";
$result_select = mysqli_query($conn, $sql_select);

?>
<style>
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
.table th { background-color: #f2f2f2; }
.action-links a { margin-right: 10px; text-decoration: none; }
.btn-add { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-bottom: 20px; }
</style>

<h2>Manajemen Produk</h2>

<?php echo $message; ?>

<a href="produk-form.php" class="btn-add">+ Tambah Produk Baru</a>
<a href="kategori.php" class="btn-add" style="background-color: #17a2b8;">Kelola Kategori</a>

<h3>Daftar Produk</h3>
<table class="table">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result_select)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
            <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
            <td><?php echo htmlspecialchars($row['ketersediaan_stok']); ?></td>
            <td class="action-links">
                <a href="produk-form.php?edit=<?php echo $row['id']; ?>">Edit</a>
                <a href="produk.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php 
mysqli_close($conn);
include 'templates/footer.php'; 
?>
