ALTER TABLE sale_item_lots ADD COLUMN returned_quantity_base DECIMAL(18,6) NOT NULL DEFAULT 0;
ALTER TABLE sale_item_serials ADD COLUMN returned_at DATETIME NULL;
ALTER TABLE sale_item_components ADD COLUMN returned_quantity_base DECIMAL(18,6) NOT NULL DEFAULT 0;
