<?php

namespace Raydragneel\HerauthLib\Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

$routes->group('herauth',function($routes){
    $routes->setDefaultNamespace('Raydragneel\HerauthLib\Controllers\Api');
    $routes->setPrioritize(true);
    $routes->group('web/{locale}', function ($routes) {
        require __DIR__.'./Routes/ApiRoutes.php';
    });
    $routes->group('api/{locale}', function ($routes) {
        require __DIR__.'./Routes/ApiRoutes.php';
    });
    $routes->setPrioritize(false);
    $routes->setPrioritize(true);
    $routes->setDefaultNamespace('Raydragneel\HerauthLib\Controllers');
    $routes->group('', function ($routes) {
        $routes->get('assets/(:any)','Assets::file/$1');
        $routes->get('', 'Home::redirLocale',['priority' => 1]);
        $routes->group('{locale}', ['filter' => 'auth_filter'], function ($routes) {
            $routes->get('logout','Auth::logout');
            $routes->get('login','Auth::login');
            $routes->setDefaultNamespace('Raydragneel\HerauthLib\Controllers\Master');
            $routes->group('master', ['filter' => 'auth_filter'], function ($routes) {
                $routes->group('group', function ($routes) {
                    $routes->get('/','Group::index');
                    $routes->get('users/(:segment)','Group::users/$1');
                    $routes->get('permissions/(:segment)','Group::permissions/$1');
                    $routes->get('add','Group::add');
                    $routes->get('edit/(:segment)','Group::edit/$1');
                });
                $routes->group('permission', function ($routes) {
                    $routes->get('/','Permission::index');
                    $routes->get('add','Permission::add');
                    $routes->get('edit/(:segment)','Permission::edit/$1');
                });
                $routes->group('client', function ($routes) {
                    $routes->get('/','Client::index');
                    $routes->get('add','Client::add');
                    $routes->get('edit/(:segment)','Client::edit/$1');
                    $routes->get('permissions/(:segment)','Client::permissions/$1');
                    $routes->get('whitelists/(:segment)','Client::whitelists/$1');
                });
                $routes->group('admin', function ($routes) {
                    $routes->get('/','Admin::index');
                    $routes->get('group/(:segment)','Admin::group/$1');
                    $routes->get('add','Admin::add');
                    $routes->get('edit/(:segment)','Admin::edit/$1');
                });
            });
            $routes->setDefaultNamespace('Raydragneel\HerauthLib\Controllers');
            $routes->get('request_log','RequestLog::index');
            $routes->get('/','Home::index');
            // $routes->get('(:any)','Home::index/$1');
        });
    });
    $routes->setPrioritize(false);
    
});
