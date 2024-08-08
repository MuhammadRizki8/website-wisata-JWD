<?php
require 'db.php';

// Fetch services
$stmt = $pdo->query('SELECT * FROM layanan');
$services = $stmt->fetchAll();

// Fetch the order
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM pesanan WHERE id = ?');
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die('Order not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $durasi = $_POST['durasi'];
    $layanan = isset($_POST['layanan']) ? $_POST['layanan'] : [];
    $jumlah_peserta = $_POST['jumlah_peserta'];
    $paket_id = $_POST['paket_id'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $no_telepon = $_POST['no_telepon'];

    // Calculate the subtotal
    $subtotal = $durasi * array_sum($layanan);
    
    // Calculate the total
    $total = $subtotal * $jumlah_peserta;

    // Update the order in the pesanan table
    $stmt = $pdo->prepare('UPDATE pesanan SET tanggal_mulai = ?, durasi = ?, jumlah_peserta = ?, subtotal = ?, total = ?, paket_id = ?, nama_pemesan = ?, no_telepon = ?, penginapan = ?, transportasi = ?, makanan = ? WHERE id = ?');
    $stmt->execute([
        $tanggal_mulai, $durasi, $jumlah_peserta, $subtotal, $total, $paket_id, 
        $nama_pemesan, $no_telepon, 
        in_array('1000000', $layanan) ? 1 : 0, 
        in_array('1200000', $layanan) ? 1 : 0, 
        in_array('500000', $layanan) ? 1 : 0, 
        $order_id
    ]);

    // Redirect to order list
    header('Location: orders.php');
    exit();
}

// Mapping layanan selected from the order
$selected_layanan = [
    '1000000' => $order['penginapan'],
    '1200000' => $order['transportasi'],
    '500000' => $order['makanan']
];
?>

<?php include 'header.php'; ?>
<div class="container">
    <h1 class="mt-5">Edit Order</h1>
    <form method="POST" class="mt-4">
        <input type="hidden" name="paket_id" value="<?= htmlspecialchars($order['paket_id']) ?>">
        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?= htmlspecialchars($order['tanggal_mulai']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="durasi" class="form-label">Duration (days)</label>
            <input type="number" class="form-control" id="durasi" name="durasi" value="<?= htmlspecialchars($order['durasi']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="layanan" class="form-label">Select Services</label>
            <?php foreach ($services as $service): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="layanan[]" value="<?= $service['harga'] ?>" id="service_<?= $service['id'] ?>" 
                        <?= !empty($selected_layanan[$service['harga']]) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="service_<?= $service['id'] ?>">
                        <?= htmlspecialchars($service['nama']) ?> - Rp. <?= number_format($service['harga'], 2) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label for="jumlah_peserta" class="form-label">Number of Participants</label>
            <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" value="<?= htmlspecialchars($order['jumlah_peserta']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="subtotal" class="form-label">Subtotal</label>
            <input type="text" class="form-control" id="subtotal" name="subtotal" value="<?= htmlspecialchars($order['subtotal']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="total" class="form-label">Total</label>
            <input type="text" class="form-control" id="total" name="total" value="<?= htmlspecialchars($order['total']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="nama_pemesan" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" value="<?= htmlspecialchars($order['nama_pemesan']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="no_telepon" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($order['no_telepon']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php include 'footer.php'; ?>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('durasi').addEventListener('input', calculateSubtotal);
    document.getElementById('jumlah_peserta').addEventListener('input', calculateSubtotal);
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

    // Initial calculation
    calculateSubtotal();
</script>
</body>
</html>
