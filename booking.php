<?php
require 'db.php'; // Menghubungkan ke database

// Mengambil data layanan dari database
$stmt = $pdo->query('SELECT * FROM layanan');
$services = $stmt->fetchAll();

// Mengambil semua paket wisata untuk dropdown
$stmt = $pdo->query('SELECT * FROM paket_wisata');
$all_packages = $stmt->fetchAll();

// Mengambil data paket wisata yang dipilih
$paket_id = isset($_GET['paket_id']) ? (int)$_GET['paket_id'] : 0;
$package = null;
if ($paket_id) {
    $stmt = $pdo->prepare('SELECT * FROM paket_wisata WHERE id = ?');
    $stmt->execute([$paket_id]);
    $package = $stmt->fetch();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Memproses data form yang dikirimkan
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $durasi = $_POST['durasi'];
    $jumlah_peserta = $_POST['jumlah_peserta'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $no_telepon = $_POST['no_telepon'];
    $paket_id = $_POST['paket_id'];

    // Memeriksa layanan tambahan yang dipilih
    $penginapan = isset($_POST['penginapan']) ? 1 : 0;
    $transportasi = isset($_POST['transportasi']) ? 1 : 0;
    $makanan = isset($_POST['makanan']) ? 1 : 0;
    $layanan = [];
    if ($penginapan) $layanan[] = 1000000;
    if ($transportasi) $layanan[] = 1200000;
    if ($makanan) $layanan[] = 500000;

    // Validasi form
    if (!$tanggal_mulai || !$durasi || !$jumlah_peserta || !$nama_pemesan || !$no_telepon || !$paket_id) {
        $error = 'Form pemesanan belum diisi lengkap.';
    } elseif (count($layanan) < 1) {
        $error = 'Minimal pilih satu layanan paket.';
    } else {
        // Menghitung subtotal
        $subtotal = array_sum($layanan) * $durasi;

        // Menghitung total
        $total = $subtotal * $jumlah_peserta;

        // Menyimpan data pesanan ke dalam tabel pesanan
        $stmt = $pdo->prepare('INSERT INTO pesanan (tanggal_mulai, durasi, jumlah_peserta, subtotal, total, tanggal_pesanan, paket_id, nama_pemesan, no_telepon, penginapan, transportasi, makanan) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$tanggal_mulai, $durasi, $jumlah_peserta, $subtotal, $total, $paket_id, $nama_pemesan, $no_telepon, $penginapan, $transportasi, $makanan]);

        // Redirect ke halaman daftar pesanan
        header('Location: orders.php?message=Order+added+successfully');
        exit();
    }
}
?>

<?php include 'header.php'; ?>
<div class="container col-lg-8">
    <h1 class="mt-4">Pesan Paket Wisata</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="mt-4 card p-4">
        <div class="row">
            <div class="col-md-6 mb-3">
            <label for="paket_id" class="form-label">Pilih Paket Wisata</label>
            <select class="form-select" id="paket_id" name="paket_id" required>
                <option value="">Pilih sebuah paket</option>
                <?php foreach ($all_packages as $pkg): ?>
                    <option value="<?= htmlspecialchars($pkg['id']) ?>" <?= ($pkg['id'] == $paket_id) ? 'selected' : '' ?>><?= htmlspecialchars($pkg['nama']) ?> - Rp. <?= number_format($pkg['harga'], 2) ?></option>
                <?php endforeach; ?>
            </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="harga_tiket" class="form-label">Harga Tiket</label>
                <input type="text" class="form-control" id="harga_tiket" name="harga_tiket" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="durasi" class="form-label">Durasi (hari)</label>
                <input type="number" class="form-control" id="durasi" name="durasi" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
            <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" required>
        </div>
        <div class="mb-3">
            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
        </div>
        <div class="mb-3">
            <label for="no_telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
        </div>
        <div class="mb-3">
            <label for="layanan" class="form-label">Pilih Layanan Tambahan</label>
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
        <div class="row">
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">Pesan</button>
            </div>
            <div class="col-md-1">
            <button type="button" class="btn btn-danger" onclick="window.location.href='index.php'">Batal</button>
            </div>
        
        </div>
        

    </form>
</div>
<?php include 'footer.php'; ?>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/javascript/script.js"></script>
</body>
</html>
