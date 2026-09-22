# Compras
Flujo: proveedor -> orden de compra -> recepción parcial/total -> inventario.
Cada partida se captura en una presentación (caja, subempaque, pieza, etc.).
La recepción puede dividirse en varias entregas. No permite recibir más de lo ordenado.
Al recibir se conservan lote, caducidad y series cuando apliquen.
Si el costo recibido difiere, la interfaz debe ofrecer actualizar costo. La decisión queda por recepción y requiere products.update_cost.
Estados: draft -> ordered -> partial -> received; cancelled termina el flujo.
