ALTER TABLE payments ADD COLUMN payment_method_id BIGINT UNSIGNED NULL;
ALTER TABLE cash_movements ADD COLUMN payment_method_id BIGINT UNSIGNED NULL;
