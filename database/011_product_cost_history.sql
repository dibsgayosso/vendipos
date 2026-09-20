CREATE TABLE product_cost_history (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 product_id BIGINT UNSIGNED NOT NULL,
 presentation_id BIGINT UNSIGNED NULL,
 old_cost DECIMAL(15,4) NOT NULL,
 new_cost DECIMAL(15,4) NOT NULL,
 source ENUM('receiving','purchase','manual') NOT NULL DEFAULT 'receiving',
 reference_id BIGINT UNSIGNED NULL,
 user_id BIGINT UNSIGNED NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_cost_history_product(business_id,product_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES ('products.update_cost','Actualizar costo desde recepción','products') ON DUPLICATE KEY UPDATE name=VALUES(name);