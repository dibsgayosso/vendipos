CREATE TABLE product_price_levels (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 name VARCHAR(100) NOT NULL,
 calculation ENUM('fixed','discount_percent') NOT NULL DEFAULT 'fixed',
 discount_percent DECIMAL(7,4) NULL,
 sort_order INT NOT NULL DEFAULT 0,
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_price_level_name(business_id,name),
 KEY idx_price_level_active(business_id,active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product_price_level_values (
 product_id BIGINT UNSIGNED NOT NULL,
 price_level_id BIGINT UNSIGNED NOT NULL,
 fixed_price DECIMAL(15,4) NULL,
 discount_percent_override DECIMAL(7,4) NULL,
 PRIMARY KEY(product_id,price_level_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE product_cost_levels (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 product_id BIGINT UNSIGNED NOT NULL,
 name VARCHAR(100) NOT NULL,
 quantity DECIMAL(18,6) NOT NULL DEFAULT 1,
 unit_cost DECIMAL(15,4) NOT NULL,
 sort_order INT NOT NULL DEFAULT 0,
 active TINYINT(1) NOT NULL DEFAULT 1,
 KEY idx_product_cost_levels(business_id,product_id,active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permissions(code,name,module) VALUES
 ('products.price_levels','Administrar niveles de precio','products'),
 ('products.cost_levels','Administrar niveles de costo','products')
ON DUPLICATE KEY UPDATE name=VALUES(name);
