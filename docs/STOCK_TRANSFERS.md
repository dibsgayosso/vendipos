# Transferencias entre sucursales
Estados: draft -> sent -> received.
Al enviar, Vendi valida y descuenta del origen, registra Kardex negativo y las series pasan a transit.
Al recibir, suma al destino, registra Kardex positivo y las series cambian de sucursal y vuelven a available.
Una transferencia no puede enviarse o recibirse dos veces.
Pendiente de endurecimiento: mover cantidades de lotes/caducidades al origen/destino y stock de variante de forma paralela al producto base; validación de permisos y recepción con diferencias/daños.
