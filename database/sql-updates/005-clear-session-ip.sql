-- Lucky Instru Hydro: database update 005 (privacy: session IP addresses)
-- phpMyAdmin > select your database > Import > this file.
-- Sessions no longer store visitor IP addresses or browser details; this
-- clears the values saved before the update. Nobody is logged out.
-- Safe to import more than once.

SET NAMES utf8mb4;

UPDATE `sessions` SET `ip_address` = NULL, `user_agent` = NULL;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_11_000001_clear_session_request_information', `next_batch`
FROM (SELECT COALESCE(MAX(`batch`), 0) + 1 AS `next_batch` FROM `migrations`) AS `batches`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_11_000001_clear_session_request_information');
