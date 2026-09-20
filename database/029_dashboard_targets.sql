CREATE TABLE branch_daily_targets (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 target_date DATE NOT NULL,
 sales_target DECIMAL(15,2) NOT NULL DEFAULT 0,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_branch_target(branch_id,target_date),
 KEY idx_target_business_date(business_id,target_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES ('dashboard.owner','Ver panel propietario','reports'),('dashboard.targets','Configurar metas de venta','reports') ON DUPLICATE KEY UPDATE name=VALUES(name);