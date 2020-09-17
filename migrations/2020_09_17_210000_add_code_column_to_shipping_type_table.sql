ALTER TABLE `shipping_type` ADD `code` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `name`;

UPDATE `shipping_type` SET `code` = 'พัสดุธรรมดา' WHERE `shipping_type`.`id` = 1;
UPDATE `shipping_type` SET `code` = 'ลงทะเบียน' WHERE `shipping_type`.`id` = 2;
UPDATE `shipping_type` SET `code` = 'EMS' WHERE `shipping_type`.`id` = 3;
UPDATE `shipping_type` SET `code` = 'FLASH EXPRESS' WHERE `shipping_type`.`id` = 4;
UPDATE `shipping_type` SET `code` = 'J&T' WHERE `shipping_type`.`id` = 5;
UPDATE `shipping_type` SET `code` = 'CoverPage' WHERE `shipping_type`.`id` = 6;