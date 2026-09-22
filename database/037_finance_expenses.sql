CREATE TABLE expenses (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 user_id BIGINT UNSIGNED NOT NULL,
 category VARCHAR(100) NOT NULL,
 description VARCHAR(255) NOT NULL,
 subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
 tax DECIMAL(15,2) NOT NULL DEFAULT 0,
 total DECIMAL(15,2) NOT NULL,
 payment_method VARCHAR(40) NULL,
 reference VARCHAR(120) NULL,
 occurred_at_utc DATETIME NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_expense_period(business_id,branch_id,occurred_at_utc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES
('expenses.view','Ver gastos','finance'),
('expenses.create','Registrar gastos','finance')
ON DUPLICATE KEY UPDATE name=VALUES(name);
