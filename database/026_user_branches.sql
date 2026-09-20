CREATE TABLE user_branches (
 user_id BIGINT UNSIGNED NOT NULL,
 branch_id BIGINT UNSIGNED NOT NULL,
 is_default TINYINT(1) NOT NULL DEFAULT 0,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(user_id,branch_id),
 KEY idx_user_branch(branch_id,user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO user_branches(user_id,branch_id,is_default)
SELECT id,default_branch_id,1 FROM users WHERE default_branch_id IS NOT NULL
ON DUPLICATE KEY UPDATE is_default=1;
INSERT INTO permissions(code,name,module) VALUES
('branches.view','Ver sucursales','admin'),
('branches.assign_users','Asignar empleados a sucursales','admin')
ON DUPLICATE KEY UPDATE name=VALUES(name);