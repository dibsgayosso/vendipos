CREATE TABLE peripheral_profiles (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 type ENUM('scale','barcode_scanner','receipt_printer','cash_drawer','customer_display') NOT NULL,
 transport ENUM('web_serial','web_hid','bridge','keyboard') NOT NULL,
 name VARCHAR(120) NOT NULL,
 config_json JSON NOT NULL,
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 KEY idx_peripheral_branch(business_id,branch_id,type,active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE branch_entitlements (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 feature_code VARCHAR(80) NOT NULL,
 status ENUM('pending','active','suspended','expired') NOT NULL DEFAULT 'pending',
 valid_until DATETIME NULL,
 grace_until DATETIME NULL,
 signed_claim TEXT NULL,
 checked_at DATETIME NULL,
 UNIQUE KEY uq_branch_feature(branch_id,feature_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;