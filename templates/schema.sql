CREATE DATABASE auctionshop
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

use auctionshop;

CREATE TABLE categories(
    `id` INT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `technical_name` VARCHAR(64) NOT NULL
);

CREATE TABLE users(
    `id` INT PRIMARY KEY,
    `registrated_at` DATE DEFAULT CURRENT_DATE,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL
);

CREATE TABLE lots(
    `id` INT PRIMARY KEY,
    `created_at` DATE DEFAULT CURRENT_DATE,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `start_price` INT UNSIGNED DEFAULT 0,
    `expiration_at` DATE NOT NULL,
    `rate_step` INT UNSIGNED NOT NULL,
    `category_id_FK` INT,
    `author_id_FK` INT,
    `winner_id_FK` INT,
    FOREIGN KEY (`category_id_FK`) REFERENCES categories(`id`),
    FOREIGN KEY (`author_id_FK`) REFERENCES users(`id`),
    FOREIGN KEY (`winner_id_FK`) REFERENCES users(`id`)
);

CREATE TABLE rates(
    `id` INT PRIMARY KEY,
    `created_at` DATE DEFAULT CURRENT_DATE,
    `price` INT UNSIGNED DEFAULT 0,
    `user_id_FK` INT,
    `lot_id_FK` INT,
    FOREIGN KEY (`user_id_FK`) REFERENCES users(`id`),
    FOREIGN KEY (`lot_id_FK`) REFERENCES lots(`id`)
);
