<?php 
include 'templates/header.php'; 
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$message = '';

// Proses form jika ada data yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $foto_lama = $_POST['foto_lama'];
    $foto_baru = $_FILES['foto_profil'];

    $nama_foto = $foto_lama;

    // Cek apakah ada foto baru yang diupload
    if ($foto_baru['error'] == 0) {
        $target_dir = "../uploads/";
        $nama_foto_baru = time() . '-' . basename($foto_baru["name"]);
        $target_file = $target_dir . $nama_foto_baru;
        
        // Validasi tipe file
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $message = "<p style='color:red;'>Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.</p>";
        } else {
            if (move_uploaded_file($foto_baru["tmp_name"], $target_file)) {
                $nama_foto = $nama_foto_baru;
                // Hapus foto lama jika ada
                if(!empty($foto_lama) && file_exists($target_dir . $foto_lama)){
                    unlink($target_dir . $foto_lama);
                }
            } else {
                $message = "<p style='color:red;'>Maaf, terjadi error saat mengupload file.</p>";
            }
        }
    }

    // Update data ke database
    if (empty($message)) {
        $sql = "UPDATE users SET nama = ?, deskripsi = ?, foto_profil = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $nama, $deskripsi, $nama_foto, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['nama'] = $nama; // Update nama di session
            $message = "<p style='color:green;'>Profil berhasil diperbarui!</p>";
        } else {
            $message = "<p style='color:red;'>Gagal memperbarui profil. Error: " . mysqli_error($conn) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }
}

// Ambil data user saat ini dari database
$sql_select = "SELECT nama, deskripsi, foto_profil FROM users WHERE id = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, 'i', $user_id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_select);

mysqli_close($conn);
?>

<style>
.profile-form { max-width: 600px; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: .5rem; }
.form-group input, .form-group textarea { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; }
.form-group textarea { min-height: 150px; }
.profile-pic-preview { max-width: 150px; margin-top: 1rem; border-radius: 50%; }
.btn-save { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
.btn-save:hover { background-color: #0056b3; }
</style>

<h2>Edit Profil</h2>
<p>Di sini Anda bisa mengubah detail profil Anda yang akan ditampilkan di halaman utama.</p>

<?php echo $message; ?>

<div class="profile-form">
    <form action="profil.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($user['foto_profil']); ?>">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" required><?php echo htmlspecialchars($user['deskripsi']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="foto_profil">Foto Profil</label>
            <input type="file" id="foto_profil" name="foto_profil">
            <p><i>Kosongkan jika tidak ingin mengubah foto.</i></p>
            <?php if (!empty($user['foto_profil'])): ?>
                <p>Foto saat ini:</p>
                <img src="../uploads/<?php echo htmlspecialchars($user['foto_profil']); ?>" alt="Foto Profil" class="profile-pic-preview">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn-save">Simpan Perubahan</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
