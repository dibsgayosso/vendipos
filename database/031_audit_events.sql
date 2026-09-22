CREATE TABLE audit_events (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NULL,
 user_id BIGINT UNSIGNED NULL,
 event_type VARCHAR(80) NOT NULL,
 entity_type VARCHAR(60) NULL,
 entity_id BIGINT UNSIGNED NULL,
 amount DECIMAL(15,2) NULL,
 detail JSON NULL,
 created_at_utc DATETIME NOT NULL,
 KEY idx_audit_events_business_date(business_id,created_at_utc),
 KEY idx_audit_events_branch_type(branch_id,event_type,created_at_utc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES ('audit.view','Ver trazabilidad operativa','reports') ON DUPLICATE KEY UPDATE name=VALUES(name);