<?php
declare(strict_types=1);
require dirname(__DIR__).'/vendor/autoload.php';
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Vendi POS</title><link rel="stylesheet" href="assets/app.css"></head><body>
<aside><div class="brand">Vendi<span>POS</span></div><nav><a class="active">Venta</a><a>Inventario</a><a>Productos</a><a>Clientes</a><a>Compras</a><a>Proveedores</a><a>Caja</a><a>Reportes</a><a>Sucursales</a><a>Periféricos</a><a>Configuración</a></nav></aside>
<main><header><div><b>Punto de venta</b><small>Sucursal principal</small></div><div><span id="scaleStatus" class="status">⚖ Sin báscula</span> <button id="connectScale">Conectar</button></div></header>
<section class="tabs" id="saleTabs"></section>
<section class="pos"><div class="catalog"><div class="search">⌕ <input placeholder="Buscar producto, SKU o escanear código"></div><div class="qtybar"><input id="qty" inputmode="decimal" placeholder="Cantidad o 4.25+3.80+6.10"><button id="qtyCalc">=</button><b id="scaleWeight"></b></div><div class="empty"><h2>Comienza una venta</h2><p>Escanea un producto o búscalo por nombre.</p></div></div>
<div class="cart"><h3>Venta actual <span>0 artículos</span></h3><div class="cartempty">Aún no agregas productos</div><div class="totals"><p>Subtotal <b>$0.00</b></p><p>Descuento <b>$0.00</b></p><p>IVA <b>$0.00</b></p><h2>Total <b>$0.00</b></h2></div><button class="charge" disabled>Cobrar</button></div></section></main><script src="assets/pos.js"></script></body></html>