ALTER TABLE `orders` ADD `total_weight` INT NOT NULL AFTER `total`;
ALTER TABLE `order_details` ADD `weight` INT NOT NULL AFTER `price`;
ALTER TABLE `products` ADD `wholesale_price` DECIMAL(8,2) NOT NULL AFTER `agent_price`;

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `filename` varchar(250) NOT NULL,
  `filetype` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `products` ADD `video_filename` VARCHAR(250) NULL AFTER `url_picture`;

ALTER TABLE products MODIFY description BLOB;