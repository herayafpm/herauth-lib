<?php
$routes->group('auth', function ($routes) {
    $routes->post('login', 'Auth::login');
});
$routes->group('master', ['namespace' => $routes->namespace . "\Master", 'filter' => 'auth_api_filter'], function ($routes) {
    $routes->group('group', function ($routes) {
        $routes->post('datatable', 'Group::datatable');
        $routes->post('add', 'Group::add');
        $routes->post('edit/(:segment)', 'Group::edit/$1');
        $routes->post('restore/(:segment)', 'Group::restore/$1');
        $routes->post('delete/(:segment)', 'Group::delete/$1');
    });
});
