INSERT INTO settings(business_id,branch_id,setting_key,setting_value)
SELECT id,NULL,'sales.quantity_mode','smart' FROM businesses
ON DUPLICATE KEY UPDATE setting_value=setting_value;
INSERT INTO settings(business_id,branch_id,setting_key,setting_value)
SELECT id,NULL,'sales.quick_payment_method','cash' FROM businesses
ON DUPLICATE KEY UPDATE setting_value=setting_value;
INSERT INTO settings(business_id,branch_id,setting_key,setting_value)
SELECT id,NULL,'sales.merge_same_product','1' FROM businesses
ON DUPLICATE KEY UPDATE setting_value=setting_value;
INSERT INTO settings(business_id,branch_id,setting_key,setting_value)
SELECT id,NULL,'sales.allow_negative_stock','0' FROM businesses
ON DUPLICATE KEY UPDATE setting_value=setting_value;