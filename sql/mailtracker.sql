--
-- Table structure for table `sent_emails`
--

CREATE TABLE IF NOT EXISTS `sent_emails` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,  -- Ensure AUTO_INCREMENT is part of the column definition
  `hash` char(32) NOT NULL,
  `headers` text DEFAULT NULL,
  `sender_name` varchar(191) DEFAULT NULL,
  `sender_email` varchar(191) DEFAULT NULL,
  `recipient_name` varchar(191) DEFAULT NULL,
  `recipient_email` varchar(191) DEFAULT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `opens` int(11) DEFAULT NULL,
  `clicks` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `clicked_at` datetime DEFAULT NULL,
  `message_id` varchar(191) DEFAULT NULL,
  `meta` text DEFAULT NULL,
  PRIMARY KEY (`id`),  -- Primary key defined during creation
  UNIQUE KEY `sent_emails_hash_unique` (`hash`),
  KEY `sent_emails_message_id_index` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sent_emails_url_clicked`
--

CREATE TABLE IF NOT EXISTS `sent_emails_url_clicked` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,  -- Ensure AUTO_INCREMENT is part of the column definition
  `sent_email_id` int(10) UNSIGNED NOT NULL,
  `url` text DEFAULT NULL,
  `hash` char(32) NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),  -- Primary key defined during creation
  KEY `sent_emails_url_clicked_sent_email_id_foreign` (`sent_email_id`),
  CONSTRAINT `sent_emails_url_clicked_sent_email_id_foreign` FOREIGN KEY (`sent_email_id`) REFERENCES `sent_emails` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- No need for redundant ALTER TABLE statements to add primary keys
