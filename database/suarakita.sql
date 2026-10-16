-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Mar 2026 pada 10.15
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suarakita`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL DEFAULT 'Fasilitas',
  `urgensi` enum('rendah','sedang','tinggi','darurat') NOT NULL DEFAULT 'sedang',
  `judul` varchar(100) NOT NULL,
  `isi` text NOT NULL,
  `lampiran` varchar(255) DEFAULT NULL,
  `is_anonim` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('dikirim','terkirim','ditinjau','diproses','selesai','ditolak') DEFAULT 'terkirim',
  `rating` tinyint(1) DEFAULT NULL,
  `ulasan_rating` text DEFAULT NULL,
  `alasan_penolakan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `siswa_id`, `sekolah_id`, `judul`, `isi`, `status`, `created_at`, `updated_at`) VALUES
(8, 2, 1, 'Kondisi Toilet Siswa Rusak dan Kurang Layak', 'Saya ingin menyampaikan pengaduan terkait kondisi toilet siswa di gedung B lantai 2. Beberapa pintu toilet rusak, air sering tidak mengalir, dan kebersihannya kurang terjaga.\r\n\r\nKondisi ini cukup mengganggu kenyamanan siswa, terutama saat jam pelajaran berlangsung. Saya berharap pihak sekolah dapat melakukan perbaikan dan perawatan secara berkala agar fasilitas dapat digunakan dengan layak.', 'selesai', '2026-01-01 04:52:24', '2026-01-01 04:58:30'),
(9, 7, 1, 'Area Parkir Kurang Aman', 'Saya ingin menyampaikan keluhan mengenai area parkir sepeda siswa yang kurang aman. Beberapa kali terjadi kehilangan helm dan sepeda tidak tertata dengan baik.\r\n\r\nSaya berharap pihak sekolah dapat menambah pengawasan atau fasilitas keamanan agar siswa merasa lebih nyaman dan aman.', 'diproses', '2026-01-01 05:00:23', '2026-01-01 05:01:45'),
(10, 2, 1, 'Keterlambatan Guru Masuk Kelas', 'Saya ingin melaporkan bahwa terdapat guru yang beberapa kali datang terlambat ke kelas tanpa adanya pemberitahuan sebelumnya.\r\n\r\nHal ini menyebabkan waktu belajar menjadi berkurang dan siswa menjadi menunggu cukup lama di kelas. Saya berharap hal ini dapat menjadi perhatian agar proses belajar mengajar berjalan lebih efektif.', 'diproses', '2026-01-01 05:11:20', '2026-01-01 05:14:57'),
(11, 12, 1, 'Keluhan terhadap dosen', 'Terlalu cepat dalam menjelaskan materi di dalam kelas dan nada bicaranya terlalu kecil', 'diproses', '2026-02-28 07:14:39', '2026-02-28 07:16:03'),
(12, 13, 2, 'Keluhan terhadap akses jaringan', 'Jaringan di kampus kurang cepat, masih ada kendala ketika mencoba untuk menyambungkan jaringan', 'selesai', '2026-03-04 15:18:51', '2026-03-04 15:24:58'),
(13, 15, 2, 'Keluhan terhadap dosen', 'Terlalu kecil dan cepat saat menjelaskan materi', 'selesai', '2026-03-07 02:23:01', '2026-03-09 07:59:12'),
(14, 17, 2, 'Parkiran terlalu sempit', 'Parkiran masih sempit sehingga jalan terlalu macet, dan motor susah keluar, mohon pertimbangannya untuk memperluas lapangan', 'diproses', '2026-03-07 07:22:06', '2026-03-07 07:23:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sekolah`
--

CREATE TABLE `sekolah` (
  `id` int(11) NOT NULL,
  `nama_sekolah` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sekolah`
--

INSERT INTO `sekolah` (`id`, `nama_sekolah`, `alamat`) VALUES
(1, 'SMK AL-MADANI GARUT', 'Jl. Samarang'),
(2, 'INSTITUT TEKNOLOGI GARUT', 'Jl. Mayor Syamsu No. 1, Jayaraga, Kec. Tarogong Kidul, Kabupaten Garut, Jawa Barat, 44151'),
(3, 'MA MUSADDAD', 'Jayaraga');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tanggapan_pengaduan`
--

CREATE TABLE `tanggapan_pengaduan` (
  `id` int(11) NOT NULL,
  `pengaduan_id` int(11) NOT NULL,
  `pengirim` enum('siswa','admin') NOT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tanggapan_pengaduan`
--

INSERT INTO `tanggapan_pengaduan` (`id`, `pengaduan_id`, `pengirim`, `pesan`, `created_at`) VALUES
(13, 8, 'admin', 'Terima kasih atas laporan yang telah disampaikan. Pihak sekolah telah menerima pengaduan ini dan akan segera melakukan evaluasi serta tindak lanjut sesuai dengan ketentuan yang berlaku.', '2026-01-01 04:54:03'),
(14, 8, 'admin', 'ditunggun proses selanjutnya ya', '2026-01-01 04:57:15'),
(15, 9, 'admin', 'Terima kasih atas laporan yang telah disampaikan. Pihak sekolah telah menerima pengaduan ini dan akan segera melakukan evaluasi serta tindak lanjut sesuai dengan ketentuan yang berlaku.', '2026-01-01 05:01:53'),
(16, 10, 'admin', 'Terima kasih atas laporan yang telah disampaikan. Pihak sekolah telah menerima pengaduan ini dan akan segera melakukan evaluasi serta tindak lanjut', '2026-01-01 05:14:50'),
(17, 11, 'admin', 'Oke saya akan ambil tindakan', '2026-02-28 07:15:50'),
(18, 11, 'siswa', 'siappppp', '2026-02-28 07:16:41'),
(19, 11, 'siswa', 'plissss aku cantik bangettt dehhh', '2026-03-04 14:55:12'),
(20, 12, 'admin', 'Oke saya cek terlebih dahulu, mohon tunggu untuk proses nya', '2026-03-04 15:20:27'),
(21, 12, 'siswa', 'baik saya tunggu', '2026-03-04 15:21:14'),
(22, 12, 'admin', 'oke, pengaduan sudah di validasi', '2026-03-04 15:22:22'),
(23, 12, 'siswa', 'siap', '2026-03-04 15:23:03'),
(24, 13, 'admin', 'Oke, pengaduan anda akan kami proses', '2026-03-07 02:24:29'),
(25, 13, 'siswa', 'Oke ditunggu min', '2026-03-07 02:26:03'),
(26, 14, 'admin', 'Oke, nanti saya akan pertimbangkan', '2026-03-07 07:23:10'),
(27, 14, 'siswa', 'oke, ditunggu', '2026-03-07 07:24:34'),
(28, 14, 'admin', 'baik', '2026-03-09 03:57:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` enum('admin','siswa') NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `sekolah_id`, `username`, `password`, `level`, `nama`, `nis`, `kelas`, `foto`) VALUES
(1, 1, 'adminsmkalmadani', 'admin123', 'admin', NULL, NULL, NULL, 'profil_1.jpeg'),
(2, 1, 'syahrilganiakbar382', '1234567', 'siswa', 'Syahril Gani Akbar', '1234567', 'XII PPLG 2', NULL),
(3, 1, 'lutvijayadi647', '2345678', 'siswa', 'Lutvi Jayadi', '2345678', 'XII PPLG 2', NULL),
(6, 1, 'madani', 'admin123', 'admin', NULL, NULL, NULL, NULL),
(11, 2, 'adminitg', 'admin1234', 'admin', NULL, NULL, NULL, 'profil_11.png'),
(13, 2, 'nendennurdianti123', 'nenden@2406113', 'siswa', 'Nenden Nurdianti', '2406113', 'Informatika B', 'profil_13.png'),
(14, 2, 'ayupatmawati664', '2406112', 'siswa', 'Ayu Patmawati', '2406112', 'Informatika B', 'profil_14.jpeg'),
(15, 2, 'nengisanlopiani922', '2406179', 'siswa', 'Neng Isan Lopiani', '2406179', 'Informatika B', 'profil_15.jpg'),
(17, 2, 'ainaanastasya871', '2406123', 'siswa', 'Aina Anastasya', '2406123', 'Informatika C', 'profil_17.jpeg'),
(19, 2, 'melishaayufitria001', 'melisha@2406051', 'siswa', 'Melisha Ayu Fitria', '2406051', 'Informatika B', 'profil_19.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tanggapan_pengaduan`
--
ALTER TABLE `tanggapan_pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduan_id` (`pengaduan_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `sekolah_id` (`sekolah_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tanggapan_pengaduan`
--
ALTER TABLE `tanggapan_pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tanggapan_pengaduan`
--
ALTER TABLE `tanggapan_pengaduan`
  ADD CONSTRAINT `tanggapan_pengaduan_ibfk_1` FOREIGN KEY (`pengaduan_id`) REFERENCES `pengaduan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`sekolah_id`) REFERENCES `sekolah` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
