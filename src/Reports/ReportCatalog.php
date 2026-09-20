<?php
declare(strict_types=1);
namespace Vendi\Reports;
final class ReportCatalog{
 public static function groups():array{return[
 ['key'=>'sales','name'=>'Ventas','reports'=>[
  ['key'=>'sales_overview','name'=>'Panorama de ventas','kind'=>'summary'],
  ['key'=>'sales_movements','name'=>'Movimientos de venta','kind'=>'detail'],
  ['key'=>'sales_trends','name'=>'Tendencias de venta','kind'=>'chart'],
  ['key'=>'sales_weekday','name'=>'Rendimiento por día','kind'=>'summary'],
  ['key'=>'sales_hour','name'=>'Rendimiento por horario','kind'=>'summary'],
  ['key'=>'sales_branches','name'=>'Desempeño por sucursal','kind'=>'summary'],
  ['key'=>'sales_cancelled','name'=>'Ventas anuladas','kind'=>'detail'],
  ['key'=>'sales_held','name'=>'Ventas pendientes','kind'=>'detail']]],
 ['key'=>'products','name'=>'Productos','reports'=>[
  ['key'=>'products_overview','name'=>'Panorama de productos','kind'=>'summary'],
  ['key'=>'products_analysis','name'=>'Análisis de productos','kind'=>'summary'],
  ['key'=>'category_profit','name'=>'Utilidad por categoría','kind'=>'summary'],
  ['key'=>'products_top','name'=>'Productos estrella','kind'=>'summary'],
  ['key'=>'products_slow','name'=>'Productos de baja rotación','kind'=>'summary'],
  ['key'=>'products_price_variance','name'=>'Variaciones de precio','kind'=>'detail'],
  ['key'=>'products_price_history','name'=>'Historial de precios','kind'=>'detail'],
  ['key'=>'products_serial_sales','name'=>'Series vendidas','kind'=>'detail'],
  ['key'=>'products_serial_trace','name'=>'Trazabilidad por serie','kind'=>'detail'],
  ['key'=>'serials','name'=>'Control de números de serie','kind'=>'detail']]],
 ['key'=>'inventory','name'=>'Inventario','reports'=>[
  ['key'=>'inventory_status','name'=>'Estado de existencias','kind'=>'summary'],
  ['key'=>'inventory_movements','name'=>'Movimientos de inventario','kind'=>'detail'],
  ['key'=>'inventory_low','name'=>'Existencias críticas','kind'=>'summary'],
  ['key'=>'inventory_history','name'=>'Inventario histórico','kind'=>'summary'],
  ['key'=>'inventory_counts','name'=>'Resultado de conteos','kind'=>'summary'],
  ['key'=>'inventory_count_detail','name'=>'Detalle de conteos','kind'=>'detail'],
  ['key'=>'inventory_expiring','name'=>'Próximos a vencer','kind'=>'summary'],
  ['key'=>'inventory_damaged','name'=>'Mercancía dañada','kind'=>'detail']]],
 ['key'=>'payments','name'=>'Caja y pagos','reports'=>[
  ['key'=>'payments_overview','name'=>'Panorama de cobros','kind'=>'summary'],
  ['key'=>'payments_movements','name'=>'Movimientos de cobro','kind'=>'detail'],
  ['key'=>'payments_register','name'=>'Cobros por caja','kind'=>'summary'],
  ['key'=>'cash_close','name'=>'Cierre de operación','kind'=>'summary'],
  ['key'=>'cash_log','name'=>'Bitácora de caja','kind'=>'detail']]],
 ['key'=>'purchases','name'=>'Compras','reports'=>[
  ['key'=>'purchases_movements','name'=>'Movimientos de compra','kind'=>'detail'],
  ['key'=>'purchases_categories','name'=>'Compras por categoría','kind'=>'summary'],
  ['key'=>'purchases_transfers','name'=>'Movimientos entre sucursales','kind'=>'detail'],
  ['key'=>'purchases_tax','name'=>'Impuestos en compras','kind'=>'summary'],
  ['key'=>'purchases_products','name'=>'Productos adquiridos','kind'=>'summary'],
  ['key'=>'purchases_payments','name'=>'Pagos de compras','kind'=>'summary'],
  ['key'=>'purchases_supplier_cost','name'=>'Comparativo de proveedores','kind'=>'summary']]],
 ['key'=>'customers','name'=>'Clientes','reports'=>[
  ['key'=>'customers_overview','name'=>'Panorama de clientes','kind'=>'summary'],
  ['key'=>'customers_history','name'=>'Historial por cliente','kind'=>'detail'],
  ['key'=>'customers_new','name'=>'Clientes nuevos','kind'=>'summary'],
  ['key'=>'customers_series','name'=>'Evolución de clientes','kind'=>'chart'],
  ['key'=>'customers_zip','name'=>'Clientes por código postal','kind'=>'summary']]],
 ['key'=>'team','name'=>'Personal','reports'=>[
  ['key'=>'team_performance','name'=>'Desempeño del equipo','kind'=>'summary'],
  ['key'=>'team_activity','name'=>'Actividad por empleado','kind'=>'detail'],
  ['key'=>'commissions_overview','name'=>'Panorama de comisiones','kind'=>'summary'],
  ['key'=>'commissions_movements','name'=>'Movimientos de comisión','kind'=>'detail'],
  ['key'=>'attendance_overview','name'=>'Panorama de asistencia','kind'=>'summary'],
  ['key'=>'attendance_detail','name'=>'Registro de asistencia','kind'=>'detail']]],
 ['key'=>'finance','name'=>'Finanzas y fiscal','reports'=>[
  ['key'=>'profit_loss','name'=>'Resultados del negocio','kind'=>'summary'],
  ['key'=>'profit_loss_detail','name'=>'Desglose de resultados','kind'=>'detail'],
  ['key'=>'expenses_overview','name'=>'Panorama de gastos','kind'=>'summary'],
  ['key'=>'expenses_movements','name'=>'Movimientos de gastos','kind'=>'detail'],
  ['key'=>'tax_overview','name'=>'Panorama fiscal','kind'=>'summary'],
  ['key'=>'customer_invoices','name'=>'Facturación a clientes','kind'=>'detail'],
  ['key'=>'supplier_invoices','name'=>'Documentos de proveedores','kind'=>'detail']]],
 ];}}
