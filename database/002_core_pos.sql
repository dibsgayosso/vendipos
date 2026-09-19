CREATE TABLE suppliers (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,name VARCHAR(180) NOT NULL,phone VARCHAR(30),email VARCHAR(190),rfc VARCHAR(13),created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_supplier_business(business_id),CONSTRAINT fk_supplier_business FOREIGN KEY(business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cash_sessions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NOT NULL,user_id BIGINT UNSIGNED NOT NULL,opening_amount DECIMAL(15,2) NOT NULL DEFAULT 0,closing_amount DECIMAL(15,2) NULL,expected_amount DECIMAL(15,2) NULL,difference_amount DECIMAL(15,2) NULL,status ENUM('open','closed') NOT NULL DEFAULT 'open',opened_at DATETIME NOT NULL,closed_at DATETIME NULL,
 KEY idx_cash_open(business_id,branch_id,user_id,status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stock_movements (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NOT NULL,product_id BIGINT UNSIGNED NOT NULL,user_id BIGINT UNSIGNED NULL,type ENUM('purchase','sale','return','transfer_in','transfer_out','adjustment') NOT NULL,qty DECIMAL(18,4) NOT NULL,unit_cost DECIMAL(15,4) NULL,reference_type VARCHAR(50) NULL,reference_id BIGINT UNSIGNED NULL,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_stock_product(business_id,branch_id,product_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE purchases (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NOT NULL,supplier_id BIGINT UNSIGNED NULL,user_id BIGINT UNSIGNED NOT NULL,status ENUM('draft','received','cancelled') NOT NULL DEFAULT 'draft',subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,tax DECIMAL(15,2) NOT NULL DEFAULT 0,total DECIMAL(15,2) NOT NULL DEFAULT 0,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,received_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE open_sale_tabs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NOT NULL,user_id BIGINT UNSIGNED NOT NULL,name VARCHAR(100) NOT NULL,cart_json JSON NOT NULL,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_tabs_user(business_id,branch_id,user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
