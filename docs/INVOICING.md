# Facturación
Módulo preparado para CFDI 4.0 mediante adaptador PAC.
## Flujo
Venta completada -> capturar/validar datos fiscales del receptor -> crear borrador inmutable desde la venta -> validar claves SAT de productos -> timbrar mediante PacProviderInterface -> guardar UUID/referencias/rutas XML/PDF.
## Diseño
El núcleo no depende de un PAC concreto. Cada integración implementa PacProviderInterface. Credenciales, CSD y llaves privadas se mantienen fuera del repositorio.
## Cancelación
La interfaz contempla cancel(uuid,rfc,reason,replacementUuid). La implementación concreta debe respetar los motivos y reglas vigentes del SAT/PAC.
## Pendiente antes de producción
Catálogos SAT actualizables, impuestos/retenciones completos, forma/método de pago, exportación, factura global, relación de CFDI, cancelación y aceptación cuando aplique, descarga segura XML/PDF, correo y pruebas sandbox con el PAC elegido.
