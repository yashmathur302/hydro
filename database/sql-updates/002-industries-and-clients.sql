-- Lucky Instru Hydro: database update 002 (industries and clients)
-- phpMyAdmin > select your database > Import > this file.
-- Creates the tables used by Admin > Industries and Admin > Clients.
-- Safe to import more than once.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `industries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `icon` varchar(40) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `industries_is_active_sort_order_index` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `logo_path` varchar(255) NOT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_is_active_sort_order_index` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_07_000001_create_industries_table', `next_batch`
FROM (SELECT COALESCE(MAX(`batch`), 0) + 1 AS `next_batch` FROM `migrations`) AS `batches`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_07_000001_create_industries_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_07_000002_create_clients_table', `next_batch`
FROM (SELECT MAX(`batch`) AS `next_batch` FROM `migrations`) AS `batches`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_07_000002_create_clients_table');
