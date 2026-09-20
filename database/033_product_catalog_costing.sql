CREATE TABLE product_categories (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 parent_id BIGINT UNSIGNED NULL,
 name VARCHAR(140) NOT NULL,
 description VARCHAR(500) NULL,
 active TINYINT(1) NOT NULL DEFAULT 1,
 sort_order INT NOT NULL DEFAULT 0,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_product_category(business_id,parent_id,name),
 KEY idx_product_category_parent(business_id,parent_id,active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE products
 ADD COLUMN category_id BIGINT UNSIGNED NULL,
 ADD COLUMN description TEXT NULL,
 ADD COLUMN weight DECIMAL(18,6) NULL,
 ADD COLUMN weight_unit ENUM('g','kg','oz','lb') NULL,
 ADD COLUMN cost_method ENUM('average') NOT NULL DEFAULT 'average',
 ADD COLUMN average_cost DECIMAL(15,4) NULL,
 ADD COLUMN sale_popup_enabled TINYINT(1) NOT NULL DEFAULT 0,
 ADD COLUMN sale_popup_title VARCHAR(140) NULL,
 ADD COLUMN sale_popup_message VARCHAR(1000) NULL;

CREATE TABLE product_cost_layers (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 product_id BIGINT UNSIGNED NOT NULL,
 variant_id BIGINT UNSIGNED NULL,
 lot_id BIGINT UNSIGNED NULL,
 source ENUM('opening','purchase','receiving','return','manual') NOT NULL,
 reference_id BIGINT UNSIGNED NULL,
 qty_received DECIMAL(18,6) NOT NULL,
 unit_cost DECIMAL(15,4) NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_cost_layers_product(business_id,branch_id,product_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permissions(code,name,module) VALUES
 ('products.categories','Administrar categorías y subcategorías','products'),
 ('products.sale_popup','Configurar avisos al vender','products')
ON DUPLICATE KEY UPDATE name=VALUES(name);
