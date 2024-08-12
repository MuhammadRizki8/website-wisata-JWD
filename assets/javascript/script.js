$(document).ready(function () {
  $('#durasi, #jumlah_peserta').on('input', calculateSubtotal);
  $('input[name="penginapan"], input[name="transportasi"], input[name="makanan"]').on('change', calculateSubtotal);
  $('#paket_id').on('change', function () {
    let selectedPrice = $('#paket_id option:selected').data('harga');
    $('#harga_tiket').val(selectedPrice ? 'Rp. ' + new Intl.NumberFormat('id-ID').format(selectedPrice) : '');
    calculateSubtotal(); // Update subtotal ketika harga tiket berubah
  });

  function calculateSubtotal() {
    let durasi = parseFloat($('#durasi').val()) || 0;
    let layananTotal = $('input[name="penginapan"]:checked, input[name="transportasi"]:checked, input[name="makanan"]:checked')
      .map(function () {
        return parseFloat($(this).val());
      })
      .get()
      .reduce((a, b) => a + b, 0);
    let packagePrice = parseFloat($('#paket_id option:selected').data('harga')) || 0;
    let subtotal = packagePrice * durasi * layananTotal;
    $('#subtotal').val(subtotal);
    let jumlahPeserta = parseInt($('#jumlah_peserta').val()) || 0;
    let total = subtotal * jumlahPeserta;
    $('#total').val(total);
  }
});
