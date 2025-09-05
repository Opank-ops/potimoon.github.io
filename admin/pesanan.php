<?php
include 'templates/header.php';
include '../config/db.php';

$message = '';

// Proses update status pesanan
if (isset($_POST['update_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status'];

    $sql_update = "UPDATE pesanan SET status = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'si', $status_baru, $id_pesanan);
    if (mysqli_stmt_execute($stmt_update)) {
        $message = "<p style='color:green;'>Status pesanan berhasil diperbarui.</p>";
    } else {
        $message = "<p style='color:red;'>Gagal memperbarui status.</p>";
    }
}

// Ambil semua pesanan dari database, diurutkan dari yang terbaru
$sql_select = "SELECT * FROM pesanan ORDER BY tanggal_pesan DESC";
$result_select = mysqli_query($conn, $sql_select);

?>
<style>
.table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.9rem; }
.table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
.table th { background-color: #f2f2f2; }
.table td form { display: flex; align-items: center; }
.table td select { margin-right: 10px; padding: 5px; }
.btn-update { background-color: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
</style>

<h2>Manajemen Pesanan</h2>

<?php echo $message; ?>

<table class="table">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Pelanggan</th>
            <th>No. Telepon</th>
            <th>Alamat</th>
            <th>Detail Pesanan</th>
            <th>Total Harga</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($result_select) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result_select)): ?>
            <tr>
                <td><?php echo date('d M Y, H:i', strtotime($row['tanggal_pesan'])); ?></td>
                <td><?php echo htmlspecialchars($row['nama_pelanggan']); ?></td>
                <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['alamat_lengkap'])); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['detail_pesanan'])); ?></td>
                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                <td>
                    <form action="pesanan.php" method="POST">
                        <input type="hidden" name="id_pesanan" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="baru" <?php echo ($row['status'] == 'baru') ? 'selected' : ''; ?>>Baru</option>
                            <option value="diproses" <?php echo ($row['status'] == 'diproses') ? 'selected' : ''; ?>>Diproses</option>
                            <option value="selesai" <?php echo ($row['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                        <button type="submit" name="update_status" class="btn-update">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;">Belum ada pesanan yang masuk.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php 
mysqli_close($conn);
include 'templates/footer.php'; 
?>
