-- Lucky Instru Hydro: database update 001 (home page sections)
-- phpMyAdmin > select your database > Import > this file.
-- Creates the page_sections table used by Admin > Home page.
-- Safe to import more than once.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `page_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `content` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_sections_page_section_unique` (`page`, `section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_06_000001_create_page_sections_table', `next_batch`
FROM (SELECT COALESCE(MAX(`batch`), 0) + 1 AS `next_batch` FROM `migrations`) AS `batches`
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_06_000001_create_page_sections_table'
);
