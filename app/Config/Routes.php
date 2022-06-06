<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->post('login', 'AuthController::index');
$routes->get('verify', 'AuthController::verify');

//DocumentTypeController
$routes->group('document_type', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('/', 'DocumentTypeController::index', ['as' => 'document_type']);
    $routes->get('create', 'DocumentTypeController::create', ['as' => 'new_document_type']);
    $routes->post('create', 'DocumentTypeController::store', ['as' => 'store_document_type']);
    $routes->get('edit/(:any)', 'DocumentTypeController::edit/$1', ['as' => 'edit_document_type']);
    $routes->post('edit', 'DocumentTypeController::update', ['as' => 'update_document_type']);
    $routes->get('delete/(:num)', 'DocumentTypeController::destroy/$1', ['as' => 'delete_document_type']);
    $routes->get('info/(:num)', 'DocumentTypeController::show/$1', ['as' => 'info_document_type']);
});

//ReportTitlesController
$routes->group('report_titles', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('/', 'ReportTitlesController::index', ['as' => 'view_report_titles']);
    $routes->get('create', 'ReportTitlesController::create', ['as' => 'new_report_titles']);
    $routes->post('create', 'ReportTitlesController::store', ['as' => 'store_report_titles']);
    $routes->get('edit/(:any)', 'ReportTitlesController::edit/$1', ['as' => 'edit_report_titles']);
    $routes->post('edit', 'ReportTitlesController::update', ['as' => 'update_report_titles']);
    $routes->get('delete/(:num)', 'ReportTitlesController::destroy/$1', ['as' => 'delete_report_titles']);
    $routes->get('info/(:num)', 'ReportTitlesController::info/$1', ['as' => 'info_report_titles']);
});

//ClosingCodesController
$routes->group('closing_codes', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('/', 'ClosingCodesController::index', ['as' => 'view_closing_codes']);
    $routes->get('create', 'ClosingCodesController::create', ['as' => 'new_closing_codes']);
    $routes->post('create', 'ClosingCodesController::store', ['as' => 'store_closing_codes']);
    $routes->get('edit/(:any)', 'ClosingCodesController::edit/$1', ['as' => 'edit_closing_codes']);
    $routes->post('edit', 'ClosingCodesController::update', ['as' => 'update_closing_codes']);
    $routes->get('delete/(:num)', 'ClosingCodesController::destroy/$1', ['as' => 'delete_closing_codes']);
    $routes->get('info/(:num)', 'ClosingCodesController::info/$1', ['as' => 'info_closing_codes']);
});

//LevelController
$routes->group('level', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('/', 'LevelController::index', ['as' => 'view_level']);
    $routes->get('create', 'LevelController::create', ['as' => 'new_level']);
    $routes->post('create', 'LevelController::store', ['as' => 'store_level']);
    $routes->get('edit/(:any)', 'LevelController::edit/$1', ['as' => 'edit_level']);
    $routes->post('edit', 'LevelController::update', ['as' => 'update_level']);
    $routes->get('delete/(:num)', 'LevelController::destroy/$1', ['as' => 'delete_level']);
});

//AccountingCatalogsController
$routes->group('accounting_catalogs', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('/', 'AccountingCatalogsController::index', ['as' => 'view_accounting_catalogs']);
    $routes->get('create', 'AccountingCatalogsController::create', ['as' => 'new_accounting_catalogs']);
    $routes->post('create', 'AccountingCatalogsController::store', ['as' => 'store_accounting_catalogs']);
    $routes->get('edit/(:any)', 'AccountingCatalogsController::edit/$1', ['as' => 'edit_accounting_catalogs']);
    $routes->post('edit', 'AccountingCatalogsController::update', ['as' => 'update_accounting_catalogs']);
    $routes->get('delete/(:num)', 'AccountingCatalogsController::destroy/$1', ['as' => 'delete_accounting_catalogs']);
    $routes->get('info/(:num)', 'AccountingCatalogsController::info/$1', ['as' => 'info_accounting_catalogs']);
    $routes->post('get_catalog', 'AccountingCatalogsController::getCatalog', ['as' => 'get_catalog']);
    $routes->post('get_account_code', 'AccountingCatalogsController::getAccountCode', ['as' => 'get_account_code']);
});

//DailyMovementsController
$routes->group('daily_movements', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'DailyMovementController::index', ['as' => 'view_daily_movement']);
    $routes->get('create', 'DailyMovementController::create', ['as' => 'new_daily_movement']);
    $routes->post('create', 'DailyMovementController::store', ['as' => 'store_daily_movement']);
    $routes->get('edit/(:any)', 'DailyMovementController::edit/$1', ['as' => 'edit_daily_movement']);
    $routes->post('edit', 'DailyMovementController::update', ['as' => 'update_daily_movement']);
    $routes->get('delete/(:num)', 'DailyMovementController::destroy/$1', ['as' => 'delete_daily_movement']);
    $routes->post('get_correlative', 'DailyMovementController::getCorrelative', ['as' => 'get_correlative']);
});

//AccountingPeriodController
$routes->group('accounting_period', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('/', 'AccountingPeriodController::index', ['as' => 'view_accounting_period']);
    $routes->get('create', 'AccountingPeriodController::create', ['as' => 'new_accounting_period']);
    $routes->post('create', 'AccountingPeriodController::store', ['as' => 'store_accounting_period']);
    $routes->get('edit/(:any)', 'AccountingPeriodController::edit/$1', ['as' => 'edit_accounting_period']);
    $routes->post('edit', 'AccountingPeriodController::update', ['as' => 'update_accounting_period']);
    $routes->get('delete/(:num)', 'AccountingPeriodController::destroy/$1', ['as' => 'delete_accounting_period']);
    $routes->get('info/(:num)', 'AccountingPeriodController::info/$1', ['as' => 'info_accounting_period']);
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
