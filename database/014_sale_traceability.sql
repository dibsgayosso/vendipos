CREATE TABLE sale_item_lots (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,sale_item_id BIGINT UNSIGNED NOT NULL,lot_id BIGINT UNSIGNED NOT NULL,quantity_base DECIMAL(18,6) NOT NULL,KEY idx_sale_item_lots(sale_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE sale_item_serials (
 sale_item_id BIGINT UNSIGNED NOT NULL,serial_id BIGINT UNSIGNED NOT NULL,PRIMARY KEY(sale_item_id,serial_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE sale_item_components (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,sale_item_id BIGINT UNSIGNED NOT NULL,component_product_id BIGINT UNSIGNED NOT NULL,component_variant_id BIGINT UNSIGNED NULL,quantity_base DECIMAL(18,6) NOT NULL,KEY idx_sale_components(sale_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;