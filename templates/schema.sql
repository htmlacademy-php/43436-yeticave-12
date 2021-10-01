CREATE DATABASE IF NOT EXISTS auctionshop
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

use auctionshop;

CREATE TABLE categories(
    `id` VARCHAR(64) DEFAULT (uuid()) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `technical_name` VARCHAR(64) NOT NULL
);

CREATE TABLE users(
    `id` VARCHAR(64) DEFAULT (uuid()) PRIMARY KEY,
    `registered_at` DATE,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE lots(
    `id` VARCHAR(64) DEFAULT (uuid()) PRIMARY KEY,
    `created_at` DATE,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `start_price` DECIMAL UNSIGNED DEFAULT 0 NOT NULL,
    `expiration_at` DATE NOT NULL,
    `rate_step` DECIMAL UNSIGNED NOT NULL,
    `category_id` VARCHAR(64) NOT NULL,
    `author_id` VARCHAR(64) NOT NULL,
    `winner_id` VARCHAR(64),
    FOREIGN KEY (`category_id`) REFERENCES categories(`id`),
    FOREIGN KEY (`author_id`) REFERENCES users(`id`),
    FOREIGN KEY (`winner_id`) REFERENCES users(`id`)
);

CREATE TABLE rates(
    `id` VARCHAR(64) DEFAULT (uuid()) PRIMARY KEY,
    `created_at` DATE,
    `price` DECIMAL UNSIGNED DEFAULT 0 NOT NULL,
    `user_id` VARCHAR(64) NOT NULL,
    `lot_id` VARCHAR(64) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES users(`id`),
    FOREIGN KEY (`lot_id`) REFERENCES lots(`id`)
);
