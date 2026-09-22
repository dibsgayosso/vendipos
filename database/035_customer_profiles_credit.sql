ALTER TABLE customers
 ADD COLUMN price_level_id BIGINT UNSIGNED NULL,
 ADD COLUMN credit_enabled TINYINT(1) NOT NULL DEFAULT 0,
 ADD COLUMN credit_limit DECIMAL(15,2) NOT NULL DEFAULT 0,
 ADD COLUMN credit_balance DECIMAL(15,2) NOT NULL DEFAULT 0,
 ADD COLUMN notes TEXT NULL,
 ADD KEY idx_customer_price_level(business_id,price_level_id);

CREATE TABLE customer_documents (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 customer_id BIGINT UNSIGNED NOT NULL,
 uploaded_by BIGINT UNSIGNED NOT NULL,
 original_name VARCHAR(255) NOT NULL,
 stored_name VARCHAR(255) NOT NULL,
 mime_type VARCHAR(100) NOT NULL,
 file_size BIGINT UNSIGNED NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_customer_documents(business_id,customer_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permissions(code,name,module) VALUES
 ('customers.documents','Administrar documentos de clientes','customers'),
 ('customers.credit','Administrar crédito de clientes','customers'),
 ('customers.price_level','Asignar nivel de precios a clientes','customers')
ON DUPLICATE KEY UPDATE name=VALUES(name);
