INSERT INTO permissions(code,name,module) VALUES
('sales.checkout','Cobrar ventas','sales'),
('inventory.transfer_create','Crear transferencias','inventory'),
('inventory.transfer_send','Enviar transferencias','inventory'),
('inventory.transfer_receive','Recibir transferencias','inventory')
ON DUPLICATE KEY UPDATE name=VALUES(name);