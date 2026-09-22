CREATE TABLE saved_reports (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 user_id BIGINT UNSIGNED NOT NULL,
 name VARCHAR(140) NOT NULL,
 report_key VARCHAR(80) NOT NULL,
 filters_json JSON NULL,
 is_shared TINYINT(1) NOT NULL DEFAULT 0,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_saved_reports(business_id,user_id,updated_at),
 KEY idx_saved_reports_key(business_id,report_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES
('reports.export','Exportar reportes','reports'),
('reports.saved','Guardar reportes','reports')
ON DUPLICATE KEY UPDATE name=VALUES(name);
