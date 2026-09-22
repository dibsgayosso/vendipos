# Vendi POS — Primera versión de prueba

Esta rama contiene la primera versión integrada para pruebas internas.

## Requisitos
- PHP 8.2+
- MySQL/MariaDB
- Composer
- Extensiones PDO MySQL, mbstring y JSON
- Document root apuntando a `public/`

## Instalación limpia
1. Copia `.env.example` a `.env` y configura la base de datos.
2. Ejecuta `composer install --no-dev --optimize-autoloader`.
3. Importa `database/schema.sql`.
4. Ejecuta las migraciones `database/002_*.sql` a `database/031_*.sql` en orden.
5. Crea negocio, sucursal activa y usuario propietario inicial en la base de datos.
6. Configura métodos de pago para la sucursal.
7. Abre el sitio usando `public/` como raíz web.

## Prueba mínima
- Inicia sesión.
- Abre caja.
- Busca/agrega producto y realiza una venta.
- Prueba efectivo y pago mixto.
- Verifica inventario y ticket.
- Revisa Dashboard propietario.
- Haz depósito/retiro de caja y comprueba Pulso del negocio.
- Prueba devolución/cancelación en una base de pruebas.
- Cambia de sucursal y confirma aislamiento de información y zona horaria.

## Importante
Esta versión es **PREVIEW / pruebas internas**. No usar todavía como única fuente de inventario, caja o facturación de un negocio real. Antes de producción deben completarse migraciones verificadas sobre una base limpia, pruebas de integración y respaldo/rollback.
