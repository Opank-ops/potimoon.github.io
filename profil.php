<?php 
// Halaman ini hanya bisa diakses oleh user yang sudah login
include 'templates/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Proses form jika ada data yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $foto_lama = $_POST['foto_lama'];
    $foto_baru = $_FILES['foto_profil'];

    $nama_foto = $foto_lama;

    // Cek apakah ada foto baru yang diupload
    if (isset($foto_baru) && $foto_baru['error'] == 0) {
        $target_dir = "uploads/";
        $nama_foto_baru = time() . '-' . basename($foto_baru["name"]);
        $target_file = $target_dir . $nama_foto_baru;
        
        if (move_uploaded_file($foto_baru["tmp_name"], $target_file)) {
            $nama_foto = $nama_foto_baru;
            if(!empty($foto_lama) && file_exists($target_dir . $foto_lama)){
                unlink($target_dir . $foto_lama);
            }
        } else {
            $message = "<p style='color:red;'>Maaf, terjadi error saat mengupload file. Pastikan folder 'uploads' memiliki izin tulis.</p>";
        }
    }

    // Update data ke database
    if (empty($message)) {
        $sql = "UPDATE users SET nama = ?, alamat = ?, no_telepon = ?, foto_profil = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssi', $nama, $alamat, $no_telepon, $nama_foto, $user_id);
        
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
$sql_select = "SELECT nama, alamat, no_telepon, foto_profil FROM users WHERE id = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, 'i', $user_id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$user = mysqli_fetch_assoc($result);

?>

<style>
.profile-page { padding: 80px 0; background-color: #f8f9fa; }
.profile-form { max-width: 700px; margin: auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: var(--shadow); }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: .5rem; font-weight: 600; }
.form-group input, .form-group textarea { width: 100%; padding: .75rem; border: 1px solid #ccc; border-radius: 4px; }
.form-group textarea { min-height: 120px; }
.profile-pic-preview { max-width: 150px; margin-top: 1rem; border-radius: 50%; }
.btn-save { background-color: var(--primary-color); color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
.btn-save:hover { background-color: #257046; }
</style>

<section class="profile-page">
    <div class="container">
        <div class="profile-form">
            <h2>Profil Saya</h2>
            <a href="riwayat-pesanan.php" class="btn-save" style="float: right; text-decoration: none;">Lihat Riwayat Pesanan</a>
            <p>Perbarui informasi kontak dan alamat Anda di sini.</p>
            <hr style="margin: 20px 0; border-color: #eee;">
            
            <?php echo $message; ?>

            <form action="profil.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($user['foto_profil']); ?>">
                
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat"><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="no_telepon">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($user['no_telepon']); ?>">
                </div>

                <div class="form-group">
                    <label for="foto_profil">Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil">
                    <p><i>Kosongkan jika tidak ingin mengubah foto.</i></p>
                    <?php if (!empty($user['foto_profil'])): ?>
                        <p>Foto saat ini:</p>
                        <img src="uploads/<?php echo htmlspecialchars($user['foto_profil']); ?>" alt="Foto Profil" class="profile-pic-preview">
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</section>

<?php 
mysqli_stmt_close($stmt_select);
mysqli_close($conn);
include 'templates/footer.php'; 
?>
