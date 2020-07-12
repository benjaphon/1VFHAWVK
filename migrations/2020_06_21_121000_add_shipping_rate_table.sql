CREATE TABLE `shipping_rate` ( `id` INT NOT NULL AUTO_INCREMENT , `weight_id` INT NOT NULL , `boxsize_id` INT NOT NULL , `parcel` DECIMAL NOT NULL , `register` DECIMAL NOT NULL , `ems` DECIMAL NOT NULL , `flash` DECIMAL NOT NULL , `jt` DECIMAL NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

INSERT INTO shipping_rate(weight_id, boxsize_id, parcel, register, ems, flash, jt)
SELECT wr.id, boxsize_id, wr.parcel, wr.register, wr.ems, wr.flash, wr.jt
FROM weight_range as wr