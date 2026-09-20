# Vendi Preview v0.1 — checklist de aceptación

Estado: candidata a prueba interna, no producción.

- [ ] Instalación limpia de schema + migraciones 002–031.
- [ ] Login propietario y cambio de sucursal.
- [ ] Apertura/cierre de caja por sucursal.
- [ ] Venta efectivo exacta y con cambio.
- [ ] Venta mixta sin duplicar cambio.
- [ ] F4 con método rápido configurado.
- [ ] Descuento y permisos.
- [ ] Cancelación y devolución.
- [ ] Inventario decrementa/restaura correctamente.
- [ ] Dashboard propietario y métodos de pago.
- [ ] CDMX/Tijuana muestran día/hora correctos.
- [ ] Alertas de diferencia de caja, devolución y cancelación.
- [ ] Depósito/retiro queda en auditoría.
- [ ] Empleado no accede a sucursal no asignada.

## Bloqueadores antes de producción
Validar migraciones en MySQL/MariaDB real, ejecutar pruebas end-to-end, revisar todos los endpoints restantes para contexto de sucursal, completar recuperación/backup y validar CFDI/PAC en sandbox.
