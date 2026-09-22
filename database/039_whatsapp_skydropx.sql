CREATE TABLE integration_credentials (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NULL,provider VARCHAR(40) NOT NULL,config_json LONGTEXT NOT NULL,active TINYINT(1) NOT NULL DEFAULT 1,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_integration_scope(business_id,branch_id,provider)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE orders (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NOT NULL,customer_id BIGINT UNSIGNED NULL,source VARCHAR(30) NOT NULL DEFAULT 'manual',external_id VARCHAR(190) NULL,status ENUM('new','confirmed','preparing','ready','paid','shipped','cancelled') NOT NULL DEFAULT 'new',customer_name VARCHAR(180) NULL,phone VARCHAR(40) NULL,total DECIMAL(15,2) NOT NULL DEFAULT 0,payload_json LONGTEXT NULL,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_orders_branch(business_id,branch_id,status,created_at),UNIQUE KEY uq_order_external(business_id,source,external_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE shipments (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NOT NULL,order_id BIGINT UNSIGNED NULL,provider VARCHAR(30) NOT NULL DEFAULT 'skydropx',quotation_id VARCHAR(190) NULL,rate_id VARCHAR(190) NULL,external_shipment_id VARCHAR(190) NULL,carrier VARCHAR(100) NULL,service VARCHAR(120) NULL,tracking_number VARCHAR(190) NULL,label_url TEXT NULL,status VARCHAR(80) NULL,cost DECIMAL(15,2) NULL,payload_json LONGTEXT NULL,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_shipments_order(business_id,branch_id,order_id),KEY idx_shipments_tracking(tracking_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES ('orders.view','Ver pedidos','orders'),('orders.manage','Administrar pedidos','orders'),('shipping.manage','Administrar envíos','shipping'),('integrations.manage','Configurar integraciones','admin') ON DUPLICATE KEY UPDATE name=VALUES(name);
