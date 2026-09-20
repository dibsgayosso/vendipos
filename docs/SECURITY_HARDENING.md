# Seguridad de API
Las operaciones mutables usan sesión, token CSRF y permisos del lado servidor. Owner/admin conservan acceso administrativo; otros roles requieren permiso explícito.
## Cliente
Obtener token desde api/session/csrf.php y enviarlo en X-CSRF-Token para POST/PUT/PATCH/DELETE.
## Pendiente
Rotación de sesión al iniciar sesión, cookies Secure/HttpOnly/SameSite, rate limiting de login, expiración por inactividad, CSP y cabeceras de seguridad, bitácora de denegaciones y pruebas automatizadas de autorización.
