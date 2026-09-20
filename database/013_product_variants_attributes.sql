CREATE TABLE product_option_groups (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,product_id BIGINT UNSIGNED NOT NULL,name VARCHAR(80) NOT NULL,option_type ENUM('variant','modifier','ingredient') NOT NULL DEFAULT 'variant',required TINYINT(1) NOT NULL DEFAULT 0,min_select INT NOT NULL DEFAULT 0,max_select INT NULL,sort_order INT NOT NULL DEFAULT 0,active TINYINT(1) NOT NULL DEFAULT 1,KEY idx_option_group_product(business_id,product_id,active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE product_option_values (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,group_id BIGINT UNSIGNED NOT NULL,name VARCHAR(100) NOT NULL,code VARCHAR(60) NULL,price_delta DECIMAL(15,2) NOT NULL DEFAULT 0,cost_delta DECIMAL(15,4) NOT NULL DEFAULT 0,sort_order INT NOT NULL DEFAULT 0,active TINYINT(1) NOT NULL DEFAULT 1,KEY idx_option_values(group_id,active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE product_variants (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,product_id BIGINT UNSIGNED NOT NULL,sku VARCHAR(100) NULL,barcode VARCHAR(100) NULL,name VARCHAR(180) NOT NULL,price_override DECIMAL(15,2) NULL,cost_override DECIMAL(15,4) NULL,active TINYINT(1) NOT NULL DEFAULT 1,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,UNIQUE KEY uq_variant_sku(business_id,sku),UNIQUE KEY uq_variant_barcode(business_id,barcode),KEY idx_variant_product(product_id,active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE product_variant_values (
 variant_id BIGINT UNSIGNED NOT NULL,option_value_id BIGINT UNSIGNED NOT NULL,PRIMARY KEY(variant_id,option_value_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE product_components (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,parent_product_id BIGINT UNSIGNED NOT NULL,component_product_id BIGINT UNSIGNED NOT NULL,component_variant_id BIGINT UNSIGNED NULL,quantity_base DECIMAL(18,6) NOT NULL,waste_percent DECIMAL(8,4) NOT NULL DEFAULT 0,optional TINYINT(1) NOT NULL DEFAULT 0,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,KEY idx_components_parent(business_id,parent_product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE variant_inventory (
 branch_id BIGINT UNSIGNED NOT NULL,variant_id BIGINT UNSIGNED NOT NULL,quantity DECIMAL(18,6) NOT NULL DEFAULT 0,reserved DECIMAL(18,6) NOT NULL DEFAULT 0,PRIMARY KEY(branch_id,variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE sale_items ADD COLUMN variant_id BIGINT UNSIGNED NULL,ADD COLUMN selected_options_json LONGTEXT NULL;
ALTER TABLE stock_movements ADD COLUMN variant_id BIGINT UNSIGNED NULL;
INSERT INTO permissions(code,name,module) VALUES ('products.variants','Administrar variantes','products'),('products.recipes','Administrar ingredientes/recetas','products') ON DUPLICATE KEY UPDATE name=VALUES(name);