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
    $harga_tiket = $_POST['harga_tiket'];

    // Memeriksa layanan tambahan yang dipilih
    $layanan = [];
    if (isset($_POST['penginapan'])) $layanan[] = (int)$_POST['penginapan'];
    if (isset($_POST['transportasi'])) $layanan[] = (int)$_POST['transportasi'];
    if (isset($_POST['makanan'])) $layanan[] = (int)$_POST['makanan'];

    // Validasi form
    if (!$tanggal_mulai || !$durasi || !$jumlah_peserta || !$nama_pemesan || !$no_telepon || !$paket_id || !$harga_tiket) {
        $error = 'Form pemesanan belum diisi lengkap.';
    } else {
        echo $harga_tiket;
        // Menghitung subtotal
        $subtotal = (array_sum($layanan) + $harga_tiket) * $durasi;

        // Menghitung total
        $total = $subtotal * $jumlah_peserta;

        // Menyimpan data pesanan ke dalam tabel pesanan
        $stmt = $pdo->prepare('INSERT INTO pesanan (tanggal_mulai, durasi, jumlah_peserta, subtotal, total, tanggal_pesanan, paket_id, nama_pemesan, no_telepon, penginapan, transportasi, makanan) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$tanggal_mulai, $durasi, $jumlah_peserta, $subtotal, $total, $paket_id, $nama_pemesan, $no_telepon, isset($_POST['penginapan']), isset($_POST['transportasi']), isset($_POST['makanan'])]);

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
        <div class="mb-3">
            <label for="paket_id" class="form-label">Pilih Paket Wisata</label>
            <select class="form-select" id="paket_id" name="paket_id" required>
                <option value="">Pilih sebuah paket</option>
                <?php foreach ($all_packages as $pkg): ?>
                    <option value="<?= htmlspecialchars($pkg['id']) ?>" data-harga="<?= htmlspecialchars($pkg['harga']) ?>" <?= ($pkg['id'] == $paket_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pkg['nama']) ?> - Rp. <?= number_format($pkg['harga'], 2) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" class="form-control" id="harga_tiket" name="harga_tiket" value="<?= $package ? $package['harga'] : 0 ?>">
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
            <?php foreach ($services as $service): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="<?= htmlspecialchars(strtolower($service['nama'])) ?>" value="<?= htmlspecialchars($service['harga']) ?>" id="<?= htmlspecialchars($service['nama']) ?>">
                    <label class="form-check-label" for="<?= htmlspecialchars($service['nama']) ?>">
                        <?= htmlspecialchars($service['nama']) ?> - Rp. <?= number_format($service['harga'], 2) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Tambahkan elemen rincian perhitungan di sini -->
        <div class="mb-3">
            <label for="rincian_perhitungan" class="form-label">Rincian Perhitungan</label>
            <ul id="rincian_perhitungan" class="list-group">
                <li class="list-group-item">Harga Tiket: <span id="rincian_harga_tiket">Rp. 0</span></li>
                <li class="list-group-item">Total Layanan Tambahan: <span id="rincian_layanan">Rp. 0</span></li>
                <li class="list-group-item">Subtotal (Harga Tiket + Layanan Tambahan) x Durasi: <span class="font-weight-bold" id="rincian_subtotal">Rp. 0</span></li>
                <li class="list-group-item">Total (Subtotal x Jumlah Peserta): <span class="font-weight-bold" id="rincian_total">Rp. 0</span></li>
            </ul>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
  // Perhitungan subtotal dan total
  $('#durasi, #jumlah_peserta').on('input', calculateSubtotal);
  $('input[name="penginapan"], input[name="transportasi"], input[name="makanan"], #paket_id').on('change', calculateSubtotal);

  function calculateSubtotal() {
    let harga = $('#paket_id').find('option:selected').data('harga') || 0;
    let durasi = parseFloat($('#durasi').val()) || 0;
    let jumlahPeserta = parseFloat($('#jumlah_peserta').val()) || 0;
    let hargaTiket = parseFloat($('#paket_id').find('option:selected').data('harga')) || 0;

    let layanan = 0;
    $('input[type="checkbox"]:checked').each(function () {
      layanan += parseFloat($(this).val());
    });
    let subtotal = (hargaTiket + layanan) * durasi;
    let total = subtotal * jumlahPeserta;

    $('#harga_tiket').val(harga);
    $('#rincian_harga_tiket').text('Rp. ' + new Intl.NumberFormat('id-ID').format(harga));
    $('#rincian_layanan').text('Rp. ' + new Intl.NumberFormat('id-ID').format(layanan));
    $('#rincian_subtotal').text('Rp. ' + new Intl.NumberFormat('id-ID').format(subtotal));
    $('#rincian_total').text('Rp. ' + new Intl.NumberFormat('id-ID').format(total));
  }

  calculateSubtotal();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php include 'footer.php'; ?>
</body>
</html>