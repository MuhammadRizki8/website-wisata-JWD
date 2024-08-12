<?php
require 'db.php';

// Mengambil semua pesanan
$stmt = $pdo->query('SELECT pesanan.*, paket_wisata.nama AS paket_nama FROM pesanan JOIN paket_wisata ON pesanan.paket_id = paket_wisata.id ORDER BY pesanan.id');
$orders = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>


<div class="container col-10">
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info mt-2">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>
    <h1 class="mt-4">Daftar Pesanan</h1>
    <a href="export_orders.php" class="btn btn-primary mb-3">Ekspor data</a>
    <table class="table table-striped mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pemesan</th>
                <th>Nomor Telepon</th>
                <th>Tanggal Mulai</th>
                <th>Durasi</th>
                <th>Peserta</th>
                <th>Paket</th>
                <th>Layanan</th>
                <th>Subtotal</th>
                <th>Total</th>
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
                    <td><?= htmlspecialchars($order['jumlah_peserta']) ?> orang</td>
                    <td><?= htmlspecialchars($order['paket_nama']) ?></td>
                    <td>
                        <?php
                        $services = [];
                        if ($order['penginapan']) $services[] = 'Penginapan';
                        if ($order['transportasi']) $services[] = 'Transportasi';
                        if ($order['makanan']) $services[] = 'Makanan';
                        echo htmlspecialchars(implode(', ', $services));
                        ?>
                    </td>
                    <td>Rp. <?= number_format($order['subtotal'], 2) ?></td>
                    <td>Rp. <?= number_format($order['total'], 2) ?></td>
                    <td><?= htmlspecialchars($order['tanggal_pesanan']) ?></td>
                    
                    <td>
                        <a href="edit_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-warning mb-1">🖋️</a>
                        <a href="delete_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php include 'footer.php'; ?>
</body>
</html>
