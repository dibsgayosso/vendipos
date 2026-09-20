CREATE TABLE payment_methods (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,code VARCHAR(50) NOT NULL,name VARCHAR(100) NOT NULL,type ENUM('cash','card','transfer','credit','other') NOT NULL DEFAULT 'other',active TINYINT(1) NOT NULL DEFAULT 1,sort_order INT NOT NULL DEFAULT 0,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_payment_method(business_id,code),KEY idx_payment_methods(business_id,active,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE branch_payment_methods (
 branch_id BIGINT UNSIGNED NOT NULL,payment_method_id BIGINT UNSIGNED NOT NULL,enabled TINYINT(1) NOT NULL DEFAULT 1,PRIMARY KEY(branch_id,payment_method_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE payments ADD COLUMN payment_method_id BIGINT UNSIGNED NULL AFTER cash_session_id;
CREATE TABLE refunds (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NOT NULL,sale_id BIGINT UNSIGNED NOT NULL,user_id BIGINT UNSIGNED NOT NULL,status ENUM('completed','cancelled') NOT NULL DEFAULT 'completed',reason VARCHAR(255) NOT NULL,total DECIMAL(15,2) NOT NULL DEFAULT 0,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,KEY idx_refund_sale(business_id,sale_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE refund_items (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,refund_id BIGINT UNSIGNED NOT NULL,sale_item_id BIGINT UNSIGNED NOT NULL,product_id BIGINT UNSIGNED NOT NULL,qty DECIMAL(18,4) NOT NULL,amount DECIMAL(15,2) NOT NULL,KEY idx_refund_item(refund_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE refund_payments (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,refund_id BIGINT UNSIGNED NOT NULL,payment_method_id BIGINT UNSIGNED NULL,method VARCHAR(50) NOT NULL,amount DECIMAL(15,2) NOT NULL,reference VARCHAR(190) NULL,KEY idx_refund_payment(refund_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES ('sales.refund_partial','Devolución parcial','sales'),('sales.refund_total','Devolución total','sales'),('sales.void','Cancelar venta','sales') ON DUPLICATE KEY UPDATE name=VALUES(name);