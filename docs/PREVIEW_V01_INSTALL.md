# Vendi POS — Preview v0.1

Primera versión instalable para pruebas controladas.

## Requisitos
- PHP 8.2+
- MySQL/MariaDB
- Composer
- Extensiones PDO MySQL y mbstring
- Document root apuntando a `public/`

## Instalación
1. Copia `.env.example` a `.env` y configura la base de datos.
2. Ejecuta `composer install --no-dev --optimize-autoloader`.
3. Importa `database/schema.sql` en una base nueva.
4. Ejecuta `php bin/install.php` para aplicar migraciones.
5. Configura HTTPS y el document root en `public/`.

## Alcance Preview
POS, caja, pagos mixtos, inventario, clientes/crédito/niveles de precio, sucursales, usuarios/permisos, compras, reportes, constructor de reportes, facturación base y tickets.

## Antes de producción
Esta preview es para pruebas. CFDI requiere PAC configurado; paqueterías/WhatsApp todavía son integraciones posteriores. Haz respaldo antes de migrar una instalación existente.
