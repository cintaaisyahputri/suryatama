-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2026 at 04:37 PM
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
-- Database: `suryatama`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `due_date` date DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `order_id`, `label`, `amount`, `due_date`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'dp pemasangan usertest', 60000000, '2026-08-20', NULL, '2026-07-30 18:40:21', '2026-07-30 18:40:21'),
(2, 1, 'DP Pemasangan', 30000000, '2026-08-05', NULL, '2026-07-23 12:00:00', '2026-07-23 12:00:00'),
(3, 5, 'DP Pemasangan', 18000000, '2026-07-15', '2026-07-12 03:00:00', '2026-07-10 02:00:00', '2026-07-12 03:00:00'),
(4, 5, 'Pelunasan', 18000000, '2026-08-10', NULL, '2026-07-24 06:00:00', '2026-07-24 06:00:00'),
(5, 6, 'DP Pemasangan', 36000000, '2026-07-08', '2026-07-06 02:30:00', '2026-07-05 02:00:00', '2026-07-06 02:30:00'),
(6, 6, 'Pelunasan', 36000000, '2026-07-20', '2026-07-19 08:00:00', '2026-07-15 02:00:00', '2026-07-19 08:00:00'),
(7, 7, 'DP Pemasangan', 18000000, '2026-07-10', '2026-07-09 04:00:00', '2026-07-08 02:00:00', '2026-07-09 04:00:00'),
(8, 7, 'Pelunasan', 18000000, '2026-07-25', '2026-07-24 09:00:00', '2026-07-18 03:00:00', '2026-07-24 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_22_000001_add_location_to_users_table', 1),
(5, '2026_07_22_000002_create_orders_table', 1),
(6, '2026_07_22_000003_add_phone_to_users_table', 2),
(7, '2026_07_23_000001_add_schedule_to_orders_table', 2),
(8, '2026_07_23_000002_create_invoices_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu_survei',
  `scheduled_at` datetime DEFAULT NULL,
  `technician_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `capacity`, `city`, `address`, `status`, `scheduled_at`, `technician_name`, `created_at`, `updated_at`) VALUES
(1, 2, '5 kWp', NULL, 'jl coba coba', 'survei_terjadwal', NULL, NULL, '2026-07-22 19:26:49', '2026-07-23 18:56:02'),
(2, 6, '4 kWp', 'Bekasi', 'Jl. Melati No. 5, Bekasi', 'menunggu_survei', NULL, NULL, '2026-07-28 02:00:00', '2026-07-28 02:00:00'),
(3, 7, '2 kWp', 'Tangerang', 'Jl. Kenanga No. 12, Tangerang', 'survei_terjadwal', '2026-08-02 09:00:00', 'Bagas', '2026-07-20 03:00:00', '2026-07-24 04:00:00'),
(4, 8, '10 kWp', 'Bandung', 'Jl. Industri No. 88, Bandung', 'menunggu_survei', NULL, NULL, '2026-07-29 07:00:00', '2026-07-29 07:00:00'),
(5, 9, '3 kWp', 'Depok', 'Jl. Margonda No. 45, Depok', 'pemasangan', '2026-07-24 13:00:00', 'Andra, Fikri', '2026-07-10 01:00:00', '2026-07-24 06:00:00'),
(6, 10, '6 kWp', 'Surabaya', 'Jl. Diponegoro No. 21, Surabaya', 'aktif', '2026-07-15 09:00:00', 'Bagas', '2026-07-05 01:00:00', '2026-07-20 05:00:00'),
(7, 11, '3 kWp', 'Jakarta Selatan', 'Jl. Fatmawati No. 9, Jakarta Selatan', 'aktif', '2026-07-18 10:00:00', 'Andra, Fikri', '2026-07-08 01:00:00', '2026-07-22 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ONxEqjP3iYKtfm2zKFgZkWUMTbsSolx6ZkmT6bqK', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia2VFR3NRRWhCVUtsWnlKRGlYZjA4czdkaWdtV2UyR0Nvcm5RTVJ5cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnZvaWNlIjtzOjU6InJvdXRlIjtzOjEzOiJ1c2VyLmludm9pY2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1785153850),
('w9rTmjIja9DzvYTXnD4bo3SM25pbdpEn7OMfXMrY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.130.0 Chrome/148.0.7778.280 Electron/42.6.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlJtbll2OFdKOVBDaklKOEVQbW9CMmxrNWZBUVRwc2ZuOGdBTlNqNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1785460477),
('wfRYirqyAuWv7pMvwceR3IbmtYuP7PCU1d0k14im', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2lOZzVFMFBQRFdwcEp4SEJ1NHdSNkRUckh1UFd4QUpBbldJQVdLUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2ludm9pY2UiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1785211976),
('YtWtnBRZvEQbxLsWH5yXx5yupi89xnbqhu42HGYU', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZnpicTg4TndNMHM4NFZlTHNIaDQ5SnVaQjdkeW10cGVaNWM0dzZXRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1785462109),
('ZahSLdeHcwkWcKuwUZKEPyZ3cMKFVxRGpi0YodKU', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZVNHRDFueWJabm9wbzEwYXdjODVjR29Bajdsa2FRTWNrZUFCa2JJUiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1785473706);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `city` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `has_seen_tutorial` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `city`, `address`, `latitude`, `longitude`, `has_seen_tutorial`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', NULL, '2026-07-22 18:36:34', '$2y$12$ojvMSs7q08Z7pdtNUGElLuekzqJ2deSAaMCXLtfflXMnA0gmtBv6.', 'user', NULL, NULL, NULL, NULL, 0, '87QKeyBMin', '2026-07-22 18:36:34', '2026-07-22 18:36:34'),
(2, 'usertest', 'usertest@gmail.com', NULL, NULL, '$2y$12$D/K7KwVupd1I0k3bSo5HG...HZR2.OHh94lAHk1SnyT0pUmjULBLy', 'user', 'Purwokerto', 'Purwokerto, Central Java, Indonesia', -7.4213900, 109.2344400, 0, NULL, '2026-07-22 18:53:40', '2026-07-23 03:41:39'),
(3, 'admin', 'admin@gmail.com', NULL, NULL, '$2y$12$I.F.37guuZzzUGHvy53fH.iCl9jgqAXewsDOTnZe0H7SK1s1l4DBK', 'admin', 'Purwokerto', 'Purwokerto, Central Java, Indonesia', -7.4213900, 109.2344400, 0, 'WMwY3jpgpQOMbkqye6w36LRMbFznlafWkjshJnTRXJJdq605ZRxEKuRYXIRz', '2026-07-23 18:51:10', '2026-07-23 18:51:11'),
(4, 'Testing Akun', 'test1@suryatama.id', NULL, NULL, '$2y$12$.x1eQrx3GPdyDqRphT1nzORhVhZ5tes5w7qIt4bhHOTlNpK4uQwX2', 'user', NULL, NULL, NULL, NULL, 0, NULL, '2026-07-23 19:19:06', '2026-07-23 19:19:06'),
(5, 'user', 'test999@suryatama.id', NULL, NULL, '$2y$12$cYu2pB.Fx3MuHB5RBaFtW.nFHACpwBy33grdJR9bxzTQ0VsxjV0U2', 'user', NULL, NULL, NULL, NULL, 0, NULL, '2026-07-23 19:33:27', '2026-07-23 19:33:27'),
(6, 'Rahmat Hidayat', 'rahmat.hidayat@example.com', '081234500001', NULL, '$2b$12$pyNjeLA3e0RIWO6Z3laSNuGOy44rz1NYZMyda8hhIBZZwQIu8lVAm', 'user', 'Bekasi', 'Jl. Melati No. 5, Bekasi', -6.2382900, 106.9756400, 0, NULL, '2026-07-28 02:00:00', '2026-07-28 02:00:00'),
(7, 'Siti Aminah', 'siti.aminah@example.com', '081234500002', NULL, '$2b$12$pyNjeLA3e0RIWO6Z3laSNuGOy44rz1NYZMyda8hhIBZZwQIu8lVAm', 'user', 'Tangerang', 'Jl. Kenanga No. 12, Tangerang', -6.1783100, 106.6318900, 0, NULL, '2026-07-20 03:00:00', '2026-07-20 03:00:00'),
(8, 'CV Berkah Jaya', 'cv.berkahjaya@example.com', '081234500003', NULL, '$2b$12$pyNjeLA3e0RIWO6Z3laSNuGOy44rz1NYZMyda8hhIBZZwQIu8lVAm', 'user', 'Bandung', 'Jl. Industri No. 88, Bandung', -6.9174900, 107.6191200, 0, NULL, '2026-07-29 07:00:00', '2026-07-29 07:00:00'),
(9, 'Yusuf Pratama', 'yusuf.pratama@example.com', '081234500004', NULL, '$2b$12$pyNjeLA3e0RIWO6Z3laSNuGOy44rz1NYZMyda8hhIBZZwQIu8lVAm', 'user', 'Depok', 'Jl. Margonda No. 45, Depok', -6.4025000, 106.7942000, 0, NULL, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(10, 'Nadia Fitriani', 'nadia.fitriani@example.com', '081234500005', NULL, '$2b$12$pyNjeLA3e0RIWO6Z3laSNuGOy44rz1NYZMyda8hhIBZZwQIu8lVAm', 'user', 'Surabaya', 'Jl. Diponegoro No. 21, Surabaya', -7.2905800, 112.7359400, 0, NULL, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(11, 'Budi Santoso', 'budi.santoso@example.com', '081234500006', NULL, '$2b$12$pyNjeLA3e0RIWO6Z3laSNuGOy44rz1NYZMyda8hhIBZZwQIu8lVAm', 'user', 'Jakarta Selatan', 'Jl. Fatmawati No. 9, Jakarta Selatan', -6.2934700, 106.7975800, 0, NULL, '2026-07-08 01:00:00', '2026-07-08 01:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_order_id_foreign` (`order_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
