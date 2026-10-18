<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'HomeController::index');

$routes->match(['get', 'post'], 'login', 'AuthController::login', ['as' => 'login']);
$routes->get('signup', 'AuthController::signup', ['as' => 'signup']);
$routes->post('signup', 'AuthController::register');
$routes->get('verify-email/(:segment)', 'AuthController::verifyEmail/$1', ['as' => 'verify-email']);


$routes->group('admin', ['filter' => ['auth', 'admin']], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard', ['as' => 'admin.dashboard']);
});

$routes->group('member', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'MemberController::dashboard', ['as' => 'member.dashboard']);
});

$routes->post('logout', 'AuthController::logout', ['filter' => 'auth', 'as' => 'logout']);