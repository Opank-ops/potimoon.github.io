<?php include 'templates/header.php'; ?>

    <section id="home">
        <h1>Rasakan Kesegaran Alami Jus Timun Murni</h1>
        <p>Dibuat dari timun pilihan terbaik untuk mengembalikan energimu setiap hari.</p>
        <button id="order-now-btn" class="btn">Pesan Sekarang</button>
    </section>

    <section id="produk">
        <div class="container">
            <h2>Produk Unggulan Kami</h2>
            <div class="slider-container">
                <div class="slider-wrapper">
                    <?php
                    $sql_produk = "SELECT * FROM produk WHERE ketersediaan_stok = 'tersedia' ORDER BY id DESC";
                    $result_produk = mysqli_query($conn, $sql_produk);
                    if (mysqli_num_rows($result_produk) > 0) {
                        while($p = mysqli_fetch_assoc($result_produk)) {
                            $foto_produk = !empty($p['foto']) ? 'uploads/' . htmlspecialchars($p['foto']) : 'https://via.placeholder.com/350x250';
                            $detail_produk_list = explode("\n", htmlspecialchars($p['detail']));
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo $foto_produk; ?>" alt="<?php echo htmlspecialchars($p['nama']); ?>">
                        </div>
                        <div class="product-details">
                            <h3><?php echo htmlspecialchars($p['nama']); ?></h3>
                            <p><strong>Harga: Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?> / botol</strong></p><br>
                            <ul>
                                <?php foreach($detail_produk_list as $item): ?>
                                    <li><?php echo trim($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php 
                        }
                    } else {
                        echo "<div class='product-card'><div class='coming-soon-card'><h3>Belum ada produk</h3><p>Nantikan produk-produk baru kami!</p></div></div>";
                    }
                    ?>
                </div>
                <button class="slider-btn prev-btn">&lt;</button>
                <button class="slider-btn next-btn">&gt;</button>
            </div>
        </div>
    </section>
    <section id="tujuan">
        <div class="container">
            <h2>Tujuan Usaha Kami</h2>
            <div class="tujuan-grid">
                <div class="tujuan-item"><div class="icon">🌿</div><h3>100% Alami</h3><p>Menyediakan minuman sehat dari bahan-bahan alami tanpa pengawet.</p></div>
                <div class="tujuan-item"><div class="icon">💪</div><h3>Meningkatkan Kesehatan</h3><p>Membantu pelanggan mencapai gaya hidup sehat melalui nutrisi yang baik.</p></div>
                <div class="tujuan-item"><div class="icon">🌍</div><h3>Mendukung Petani Lokal</h3><p>Bekerja sama dengan petani lokal untuk mendapatkan bahan baku terbaik.</p></div>
                <div class="tujuan-item"><div class="icon">😊</div><h3>Memberi Kesegaran</h3><p>Menjadi pilihan utama untuk melepas dahaga dengan cara yang menyehatkan.</p></div>
            </div>
        </div>
    </section>
    <section id="tim-kami">
        <div class="container">
            <h2>Tim Kami</h2>
            <div class="tim-grid">
                <?php
                $sql_tim = "SELECT id, nama, deskripsi, foto_profil FROM users WHERE role = 'admin' ORDER BY id";
                $result_tim = mysqli_query($conn, $sql_tim);
                if (mysqli_num_rows($result_tim) > 0) {
                    while($row = mysqli_fetch_assoc($result_tim)) {
                        $foto = !empty($row['foto_profil']) ? 'uploads/' . htmlspecialchars($row['foto_profil']) : 'https://via.placeholder.com/150';
                        $nama = htmlspecialchars($row['nama']);
                        $deskripsi_singkat = substr(htmlspecialchars($row['deskripsi']), 0, 100) . '...';
                        $id_user = $row['id'];

                        echo "<a href='detail-tim.php?id={$id_user}' class='tim-item'>";
                        echo "<img src='{$foto}' alt='Foto {$nama}'>";
                        echo "<h3>{$nama}</h3>";
                        echo "<p>{$deskripsi_singkat}</p>";
                        echo "</a>";
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <section id="kontak">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <div class="contact-content">
                <p>Siap merasakan kesegarannya? Jangan ragu untuk menghubungi kami!</p>
                <p><strong>WhatsApp:</strong> 087755104177</p>
                <p><strong>Instagram:</strong> @PotimoonJuiceID</p>
                <p><strong>Lokasi:</strong> Banjarnegara, Jawa Tengah</p>
            </div>
        </div>
    </section>

    <div id="order-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Form Pemesanan</h2>
            <form id="order-form">
                <?php
                $nama_pemesan = '';
                $alamat_pemesan = '';
                if(isset($_SESSION['user_id'])){
                    $user_id_pemesan = $_SESSION['user_id'];
                    $sql_user = "SELECT nama, alamat FROM users WHERE id = $user_id_pemesan";
                    $result_user = mysqli_query($conn, $sql_user);
                    if($user_data = mysqli_fetch_assoc($result_user)){
                        $nama_pemesan = $user_data['nama'];
                        $alamat_pemesan = $user_data['alamat'];
                    }
                }
                ?>
                <div class="form-group">
                    <label for="jenis_produk">Pilih Produk:</label>
                    <select id="jenis_produk" name="jenis_produk">
                        <?php
                        // Reset pointer hasil query produk
                        if(isset($result_produk)) mysqli_data_seek($result_produk, 0);
                        else $result_produk = mysqli_query($conn, "SELECT * FROM produk WHERE ketersediaan_stok = 'tersedia' ORDER BY id DESC");

                        while($p = mysqli_fetch_assoc($result_produk)) {
                            echo "<option value='" . htmlspecialchars($p['nama']) . "'>" . htmlspecialchars($p['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" id="jumlah" name="jumlah" value="1" min="1" required>
                </div>
                <div class="form-group">
                    <label for="nama_pemesan">Nama Anda:</label>
                    <input type="text" id="nama_pemesan" name="nama_pemesan" placeholder="Masukkan nama lengkap Anda" value="<?php echo htmlspecialchars($nama_pemesan); ?>" required>
                </div>
                 <div class="form-group">
                    <label for="alamat_pemesan">Alamat Pengiriman:</label>
                    <textarea id="alamat_pemesan" name="alamat_pemesan" placeholder="Masukkan alamat lengkap untuk pengiriman" required><?php echo htmlspecialchars($alamat_pemesan); ?></textarea>
                </div>
                <button type="submit" class="btn">Kirim Pesanan & Lanjut ke WhatsApp</button>
            </form>
        </div>
    </div>

<?php include 'templates/footer.php'; ?>