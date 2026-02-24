ALTER TABLE `activity` ADD `payer_data` TEXT NULL DEFAULT NULL AFTER `driver_data`;
ALTER TABLE `product` ADD `volume` DECIMAL(10,4) NULL DEFAULT NULL AFTER `weight`;