CREATE DATABASE IF NOT EXISTS auctionshop;

use auctionshop;

CREATE TABLE categories(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `technical_name` VARCHAR(64) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE users(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `registered_at` DATETIME,
    `name` VARCHAR(255) UNIQUE NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) UNIQUE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE lots(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `created_at` DATETIME,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `start_price` DECIMAL UNSIGNED DEFAULT 0 NOT NULL,
    `expiration_at` DATETIME NOT NULL,
    `rate_step` DECIMAL UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    `category_id` INT(11) NOT NULL,
    `author_id` INT(11) NOT NULL,
    `winner_id` INT(11),
    KEY `category_id` (`category_id`),
    KEY `author_id` (`author_id`),
    KEY `winner_id` (`author_id`),
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`),
    FOREIGN KEY (`author_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`winner_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE rates(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `created_at` DATETIME,
    `price` DECIMAL UNSIGNED DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    `user_id` INT(11) NOT NULL,
    `lot_id` INT(11) NOT NULL,
    `is_winner` TinyInt(1) DEFAULT 0,
    KEY `user_id` (`user_id`),
    KEY `lot_id` (`lot_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`lot_id`) REFERENCES `lots`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- create fulltext search for lots
CREATE FULLTEXT INDEX lot_ft_search ON lots(name, description);

-- SELECT * FROM lots WHERE MATCH(name,description) AGAINST('Маска Oakley');

-- add new column
-- ALTER TABLE rates ADD COLUMN is_winner TinyInt(1) DEFAULT 0;
