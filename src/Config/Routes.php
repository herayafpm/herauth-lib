<?php

namespace Raydragneel\HerauthLib\Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

$routes->group('herauth',function($routes){
    $routes->namespace = 'Raydragneel\HerauthLib\Controllers\Api';
    $routes->group('web/{locale}', ['namespace' => $routes->namespace], function ($routes) {
        require __DIR__.'./Routes/ApiRoutes.php';
    });
    $routes->group('api/{locale}', ['namespace' => $routes->namespace], function ($routes) {
        require __DIR__.'./Routes/ApiRoutes.php';
    });
    $routes->namespace = 'Raydragneel\HerauthLib\Controllers';
    $routes->group('', ['namespace' => $routes->namespace], function ($routes) {
        $routes->addRedirect('', 'herauth/id/login');
        $routes->group('{locale}', ['namespace' => $routes->namespace], function ($routes) {
            $routes->get('login','Auth::login');
        });
        $routes->get('assets/(:any)','Assets::file/$1');
    });
});
