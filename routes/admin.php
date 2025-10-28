<?php

use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\CashClosingController;
use App\Http\Controllers\Admin\DailySaleController;
use App\Http\Controllers\Admin\OutputController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Livewire\Admin\Bills\Create as BillsCreate;
use App\Http\Livewire\Admin\Bills\Index as BillsIndex;
use App\Http\Livewire\Admin\Bills\Show as BillsShow;
use App\Http\Livewire\Admin\CashClosing\Index as CashClosingIndex;
use App\Http\Livewire\Admin\Company\Index as CompanyIndex;
use App\Http\Livewire\Admin\Customers\Index;
use App\Http\Livewire\Admin\DailySales\Index as DailySalesIndex;
use App\Http\Livewire\Admin\Factus\Connection;
use App\Http\Livewire\Admin\Finances\Index as FinancesIndex;
use App\Http\Livewire\Admin\Logs\Index as LogsIndex;
use App\Http\Livewire\Admin\Modules\Index as ModulesIndex;
use App\Http\Livewire\Admin\NumberingRanges\Index as NumberingRangesIndex;
use App\Http\Livewire\Admin\Outputs\Index as OutputsIndex;
use App\Http\Livewire\Admin\Payroll\Factus;
use App\Http\Livewire\Admin\Payroll\Index as PayrollIndex;
use App\Http\Livewire\Admin\Products\Index as ProductsIndex;
use App\Http\Livewire\Admin\Providers\Index as ProvidersIndex;
use App\Http\Livewire\Admin\Purchases\Create;
use App\Http\Livewire\Admin\Purchases\Index as PurchasesIndex;
use App\Http\Livewire\Admin\Purchases\Show;
use App\Http\Livewire\Admin\Roles\Index as RolesIndex;
use App\Http\Livewire\Admin\Sales\Index as SalesIndex;
use App\Http\Livewire\Admin\Staff\Index as StaffIndex;
use App\Http\Livewire\Admin\TaxRates\Index as TaxRatesIndex;
use App\Http\Livewire\Admin\Terminals\Index as TerminalsIndex;
use App\Http\Livewire\Admin\Users\Index as UsersIndex;
use App\Http\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Services\Index as ServicesIndex;
use App\Http\Livewire\Admin\Services\Create as ServicesCreate;
use App\Http\Livewire\Admin\Services\Edit as ServicesEdit;
use App\Http\Livewire\Admin\Services\Show as ServicesShow;

Route::get('/', Dashboard::class)->name('home');

Route::get('clientes', Index::class)->middleware('module:clientes')->name('customers.index');

Route::get('proveedores', ProvidersIndex::class)->middleware('module:proveedores')->name('providers.index');

Route::get('productos', ProductsIndex::class)->middleware('module:productos')->name('products.index');

Route::get('empleados', StaffIndex::class)->middleware(['module:empleados', 'check-payroll'])->name('staff.index');

Route::group(['middleware' => ['module:nomina', 'check-payroll']], function () {

    Route::get('nomina/factus/', Factus::class)->name('payroll.factus')->withoutMiddleware('check-payroll');

    Route::get('nomina', PayrollIndex::class)->name('payroll.index');

    Route::get('nomina/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');

    Route::get('nomina-download/{payroll}', [PayrollController::class, 'download'])->name('payroll.download');
});

Route::group(['middleware' => ['module:egresos']], function () {

    Route::get('egresos', OutputsIndex::class)->name('outputs.index');

    Route::get('egreso/{output}', [OutputController::class, 'show'])->name('outputs.show');

    Route::get('egreso-download/{output}', [OutputController::class, 'download'])->name('outputs.download');

});

Route::group(['middleware' => ['module:compras']], function () {

    Route::get('compras', PurchasesIndex::class)->name('purchases.index');

    Route::get('compras/agregar-compra', Create::class)->name('purchases.create');

    Route::get('compra/{purchase}', Show::class)->name('purchases.show');

    Route::get('compra/pdf/{purchase}', [PurchaseController::class, 'show'])->name('purchases.pdf');

    Route::get('compra-download/{purchase}', [PurchaseController::class, 'download'])->name('purchases.download');

});

Route::group(['middleware' => ['module:facturas']], function () {

    Route::get('facturas', BillsIndex::class)->name('bills.index');

    Route::get('facturas/nueva-factura', BillsCreate::class)->name('bills.create');

    Route::get('facturas/{bill}', BillsShow::class)->name('bills.show');

    Route::get('facturas/informacion/{bill}', [BillController::class, 'getBill'])->name('bills.information');

    Route::get('facturas/pdf/{bill}', [BillController::class, 'show'])->name('bills.pdf');

    Route::get('facturas-download/{bill}', [BillController::class, 'download'])->name('bills.download');

});

Route::get('ventas-rapidas/nueva-venta', fn () => view('livewire.admin.quick-sale.Index'))->name('quick-sales.create')->middleware('module:ventas rapidas');

Route::get('financiaciones', FinancesIndex::class)->middleware('module:financiaciones')->name('finances.index');

Route::get('usuarios', UsersIndex::class)->middleware('module:usuarios')->name('users.index');

Route::get('productos-vendidos', SalesIndex::class)->name('sold-products.index');

Route::get('configuración', CompanyIndex::class)->middleware('module:configuraciones')->name('companies.settings');

Route::group(['middleware' => ['module:cierre de caja']], function () {

    Route::get('cierre-de-caja', CashClosingIndex::class)->name('cash-closing.index');

    Route::get('cierre-de-caja/pdf/{cashClosing}', [CashClosingController::class, 'show'])->name('cash-closing.pdf');

});

Route::get('impuestos', TaxRatesIndex::class)->middleware('module:impuestos')->name('tax-rates.index');

Route::get('ventas-diarias', DailySalesIndex::class)->middleware('module:roles y permisos')->name('daily-sales.index');

Route::get('ventas-diarias/pdf/{dailySale?}', [DailySaleController::class, 'show'])->middleware('module:roles y permisos')->name('daily-sales.pdf');

Route::get('roles-y-permisos', RolesIndex::class)->middleware('module:roles y permisos')->name('roles.index');

Route::get('rangos-de-numeracion', NumberingRangesIndex::class)->name('numbering-ranges.index')->middleware('module:rangos de numeración');

Route::get('terminales', TerminalsIndex::class)->middleware('module:terminales')->name('terminals.index');

Route::get('modulos', ModulesIndex::class)->name('modules.index');

Route::get('factus/conexion', Connection::class)->name('factus.connection');

Route::get('logs', LogsIndex::class)->name('logs.index');

Route::get('logs-file', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs.file');

//Route::group(['middleware' => ['module:servicios']], function () {

    Route::get('servicios', ServicesIndex::class)->name('services.index');

    Route::get('servicios/nuevo-servicio', ServicesCreate::class)->name('services.create');

    Route::get('servicios/crear', ServicesEdit::class)->name('admin.services.create');

    Route::get('servicios/{service}/editar', ServicesEdit::class)->name('services.edit');

    Route::get('servicios/{service}', ServicesShow::class)->name('services.show');

//});
