-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 06:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistembg`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(255) NOT NULL,
  `nama_admin` varchar(255) DEFAULT NULL,
  `email_admin` varchar(255) DEFAULT NULL,
  `alamat_admin` varchar(255) DEFAULT NULL,
  `no_hp_admin` varchar(255) DEFAULT NULL,
  `foto_admin` varchar(255) DEFAULT NULL,
  `kecamatan_admin` varchar(255) DEFAULT NULL,
  `nomor_dapur_admin` int(255) DEFAULT NULL,
  `password_admin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `email_admin`, `alamat_admin`, `no_hp_admin`, `foto_admin`, `kecamatan_admin`, `nomor_dapur_admin`, `password_admin`) VALUES
(29, 'admin test', 'admintest@gmail.com', 'Alamat test', '081234567890', 'admin test.jpg', 'Melinting', 1, '12345'),
(30, 'admin test kedua', 'admintest2@gmail.com', 'Jalan SMAN 2 Kalianda', '09876543212', 'admin test kedua.jpeg', 'Batanghari', 2, '12345');

-- --------------------------------------------------------

--
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` int(255) NOT NULL,
  `nama_bahan` varchar(255) DEFAULT NULL,
  `satuan_bahan` varchar(255) DEFAULT NULL,
  `limit_bahan` int(255) DEFAULT NULL,
  `keterangan_bahan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `satuan_bahan`, `limit_bahan`, `keterangan_bahan`) VALUES
(1, 'Cabai', 'gram', 5000, 'Sayuran'),
(2, 'Bawang', 'gram', 5000, 'Sayuran');

-- --------------------------------------------------------

--
-- Table structure for table `bahan_menu`
--

CREATE TABLE `bahan_menu` (
  `id_bahan_menu` int(255) NOT NULL,
  `id_menu_harian` int(255) DEFAULT NULL,
  `id_jadwal_menu_harian` int(255) DEFAULT NULL,
  `id_bahan` int(255) DEFAULT NULL,
  `tanggal_bahan_menu` date DEFAULT NULL,
  `nama_bahan_menu` varchar(255) DEFAULT NULL,
  `jumlah_bahan_menu` varchar(255) DEFAULT NULL,
  `satuan_bahan_menu` varchar(255) DEFAULT NULL,
  `nomor_dapur_bahan_menu` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bahan_menu`
--

INSERT INTO `bahan_menu` (`id_bahan_menu`, `id_menu_harian`, `id_jadwal_menu_harian`, `id_bahan`, `tanggal_bahan_menu`, `nama_bahan_menu`, `jumlah_bahan_menu`, `satuan_bahan_menu`, `nomor_dapur_bahan_menu`) VALUES
(2, 1, 7, 1, '2025-10-15', 'Cabai', '3000', 'gram', 1),
(3, 1, 7, 1, '2025-10-15', 'Cabai', '2000', 'gram', 1),
(4, 1, 7, 2, '2025-10-15', 'Bawang', '5000', 'gram', 1),
(8, 2, 2, 2, '2025-10-13', 'Bawang', '5000', 'gram', 1),
(9, 2, 2, 1, '2025-10-13', 'Cabai', '5000', 'gram', 1),
(10, 1, 9, 2, '2025-10-19', 'Bawang', '2500', 'gram', 1),
(11, 1, 9, 2, '2025-10-19', 'Bawang', '2500', 'gram', 1),
(12, 1, 9, 1, '2025-10-19', 'Cabai', '5000', 'gram', 1);

-- --------------------------------------------------------

--
-- Table structure for table `barang_modal_keluar`
--

CREATE TABLE `barang_modal_keluar` (
  `id_barang_modal_keluar` int(255) NOT NULL,
  `id_data_koperasi` int(255) DEFAULT NULL,
  `nomor_dapur_barang_modal_keluar` int(255) DEFAULT NULL,
  `nama_barang_modal_keluar` varchar(255) DEFAULT NULL,
  `jumlah_barang_modal_keluar` int(255) DEFAULT NULL,
  `satuan_barang_modal_keluar` varchar(255) DEFAULT NULL,
  `harga_barang_modal_keluar` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barang_modal_keluar`
--

INSERT INTO `barang_modal_keluar` (`id_barang_modal_keluar`, `id_data_koperasi`, `nomor_dapur_barang_modal_keluar`, `nama_barang_modal_keluar`, `jumlah_barang_modal_keluar`, `satuan_barang_modal_keluar`, `harga_barang_modal_keluar`) VALUES
(1, 24, 1, 'Kompor Gas', 5, 'pcs', 250000),
(2, 38, 1, 'Beras', 10, 'kg', 250000),
(3, 38, 1, 'Ulekan', 10, 'pcs', 300000),
(4, 38, 1, 'Kuali', 10, 'pcs', 200000),
(5, 38, 1, 'Kangkung', 15, 'iket', 150000);

-- --------------------------------------------------------

--
-- Table structure for table `barang_supplier`
--

CREATE TABLE `barang_supplier` (
  `id_barang_supplier` int(255) NOT NULL,
  `id_informasi_supplier` int(255) DEFAULT NULL,
  `nomor_dapur_barang_supplier` int(255) DEFAULT NULL,
  `nama_barang_supplier` varchar(255) DEFAULT NULL,
  `jumlah_barang_supplier` int(255) DEFAULT NULL,
  `satuan_barang_supplier` varchar(255) DEFAULT NULL,
  `harga_barang_supplier` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barang_supplier`
--

INSERT INTO `barang_supplier` (`id_barang_supplier`, `id_informasi_supplier`, `nomor_dapur_barang_supplier`, `nama_barang_supplier`, `jumlah_barang_supplier`, `satuan_barang_supplier`, `harga_barang_supplier`) VALUES
(34, 1, 1, 'Kuali', 10, 'pcs', 25000),
(38, 1, 1, 'Beras', 10, 'kg', 500000),
(51, 1, 1, 'Kompor Gas', 10, 'pcs', 500000),
(52, 1, 1, 'Bawang', 15, 'kg', 400000),
(53, 1, 1, 'Paprika', 20, 'kg', 500000);

-- --------------------------------------------------------

--
-- Table structure for table `dapur`
--

CREATE TABLE `dapur` (
  `id_dapur` int(255) NOT NULL,
  `nomor_dapur` int(255) DEFAULT NULL,
  `nama_dapur` varchar(255) DEFAULT NULL,
  `dapur_kecamatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dapur`
--

INSERT INTO `dapur` (`id_dapur`, `nomor_dapur`, `nama_dapur`, `dapur_kecamatan`) VALUES
(14, 1, 'Dapur 1', 'Melinting'),
(15, 1, 'Dapur 1', 'Metro Kibang'),
(16, 1, 'Dapur 1', 'Raman Utara'),
(18, 2, 'Dapur 2', 'Melinting'),
(19, 2, 'Dapur 2', 'Purbolinggo'),
(20, 2, 'Dapur 2', 'Pasir Sakti');

-- --------------------------------------------------------

--
-- Table structure for table `data_koperasi`
--

CREATE TABLE `data_koperasi` (
  `id_data_koperasi` int(11) NOT NULL,
  `id_informasi_supplier` int(255) DEFAULT NULL,
  `nomor_dapur_data_koperasi` int(255) DEFAULT NULL,
  `tanggal_data_koperasi` date DEFAULT NULL,
  `jenis_data_koperasi` varchar(255) DEFAULT NULL,
  `kategori_data_koperasi` varchar(255) DEFAULT NULL,
  `harga_data_koperasi` int(255) DEFAULT NULL,
  `status_data_koperasi` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_koperasi`
--

INSERT INTO `data_koperasi` (`id_data_koperasi`, `id_informasi_supplier`, `nomor_dapur_data_koperasi`, `tanggal_data_koperasi`, `jenis_data_koperasi`, `kategori_data_koperasi`, `harga_data_koperasi`, `status_data_koperasi`) VALUES
(17, 1, 1, '2025-10-24', 'modal_keluar', 'Pembelian bahan dari supplier', NULL, 0),
(18, 1, 1, '2025-10-24', 'modal_masuk', 'Pemerintah Anggaran', 6000000, 0),
(24, NULL, 1, '2025-10-25', 'modal_keluar', 'Belanja Alat', 1000000, 1),
(25, NULL, 1, '2025-10-25', 'modal_masuk', 'Donasi Panti', 2000000, 1),
(37, NULL, 1, '2025-11-03', 'modal_masuk', 'Donasi Panti', 2000000, 0),
(38, NULL, 1, '2025-11-03', 'modal_keluar', 'Belanja Alat', 0, 0),
(39, NULL, 1, '2025-11-03', 'modal_masuk', 'Anggaran Pemerintah', 1000000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `data_pekerja`
--

CREATE TABLE `data_pekerja` (
  `id_data_pekerja` int(255) NOT NULL,
  `nomor_dapur_data_pekerja` int(255) DEFAULT NULL,
  `nama_data_pekerja` varchar(255) DEFAULT NULL,
  `peran_data_pekerja` varchar(255) DEFAULT NULL,
  `no_hp_data_pekerja` varchar(255) DEFAULT NULL,
  `foto_data_pekerja` varchar(255) DEFAULT NULL,
  `ktp_data_pekerja` varchar(255) DEFAULT NULL,
  `status_validasi_data_pekerja` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `data_pekerja`
--

INSERT INTO `data_pekerja` (`id_data_pekerja`, `nomor_dapur_data_pekerja`, `nama_data_pekerja`, `peran_data_pekerja`, `no_hp_data_pekerja`, `foto_data_pekerja`, `ktp_data_pekerja`, `status_validasi_data_pekerja`) VALUES
(13, 2, 'Rahma Tia', 'Petugas Pembersih', '081234567890', 'Foto_Rahma Tia.png', 'KTP_Rahma Tia.png', 1),
(14, 1, 'Aldian Alfi Putra', 'Juru Masak', '081234567890', 'Foto_Aldian Alfi Putra.png', 'KTP_Aldian Alfi Putra.png', 2),
(16, 2, 'Gilang Azka', 'Penata Makanan', '081234567890', 'Foto_Gilang Azka.png', 'KTP_Gilang Azka.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `distribusi`
--

CREATE TABLE `distribusi` (
  `id_distribusi` int(255) NOT NULL,
  `nomor_dapur_distribusi` int(255) DEFAULT NULL,
  `nama_distributor` varchar(255) DEFAULT NULL,
  `kecamatan_sekolah` varchar(255) DEFAULT NULL,
  `tanggal_distribusi` date DEFAULT NULL,
  `tujuan_distribusi` varchar(255) DEFAULT NULL,
  `jumlah_paket` int(255) DEFAULT NULL,
  `menu_makanan` varchar(255) DEFAULT NULL,
  `bukti_pengiriman` varchar(255) DEFAULT NULL,
  `kendala_distribusi` varchar(255) DEFAULT NULL,
  `lokasi_distribusi` varchar(255) DEFAULT NULL,
  `status_distribusi` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `distribusi`
--

INSERT INTO `distribusi` (`id_distribusi`, `nomor_dapur_distribusi`, `nama_distributor`, `kecamatan_sekolah`, `tanggal_distribusi`, `tujuan_distribusi`, `jumlah_paket`, `menu_makanan`, `bukti_pengiriman`, `kendala_distribusi`, `lokasi_distribusi`, `status_distribusi`) VALUES
(13, 1, 'distributor test', 'Raman Utara', '2025-10-30', 'SMAN 1 Kalianda', 100, 'Tumis Kangkung', 'Bukti Terima_distributor test_2025-10-18_11-33-47.png', NULL, '-5.736859,105.59143800000001', 1),
(14, 1, 'distributor test', 'Raman Utara', '2025-10-30', 'SMAN 3 Kalianda', 100, 'Tumis Kangkung', 'Bukti Terima_distributor test_2025-10-18_11-35-22.png', NULL, '-5.736859,105.59143800000001', 1),
(15, 1, 'distributor test', 'Raman Utara', '2025-10-30', 'SMAN 2 Kalianda', 200, 'Tumis Kangkung', 'Bukti Terima_distributor test_2025-10-25_12-41-11.png', NULL, '-5.7367975,105.591852', 0),
(16, 1, 'distributor test', 'Raman Utara', '2025-10-25', 'SMAN 2 Kalianda', 200, 'Tumis Kangkung', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `distributor`
--

CREATE TABLE `distributor` (
  `id_distributor` int(255) NOT NULL,
  `nama_distributor` varchar(255) DEFAULT NULL,
  `email_distributor` varchar(255) DEFAULT NULL,
  `alamat_distributor` varchar(255) DEFAULT NULL,
  `no_hp_distributor` varchar(255) DEFAULT NULL,
  `foto_distributor` varchar(255) DEFAULT NULL,
  `kecamatan_distributor` varchar(255) DEFAULT NULL,
  `nomor_dapur_distributor` int(255) DEFAULT NULL,
  `password_distributor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `distributor`
--

INSERT INTO `distributor` (`id_distributor`, `nama_distributor`, `email_distributor`, `alamat_distributor`, `no_hp_distributor`, `foto_distributor`, `kecamatan_distributor`, `nomor_dapur_distributor`, `password_distributor`) VALUES
(10, 'distributor test', 'distributortest@gmail.com', 'Di Rumah', '080987654321', 'distributor test.jpeg', 'Labuhan Maringgai', 1, '12345'),
(11, 'distributor test kedua', 'distributortest2@gmail.com', 'Jalan Depan Bandar No.100', '081234567890', 'distributor test kedua.png', 'Labuhan Maringgai', 2, '12345');

-- --------------------------------------------------------

--
-- Table structure for table `informasi_supplier`
--

CREATE TABLE `informasi_supplier` (
  `id_informasi_supplier` int(255) DEFAULT NULL,
  `nama_informasi_supplier` varchar(255) DEFAULT NULL,
  `nomor_dapur_informasi_supplier` int(255) DEFAULT NULL,
  `nota_informasi_supplier` varchar(255) DEFAULT NULL,
  `bukti_terima_informasi_supplier` varchar(255) DEFAULT NULL,
  `status_informasi_supplier` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `informasi_supplier`
--

INSERT INTO `informasi_supplier` (`id_informasi_supplier`, `nama_informasi_supplier`, `nomor_dapur_informasi_supplier`, `nota_informasi_supplier`, `bukti_terima_informasi_supplier`, `status_informasi_supplier`) VALUES
(1, 'Dila Cantika Putri', 1, 'Nota_Dila Cantika Putri.png', 'Bukti Terima_Dila Cantika Putri.png', 0),
(4, 'Gibran Rakabuming', 1, 'Nota_Gibran Rakabuming.jpeg', 'Bukti Terima_Gibran Rakabuming.png', 0),
(9, 'Ari Apendi', 2, 'Nota_Ari Apendi.png', 'Bukti Terima_Ari Apendi.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_menu_harian`
--

CREATE TABLE `jadwal_menu_harian` (
  `id_jadwal_menu_harian` int(255) NOT NULL,
  `id_menu_harian` int(255) DEFAULT NULL,
  `tanggal_jadwal_menu_harian` date DEFAULT NULL,
  `jumlah_porsi_menu_harian` int(255) DEFAULT NULL,
  `kendala_jadwal_menu_harian` varchar(255) DEFAULT NULL,
  `nomor_dapur_jadwal_menu_harian` int(255) DEFAULT NULL,
  `status_jadwal_menu_harian` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jadwal_menu_harian`
--

INSERT INTO `jadwal_menu_harian` (`id_jadwal_menu_harian`, `id_menu_harian`, `tanggal_jadwal_menu_harian`, `jumlah_porsi_menu_harian`, `kendala_jadwal_menu_harian`, `nomor_dapur_jadwal_menu_harian`, `status_jadwal_menu_harian`) VALUES
(2, 2, '2025-10-13', 350, NULL, 1, 2),
(7, 1, '2025-10-15', 200, 'Gas Habis', 1, 1),
(9, 1, '2025-10-19', 200, 'Stok hampir habis', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kepala_dapur`
--

CREATE TABLE `kepala_dapur` (
  `id` int(255) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `nomor_dapur_kepala_dapur` int(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kepala_dapur`
--

INSERT INTO `kepala_dapur` (`id`, `nama_lengkap`, `email`, `alamat`, `no_hp`, `foto`, `kecamatan`, `nomor_dapur_kepala_dapur`, `password`) VALUES
(24, 'kepala dapur test', 'kepaladapurtest@gmail.com', 'Alamat Kepala Dapur Test', '081234567890', 'kepala dapur test.jpg', 'Melinting', 1, '12345'),
(25, 'kepala dapur test kedua', 'kepaladapurtest2@gmail.com', 'Jalan Besar Batang Hari', '088888888888', 'kepala dapur test kedua.png', 'Gunung Pelindung', 2, '12345');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id_laporan_keuangan` int(255) NOT NULL,
  `id_data_koperasi` int(255) DEFAULT NULL,
  `id_informasi_supplier` int(255) DEFAULT NULL,
  `nomor_dapur_keuangan` int(255) DEFAULT NULL,
  `tanggal_laporan_keuangan` date DEFAULT NULL,
  `jenis_transaksi` varchar(255) DEFAULT NULL,
  `kategori_laporan_keuangan` varchar(255) DEFAULT NULL,
  `keterangan_laporan_keuangan` varchar(255) DEFAULT NULL,
  `jumlah_dana` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `keuangan`
--

INSERT INTO `keuangan` (`id_laporan_keuangan`, `id_data_koperasi`, `id_informasi_supplier`, `nomor_dapur_keuangan`, `tanggal_laporan_keuangan`, `jenis_transaksi`, `kategori_laporan_keuangan`, `keterangan_laporan_keuangan`, `jumlah_dana`) VALUES
(3, NULL, 1, 1, '2025-10-22', 'Pemasukan', 'Anggaran Pemerintah', '-', 1500000),
(4, NULL, 1, 1, '2025-10-22', 'Pengeluaran', 'Belanja Bahan Dapur', '-', 700000),
(5, 18, NULL, 1, '2025-10-23', 'Pemasukan', 'Anggaran Pemerintah', '-', 800000),
(6, 17, NULL, 1, '2025-10-23', 'Pengeluaran', 'Belanja Bahan Dapur', '-', 400000),
(38, 24, NULL, 1, '2025-10-25', 'Pengeluaran', NULL, NULL, NULL),
(39, 25, NULL, 1, '2025-10-25', 'Pemasukan', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_harian`
--

CREATE TABLE `menu_harian` (
  `id_menu_harian` int(255) NOT NULL,
  `nama_menu_harian` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `menu_harian`
--

INSERT INTO `menu_harian` (`id_menu_harian`, `nama_menu_harian`) VALUES
(1, 'Sayur Asem'),
(2, 'Tumis Kangkung');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_08_03_023211_create_presensi_table', 1),
(6, '2024_08_03_034806_create_pengajuan_izin_table', 1),
(7, '2024_08_03_035641_create_murid_table', 1),
(8, '2024_08_03_040109_create_konfigurasi_lokasi_table', 1),
(9, '2024_08_03_040307_create_jurusan_table', 1),
(10, '2025_02_25_000758_create_presensi_table', 2),
(11, '2025_04_16_232632_create_riwayat_pelanggaran_table', 2),
(13, '2025_04_21_234729_create_libur_sekolah_table', 3),
(14, '2025_05_13_112838_create_jamsekolah_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id`, `name`, `email`, `password`) VALUES
(1, 'Owner', 'owner@gmail.com', '$2y$10$GepN6CP3AcloKXUglciQueo2m2/b0AYUhvHhE4hF6EJb4JS6IEtmO');

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `id_stok` int(255) NOT NULL,
  `nomor_dapur_stok` int(255) DEFAULT NULL,
  `nama_kepala_dapur` varchar(255) DEFAULT NULL,
  `nama_stok` varchar(255) DEFAULT NULL,
  `tanggal_masuk_stok` date DEFAULT NULL,
  `jumlah_stok_masuk` int(255) DEFAULT NULL,
  `tanggal_keluar_stok` date DEFAULT NULL,
  `jumlah_stok_keluar` int(255) DEFAULT NULL,
  `sisa_stok` int(255) DEFAULT NULL,
  `satuan_stok` varchar(255) DEFAULT NULL,
  `sumber_masuk_stok` varchar(255) DEFAULT NULL,
  `tujuan_penggunaan_stok` varchar(255) DEFAULT NULL,
  `tanggal_kadaluarsa_stok` date DEFAULT NULL,
  `status_stok` int(10) DEFAULT NULL COMMENT '0 (Habis); 1 (Hampir Habis); 2 (Tersedia); 3 (Hampir Kadaluarsa); 4 (Hampir Habis dan Hampir Kadaluarsa); 5 (Kadaluarsa)',
  `keterangan_stok` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`id_stok`, `nomor_dapur_stok`, `nama_kepala_dapur`, `nama_stok`, `tanggal_masuk_stok`, `jumlah_stok_masuk`, `tanggal_keluar_stok`, `jumlah_stok_keluar`, `sisa_stok`, `satuan_stok`, `sumber_masuk_stok`, `tujuan_penggunaan_stok`, `tanggal_kadaluarsa_stok`, `status_stok`, `keterangan_stok`) VALUES
(1, 1, 'Kevin', 'Cabai', '2025-09-22', 15, '2025-09-23', 7, 8, 'kg', 'Belanja bahan', 'Untuk membuat menu telur sambal', '2025-09-30', 2, 'Tidak ada'),
(2, 1, 'Kevin', 'Cabai Keriting', '2025-09-21', 15, '2025-09-24', 8, 0, 'Kg', 'Donasi', 'Untuk menu sayur tumis', '2025-09-30', 0, 'Habis'),
(3, 1, 'Kevin', 'Bawang', '2025-09-22', 10, '2025-09-23', 8, 2, 'kg', 'Donasi Panti Asuhan', 'Untuk menu tempe sambal', '2025-10-02', 4, 'Kadaluarsa'),
(4, 2, 'Sri Mulya W.', 'Mie', '2025-10-10', 10000, NULL, NULL, NULL, 'gram', 'Donasi', NULL, NULL, NULL, 'Tidak ada'),
(7, 1, 'Kevin', 'Toge', '2025-09-23', 10000, NULL, NULL, 10000, 'gram', 'Belanja Bahan', NULL, NULL, 3, '-');

-- --------------------------------------------------------

--
-- Table structure for table `stok_keluar`
--

CREATE TABLE `stok_keluar` (
  `id_stok_keluar` int(255) NOT NULL,
  `id_stok_masuk` int(255) DEFAULT NULL,
  `id_bahan` int(255) DEFAULT NULL,
  `nomor_dapur_stok_keluar` int(255) DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `jumlah_keluar` int(255) DEFAULT NULL,
  `tujuan_stok_keluar` varchar(255) DEFAULT NULL,
  `keterangan_stok_keluar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stok_keluar`
--

INSERT INTO `stok_keluar` (`id_stok_keluar`, `id_stok_masuk`, `id_bahan`, `nomor_dapur_stok_keluar`, `tanggal_keluar`, `jumlah_keluar`, `tujuan_stok_keluar`, `keterangan_stok_keluar`) VALUES
(16, 16, 1, 1, '2025-10-04', 9000, 'Dimasak', '-'),
(22, 17, 2, 1, '2025-10-05', 10000, 'Dimasak', '-'),
(23, 16, 1, 1, '2025-10-05', 6000, 'Dimasak', '-');

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id_stok_masuk` int(255) NOT NULL,
  `id_bahan` int(255) DEFAULT NULL,
  `nomor_dapur_stok_masuk` int(255) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_kadaluarsa` date DEFAULT NULL,
  `jumlah_masuk` int(255) DEFAULT NULL,
  `sumber_stok_masuk` varchar(255) DEFAULT NULL,
  `keterangan_stok_masuk` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stok_masuk`
--

INSERT INTO `stok_masuk` (`id_stok_masuk`, `id_bahan`, `nomor_dapur_stok_masuk`, `tanggal_masuk`, `tanggal_kadaluarsa`, `jumlah_masuk`, `sumber_stok_masuk`, `keterangan_stok_masuk`) VALUES
(16, 1, 1, '2025-10-02', '2025-10-26', 15000, 'Belanja di Pasar', '-'),
(17, 2, 1, '2025-10-02', '2025-10-25', 15000, 'Donasi', '-'),
(18, 1, 1, '2025-10-04', '2025-10-27', 15000, 'Donasi', '-');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(255) NOT NULL,
  `nomor_dapur_supplier` int(255) DEFAULT NULL,
  `nama_supplier` varchar(255) DEFAULT NULL,
  `alamat_supplier` varchar(255) DEFAULT NULL,
  `no_hp_supplier` varchar(255) DEFAULT NULL,
  `status_supplier` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nomor_dapur_supplier`, `nama_supplier`, `alamat_supplier`, `no_hp_supplier`, `status_supplier`) VALUES
(1, 1, 'Dila Cantika Putri', 'Sukadana', '088975660169', 0),
(4, 1, 'Gibran Rakabuming', 'Pekalongan', '086975666969', 0),
(12, 1, 'Diah Cantika Putri', 'Jalan Kedaton', '08655645634555', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indexes for table `bahan_menu`
--
ALTER TABLE `bahan_menu`
  ADD PRIMARY KEY (`id_bahan_menu`);

--
-- Indexes for table `barang_modal_keluar`
--
ALTER TABLE `barang_modal_keluar`
  ADD PRIMARY KEY (`id_barang_modal_keluar`);

--
-- Indexes for table `barang_supplier`
--
ALTER TABLE `barang_supplier`
  ADD PRIMARY KEY (`id_barang_supplier`);

--
-- Indexes for table `dapur`
--
ALTER TABLE `dapur`
  ADD PRIMARY KEY (`id_dapur`);

--
-- Indexes for table `data_koperasi`
--
ALTER TABLE `data_koperasi`
  ADD PRIMARY KEY (`id_data_koperasi`);

--
-- Indexes for table `data_pekerja`
--
ALTER TABLE `data_pekerja`
  ADD PRIMARY KEY (`id_data_pekerja`);

--
-- Indexes for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD PRIMARY KEY (`id_distribusi`);

--
-- Indexes for table `distributor`
--
ALTER TABLE `distributor`
  ADD PRIMARY KEY (`id_distributor`);

--
-- Indexes for table `informasi_supplier`
--
ALTER TABLE `informasi_supplier`
  ADD UNIQUE KEY `id_informasi_supplier` (`id_informasi_supplier`);

--
-- Indexes for table `jadwal_menu_harian`
--
ALTER TABLE `jadwal_menu_harian`
  ADD PRIMARY KEY (`id_jadwal_menu_harian`);

--
-- Indexes for table `kepala_dapur`
--
ALTER TABLE `kepala_dapur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id_laporan_keuangan`);

--
-- Indexes for table `menu_harian`
--
ALTER TABLE `menu_harian`
  ADD PRIMARY KEY (`id_menu_harian`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id_stok`);

--
-- Indexes for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD PRIMARY KEY (`id_stok_keluar`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id_stok_masuk`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id_bahan` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bahan_menu`
--
ALTER TABLE `bahan_menu`
  MODIFY `id_bahan_menu` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `barang_modal_keluar`
--
ALTER TABLE `barang_modal_keluar`
  MODIFY `id_barang_modal_keluar` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `barang_supplier`
--
ALTER TABLE `barang_supplier`
  MODIFY `id_barang_supplier` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `dapur`
--
ALTER TABLE `dapur`
  MODIFY `id_dapur` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `data_koperasi`
--
ALTER TABLE `data_koperasi`
  MODIFY `id_data_koperasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `data_pekerja`
--
ALTER TABLE `data_pekerja`
  MODIFY `id_data_pekerja` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `distribusi`
--
ALTER TABLE `distribusi`
  MODIFY `id_distribusi` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `distributor`
--
ALTER TABLE `distributor`
  MODIFY `id_distributor` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jadwal_menu_harian`
--
ALTER TABLE `jadwal_menu_harian`
  MODIFY `id_jadwal_menu_harian` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kepala_dapur`
--
ALTER TABLE `kepala_dapur`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id_laporan_keuangan` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `menu_harian`
--
ALTER TABLE `menu_harian`
  MODIFY `id_menu_harian` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id_stok` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  MODIFY `id_stok_keluar` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id_stok_masuk` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
