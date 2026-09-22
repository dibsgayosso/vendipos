# Variantes, opciones e ingredientes
Vendi separa tres conceptos para no duplicar productos:
1. Variant: combinación con inventario propio, SKU/código de barras y opcionalmente precio/costo. Ej.: Playera / Roja / M.
2. Modifier: elección comercial sin inventario independiente. Ej.: sin hielo, extra queso, envoltura regalo.
3. Ingredient: componente/receta que consume inventario. Ej.: bebida preparada consume café, leche y jarabe.
Un producto puede tener múltiples grupos: Color, Talla, Material, Sabor, Presentación especial, etc.
Las variantes pueden tener existencia por sucursal. Los ingredientes usan cantidad en unidad base y porcentaje de merma.
Presentaciones (caja/paquete/pieza) siguen siendo conversiones de unidad y NO se modelan como variantes salvo que realmente sean productos físicamente distintos.
