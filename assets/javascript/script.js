$(document).ready(function () {
  $('#durasi, #jumlah_peserta').on('input', calculateSubtotal);
  $('input[name="penginapan"], input[name="transportasi"], input[name="makanan"]').on('change', calculateSubtotal);

  function calculateSubtotal() {
    let durasi = parseFloat($('#durasi').val()) || 0;
    let layananTotal = $('input[name="penginapan"]:checked, input[name="transportasi"]:checked, input[name="makanan"]:checked')
      .map(function () {
        return parseFloat($(this).val());
      })
      .get()
      .reduce((a, b) => a + b, 0);
    let subtotal = durasi * layananTotal;
    $('#subtotal').val(subtotal);
    let jumlahPeserta = parseInt($('#jumlah_peserta').val()) || 0;
    let total = subtotal * jumlahPeserta;
    $('#total').val(total);
  }
});
