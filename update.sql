ALTER TABLE `activity_palett_has_product` ADD `delivery_number` VARCHAR(255) NULL DEFAULT NULL AFTER `product_barcode`, ADD `volume` DECIMAL(10,2) NULL DEFAULT NULL AFTER `delivery_number`; 
