CREATE TABLE employee_commission_profiles (
 user_id BIGINT UNSIGNED PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 enabled TINYINT(1) NOT NULL DEFAULT 0,
 rate_percent DECIMAL(7,4) NOT NULL DEFAULT 0,
 basis ENUM('sales','profit') NOT NULL DEFAULT 'sales',
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_commission_business(business_id,enabled)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sale_commissions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 sale_id BIGINT UNSIGNED NOT NULL,
 user_id BIGINT UNSIGNED NOT NULL,
 basis ENUM('sales','profit') NOT NULL,
 base_amount DECIMAL(15,2) NOT NULL,
 rate_percent DECIMAL(7,4) NOT NULL,
 commission_amount DECIMAL(15,2) NOT NULL,
 status ENUM('pending','approved','paid','cancelled') NOT NULL DEFAULT 'pending',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_sale_commission(sale_id,user_id),
 KEY idx_commission_user(business_id,branch_id,user_id,status,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permissions(code,name,module) VALUES
 ('employees.view','Ver desempeño del personal','employees'),
 ('employees.commissions','Administrar comisiones','employees')
ON DUPLICATE KEY UPDATE name=VALUES(name);
