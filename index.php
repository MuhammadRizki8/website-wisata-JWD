<?php
require 'db.php'; // Mengimpor file 'db.php' yang berisi koneksi ke database

// Mengambil semua paket wisata dari database
$stmt = $pdo->query('SELECT * FROM paket_wisata');
$packages = $stmt->fetchAll(); // Menyimpan hasil query ke dalam array $packages
?>

<?php include 'header.php'; ?> <!-- Mengimpor file 'header.php' yang berisi elemen header -->

<!-- Konten Utama -->
<div class="container mt-5">
    <div class="row">
        <!-- Daftar Paket Wisata -->
        <div class="col-md-8">
            <h2>Paket Wisata Untukmu</h2> <!-- Judul section paket wisata -->
            <div class="row mt-4">
                <?php foreach ($packages as $package): ?> <!-- Looping untuk menampilkan setiap paket wisata -->
                    <div class="col-md-6 mb-4">
                        <div class="card"> <!-- Membuat kartu untuk setiap paket wisata -->
                            <!-- Menampilkan gambar paket wisata -->
                            <img alt="<?= htmlspecialchars($package['nama']) ?>" src="<?= htmlspecialchars($package['image_url']) ?>" class="card-img-top img-fluid">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($package['nama']) ?></h5> <!-- Menampilkan nama paket wisata -->
                                <p class="card-text"><?= htmlspecialchars($package['deskripsi']) ?></p> <!-- Menampilkan deskripsi paket wisata -->
                                <p class="card-text"><strong>Harga mulai dari:</strong> Rp. <?= number_format($package['harga'], 2) ?></p> <!-- Menampilkan harga paket wisata -->
                                <!-- Link untuk memesan paket wisata -->
                                <a href="booking.php?paket_id=<?= $package['id'] ?>" class="btn btn-primary">Booking sekarang</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?> <!-- Akhir looping paket wisata -->
            </div>
        </div>
        
        <!-- Video Promo di YouTube -->
        <div class="col-md-4">
            <h2>Video Promo Kami</h2> <!-- Judul section video promo -->
            <div class="row mt-3">
                <!-- Video Promo Pertama -->
                <div class="col-12 mb-4">
                    <div class="embed-responsive embed-responsive-16by9">
                        <!-- Menyematkan video YouTube pertama -->
                        <iframe width="100%" height="250" src="https://www.youtube.com/embed/4sdyBDSrzN4?si=17NC7GLlcUlzm73l" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
                <!-- Video Promo Kedua -->
                <div class="col-12">
                    <div class="embed-responsive embed-responsive-16by9">
                        <!-- Menyematkan video YouTube kedua -->
                        <iframe width="100%" height="250" src="https://www.youtube.com/embed/MrnNFFBZgSo?si=bDp-sUN89BR8ttaA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> <!-- Mengimpor file 'footer.php' yang berisi elemen footer -->

<!-- Menyertakan skrip Bootstrap untuk interaktivitas halaman -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
