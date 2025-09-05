<?php
include 'templates/header.php';
include '../config/db.php';

$message = '';

// Proses Tambah/Edit Kategori
if (isset($_POST['submit'])) {
    $nama_kategori = $_POST['nama_kategori'];
    $id_kategori = $_POST['id_kategori'];

    if (!empty($nama_kategori)) {
        if (empty($id_kategori)) {
            // Tambah Baru
            $sql = "INSERT INTO kategori (nama) VALUES (?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 's', $nama_kategori);
            if(mysqli_stmt_execute($stmt)) $message = "<p style='color:green;'>Kategori berhasil ditambahkan.</p>";
        } else {
            // Edit
            $sql = "UPDATE kategori SET nama = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $nama_kategori, $id_kategori);
            if(mysqli_stmt_execute($stmt)) $message = "<p style='color:green;'>Kategori berhasil diperbarui.</p>";
        }
    } else {
        $message = "<p style='color:red;'>Nama kategori tidak boleh kosong.</p>";
    }
}

// Proses Hapus Kategori
if (isset($_GET['hapus'])) {
    $id_kategori = $_GET['hapus'];
    $sql = "DELETE FROM kategori WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_kategori);
    if(mysqli_stmt_execute($stmt)) $message = "<p style='color:green;'>Kategori berhasil dihapus.</p>";
}

// Ambil data untuk form edit
$edit_nama = '';
$edit_id = '';
if (isset($_GET['edit'])) {
    $id_kategori = $_GET['edit'];
    $sql = "SELECT id, nama FROM kategori WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_kategori);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($result)){
        $edit_nama = $row['nama'];
        $edit_id = $row['id'];
    }
}

// Ambil semua kategori untuk ditampilkan
$sql_select = "SELECT id, nama FROM kategori ORDER BY nama ASC";
$result_select = mysqli_query($conn, $sql_select);

?>
<style>
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
.table th { background-color: #f2f2f2; }
.action-links a { margin-right: 10px; text-decoration: none; }
.form-container { max-width: 500px; padding: 20px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 30px; }
.form-container h3 { margin-top: 0; }
.form-group { margin-bottom: 1rem; }
.form-group input { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; }
.btn-save { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
</style>

<h2>Manajemen Kategori</h2>

<?php echo $message; ?>

<div class="form-container">
    <h3><?php echo empty($edit_id) ? 'Tambah Kategori Baru' : 'Edit Kategori'; ?></h3>
    <form action="kategori.php" method="POST">
        <input type="hidden" name="id_kategori" value="<?php echo $edit_id; ?>">
        <div class="form-group">
            <input type="text" name="nama_kategori" value="<?php echo htmlspecialchars($edit_nama); ?>" placeholder="Nama Kategori" required>
        </div>
        <button type="submit" name="submit" class="btn-save">Simpan</button>
    </form>
</div>

<h3>Daftar Kategori</h3>
<table class="table">
    <thead>
        <tr>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result_select)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td class="action-links">
                <a href="kategori.php?edit=<?php echo $row['id']; ?>">Edit</a>
                <a href="kategori.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Ini juga akan menghapus semua produk di dalamnya.');">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php 
mysqli_close($conn);
include 'templates/footer.php'; 
?>
