CREATE TABLE `settings` ( `id` INT NOT NULL AUTO_INCREMENT , `meta` VARCHAR(50) NOT NULL , `value` LONGTEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `settings` (`id`, `meta`, `value`) VALUES (NULL, 'maintenance', 'false');