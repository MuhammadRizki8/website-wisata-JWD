<?php
require 'db.php';

// Fetch services
$stmt = $pdo->query('SELECT * FROM layanan');
$services = $stmt->fetchAll();

// Fetch all travel packages for the dropdown
$stmt = $pdo->query('SELECT * FROM paket_wisata');
$all_packages = $stmt->fetchAll();

// Fetch the selected travel package
$paket_id = isset($_GET['paket_id']) ? (int)$_GET['paket_id'] : 0;
$package = null;
if ($paket_id) {
    $stmt = $pdo->prepare('SELECT * FROM paket_wisata WHERE id = ?');
    $stmt->execute([$paket_id]);
    $package = $stmt->fetch();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $durasi = $_POST['durasi'];
    $jumlah_peserta = $_POST['jumlah_peserta'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $no_telepon = $_POST['no_telepon'];
    $paket_id = $_POST['paket_id'];

    $penginapan = isset($_POST['penginapan']) ? 1 : 0;
    $transportasi = isset($_POST['transportasi']) ? 1 : 0;
    $makanan = isset($_POST['makanan']) ? 1 : 0;
    $layanan = [];
    if ($penginapan) $layanan[] = 1000000;
    if ($transportasi) $layanan[] = 1200000;
    if ($makanan) $layanan[] = 500000;

    if (!$tanggal_mulai || !$durasi || !$jumlah_peserta || !$nama_pemesan || !$no_telepon || !$paket_id) {
        $error = 'Form pemesanan belum diisi.';
    } elseif (count($layanan) < 1) {
        $error = 'Minimal pilih satu layanan paket.';
    } else {
        // Calculate the subtotal
        $subtotal = $durasi * array_sum($layanan);
        
        // Calculate the total
        $total = $subtotal * $jumlah_peserta;

        // Insert into pesanan table
        $stmt = $pdo->prepare('INSERT INTO pesanan (tanggal_mulai, durasi, jumlah_peserta, subtotal, total, tanggal_pesanan, paket_id, nama_pemesan, no_telepon, penginapan, transportasi, makanan) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$tanggal_mulai, $durasi, $jumlah_peserta, $subtotal, $total, $paket_id, $nama_pemesan, $no_telepon, $penginapan, $transportasi, $makanan]);

        // Redirect to order list
        header('Location: orders.php');
        exit();
    }
}
?>

<?php include 'header.php'; ?>
<div class="container">
    <h1 class="mt-5">Book a Travel Package</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="paket_id" class="form-label">Select Travel Package</label>
            <select class="form-select" id="paket_id" name="paket_id" required>
                <option value="">Select a package</option>
                <?php foreach ($all_packages as $pkg): ?>
                    <option value="<?= htmlspecialchars($pkg['id']) ?>" <?= ($pkg['id'] == $paket_id) ? 'selected' : '' ?>><?= htmlspecialchars($pkg['nama']) ?> - Rp. <?= number_format($pkg['harga'], 2) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
        </div>
        <div class="mb-3">
            <label for="durasi" class="form-label">Duration (days)</label>
            <input type="number" class="form-control" id="durasi" name="durasi" required>
        </div>
        <div class="mb-3">
            <label for="jumlah_peserta" class="form-label">Number of Participants</label>
            <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" required>
        </div>
        <div class="mb-3">
            <label for="nama_pemesan" class="form-label">Name</label>
            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
        </div>
        <div class="mb-3">
            <label for="no_telepon" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
        </div>
        <div class="mb-3">
            <label for="layanan" class="form-label">Select Services</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="penginapan" value="1000000" id="penginapan">
                <label class="form-check-label" for="penginapan">
                    Penginapan - Rp. 1,000,000
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="transportasi" value="1200000" id="transportasi">
                <label class="form-check-label" for="transportasi">
                    Transportasi - Rp. 1,200,000
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="makanan" value="500000" id="makanan">
                <label class="form-check-label" for="makanan">
                    Makanan - Rp. 500,000
                </label>
            </div>
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
<?php include 'footer.php'; ?>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('durasi').addEventListener('input', calculateSubtotal);
    document.getElementById('jumlah_peserta').addEventListener('input', calculateSubtotal);
    document.querySelectorAll('input[name="penginapan"], input[name="transportasi"], input[name="makanan"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', calculateSubtotal);
    });

    function calculateSubtotal() {
        let durasi = parseFloat(document.getElementById('durasi').value) || 0;
        let layananTotal = Array.from(document.querySelectorAll('input[name="penginapan"]:checked, input[name="transportasi"]:checked, input[name="makanan"]:checked'))
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
