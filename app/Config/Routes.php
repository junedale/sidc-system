<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->group('/', static function($routes) {
   $routes->match(['get', 'post'], 'login', 'Account::login', ['filter' => 'auth']);
   $routes->get('logout', 'Account::logout');
   $routes->match(['get','post'], 'settings', 'Account::settings');

   $routes->group('stock', static function($routes) {
      $routes->get('', 'Stock::index');
      $routes->get('search', 'Stock::search');
      $routes->get('view/(:num)', 'Stock::show/$1');
      $routes->match(['get', 'post'], 'create', 'Stock::create');
      $routes->match(['get', 'post'], 'update/(:num)', 'Stock::update/$1');
      $routes->delete('delete/(:num)', 'Stock::delete/$1');
      $routes->put('cancel/(:num)', 'Stock::cancel/$1');
   });

   $routes->group('ob', static function($routes) {
      $routes->get('', 'OffBusiness::index');
      $routes->get('search', 'OffBusiness::search');
      $routes->get('view/(:num)', 'OffBusiness::show/$1');
      $routes->get('retrieveItem/(:num)', 'OffBusiness::retrieveItem/$1');
      $routes->match(['get', 'post'], 'create', 'OffBusiness::create');
      $routes->match(['get', 'post', 'put'], 'update/(:num)', 'OffBusiness::update/$1');
      $routes->put('cancel/(:num)', 'OffBusiness::cancel/$1');
      $routes->delete('delete/(:num)', 'OffBusiness::delete/$1');
   });

   $routes->group('leave', static function($routes) {
       $routes->get('', 'Leave::index');
       $routes->get('search', 'Leave::search');
       $routes->get('view/(:num)', 'Leave::show/$1');
       $routes->match(['get', 'post'], 'create', 'Leave::create');
       $routes->match(['get', 'post', 'put'], 'update/(:num)', 'Leave::update/$1');
       $routes->put('cancel/(:num)', 'Leave::cancel/$1');
   });

   $routes->group('overtime', static function($routes) {
       $routes->get('', 'Overtime::index');
       $routes->get('search', 'Overtime::search');
       $routes->get('view/(:num)', 'Overtime::show/$1');
       $routes->match(['get', 'post'], 'create', 'Overtime::create');
       $routes->match(['get', 'post', 'put'], 'update/(:num)', 'Overtime::update/$1');
       $routes->put('cancel/(:num)', 'Overtime::cancel/$1');
   });

   $routes->group('admin', static function($routes) {
       $routes->get('', 'Admin::index');
       $routes->get('search', 'Admin::search');
       $routes->match(['get', 'post'], 'create', 'Admin::create');
       $routes->match(['get', 'post'], 'update/(:num)', 'Admin::update/$1');
       $routes->put('reset/(:num)', 'Admin::reset/$1');
   });

   $routes->group('error', static function($routes) {
      $routes->get('forbidden', 'Error::forbidden');
   });

   $routes->addRedirect('', 'login');
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
