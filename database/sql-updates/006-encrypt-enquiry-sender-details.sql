-- Lucky Instru Hydro: database update 006 (privacy: encrypted sender details)
-- phpMyAdmin > select your database > Import > this file.
-- Enquiries and messages now store the sender's IP address and browser
-- encrypted. This widens the two columns for the encrypted text and clears
-- the unencrypted values saved before the update (phpMyAdmin cannot encrypt
-- them; only the website's secret key can). Encrypted values are kept.
-- Safe to import more than once.

SET NAMES utf8mb4;

ALTER TABLE `enquiries`
  MODIFY `ip_address` TEXT NULL,
  MODIFY `user_agent` TEXT NULL;

-- Encrypted values always start with "eyJ"; anything else is plain text.
UPDATE `enquiries` SET `ip_address` = NULL WHERE `ip_address` IS NOT NULL AND `ip_address` NOT LIKE 'eyJ%';
UPDATE `enquiries` SET `user_agent` = NULL WHERE `user_agent` IS NOT NULL AND `user_agent` NOT LIKE 'eyJ%';

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_11_000002_encrypt_enquiry_sender_details', `next_batch`
FROM (SELECT COALESCE(MAX(`batch`), 0) + 1 AS `next_batch` FROM `migrations`) AS `batches`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_11_000002_encrypt_enquiry_sender_details');
