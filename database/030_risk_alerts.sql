CREATE TABLE risk_events (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NULL,
 user_id BIGINT UNSIGNED NULL,
 event_type VARCHAR(60) NOT NULL,
 severity ENUM('info','low','medium','high','critical') NOT NULL DEFAULT 'medium',
 entity_type VARCHAR(60) NULL,
 entity_id BIGINT UNSIGNED NULL,
 title VARCHAR(160) NOT NULL,
 detail VARCHAR(500) NULL,
 amount DECIMAL(15,2) NULL,
 fingerprint CHAR(64) NULL,
 status ENUM('open','reviewed','dismissed','resolved') NOT NULL DEFAULT 'open',
 reviewed_by BIGINT UNSIGNED NULL,
 reviewed_at DATETIME NULL,
 created_at_utc DATETIME NOT NULL,
 UNIQUE KEY uq_risk_fingerprint(fingerprint),
 KEY idx_risk_business_status(business_id,status,severity),
 KEY idx_risk_branch_date(branch_id,created_at_utc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE risk_rules (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 rule_code VARCHAR(60) NOT NULL,
 enabled TINYINT(1) NOT NULL DEFAULT 1,
 threshold_value DECIMAL(15,4) NULL,
 window_minutes INT UNSIGNED NULL,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_risk_rule(business_id,rule_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO permissions(code,name,module) VALUES ('risk.view','Ver alertas operativas','reports'),('risk.review','Revisar alertas operativas','reports'),('risk.configure','Configurar reglas de alertas','admin') ON DUPLICATE KEY UPDATE name=VALUES(name);