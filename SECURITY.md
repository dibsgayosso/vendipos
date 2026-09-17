# Seguridad

- Todas las operaciones deben filtrar por `business_id`.
- Toda operación de sucursal debe validar además `branch_id`.
- Las sucursales nuevas comienzan en `pending`.
- Nunca almacenar tokens de activación en texto plano.
- CSD, claves privadas y secretos de APIs deben mantenerse cifrados y fuera del webroot.
- Usar `password_hash()` y `password_verify()`.
- CSRF obligatorio en operaciones de escritura.
- Cookies de sesión con HttpOnly, Secure y SameSite.
- Regenerar ID de sesión tras autenticar.
- Cancelaciones, devoluciones, descuentos, cambios de precio y activaciones deben quedar en auditoría.
- Las ventas deben usar una `idempotency_key` para evitar cobros duplicados por doble clic o reintentos.
- La suspensión comercial no debe borrar información del cliente.
