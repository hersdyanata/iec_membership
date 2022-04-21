<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';
Route::get('/', function () {
    // return view('welcome');
    return redirect('/login');
});

// Route::get('/dashboard', function () {
//     return view('modules.dashboard.index');
// })->middleware(['auth'])->name('dashboard');

Route::name('main.')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('profile', App\Http\Controllers\ProfileController::class);

    Route::resource('compro', App\Http\Controllers\ComproController::class);
    Route::resource('member', App\Http\Controllers\MemberController::class);
});

/* ======================================================================= Route List Core ======================================================================= */
Route::post('core/switch-theme', [App\Http\Controllers\CoreController::class, 'switchTheme'])->name('switch_theme');
Route::resource('core', App\Http\Controllers\CoreController::class);
Route::resource('menu-manager', App\Http\Controllers\CoreMenuController::class);
Route::post('menu-manager/list', [App\Http\Controllers\CoreMenuController::class, 'list_menu'])->name('menu-manager.list_menu');
Route::post('menu-manager/predelete', [App\Http\Controllers\CoreMenuController::class, 'predelete_menu'])->name('menu-manager.predelete');
Route::post('menu-manager/delete', [App\Http\Controllers\CoreMenuController::class, 'delete_menu'])->name('menu-manager.delete');
Route::post('menu-manager/set-order-menu', [App\Http\Controllers\CoreMenuController::class, 'set_order_menu'])->name('menu-manager.set_order_menu');
Route::post('menu-manager/set-order-divider', [App\Http\Controllers\CoreMenuController::class, 'set_order_divider'])->name('menu-manager.set_order_divider');
Route::post('menu-manager/divider/store', [App\Http\Controllers\CoreMenuController::class, 'simpan_divider'])->name('save_divider');
Route::post('menu-manager/divider/list', [App\Http\Controllers\CoreMenuController::class, 'list_divider'])->name('core.list_divider');
Route::post('menu-manager/divider/read', [App\Http\Controllers\CoreMenuController::class, 'read_divider'])->name('core.read_divider');
Route::post('menu-manager/divider/update', [App\Http\Controllers\CoreMenuController::class, 'update_divider'])->name('update_divider');
Route::post('menu-manager/divider/predelete', [App\Http\Controllers\CoreMenuController::class, 'predelete_divider'])->name('predelete_divider');
Route::post('menu-manager/divider/delete', [App\Http\Controllers\CoreMenuController::class, 'delete_divider'])->name('delete_divider');
Route::get('menu-manager/divider/options', [App\Http\Controllers\CoreMenuController::class, 'divider_options'])->name('core.options_divider');
Route::post('menu-manager/divider/menu', [App\Http\Controllers\CoreMenuController::class, 'list_menu_divider'])->name('core.menu_divider');

Route::resource('usergroup', App\Http\Controllers\UsergroupController::class);
Route::post('usergroup/list', [App\Http\Controllers\UsergroupController::class, 'list_usergroup'])->name('usergorup.list');
Route::post('usergroup/predelete', [App\Http\Controllers\UsergroupController::class, 'predelete'])->name('usergroup.predelete');
Route::post('usergroup/delete', [App\Http\Controllers\UsergroupController::class, 'delete'])->name('usergroup.delete');

Route::resource('user', App\Http\Controllers\UserController::class);
Route::post('user/list', [App\Http\Controllers\UserController::class, 'list_user'])->name('user.list');
Route::get('user/show/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('user.show');
Route::delete('user/destroy/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
/* ======================================================================= Route List Core ======================================================================= */

Route::name('masterdata.')->group(function () {
    Route::resource('masterdata/mst_komoditas', App\Http\Controllers\MstKomoditasController::class);
    Route::post('masterdata/mst_komoditas/list', [App\Http\Controllers\MstKomoditasController::class, 'list'])->name('mst_komoditas.list');

    Route::resource('masterdata/mst_supplier', App\Http\Controllers\MstSupplierController::class);
    Route::post('masterdata/mst_supplier/list', [App\Http\Controllers\MstSupplierController::class, 'list'])->name('mst_supplier.list');
    
    Route::resource('masterdata/mst_buyer', App\Http\Controllers\MstBuyerController::class);
    Route::post('masterdata/mst_buyer/list', [App\Http\Controllers\MstBuyerController::class, 'list'])->name('mst_buyer.list');

    /* Masterdata wilayah */
    Route::get('wilayah/provinsi', [App\Http\Controllers\Mst_WilayahController::class, 'get_provinsi'])->name('wilayah.provinsi');
    Route::get('wilayah/kota/{provinsi}', [App\Http\Controllers\Mst_WilayahController::class, 'get_kota'])->name('wilayah.kota');
    Route::get('wilayah/kecamatan/{kota}', [App\Http\Controllers\Mst_WilayahController::class, 'get_kecamatan'])->name('wilayah.kecamatan');
    Route::get('wilayah/kelurahan/{kecamatan}', [App\Http\Controllers\Mst_WilayahController::class, 'get_kelurahan'])->name('wilayah.kelurahan');

    /* Masterdata HS Code */
    Route::get('hs/parent', [App\Http\Controllers\Mst_HSController::class, 'get_parent'])->name('hs.parent');
    Route::get('hs/sub/{parent}', [App\Http\Controllers\Mst_HSController::class, 'get_sub'])->name('hs.sub');
    Route::get('hs/code/{sub}', [App\Http\Controllers\Mst_HSController::class, 'get_hscode'])->name('hs.code');
});

Route::name('tools.')->group(function () {
    Route::resource('tools/pricing_generator', App\Http\Controllers\PriceGeneratorController::class);
    Route::post('tools/pricing_generator/supplier_komoditas', [App\Http\Controllers\PriceGeneratorController::class, 'supplier_komoditas'])->name('pricing_generator.supplier_komoditas');
    Route::post('tools/pricing_generator/bim_salabim', [App\Http\Controllers\PriceGeneratorController::class, 'generate'])->name('pricing_generator.generate');
    Route::post('tools/pricing_generator/list', [App\Http\Controllers\PriceGeneratorController::class, 'list'])->name('pricing_generator.list');
    Route::get('tools/pricing_generator/cetak/{param}/{terms}', [App\Http\Controllers\PriceGeneratorController::class, 'cetak'])->name('pricing_generator.cetak');
    Route::get('tools/pricing_generator/pra_copy/{id}', [App\Http\Controllers\PriceGeneratorController::class, 'pra_copy'])->name('pricing_generator.pra_copy');
    Route::post('tools/pricing_generator/copy', [App\Http\Controllers\PriceGeneratorController::class, 'copy'])->name('pricing_generator.copy');
});
