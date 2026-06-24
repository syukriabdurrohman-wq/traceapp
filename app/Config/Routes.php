<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', ['filter' => 'guest'], static function (RouteCollection $routes): void {
    $routes->get('login', 'AuthController::loginPage');
    $routes->post('login', 'AuthController::login');
    $routes->get('login/otp', 'AuthController::otpPage');
    $routes->post('login/otp', 'AuthController::verifyOtp');
    $routes->get('register', 'AuthController::registerPage');
    $routes->post('register', 'AuthController::register');
});

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'DashboardController::index');
    $routes->get('logout', 'AuthController::logout');
    $routes->post('logout', 'AuthController::logout');

    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/photo', 'ProfileController::uploadPhoto');

    $routes->get('reports/create', 'DailyReportController::create');
    $routes->get('reports/edit/(:num)', 'DailyReportController::edit/$1');
    $routes->post('reports/save-draft', 'DailyReportController::saveDraft');
    $routes->get('reports/review/(:num)', 'DailyReportController::review/$1');
    $routes->post('reports/submit/(:num)', 'DailyReportController::submit/$1');
    $routes->get('reports/detail/(:num)', 'DailyReportController::detail/$1');
    $routes->get('reports/pdf/(:num)', 'DailyReportController::pdf/$1');

    $routes->group('admin', ['filter' => 'role:Admin'], static function (RouteCollection $routes): void {
        $routes->get('users', 'AdminController::users');
        $routes->post('users/save', 'AdminController::saveUser');
        $routes->get('users/toggle/(:num)', 'AdminController::toggleUserStatus/$1');
        $routes->get('users/delete/(:num)', 'AdminController::deleteUser/$1');
    });
    
    $routes->group('admin', ['filter' => 'role:Admin,Manager,Supervisor'], static function (RouteCollection $routes): void {
        $routes->get('reports', 'AdminController::reports');
    });

    $routes->group('manager', ['filter' => 'role:Admin,Manager'], static function (RouteCollection $routes): void {
        $routes->get('/', 'ManagerController::index');
    });
});

$routes->group('api', static function (RouteCollection $routes): void {
    $routes->post('auth/token', 'Api\AuthTokenController::issueToken');
    $routes->post('auth/refresh', 'Api\AuthTokenController::refreshToken');
});

$routes->group('api', ['filter' => 'jwtAuth'], static function (RouteCollection $routes): void {
    $routes->get('reports/today', 'Api\ReportApiController::today');
    $routes->get('reports/detail/(:num)', 'Api\ReportApiController::detail/$1');
});