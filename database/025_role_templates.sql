CREATE TABLE role_templates (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 business_id BIGINT UNSIGNED NULL,
 code VARCHAR(40) NOT NULL,
 name VARCHAR(100) NOT NULL,
 description VARCHAR(255) NULL,
 is_system TINYINT(1) NOT NULL DEFAULT 0,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_role_template(business_id,code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE role_template_permissions (
 role_template_id BIGINT UNSIGNED NOT NULL,
 permission_id BIGINT UNSIGNED NOT NULL,
 allowed TINYINT(1) NOT NULL DEFAULT 1,
 PRIMARY KEY(role_template_id,permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE users ADD COLUMN role_template_id BIGINT UNSIGNED NULL AFTER role;
INSERT INTO role_templates(business_id,code,name,description,is_system) VALUES
(NULL,'admin','Administrador','Control operativo completo, configuración, usuarios, reportes y sucursales.',1),
(NULL,'manager','Gerente','Operación diaria, ventas, inventario, compras, caja y reportes; sin administración crítica.',1),
(NULL,'employee','Empleado','Venta, clientes, inventario de consulta y operaciones básicas de caja.',1);
INSERT INTO role_template_permissions(role_template_id,permission_id,allowed)
SELECT rt.id,p.id,1 FROM role_templates rt JOIN permissions p
WHERE rt.business_id IS NULL AND (
(rt.code='admin') OR
(rt.code='manager' AND p.code IN ('sales.view','sales.create','sales.checkout','sales.discount','sales.quick_finish','sales.refund','sales.refund_partial','inventory.view','inventory.receive','inventory.count','inventory.transfer','inventory.transfer_create','inventory.transfer_send','inventory.transfer_receive','products.view','products.create','products.edit','products.cost','customers.view','customers.edit','suppliers.manage','purchases.manage','purchases.create','purchases.receive','cash.open','cash.close','cash.movements','reports.view','reports.profit','invoices.view','invoices.create')) OR
(rt.code='employee' AND p.code IN ('sales.view','sales.create','sales.checkout','sales.quick_finish','inventory.view','products.view','customers.view','customers.edit','cash.open','cash.movements'))
);
