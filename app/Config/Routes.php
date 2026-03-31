<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'StudentController::create'); // Public registration (Student perspective)

// Auth Routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::postLogin');
$routes->get('/logout', 'AuthController::logout');

// Student Management (Admin restricted by filter in Filters.php)
$routes->group('students', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('/', 'StudentController::index');
    $routes->get('create', 'StudentController::create');
    $routes->post('save', 'StudentController::save');
    $routes->get('view/(:num)', 'StudentController::view/$1');
    $routes->get('edit/(:num)', 'StudentController::edit/$1');
    $routes->post('update/(:num)', 'StudentController::update/$1');
    $routes->get('delete/(:num)', 'StudentController::delete/$1');
    $routes->post('status/(:num)', 'StudentController::updateStatus/$1');
    $routes->get('export-zip', 'StudentController::exportZip');
    $routes->get('storage-stats', 'StudentController::storageStats');
});