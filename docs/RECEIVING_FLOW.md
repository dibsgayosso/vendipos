# Recepción de mercancía
La recepción convierte la presentación capturada a unidad base antes de afectar inventario.
- Si controla lote, lote obligatorio.
- Si controla caducidad, fecha obligatoria.
- Si controla series, exige una serie por unidad base.
- Registra movimiento de inventario tipo purchase.
- La operación es transaccional: inventario, lote y series se confirman juntos o se revierten juntos.
Siguiente integración: orden de compra -> recepción parcial/total -> actualización de costo -> cuentas por pagar opcionales.