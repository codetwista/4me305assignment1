<?php

namespace Config;

// Create a new instance of our RouteCollection class.
use App\Controllers\HomeController;
use App\Controllers\PhotoController;
use App\Controllers\TwitterAuth;
use App\Controllers\UserController;

$routes = Services::routes();

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
$routes->get('/', [HomeController::class, 'index']);
$routes->get('welcome', [UserController::class, 'index']);
$routes->match(['get', 'post'], 'register', [UserController::class, 'register']);
$routes->match(['get', 'post'], 'login', [UserController::class, 'login']);
$routes->match(['get', 'post'], 'photos', [PhotoController::class, 'index']);
$routes->match(['get', 'post'], 'upload', [PhotoController::class, 'upload']);
$routes->get('logout', [UserController::class, 'logout']);

$routes->get('twitter', [TwitterAuth::class, 'index']);
$routes->get('twitter/login', [TwitterAuth::class, 'login']);
$routes->match(['get', 'post'],'tweet', [TwitterAuth::class, 'tweet']);
$routes->get('twitter/urls', [TwitterAuth::class, 'upload']);

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
