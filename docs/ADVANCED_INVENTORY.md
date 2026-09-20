# Inventario avanzado Vendi
## Unidad base y presentaciones
La existencia física se contabiliza en unidad base. Cada presentación convierte a esa unidad.
Ejemplo: pieza=1, paquete=6, caja=72. Comprar 2 cajas agrega 144 piezas base; vender 3 paquetes descuenta 18.
Cada presentación puede tener SKU, código de barras, precio y costo propios.
## Lotes y caducidad
Los productos pueden activar lotes y caducidad. FEFO propone/descuenta primero el lote con vencimiento más próximo. La política de venta de vencidos es allow/warn/block y por defecto block.
## Series
Los productos serializados mantienen una serie única y estado available/reserved/sold/transit/returned/damaged, con sucursal y trazabilidad.
## Transferencias
Los traspasos conservan cantidades base, presentación de captura, lote y serie. Flujo draft -> sent -> received; enviar y recibir son eventos distintos para evitar inventario fantasma.
