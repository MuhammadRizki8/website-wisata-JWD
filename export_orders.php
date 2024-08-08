<?php
require 'vendor/autoload.php'; // Pastikan path ini benar

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

require 'db.php';

// Fetch all orders
$stmt = $pdo->query('SELECT pesanan.*, paket_wisata.nama AS paket_nama FROM pesanan JOIN paket_wisata ON pesanan.paket_id = paket_wisata.id');
$orders = $stmt->fetchAll();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Orders');

// Set header
$headers = [
    'A1' => 'ID',
    'B1' => 'Start Date',
    'C1' => 'Duration',
    'D1' => 'Services',
    'E1' => 'Participants',
    'F1' => 'Subtotal',
    'G1' => 'Total',
    'H1' => 'Package',
    'I1' => 'Order Date',
    'J1' => 'Customer Name',
    'K1' => 'Phone Number'
];

foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header);
}

// Populate data
$row = 2;
foreach ($orders as $order) {
    $services = [];
    if ($order['penginapan']) $services[] = 'Penginapan';
    if ($order['transportasi']) $services[] = 'Transportasi';
    if ($order['makanan']) $services[] = 'Makanan';
    $serviceString = implode(', ', $services);

    $sheet->setCellValue('A' . $row, $order['id']);
    $sheet->setCellValue('B' . $row, $order['tanggal_mulai']);
    $sheet->setCellValue('C' . $row, $order['durasi']);
    $sheet->setCellValue('D' . $row, $serviceString);
    $sheet->setCellValue('E' . $row, $order['jumlah_peserta']);
    $sheet->setCellValue('F' . $row, $order['subtotal']);
    $sheet->setCellValue('G' . $row, $order['total']);
    $sheet->setCellValue('H' . $row, $order['paket_nama']);
    $sheet->setCellValue('I' . $row, $order['tanggal_pesanan']);
    $sheet->setCellValue('J' . $row, $order['nama_pemesan']);
    $sheet->setCellValue('K' . $row, $order['no_telepon']);

    $row++;
}

// Set response headers and output file
$writer = new Xlsx($spreadsheet);
$filename = 'orders.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
