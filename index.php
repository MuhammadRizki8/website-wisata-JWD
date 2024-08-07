<?php
require 'db.php';

// Fetch all travel packages
$stmt = $pdo->query('SELECT * FROM paket_wisata');
$packages = $stmt->fetchAll();
?>


<?php include 'header.php';?>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <!-- Travel Packages -->
            <div class="col-md-8">
                <h2>Available Travel Packages</h2>
                <div class="row mt-4">
                    <?php foreach ($packages as $package): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                            <img alt="crypto" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ97z2Ylq_k-XnFoFrCGvfHHvebCPJL5SRO8Q&s">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($package['nama']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($package['deskripsi']) ?></p>
                                    <p class="card-text"><strong>Price:</strong> Rp. <?= number_format($package['harga'], 2) ?></p>
                                    <a href="booking.php?paket_id=<?= $package['id'] ?>" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- YouTube Video -->
            <div class="col-md-4">
                <h2>Watch Our Promo Video</h2>
                <div class="embed-responsive embed-responsive-16by9 mt-3">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/your_video_id" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php';?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
