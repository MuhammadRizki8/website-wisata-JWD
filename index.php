<?php
require 'db.php';

// Fetch all travel packages
$stmt = $pdo->query('SELECT * FROM paket_wisata');
$packages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Packages</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom Styles -->
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white text-center py-4">
        <div class="container">
            <h1>Travel Booking Application</h1>
            <nav class="mt-3">
                <a href="index.php" class="text-white mx-3">Beranda</a>
                <a href="booking.php" class="text-white mx-3">Form Pemesanan</a>
                <a href="orders.php" class="text-white mx-3">Daftar Pesanan</a>
            </nav>
        </div>
    </header>

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

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Tentang Kami</h5>
                    <p>Informasi mengenai UMKM yang menyediakan paket wisata terbaik.</p>
                </div>
                <div class="col-md-4">
                    <h5>Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Beranda</a></li>
                        <li><a href="booking.php" class="text-white">Form Pemesanan</a></li>
                        <li><a href="orders.php" class="text-white">Daftar Pesanan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak Kami</h5>
                    <p>Email: contact@umkmpariwisata.com</p>
                    <p>Phone: +62 123 4567 890</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
