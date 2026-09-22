# Trazabilidad al cobrar
Al completar una venta:
- descuenta existencia del producto y, cuando aplica, de la variante;
- lotes se consumen FEFO y quedan ligados a la partida;
- un lote vencido con política block impide la venta;
- productos serializados exigen una serie disponible por unidad;
- series pasan a sold y quedan ligadas a venta/partida;
- recetas consumen ingredientes en unidad base y guardan el consumo real;
- stock_movements conserva producto, variante, sucursal, usuario y venta.
Toda la operación debe ejecutarse dentro de la misma transacción del checkout para garantizar rollback completo.
