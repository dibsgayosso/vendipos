ALTER TABLE stock_transfer_items ADD COLUMN variant_id BIGINT UNSIGNED NULL,ADD COLUMN lot_number VARCHAR(100) NULL,ADD COLUMN expires_at DATE NULL;
ALTER TABLE inventory_lots ADD UNIQUE KEY uq_lot_branch_product(business_id,branch_id,product_id,lot_number);
