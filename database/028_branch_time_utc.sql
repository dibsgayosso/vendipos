ALTER TABLE sales ADD COLUMN completed_at_utc DATETIME NULL AFTER completed_at;
ALTER TABLE cash_sessions ADD COLUMN opened_at_utc DATETIME NULL AFTER opened_at, ADD COLUMN closed_at_utc DATETIME NULL AFTER closed_at;
ALTER TABLE cash_movements ADD COLUMN created_at_utc DATETIME NULL AFTER created_at;
UPDATE sales SET completed_at_utc=completed_at WHERE completed_at_utc IS NULL;
UPDATE cash_sessions SET opened_at_utc=opened_at WHERE opened_at_utc IS NULL;
UPDATE cash_sessions SET closed_at_utc=closed_at WHERE closed_at IS NOT NULL AND closed_at_utc IS NULL;
UPDATE cash_movements SET created_at_utc=created_at WHERE created_at_utc IS NULL;