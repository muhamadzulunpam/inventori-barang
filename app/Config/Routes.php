<?php

namespace Config;

$routes = Services::routes();

// Load system routes
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * Router Setup
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * Route Definitions
 */

// Public routes (tanpa login)
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/auth/login', 'AuthController::login');
$routes->get('/auth/logout', 'AuthController::logout');
$routes->get('/dashboard', 'DashboardController::index');

// Protected routes (wajib login)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->group('barang', function($routes) {
        $routes->get('/', 'BarangController::index');
        $routes->get('detail/(:any)', 'BarangController::detail/$1');
        $routes->get('exportExcel', 'BarangController::exportExcel');
        $routes->get('print', 'BarangController::print');

        $routes->get('new', 'BarangController::new', ['filter' => 'admin']);
        $routes->post('/', 'BarangController::create', ['filter' => 'admin']);
        $routes->get('edit/(:any)', 'BarangController::edit/$1', ['filter' => 'admin']);
        $routes->post('update/(:any)', 'BarangController::update/$1', ['filter' => 'admin']);
        $routes->post('delete/(:any)', 'BarangController::delete/$1', ['filter' => 'admin']);
    });
    
    $routes->get('/barang_masuk', 'BarangMasukController::index');
    $routes->get('/barang_masuk/create', 'BarangMasukController::create');  // Form tambah
    $routes->post('/barang_masuk/store', 'BarangMasukController::store');
    $routes->get('/barang_masuk/detail/(:any)', 'BarangMasukController::detail/$1');
    $routes->get('/barang_masuk/edit/(:any)', 'BarangMasukController::edit/$1');
    $routes->post('/barang_masuk/update/(:any)', 'BarangMasukController::update/$1');
    $routes->put('/barang_masuk/update/(:any)', 'BarangMasukController::update/$1'); 
    $routes->delete('/barang_masuk/delete/(:any)', 'BarangMasukController::delete/$1');
    $routes->get('/barang_masuk/export-excel', 'BarangMasukController::exportExcel');
    $routes->post('/barang_masuk/get-data', 'BarangMasukController::getDataAjax');
    $routes->get('barang_masuk/print', 'BarangMasukController::print');
    $routes->get('barang_masuk/print/filter', 'BarangMasukController::printFilter');
    
    // Barang Keluar (Semua Level)
    $routes->get('/barang_keluar', 'BarangKeluarController::index');
    $routes->get('/barang_keluar/create', 'BarangKeluarController::create');
    $routes->post('/barang_keluar/store', 'BarangKeluarController::store');
    $routes->get('/barang_keluar/detail/(:any)', 'BarangKeluarController::detail/$1');
    $routes->get('/barang_keluar/edit/(:any)', 'BarangKeluarController::edit/$1');
    $routes->post('/barang_keluar/update/(:any)', 'BarangKeluarController::update/$1');
    $routes->get('/barang_keluar/delete/(:any)', 'BarangKeluarController::delete/$1');
    $routes->get('barang_keluar/print', 'BarangKeluarController::print');
    
    // Super Admin only
    $routes->group('', ['filter' => 'superadmin'], function($routes) {
        $routes->resource('jenis_barang', ['controller' => 'JenisBarangController']);

        // User
        $routes->get('user', 'UserController::index');
        $routes->get('user/new', 'UserController::new');
        $routes->post('user', 'UserController::create');
        $routes->get('user/edit/(:any)', 'UserController::edit/$1');
        $routes->get('user/detail/(:any)', 'UserController::detail/$1');
        $routes->post('user/delete/(:any)', 'UserController::delete/$1');
        $routes->post('user/update/(:any)', 'UserController::update/$1');
        $routes->get('user/toggleStatus/(:any)', 'UserController::toggleStatus/$1');

        // Supplier
        $routes->get('supplier', 'SupplierController::index');
        $routes->get('supplier/new', 'SupplierController::new');
        $routes->post('supplier', 'SupplierController::create');
        $routes->get('supplier/detail/(:any)', 'SupplierController::detail/$1');
        $routes->get('supplier/edit/(:any)', 'SupplierController::edit/$1');
        $routes->post('supplier/update/(:any)', 'SupplierController::update/$1');
        $routes->post('supplier/delete/(:any)', 'SupplierController::delete/$1');
        $routes->get('supplier/print', 'SupplierController::print');

        // Jenis Barang
        $routes->get('jenis', 'JenisController::index');
        $routes->get('jenis/new', 'JenisController::new');
        $routes->post('jenis', 'JenisController::create');
        $routes->get('jenis/detail/(:any)', 'JenisController::detail/$1');
        $routes->get('jenis/edit/(:any)', 'JenisController::edit/$1');
        $routes->post('jenis/update/(:any)', 'JenisController::update/$1');
        $routes->post('jenis/delete/(:any)', 'JenisController::delete/$1');
        $routes->get('jenis/print', 'JenisController::print');

        // Satuan
        $routes->get('satuan', 'SatuanController::index');
        $routes->get('satuan/new', 'SatuanController::new');
        $routes->post('satuan', 'SatuanController::create');
        $routes->get('satuan/detail/(:any)', 'SatuanController::detail/$1');
        $routes->get('satuan/edit/(:any)', 'SatuanController::edit/$1');
        $routes->post('satuan/update/(:any)', 'SatuanController::update/$1');
        $routes->post('satuan/delete/(:any)', 'SatuanController::delete/$1');
        $routes->get('satuan/print', 'SatuanController::print');

        // ========================================
        // LAPORAN BARANG KELUAR (Semua Level)      
        });
        // Laporan Stok (semua level bisa akses)
        $routes->get('/laporan_stok', 'LaporanStokController::index');
        $routes->get('/laporan_stok/exportExcel', 'LaporanStokController::exportExcel');
        $routes->get('/laporan_stok/print', 'LaporanStokController::print');

        // Laporan Barang Masuk
        $routes->get('/laporan_masuk', 'LaporanMasukController::index');
        $routes->get('/laporan_masuk/exportExcel', 'LaporanMasukController::exportExcel');
        $routes->get('/laporan_masuk/print', 'LaporanMasukController::print');
        $routes->get('/laporan_keluar', 'LaporanKeluarController::index');
        $routes->get('/laporan_keluar/exportExcel', 'LaporanKeluarController::exportExcel');
        $routes->get('/laporan_keluar/print', 'LaporanKeluarController::print');
});