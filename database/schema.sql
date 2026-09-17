SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE businesses (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  legal_name VARCHAR(255) NULL,
  rfc VARCHAR(13) NULL,
  status ENUM('trial','active','past_due','suspended','cancelled') NOT NULL DEFAULT 'trial',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE plans (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  base_branches INT UNSIGNED NOT NULL DEFAULT 1,
  active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subscriptions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  plan_id BIGINT UNSIGNED NOT NULL,
  status ENUM('trial','active','past_due','suspended','cancelled') NOT NULL DEFAULT 'trial',
  starts_at DATETIME NOT NULL,
  ends_at DATETIME NULL,
  grace_until DATETIME NULL,
  CONSTRAINT fk_sub_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_sub_plan FOREIGN KEY (plan_id) REFERENCES plans(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE branches (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  code VARCHAR(40) NOT NULL,
  name VARCHAR(140) NOT NULL,
  status ENUM('pending','active','suspended','revoked') NOT NULL DEFAULT 'pending',
  is_billable_addon TINYINT(1) NOT NULL DEFAULT 1,
  addon_status ENUM('included','pending','active','past_due','cancelled') NOT NULL DEFAULT 'pending',
  activation_token_hash CHAR(64) NULL,
  activated_at DATETIME NULL,
  last_license_check_at DATETIME NULL,
  last_seen_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_branch_code (business_id, code),
  CONSTRAINT fk_branch_business FOREIGN KEY (business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  default_branch_id BIGINT UNSIGNED NULL,
  name VARCHAR(160) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('owner','admin','manager','cashier','warehouse','employee') NOT NULL DEFAULT 'cashier',
  status ENUM('active','disabled') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_email_business (business_id, email),
  CONSTRAINT fk_user_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_user_branch FOREIGN KEY (default_branch_id) REFERENCES branches(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  sku VARCHAR(80) NULL,
  barcode VARCHAR(80) NULL,
  name VARCHAR(190) NOT NULL,
  unit ENUM('piece','kg','meter','liter','box','pack','roll') NOT NULL DEFAULT 'piece',
  cost DECIMAL(15,4) NOT NULL DEFAULT 0,
  price DECIMAL(15,4) NOT NULL DEFAULT 0,
  tax_rate DECIMAL(7,4) NOT NULL DEFAULT 0,
  sat_product_key VARCHAR(20) NULL,
  sat_unit_key VARCHAR(10) NULL,
  tax_object VARCHAR(10) NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_product_sku (business_id, sku),
  KEY idx_product_barcode (business_id, barcode),
  CONSTRAINT fk_product_business FOREIGN KEY (business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventory (
  business_id BIGINT UNSIGNED NOT NULL,
  branch_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  qty DECIMAL(18,4) NOT NULL DEFAULT 0,
  reserved_qty DECIMAL(18,4) NOT NULL DEFAULT 0,
  min_qty DECIMAL(18,4) NOT NULL DEFAULT 0,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (branch_id, product_id),
  CONSTRAINT fk_inventory_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_inventory_branch FOREIGN KEY (branch_id) REFERENCES branches(id),
  CONSTRAINT fk_inventory_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE customers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(180) NOT NULL,
  phone VARCHAR(30) NULL,
  email VARCHAR(190) NULL,
  rfc VARCHAR(13) NULL,
  tax_name VARCHAR(255) NULL,
  tax_zip VARCHAR(10) NULL,
  tax_regime VARCHAR(10) NULL,
  cfdi_use VARCHAR(10) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_customer_phone (business_id, phone),
  CONSTRAINT fk_customer_business FOREIGN KEY (business_id) REFERENCES businesses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sales (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  branch_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  customer_id BIGINT UNSIGNED NULL,
  status ENUM('open','completed','cancelled','refunded') NOT NULL DEFAULT 'open',
  subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
  discount DECIMAL(15,2) NOT NULL DEFAULT 0,
  tax DECIMAL(15,2) NOT NULL DEFAULT 0,
  total DECIMAL(15,2) NOT NULL DEFAULT 0,
  cost_total DECIMAL(15,2) NOT NULL DEFAULT 0,
  idempotency_key CHAR(36) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  completed_at DATETIME NULL,
  UNIQUE KEY uq_sale_idempotency (business_id, idempotency_key),
  KEY idx_sales_branch_date (business_id, branch_id, created_at),
  CONSTRAINT fk_sale_business FOREIGN KEY (business_id) REFERENCES businesses(id),
  CONSTRAINT fk_sale_branch FOREIGN KEY (branch_id) REFERENCES branches(id),
  CONSTRAINT fk_sale_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_sale_customer FOREIGN KEY (customer_id) REFERENCES customers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sale_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sale_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  description VARCHAR(190) NOT NULL,
  qty DECIMAL(18,4) NOT NULL,
  unit_price DECIMAL(15,4) NOT NULL,
  unit_cost DECIMAL(15,4) NOT NULL,
  discount DECIMAL(15,2) NOT NULL DEFAULT 0,
  tax DECIMAL(15,2) NOT NULL DEFAULT 0,
  line_total DECIMAL(15,2) NOT NULL,
  CONSTRAINT fk_sale_item_sale FOREIGN KEY (sale_id) REFERENCES sales(id),
  CONSTRAINT fk_sale_item_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sale_id BIGINT UNSIGNED NOT NULL,
  method ENUM('cash','card','transfer','credit','other') NOT NULL,
  amount DECIMAL(15,2) NOT NULL,
  reference VARCHAR(120) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payment_sale FOREIGN KEY (sale_id) REFERENCES sales(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE audit_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id BIGINT UNSIGNED NOT NULL,
  branch_id BIGINT UNSIGNED NULL,
  user_id BIGINT UNSIGNED NULL,
  event VARCHAR(100) NOT NULL,
  entity_type VARCHAR(100) NULL,
  entity_id BIGINT UNSIGNED NULL,
  metadata JSON NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_audit_business_date (business_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
