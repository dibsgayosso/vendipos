CREATE TABLE settings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,business_id BIGINT UNSIGNED NOT NULL,branch_id BIGINT UNSIGNED NULL,setting_key VARCHAR(120) NOT NULL,setting_value TEXT NULL,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_setting_scope(business_id,branch_id,setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE permissions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,code VARCHAR(120) NOT NULL UNIQUE,name VARCHAR(160) NOT NULL,module VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE user_permissions (
 user_id BIGINT UNSIGNED NOT NULL,permission_id BIGINT UNSIGNED NOT NULL,allowed TINYINT(1) NOT NULL DEFAULT 1,PRIMARY KEY(user_id,permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO permissions(code,name,module) VALUES
('sales.view','Ver ventas','sales'),('sales.create','Realizar ventas','sales'),('sales.discount','Aplicar descuentos','sales'),('sales.change_price','Cambiar precio','sales'),('sales.cancel','Cancelar ventas','sales'),('sales.refund','Devoluciones','sales'),('sales.quick_finish','Terminar venta rápida','sales'),
('inventory.view','Ver inventario','inventory'),('inventory.adjust','Ajustar inventario','inventory'),('inventory.transfer','Transferir inventario','inventory'),
('products.view','Ver productos','products'),('products.create','Crear productos','products'),('products.edit','Editar productos','products'),('products.cost','Ver costos','products'),
('customers.view','Ver clientes','customers'),('customers.edit','Editar clientes','customers'),('suppliers.manage','Administrar proveedores','suppliers'),('purchases.manage','Administrar compras','purchases'),
('cash.open','Abrir caja','cash'),('cash.close','Cerrar/cortar caja','cash'),('cash.movements','Entradas y salidas de caja','cash'),
('reports.view','Ver reportes','reports'),('reports.profit','Ver utilidad','reports'),
('users.manage','Administrar usuarios y permisos','admin'),('settings.manage','Modificar configuración','admin'),('branches.manage','Administrar sucursales','admin');