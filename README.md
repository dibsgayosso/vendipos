# Vendi POS

Vendi POS es una plataforma de punto de venta y operación comercial para PyMEs.

## Alcance inicial

- POS y ventas simultáneas
- Inventario multi-sucursal
- Clientes, proveedores y compras
- Caja, cortes y movimientos
- Pedidos y cotizaciones
- WhatsApp y pedidos conversacionales
- Envíos mediante proveedores como Skydropx y Pakke
- Facturación CFDI mediante PAC
- Empleados, comisiones y nómina operativa
- Reportes, utilidad y auditoría
- Multiempresa y multisucursal
- Activación remota y cobro por sucursal adicional

## Principios

- Código nuevo e independiente
- PHP 8.x
- MySQL/MariaDB
- Arquitectura modular
- Seguridad por diseño
- Multi-tenant con business_id
- Sucursales aisladas con branch_id
- Integraciones desacopladas por interfaces
- Sin secretos dentro del repositorio

## Licenciamiento comercial de sucursales

Cada sucursal se registra inicialmente como `pending`.

La sucursal sólo puede operar si:

1. La cuenta principal está activa.
2. La sucursal ha sido activada remotamente.
3. El add-on correspondiente está vigente.
4. La licencia local supera la validación de integridad.

Las tarifas se administrarán desde el servicio comercial y no quedarán codificadas de forma fija en el POS.

## Estado

Inicio de arquitectura base.
