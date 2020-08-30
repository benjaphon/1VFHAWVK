ALTER TABLE `box_sizes` ADD `price` DECIMAL NOT NULL AFTER `size_name`, ADD `add_price` DECIMAL NOT NULL AFTER `price`;

UPDATE `box_sizes` SET `price` = '35', `add_price` = '5' WHERE `box_sizes`.`id` = 2;
UPDATE `box_sizes` SET `price` = '45', `add_price` = '5' WHERE `box_sizes`.`id` = 3;
UPDATE `box_sizes` SET `price` = '55', `add_price` = '5' WHERE `box_sizes`.`id` = 4;

INSERT INTO `box_sizes` (`id`, `size_index`, `size_code`, `size_name`, `price`, `add_price`) VALUES (NULL, '4', 'XL', 'ใหญ่มาก', '75', '10'), (NULL, '5', 'XXL', 'ใหญ่ที่สุด', '85', '15');