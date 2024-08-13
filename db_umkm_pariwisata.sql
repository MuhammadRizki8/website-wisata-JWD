-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Agu 2024 pada 16.34
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_umkm_pariwisata`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id`, `nama`, `harga`) VALUES
(1, 'Penginapan', '1000000.00'),
(2, 'Transportasi', '1200000.00'),
(3, 'Makanan', '500000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket_wisata`
--

CREATE TABLE `paket_wisata` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `paket_wisata`
--

INSERT INTO `paket_wisata` (`id`, `nama`, `deskripsi`, `harga`, `image_url`) VALUES
(1, 'Paket Wisata Pulau', 'Nikmati indahnya pulau dengan berbagai aktivitas menarik.', '300000.00', 'https://nibble-images.b-cdn.net/nibble/original_images/tempat_wisata_alam_di_bandung_2.jpg'),
(2, 'Paket Wisata Pegunungan', 'Eksplorasi pegunungan dan nikmati pemandangan alam yang menakjubkan.', '200000.00', 'https://static.promediateknologi.id/crop/0x0:0x0/0x0/webp/photo/p2/80/2023/10/21/pura-ulun-95231650.jpg'),
(3, 'Wisata Kuliner', 'Jelajahi ragam kuliner yang menggugah selera', '170000.00', 'https://ik.imagekit.io/tvlk/blog/2020/01/wisata-kuliner-street-food-1-Wikimedia.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `durasi` int(11) NOT NULL,
  `jumlah_peserta` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `tanggal_pesanan` date NOT NULL,
  `paket_id` int(11) NOT NULL,
  `nama_pemesan` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `penginapan` tinyint(1) DEFAULT 0,
  `transportasi` tinyint(1) DEFAULT 0,
  `makanan` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `tanggal_mulai`, `durasi`, `jumlah_peserta`, `subtotal`, `total`, `tanggal_pesanan`, `paket_id`, `nama_pemesan`, `no_telepon`, `penginapan`, `transportasi`, `makanan`) VALUES
(24, '2024-08-12', 1, 1, '800000.00', '800000.00', '2024-08-12', 1, 'Budi Mencari Jati Diri', '08123123', 0, 0, 1),
(27, '2024-08-12', 2, 2, '6000000.00', '12000000.00', '2024-08-12', 1, 'error ga ya?', '08123123', 1, 1, 1),
(29, '2024-08-12', 2, 2, '6000000.00', '12000000.00', '2024-08-12', 1, 'ajad berhasil pesen', '08123123', 1, 1, 1),
(30, '2024-08-13', 1, 1, '1800000.00', '1800000.00', '2024-08-13', 1, 'Ijat Pendekar Sakti', '08123123', 1, 0, 1),
(31, '2024-08-13', 1, 2, '3000000.00', '6000000.00', '2024-08-13', 1, 'Siti Anderson', '08123123', 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `paket_wisata`
--
ALTER TABLE `paket_wisata`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_pesanan_paket` (`paket_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `paket_wisata`
--
ALTER TABLE `paket_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `FK_pesanan_paket` FOREIGN KEY (`paket_id`) REFERENCES `paket_wisata` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
