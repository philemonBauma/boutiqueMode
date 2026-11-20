-- Script complet de la base boutique_mode (MySQL/MariaDB, XAMPP).
-- Sélectionner la base cible dans phpMyAdmin avant l'import.
-- Pour le client mysql, décommenter le CREATE DATABASE / USE ci-dessous
-- et adapter le nom si DB_DATABASE dans .env est différent.

/*CREATE DATABASE IF NOT EXISTS `boutique_mode`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `boutique_mode`;

SET NAMES utf8mb4;
*/
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) NOT NULL DEFAULT 'client',
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add the role column when upgrading a database created by an older version of this script.
SET @role_column_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'users'
      AND COLUMN_NAME = 'role'
);
SET @add_role_column = IF(
    @role_column_exists = 0,
    'ALTER TABLE `users` ADD COLUMN `role` VARCHAR(255) NOT NULL DEFAULT ''client'' AFTER `password`',
    'SELECT 1'
);
PREPARE add_role_column_statement FROM @add_role_column;
EXECUTE add_role_column_statement;
DEALLOCATE PREPARE add_role_column_statement;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `recipes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `ingredients` JSON NOT NULL,
    `instructions` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `category` VARCHAR(255) NOT NULL,
    `gender` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `price` DECIMAL(8, 2) NOT NULL,
    `compare_at_price` DECIMAL(8, 2) NULL DEFAULT NULL,
    `image` VARCHAR(255) NOT NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `products_slug_unique` (`slug`),
    KEY `products_gender_category_is_active_index` (`gender`, `category`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reservations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
    `total` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `reservations_status_created_at_index` (`status`, `created_at`),
    CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reservation_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reservation_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `unit_price` DECIMAL(8, 2) NOT NULL,
    `quantity` SMALLINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `reservation_items_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `reservation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conversations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `conversation_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `body` TEXT NOT NULL,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `messages_conversation_id_created_at_index` (`conversation_id`, `created_at`),
    CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT source.`migration`, source.`batch`
FROM (
    SELECT '0001_01_01_000000_create_users_table' AS `migration`, 1 AS `batch`
    UNION ALL SELECT '0001_01_01_000001_create_cache_table', 1
    UNION ALL SELECT '0001_01_01_000002_create_jobs_table', 1
    UNION ALL SELECT '2026_07_04_100904_create_recipes_table', 1
    UNION ALL SELECT '2026_11_17_000000_create_products_table', 1
    UNION ALL SELECT '2026_11_20_000000_add_role_to_users_table', 2
    UNION ALL SELECT '2026_11_20_000001_create_reservations_table', 2
    UNION ALL SELECT '2026_11_20_000002_create_conversations_table', 2
) AS source
WHERE NOT EXISTS (
    SELECT 1
    FROM `migrations` AS existing
    WHERE existing.`migration` = source.`migration`
);

INSERT INTO `recipes` (`id`, `title`, `ingredients`, `instructions`) VALUES
    (1, 'Spaghetti Bolognese',
     '["spaghetti","ground beef","onion","garlic","tomato sauce","olive oil","salt","pepper"]',
     '["Cook spaghetti according to package instructions.","In a separate pan, sauté onion and garlic in olive oil until translucent.","Add ground beef and cook until browned.","Pour in tomato sauce and simmer for 20 minutes.","Season with salt and pepper to taste.","Serve sauce over cooked spaghetti."]'),
    (2, 'Chicken Curry',
     '["chicken pieces","onion","garlic","ginger","curry powder","coconut milk","vegetable oil","salt","pepper"]',
     '["Heat oil in a pan and sauté onion, garlic, and ginger until fragrant.","Add chicken pieces and cook until browned.","Stir in curry powder and cook for 1-2 minutes.","Pour in coconut milk and simmer until chicken is cooked through.","Season with salt and pepper to taste.","Serve hot with rice or naan bread."]'),
    (3, 'Vegetable Stir-Fry',
     '["broccoli","carrots","bell peppers","snap peas","soy sauce","garlic","ginger","sesame oil"]',
     '["Heat sesame oil in a wok or large pan.","Add garlic and ginger, sauté for 1 minute.","Add vegetables and stir-fry for 5-7 minutes until tender-crisp.","Pour in soy sauce and toss to coat.","Serve hot over rice or noodles."]'),
    (4, 'Caesar Salad',
     '["romaine lettuce","croutons","parmesan cheese","caesar dressing"]',
     '["Wash and chop the romaine lettuce.","In a large bowl, toss the lettuce with caesar dressing.","Add croutons and parmesan cheese.","Toss gently to combine."]')
ON DUPLICATE KEY UPDATE
    `id` = `id`;

INSERT INTO `products`
    (`name`, `slug`, `category`, `gender`, `description`, `price`, `compare_at_price`, `image`, `is_featured`, `is_active`)
VALUES
    ('Chemise en lin', 'chemise-en-lin', 'Chemises', 'homme', 'Une chemise légère en lin naturel, parfaite pour les journées ensoleillées.', 59.00, 75.00, 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=85', 1, 1),
    ('Veste de ville', 'veste-de-ville', 'Vestes', 'homme', 'Une coupe structurée et confortable, pensée pour passer du bureau au week-end.', 129.00, NULL, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=85', 1, 1),
    ('Pull col rond', 'pull-col-rond', 'Maille', 'homme', 'Un essentiel doux et facile à associer, tricoté dans une maille agréable.', 79.00, NULL, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=85', 0, 1),
    ('Robe fluide', 'robe-fluide', 'Robes', 'femme', 'Une silhouette aérienne et élégante à porter en journée comme en soirée.', 89.00, NULL, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=800&q=85', 1, 1),
    ('Blazer essentiel', 'blazer-essentiel', 'Vestes', 'femme', 'Un blazer intemporel à la ligne moderne, pour une allure naturellement assurée.', 119.00, 145.00, 'https://images.unsplash.com/photo-1591369822096-ffd140ec948f?auto=format&fit=crop&w=800&q=85', 1, 1),
    ('Chemisier en coton', 'chemisier-en-coton', 'Chemises', 'femme', 'Un chemisier en coton doux aux détails délicats pour tous les jours.', 64.00, NULL, 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=800&q=85', 0, 1),
    ('Sac signature', 'sac-signature', 'Accessoires', 'femme', 'Le petit sac pratique et raffiné qui accompagne vos essentiels.', 74.00, NULL, 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=85', 0, 1),
    ('Jean coupe droite', 'jean-coupe-droite', 'Pantalons', 'homme', 'Le jean facile à vivre, avec une coupe droite et un denim confortable.', 89.00, NULL, 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=85', 0, 1)
ON DUPLICATE KEY UPDATE
    `slug` = `slug`;

-- Admin initial de démonstration : test@example.com / password.
-- Créé avec le rôle admin uniquement s'il n'existe pas déjà.
-- Si ce compte existe déjà, son nom, son mot de passe et son rôle restent inchangés.
-- Pour accorder explicitement le rôle admin une seule fois à ce compte, exécutez :
-- UPDATE `users` SET `role` = 'admin' WHERE `email` = 'test@example.com';
-- Changez son mot de passe avant de rendre le site accessible au public.
INSERT INTO `users`
    (`name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`)
VALUES
    ('Admin PHILINDA', 'test@example.com', CURRENT_TIMESTAMP,
     '$2y$10$CU7.YZapIWvH27/78UHIQulJlSbZ7ndjCzYKcxyabCTVHxyXsfLmm',
     'admin', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE
    `email` = `email`;
