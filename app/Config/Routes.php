<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'HomeController::index');

$routes->group('', ['filter' => ['guest', 'honeypot', 'captcha']], static function ($routes) {
    $routes->match(['GET', 'POST'], 'login', 'AuthController::login', ['as' => 'login']);
    $routes->get('signup', 'AuthController::signup', ['as' => 'signup']);
    $routes->post('signup', 'AuthController::register');
});

$routes->get('verify-email/(:segment)', 'AuthController::verifyEmail/$1', ['as' => 'verify-email']);


$routes->group('admin', ['filter' => ['auth', 'admin']], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard', ['as' => 'admin.dashboard']);
    $routes->get('users', 'AdminUserController::users', ['as' => 'admin.users']);
    $routes->get('users/(:num)/edit', 'AdminUserController::editUser/$1');
    $routes->post('users/(:num)/update', 'AdminUserController::updateUser/$1');
    $routes->post('users/(:num)/status', 'AdminUserController::updateUserStatus/$1');
    $routes->post('users/(:num)/delete', 'AdminUserController::deleteUser/$1');

    $routes->get('tasks', 'AdminTaskController::index', ['as' => 'admin.tasks']);
    $routes->get('tasks/create', 'AdminTaskController::create');
    $routes->post('tasks', 'AdminTaskController::store');
    $routes->get('tasks/(:num)/edit', 'AdminTaskController::edit/$1');
    $routes->post('tasks/(:num)/update', 'AdminTaskController::update/$1');
    $routes->post('tasks/(:num)/delete', 'AdminTaskController::delete/$1');
});

$routes->group('member', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'MemberTaskController::dashboard', ['as' => 'member.dashboard']);
    $routes->get('tasks', 'MemberTaskController::index', ['as' => 'member.tasks']);
    $routes->post('tasks/(:num)/status', 'MemberTaskController::updateStatus/$1', ['filter' => 'auth']);
});

$routes->post('logout', 'AuthController::logout', ['filter' => 'auth', 'as' => 'logout']);