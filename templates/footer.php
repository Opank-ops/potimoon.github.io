    <footer>
        <p>&copy; 2025 Potimoon Juice. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SCRIPT UNTUK SLIDER PRODUK ---
            const wrapper = document.querySelector('.slider-wrapper');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            if(wrapper) {
                nextBtn.addEventListener('click', () => {
                    wrapper.scrollBy({ left: wrapper.clientWidth, behavior: 'smooth' });
                });
                prevBtn.addEventListener('click', () => {
                    wrapper.scrollBy({ left: -wrapper.clientWidth, behavior: 'smooth' });
                });
            }
            
            // --- SCRIPT UNTUK MODAL PEMESANAN ---
            const modal = document.getElementById('order-modal');
            const btn = document.getElementById('order-now-btn');
            const span = document.getElementsByClassName('close-btn')[0];
            const orderForm = document.getElementById('order-form');

            if (btn) {
                btn.onclick = function() {
                    modal.style.display = "block";
                }
            }

            if (span) {
                span.onclick = function() {
                    modal.style.display = "none";
                }
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            if (orderForm) {
                orderForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(orderForm);
                    const submitButton = orderForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.textContent;
                    submitButton.textContent = 'Menyimpan...';
                    submitButton.disabled = true;

                    fetch('pemesanan.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(text => {
                        console.log(text);
                        const jenis_produk = formData.get('jenis_produk');
                        const jumlah = formData.get('jumlah');
                        const nama_pemesan = formData.get('nama_pemesan');
                        const alamat_pemesan = formData.get('alamat_pemesan');
                        const nomorWA = '6287755104177';

                        const teksPesan = `Saya ingin pesan:\n\nProduk: *${jenis_produk}*\nJumlah: *${jumlah}*\n\n---\n\nNama: *${nama_pemesan}*\nAlamat Pengiriman:
${alamat_pemesan}`;
                        const encodedTeks = encodeURIComponent(teksPesan);
                        const urlWA = `https://wa.me/${nomorWA}?text=${encodedTeks}`;
                        
                        window.open(urlWA, '_blank');

                        submitButton.textContent = originalButtonText;
                        submitButton.disabled = false;
                        modal.style.display = "none";
                        orderForm.reset();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.');
                        submitButton.textContent = originalButtonText;
                        submitButton.disabled = false;
                    });
                });
            }

            // --- SCRIPT UNTUK HAMBURGER MENU ---
            const hamburger = document.querySelector('.hamburger-menu');
            const navLinks = document.querySelector('.nav-links');

            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });
        });
    </script>
<?php if(isset($conn)) mysqli_close($conn); ?>
</body>
</html>