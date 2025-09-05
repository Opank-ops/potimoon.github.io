<?php 
include 'templates/header.php'; 

// Cek apakah ID ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<section><div class='container'><p>Anggota tim tidak ditemukan.</p></div></section>";
    include 'templates/footer.php';
    exit();
}

$id = $_GET['id'];

// Ambil data dari database
$sql = "SELECT nama, deskripsi, foto_profil FROM users WHERE id = ? AND role = 'admin'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tim = mysqli_fetch_assoc($result);

if (!$tim) {
    echo "<section><div class='container'><p>Anggota tim tidak ditemukan.</p></div></section>";
    include 'templates/footer.php';
    exit();
}

$foto = !empty($tim['foto_profil']) ? 'uploads/' . htmlspecialchars($tim['foto_profil']) : 'https://via.placeholder.com/300';
$nama = htmlspecialchars($tim['nama']);
$deskripsi = nl2br(htmlspecialchars($tim['deskripsi'])); // nl2br untuk mengubah baris baru menjadi <br>

?>

<style>
.detail-tim-section {
    padding: 80px 0;
}
.detail-tim-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 50px;
    align-items: flex-start;
}
.detail-tim-img {
    width: 100%;
    max-width: 300px;
    height: 300px;
    border-radius: 50%;
    object-fit: cover;
    border: 8px solid var(--secondary-color);
}
.detail-tim-info h1 {
    font-size: 2.5rem;
}
.detail-tim-info p {
    font-size: 1.1rem;
    line-height: 1.8;
}

@media (max-width: 768px) {
    .detail-tim-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    .detail-tim-img {
        margin: 0 auto 30px auto;
    }
}
</style>

<section class="detail-tim-section">
    <div class="container">
        <div class="detail-tim-content">
            <div class="detail-tim-pic">
                <img src="<?php echo $foto; ?>" alt="Foto <?php echo $nama; ?>" class="detail-tim-img">
            </div>
            <div class="detail-tim-info">
                <h1><?php echo $nama; ?></h1>
                <p><?php echo $deskripsi; ?></p>
            </div>
        </div>
    </div>
</section>

<?php 
mmysqli_stmt_close($stmt);
mysqli_close($conn);
include 'templates/footer.php'; 
?>
