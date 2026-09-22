ALTER TABLE payments MODIFY COLUMN method VARCHAR(50) NOT NULL;
ALTER TABLE payments ADD COLUMN change_amount DECIMAL(15,2) NOT NULL DEFAULT 0;
ALTER TABLE sales ADD COLUMN change_total DECIMAL(15,2) NOT NULL DEFAULT 0;
UPDATE payment_methods SET quick_compatible=0 WHERE requires_reference=1;
