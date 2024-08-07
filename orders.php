<?php
require 'db.php';

// Fetch all orders
$stmt = $pdo->query('SELECT pesanan.*, paket_wisata.nama AS paket_nama FROM pesanan JOIN paket_wisata ON pesanan.paket_id = paket_wisata.id');
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
     <!-- Bootstrap CSS from CDN -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom Styles -->
    <!-- <link rel="stylesheet" href="assets/css/styles.css"> -->
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
    <div class="container">
        <h1 class="mt-5">Order List</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Start Date</th>
                    <th>Duration</th>
                    <th>Services</th>
                    <th>Participants</th>
                    <th>Subtotal</th>
                    <th>Total</th>
                    <th>Package</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['tanggal_mulai']) ?></td>
                        <td><?= htmlspecialchars($order['durasi']) ?> days</td>
                        <td><?= htmlspecialchars($order['layanan']) ?></td>
                        <td><?= htmlspecialchars($order['jumlah_peserta']) ?></td>
                        <td>Rp. <?= number_format($order['subtotal'], 2) ?></td>
                        <td>Rp. <?= number_format($order['total'], 2) ?></td>
                        <td><?= htmlspecialchars($order['paket_nama']) ?></td>
                        <td><?= htmlspecialchars($order['tanggal_pesanan']) ?></td>
                        <td>
                            <a href="edit_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
