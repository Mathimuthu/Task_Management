-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 07:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task`
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:12:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"read users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"write users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"read department\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"write department\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"delete department\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:9:\"read role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:10:\"write role\";s:1:\"c\";s:3:\"web\";}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:11:\"delete role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"read tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:11:\"write tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"delete tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1739782922);

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
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `manager_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Accounts and Finance', NULL, 5, 1, NULL, '2025-02-11 04:05:33'),
(2, 'HR', NULL, 8, 1, NULL, '2025-02-13 02:50:01'),
(3, 'Sales and Marketing (Business Development)', NULL, NULL, 1, NULL, NULL),
(4, 'Infrastructures', NULL, NULL, 1, NULL, NULL),
(5, 'Research and Development', NULL, NULL, 1, NULL, NULL),
(6, 'Learning and Development', NULL, NULL, 1, NULL, NULL),
(7, 'IT Services', NULL, NULL, 1, NULL, NULL),
(8, 'Product Development', NULL, NULL, 1, NULL, NULL),
(9, 'Admin Department', NULL, NULL, 1, NULL, NULL),
(10, 'Security and Transport', NULL, NULL, 1, NULL, NULL),
(11, 'Development1', NULL, 5, 1, '2025-02-13 04:37:04', '2025-02-13 04:37:04');

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
(4, '2025_02_07_104156_create_permission_tables', 2),
(5, '2025_02_07_141143_add_role_to_users_table', 3),
(6, '2025_02_09_101525_user_table_update', 4),
(7, '2025_01_19_064040_create_products_table', 5),
(8, '2025_01_20_093716_create_sales_table', 5),
(9, '2025_01_20_121844_create_customers_table', 5),
(11, '2025_02_09_112856_create_department_table', 6),
(12, '2025_02_10_075313_create_task_table', 7),
(13, '2025_02_13_082651_create_task_details_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'read users', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(2, 'write users', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(3, 'delete users', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(4, 'read department', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(5, 'write department', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(6, 'delete department', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(7, 'read role', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(8, 'write role', 'web', '2025-02-10 10:42:49', '2025-02-10 10:42:49'),
(9, 'delete role', 'web', '2025-02-10 10:42:52', '2025-02-10 10:42:52'),
(10, 'read tasks', 'web', '2025-02-12 03:26:10', '2025-02-12 03:26:10'),
(11, 'write tasks', 'web', '2025-02-12 03:28:50', '2025-02-12 03:28:50'),
(12, 'delete tasks', 'web', '2025-02-12 03:28:57', '2025-02-12 03:28:57');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-02-10 10:38:19', '2025-02-10 10:38:19'),
(2, 'Manager', 'web', '2025-02-10 10:38:19', '2025-02-10 10:38:19'),
(3, 'Employee', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1);

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
('vFfrHn6BxIMbpnqKynm1gGd19fqqh4dTBfekwvZX', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNXowREpjOXZDNWhnVFRkdWUwTUh2M3JjcEQxRmFlZVFBVVNyUTBESiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwczovL2xvY2FsaG9zdC90YXNrL3B1YmxpYy90YXNrcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1739696756);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `attachments` varchar(255) DEFAULT NULL,
  `priority` enum('Low','Medium','High') NOT NULL DEFAULT 'Medium',
  `date` date NOT NULL DEFAULT '2025-02-10',
  `deadline` date DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`employee_ids`)),
  `status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `attachments`, `priority`, `date`, `deadline`, `department_id`, `role_id`, `employee_ids`, `status`, `created_at`, `updated_at`) VALUES
(1, 'new task', NULL, NULL, 'High', '2025-02-10', '2025-02-11', 3, NULL, '\"[\\\"3\\\"]\"', 'In Progress', '2025-02-10 03:37:13', '2025-02-10 03:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(32) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `registration_no` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `email_verified_at`, `password`, `created_by`, `remember_token`, `created_at`, `updated_at`, `role`, `registration_no`, `department_id`, `status`) VALUES
(1, 'Jenitta', 'admin@gmail.com', '9874563210', '2025-02-09 00:37:46', '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', NULL, 'Q1pXDJjo5ThFOxefUKjkFl6xU8NIVEINUqHGPy8IgFAJEKAjVXK9lYjT25cJ', '2025-02-09 00:37:46', '2025-02-13 02:51:08', '1', '1', 2, 1),
(3, 'Murugesan Pandi', 'admin1@gmail.com', '9874563210', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', NULL, NULL, '2025-02-10 01:57:54', '2025-02-11 03:56:36', '1', NULL, NULL, 1),
(4, 'Bob Williams', 'bobwilliams@example.com', '9876543213', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 03:56:49', '1', 'REG004', 4, 1),
(5, 'Charlie Brown', 'charliebrown@example.com', '9876543214', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 03:59:07', '2', 'REG005', 5, 1),
(6, 'David Wilson', 'davidwilson@example.com', '9876543215', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 03:59:02', '3', 'REG006', 6, 1),
(7, 'Emily Davis', 'emilydavis@example.com', '9876543216', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 03:59:11', '3', 'REG007', 7, 1),
(8, 'Franklin Thomas', 'franklinthomas@example.com', '9876543217', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 04:05:20', '2', 'REG008', 8, 1),
(9, 'Grace Lee', 'gracelee@example.com', '9876543218', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 03:59:51', '3', 'REG009', 9, 1),
(10, 'Henry White', 'henrywhite@example.com', '9876543219', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 03:59:58', '3', 'REG010', 10, 1),
(11, 'John Doe', 'johndoe@example.com', '9876543210', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-11 04:05:14', '1', 'REG001', 1, 1),
(12, 'Jane Smith', 'janesmith@example.com', '9876543211', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-10 14:52:49', '', 'REG002', 2, 1),
(13, 'Alice Johnson', 'alicejohnson@example.com', '9876543212', NULL, '$2y$12$pcLy.OYyQuyOq1J8FJfFwuzFcuiaiigR/D8z9DvbKbdv2iKWRPq4m', 1, NULL, '2025-02-10 14:52:49', '2025-02-10 14:52:49', '', 'REG003', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
