<?php
require 'db.php';

// Fetch services
$stmt = $pdo->query('SELECT * FROM layanan');
$services = $stmt->fetchAll();

// Fetch the selected travel package
$paket_id = isset($_GET['paket_id']) ? (int)$_GET['paket_id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM paket_wisata WHERE id = ?');
$stmt->execute([$paket_id]);
$package = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $durasi = $_POST['durasi'];
    $layanan = isset($_POST['layanan']) ? implode(',', $_POST['layanan']) : '';
    $jumlah_peserta = $_POST['jumlah_peserta'];
    $paket_id = $_POST['paket_id'];

    // Calculate the subtotal
    $subtotal = $durasi * array_sum($_POST['layanan']);
    
    // Calculate the total
    $total = $subtotal * $jumlah_peserta;

    // Insert into pesanan table
    $stmt = $pdo->prepare('INSERT INTO pesanan (tanggal_mulai, durasi, layanan, jumlah_peserta, subtotal, total, tanggal_pesanan, paket_id) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)');
    $stmt->execute([$tanggal_mulai, $durasi, $layanan, $jumlah_peserta, $subtotal, $total, $paket_id]);

    // Redirect to order list
    header('Location: orders.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Package</title>
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
    <div class="container">
        <h1 class="mt-5">Book a Travel Package</h1>
        <form method="POST" class="mt-4">
            <input type="hidden" name="paket_id" value="<?= htmlspecialchars($package['id']) ?>">
            <div class="mb-3">
                <label for="tanggal_mulai" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
            </div>
            <div class="mb-3">
                <label for="durasi" class="form-label">Duration (days)</label>
                <input type="number" class="form-control" id="durasi" name="durasi" required>
            </div>
            <div class="mb-3">
                <label for="layanan" class="form-label">Select Services</label>
                <?php foreach ($services as $service): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="layanan[]" value="<?= $service['harga'] ?>" id="service_<?= $service['id'] ?>">
                        <label class="form-check-label" for="service_<?= $service['id'] ?>">
                            <?= htmlspecialchars($service['nama']) ?> - Rp. <?= number_format($service['harga'], 2) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mb-3">
                <label for="jumlah_peserta" class="form-label">Number of Participants</label>
                <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" required>
            </div>
            <div class="mb-3">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="text" class="form-control" id="subtotal" name="subtotal" readonly>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" class="form-control" id="total" name="total" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
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
    <script>
        document.getElementById('durasi').addEventListener('input', calculateSubtotal);
        document.querySelectorAll('input[name="layanan[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', calculateSubtotal);
        });

        function calculateSubtotal() {
            let durasi = parseFloat(document.getElementById('durasi').value) || 0;
            let layananTotal = Array.from(document.querySelectorAll('input[name="layanan[]"]:checked'))
                                    .map(input => parseFloat(input.value))
                                    .reduce((a, b) => a + b, 0);
            let subtotal = durasi * layananTotal;
            document.getElementById('subtotal').value = subtotal;
            let jumlahPeserta = parseInt(document.getElementById('jumlah_peserta').value) || 0;
            let total = subtotal * jumlahPeserta;
            document.getElementById('total').value = total;
        }
    </script>
</body>
</html>
