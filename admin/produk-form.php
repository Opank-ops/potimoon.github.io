<?php
include 'templates/header.php';
include '../config/db.php';

$message = '';
$p = []; // product array
$edit_mode = false;

// Cek apakah mode edit
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_produk = $_GET['edit'];
    $sql = "SELECT * FROM produk WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_produk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $p = mysqli_fetch_assoc($result);
}

// Proses form
if (isset($_POST['submit'])) {
    $id_produk = $_POST['id_produk'];
    $nama = $_POST['nama'];
    $kategori_id = $_POST['kategori_id'];
    $harga = $_POST['harga'];
    $detail = $_POST['detail'];
    $ketersediaan_stok = $_POST['ketersediaan_stok'];
    $foto_lama = $_POST['foto_lama'];
    $foto_baru = $_FILES['foto'];

    $nama_foto = $foto_lama;

    // Proses upload foto jika ada
    if ($foto_baru['error'] == 0) {
        $target_dir = "../uploads/";
        $nama_foto_baru = time() . '-' . basename($foto_baru["name"]);
        $target_file = $target_dir . $nama_foto_baru;
        if (move_uploaded_file($foto_baru["tmp_name"], $target_file)) {
            $nama_foto = $nama_foto_baru;
            if(!empty($foto_lama) && file_exists($target_dir . $foto_lama)){
                unlink($target_dir . $foto_lama);
            }
        }
    }

    if (empty($id_produk)) {
        // Insert
        $sql = "INSERT INTO produk (nama, kategori_id, harga, detail, ketersediaan_stok, foto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'siisss', $nama, $kategori_id, $harga, $detail, $ketersediaan_stok, $nama_foto);
    } else {
        // Update
        $sql = "UPDATE produk SET nama=?, kategori_id=?, harga=?, detail=?, ketersediaan_stok=?, foto=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'siisssi', $nama, $kategori_id, $harga, $detail, $ketersediaan_stok, $nama_foto, $id_produk);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: produk.php");
        exit();
    } else {
        $message = "<p style='color:red;'>Terjadi kesalahan.</p>";
    }
}

// Ambil daftar kategori
$sql_kategori = "SELECT * FROM kategori ORDER BY nama";
$result_kategori = mysqli_query($conn, $sql_kategori);

?>
<style>
.form-container { max-width: 700px; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: .5rem; }
.form-group input, .form-group textarea, .form-group select { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; }
.form-group textarea { min-height: 150px; }
.btn-save { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
</style>

<h2><?php echo $edit_mode ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h2>
<a href="produk.php">&larr; Kembali ke Daftar Produk</a>

<?php echo $message; ?>

<div class="form-container">
    <form action="produk-form.php<?php echo $edit_mode ? '?edit='.$id_produk : ''; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_produk" value="<?php echo $p['id'] ?? ''; ?>">
        <input type="hidden" name="foto_lama" value="<?php echo $p['foto'] ?? ''; ?>">
        
        <div class="form-group">
            <label for="nama">Nama Produk</label>
            <input type="text" id="nama" name="nama" value="<?php echo $p['nama'] ?? ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while($k = mysqli_fetch_assoc($result_kategori)): ?>
                <option value="<?php echo $k['id']; ?>" <?php echo (isset($p['kategori_id']) && $p['kategori_id'] == $k['id']) ? 'selected' : ''; ?> >
                    <?php echo htmlspecialchars($k['nama']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" id="harga" name="harga" value="<?php echo $p['harga'] ?? ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="detail">Detail / Deskripsi</label>
            <textarea id="detail" name="detail" required><?php echo $p['detail'] ?? ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="ketersediaan_stok">Ketersediaan Stok</label>
            <select id="ketersediaan_stok" name="ketersediaan_stok" required>
                <option value="tersedia" <?php echo (isset($p['ketersediaan_stok']) && $p['ketersediaan_stok'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                <option value="habis" <?php echo (isset($p['ketersediaan_stok']) && $p['ketersediaan_stok'] == 'habis') ? 'selected' : ''; ?>>Habis</option>
            </select>
        </div>

        <div class="form-group">
            <label for="foto">Foto Produk</label>
            <input type="file" id="foto" name="foto">
            <?php if ($edit_mode && !empty($p['foto'])): ?>
                <p>Foto saat ini: <img src="../uploads/<?php echo $p['foto']; ?>" width="100"></p>
            <?php endif; ?>
        </div>

        <button type="submit" name="submit" class="btn-save">Simpan Produk</button>
    </form>
</div>

<?php 
mysqli_close($conn);
include 'templates/footer.php'; 
?>
