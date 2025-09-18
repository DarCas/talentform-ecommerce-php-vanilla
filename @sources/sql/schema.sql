CREATE TABLE `users` (
	`id` CHAR(36) NOT NULL UNIQUE,
	`usernm` VARCHAR(255) NOT NULL UNIQUE,
	`passwd` VARCHAR(45) NOT NULL,
	PRIMARY KEY(`id`)
);

CREATE INDEX `passwd` ON `users` (`passwd`);
CREATE TABLE `users_logs` (
	`user_id` CHAR(36) NOT NULL,
	`latest_login` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	PRIMARY KEY(`user_id`)
);

CREATE INDEX `user_id` ON `users_logs` (`user_id`);
CREATE TABLE `products` (
	`id` CHAR(36) NOT NULL UNIQUE,
	`category` VARCHAR(100) NOT NULL,
	`title` VARCHAR(100) NOT NULL,
	`description` TEXT(65535) NOT NULL,
	`image` VARCHAR(255),
	`qty` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
	`highlight` BOOLEAN NOT NULL DEFAULT FALSE,
	`status` TINYINT NOT NULL DEFAULT 0 COMMENT 'Cestinato (-1), Bozza (0), Pubblico (1)',
	`update_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() on update CURRENT_TIMESTAMP(),
	PRIMARY KEY(`id`)
);

CREATE INDEX `category`
ON `products` (`category`);
CREATE INDEX `title`
ON `products` (`title`);
CREATE INDEX `description`
ON `products` (`description`);
CREATE INDEX `qty`
ON `products` (`qty`);
CREATE INDEX `highlight`
ON `products` (`highlight`);
CREATE INDEX `status`
ON `products` (`status`);
CREATE UNIQUE INDEX `UNIQUE_product`
ON `products` (`category`, `title`);
CREATE TABLE `carts` (
	`id` CHAR(36) NOT NULL UNIQUE,
	`customer_id` CHAR(36) NOT NULL,
	`product_id` CHAR(36) NOT NULL,
	`qty` SMALLINT UNSIGNED NOT NULL,
	`create_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	`status_date` DATETIME DEFAULT NULL,
	`shipping_date` DATETIME DEFAULT NULL,
	`tracking` VARCHAR(255) DEFAULT NULL,
	`status` TINYINT NOT NULL DEFAULT 0 COMMENT 'Annullato (-1), Ricevuto (0), Elaborato (1), Evaso (2), Spedito (3)',
	PRIMARY KEY(`id`)
);

CREATE TABLE `customers` (
	`id` CHAR(36) NOT NULL UNIQUE,
	`name` VARCHAR(100) NOT NULL,
	`surname` VARCHAR(100) NOT NULL,
	`tax_code` CHAR(16) NOT NULL,
	`address` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`telefono` VARCHAR(255) NOT NULL,
	`note` TEXT(65535) DEFAULT NULL,
	PRIMARY KEY(`id`)
);

CREATE INDEX `name`
ON `customers` (`name`);
CREATE INDEX `surname`
ON `customers` (`surname`);
CREATE UNIQUE INDEX `text_code`
ON `customers` (`tax_code`);
CREATE UNIQUE INDEX `email`
ON `customers` (`email`);
CREATE INDEX `telefono`
ON `customers` (`telefono`);
ALTER TABLE `users_logs`
ADD FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE `carts`
ADD FOREIGN KEY(`product_id`) REFERENCES `products`(`id`)
ON UPDATE NO ACTION ON DELETE RESTRICT;
ALTER TABLE `carts`
ADD FOREIGN KEY(`customer_id`) REFERENCES `customers`(`id`)
ON UPDATE NO ACTION ON DELETE RESTRICT;
