# Controles de inventario
## Conteo físico
El usuario captura existencia real. Al publicar, Vendi guarda existencia del sistema, existencia contada, diferencia, motivo, usuario y fecha. La diferencia genera un movimiento adjustment en Kardex.
## Mínimos y máximos
Cada producto puede definir min_stock y max_stock. El panel de faltantes lista productos en o debajo del mínimo y conserva el máximo como objetivo sugerido de reposición.
Los ajustes deben restringirse con inventory.adjust / inventory.count.
