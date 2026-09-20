ALTER TABLE branches
 ADD COLUMN legal_name VARCHAR(180) NULL AFTER name,
 ADD COLUMN address_line1 VARCHAR(190) NULL,
 ADD COLUMN address_line2 VARCHAR(190) NULL,
 ADD COLUMN neighborhood VARCHAR(120) NULL,
 ADD COLUMN city VARCHAR(120) NULL,
 ADD COLUMN state VARCHAR(120) NULL,
 ADD COLUMN postal_code VARCHAR(12) NULL,
 ADD COLUMN country_code CHAR(2) NOT NULL DEFAULT 'MX',
 ADD COLUMN phone VARCHAR(40) NULL,
 ADD COLUMN phone_alt VARCHAR(40) NULL,
 ADD COLUMN email VARCHAR(190) NULL,
 ADD COLUMN timezone VARCHAR(64) NOT NULL DEFAULT 'America/Mexico_City',
 ADD COLUMN locale VARCHAR(20) NOT NULL DEFAULT 'es-MX',
 ADD COLUMN currency CHAR(3) NOT NULL DEFAULT 'MXN';
ALTER TABLE branches ADD COLUMN activated_by BIGINT UNSIGNED NULL AFTER activated_at;
INSERT INTO permissions(code,name,module) VALUES
('branches.manage','Crear y editar sucursales','admin'),
('branches.activate','Activar o suspender sucursales','admin')
ON DUPLICATE KEY UPDATE name=VALUES(name);