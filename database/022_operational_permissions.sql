INSERT INTO permissions(code,name,module) VALUES
('inventory.receive','Recibir mercancía','inventory'),
('inventory.count','Realizar conteos físicos','inventory'),
('purchases.create','Crear órdenes de compra','purchases'),
('purchases.receive','Recibir órdenes de compra','purchases')
ON DUPLICATE KEY UPDATE name=VALUES(name);