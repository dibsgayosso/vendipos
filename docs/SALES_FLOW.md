# Flujo rápido de venta

1. Una sola barra acepta nombre, clave/SKU o código de barras.
2. Nombre/clave: sugerencias desde 3 caracteres, relevancia primero y ventas de 90 días después.
3. Código exacto + Enter: agrega directamente al carrito.
4. Preferencia sales.quantity_mode:
   - one: agrega cantidad 1.
   - select: agrega y selecciona cantidad.
   - smart: pieza/caja/pack = 1; kg/metro/litro/rollo enfoca cantidad.
5. Producto repetido puede acumular cantidad según sales.merge_same_product.
6. F2 cobro completo; F3 guarda; F4 venta rápida.
7. Nunca se confía en precios/totales enviados por navegador: checkout recalcula en servidor.
