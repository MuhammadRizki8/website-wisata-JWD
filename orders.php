<?php
require 'db.php';

// Mengambil semua pesanan
$stmt = $pdo->query('SELECT pesanan.*, paket_wisata.nama AS paket_nama FROM pesanan JOIN paket_wisata ON pesanan.paket_id = paket_wisata.id');
$orders = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>
<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-info mt-4">
        <?= htmlspecialchars($_GET['message']) ?>
    </div>
<?php endif; ?>

<div class="container">
    <h1 class="mt-5">Daftar Pesanan</h1>
    <a href="export_orders.php" class="btn btn-primary mb-3">Ekspor ke Excel</a>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pemesan</th>
                <th>Nomor Telepon</th>
                <th>Tanggal Mulai</th>
                <th>Durasi</th>
                <th>Layanan</th>
                <th>Peserta</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Paket</th>
                <th>Tanggal Pesanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['nama_pemesan']) ?></td>
                    <td><?= htmlspecialchars($order['no_telepon']) ?></td>
                    <td><?= htmlspecialchars($order['tanggal_mulai']) ?></td>
                    <td><?= htmlspecialchars($order['durasi']) ?> hari</td>
                    <td>
                        <?php
                        $services = [];
                        if ($order['penginapan']) $services[] = 'Penginapan';
                        if ($order['transportasi']) $services[] = 'Transportasi';
                        if ($order['makanan']) $services[] = 'Makanan';
                        echo htmlspecialchars(implode(', ', $services));
                        ?>
                    </td>
                    <td><?= htmlspecialchars($order['jumlah_peserta']) ?></td>
                    <td>Rp. <?= number_format($order['subtotal'], 2) ?></td>
                    <td>Rp. <?= number_format($order['total'], 2) ?></td>
                    <td><?= htmlspecialchars($order['paket_nama']) ?></td>
                    <td><?= htmlspecialchars($order['tanggal_pesanan']) ?></td>
                    
                    <td>
                        <a href="edit_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
