<?php 
// Halaman ini hanya bisa diakses oleh user yang sudah login
include 'templates/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pesanan untuk user ini
$sql = "SELECT * FROM pesanan WHERE user_id = ? ORDER BY tanggal_pesan DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<style>
.history-page { padding: 80px 0; background-color: #f8f9fa; min-height: 70vh; }
.order-card { background-color: #fff; border-radius: 8px; box-shadow: var(--shadow); margin-bottom: 20px; overflow: hidden; }
.order-header { background-color: #f1f1f1; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
.order-header span { font-weight: bold; }
.order-body { padding: 20px; }
.order-body p { margin: 0 0 10px 0; }
.status { padding: 5px 10px; border-radius: 15px; color: white; font-size: 0.9rem; text-transform: capitalize; }
.status.baru { background-color: #007bff; }
.status.diproses { background-color: #ffc107; color: #333; }
.status.selesai { background-color: #28a745; }
</style>

<section class="history-page">
    <div class="container">
        <h2>Riwayat Pesanan Saya</h2>
        <hr style="margin: 20px 0 40px 0; border-color: #eee;">

        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($order = mysqli_fetch_assoc($result)): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span>Pesanan: #<?php echo $order['id']; ?></span>
                        <span><?php echo date('d F Y', strtotime($order['tanggal_pesan'])); ?></span>
                    </div>
                    <div class="order-body">
                        <p><strong>Detail:</strong><br><?php echo nl2br(htmlspecialchars($order['detail_pesanan'])); ?></p>
                        <p><strong>Total Harga:</strong> Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></p>
                        <p><strong>Status:</strong> <span class="status <?php echo htmlspecialchars($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Anda belum memiliki riwayat pesanan.</p>
        <?php endif; ?>

    </div>
</section>

<?php 
mysqli_stmt_close($stmt);
include 'templates/footer.php'; 
?>
