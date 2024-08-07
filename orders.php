<?php
require 'db.php';

// Fetch all orders
$stmt = $pdo->query('SELECT pesanan.*, paket_wisata.nama AS paket_nama FROM pesanan JOIN paket_wisata ON pesanan.paket_id = paket_wisata.id');
$orders = $stmt->fetchAll();
?>

<?php include 'header.php';?>
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

    <?php include 'footer.php';?>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
