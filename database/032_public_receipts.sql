ALTER TABLE sales ADD COLUMN public_token CHAR(48) NULL AFTER idempotency_key, ADD UNIQUE KEY uq_sales_public_token(public_token);
UPDATE sales SET public_token=SUBSTRING(SHA2(CONCAT(UUID(),id,RAND()),256),1,48) WHERE public_token IS NULL;
ALTER TABLE sales MODIFY public_token CHAR(48) NOT NULL;
INSERT INTO settings(business_id,branch_id,setting_key,setting_value) SELECT id,NULL,'receipt.public_portal','1' FROM businesses ON DUPLICATE KEY UPDATE setting_value=setting_value;