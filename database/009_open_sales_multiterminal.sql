ALTER TABLE open_sale_tabs
 ADD COLUMN public_id CHAR(36) NULL AFTER user_id,
 ADD COLUMN customer_id BIGINT UNSIGNED NULL AFTER name,
 ADD COLUMN payload_json LONGTEXT NULL AFTER cart_json,
 ADD COLUMN status ENUM('open','completed','cancelled') NOT NULL DEFAULT 'open' AFTER payload_json;
CREATE UNIQUE INDEX uq_open_sale_public ON open_sale_tabs(public_id);
CREATE INDEX idx_open_sales_branch ON open_sale_tabs(business_id,branch_id,status,updated_at);
