-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 25, 2023 at 12:30 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel-crawler-test`
--

-- --------------------------------------------------------

--
-- Table structure for table `endpoints`
--

DROP TABLE IF EXISTS `endpoints`;
CREATE TABLE IF NOT EXISTS `endpoints` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `endpoints`
--

INSERT INTO `endpoints` (`id`, `url`, `created_at`, `updated_at`) VALUES
(1, 'https://public-mdc.trendyol.com/discovery-web-socialgw-service/api/review/284671018?merchantId=107671&storefrontId=1&culture=tr-TR&order=5&searchValue=&onlySellerReviews=false&page=4&tagValue', NULL, NULL),
(2, 'https://public.trendyol.com/discovery-web-productgw-service/api/productDetail/687004388?storefrontId=1&culture=tr-TR&linearVariants=true&isLegalRequirementConfirmed=false', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
CREATE TABLE IF NOT EXISTS `fields` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_id` bigint UNSIGNED NOT NULL,
  `source` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `destination` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fields_template_id_foreign` (`template_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `template_id`, `source`, `destination`, `created_at`, `updated_at`) VALUES
(1, 1, 'result->productReviews->content->commentTitle', 'title', NULL, NULL),
(2, 1, 'result->productReviews->content->comment', 'description', NULL, NULL),
(3, 1, 'result->productReviews->content->productSize', 'productSize', NULL, NULL),
(4, 1, 'result->productReviews->content->userFullName', 'username', NULL, NULL),
(5, 1, 'result->productReviews->content->id', 'id', NULL, NULL),
(6, 1, 'result->productReviews->content->rate', 'rate', NULL, NULL),
(7, 1, 'result->productReviews->content->mediaFiles->url', 'url', NULL, NULL),
(8, 2, 'result->color', 'color', NULL, NULL),
(9, 2, 'result->name', 'title', NULL, NULL),
(10, 2, 'result->productCode', 'productCode', NULL, NULL),
(11, 2, 'result->nameWithProductCode', 'nameWithProductCode', NULL, NULL),
(12, 3, 'result->images', 'images', NULL, NULL),
(13, 4, 'result->category->id', 'id', NULL, NULL),
(14, 4, 'result->category->name', 'name', NULL, NULL),
(15, 4, 'result->category->hierarchy', 'hierarchy', NULL, NULL),
(16, 5, 'result->brand->id', 'id', NULL, NULL),
(17, 5, 'result->brand->name', 'name', NULL, NULL),
(18, 5, 'result->brand->path', 'slug', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_04_24_084655_create_templates_table', 1),
(6, '2023_04_24_084717_create_endpoints_table', 1),
(7, '2023_04_24_084736_create_fields_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_general_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `endpoint_id` bigint UNSIGNED NOT NULL,
  `site` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `table` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `templates_endpoint_id_foreign` (`endpoint_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `endpoint_id`, `site`, `table`, `created_at`, `updated_at`) VALUES
(1, 1, 'trendyol', 'comments', NULL, NULL),
(2, 2, 'trendyol', 'products', NULL, NULL),
(3, 2, 'trendyol', 'images', NULL, NULL),
(4, 2, 'trendyol', 'category', NULL, NULL),
(5, 2, 'trendyol', 'brand', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
