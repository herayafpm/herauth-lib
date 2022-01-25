<?php
$routes->group('auth', function ($routes) {
    $routes->post('login', 'Auth::login');
});
$routes->group('master', ['namespace' => $routes->namespace . "\Master", 'filter' => 'auth_api_filter'], function ($routes) {
    $routes->group('group', function ($routes) {
        $routes->post('', 'Group::index');
        $routes->post('datatable', 'Group::datatable');
        $routes->post('add', 'Group::add');
        $routes->post('permissions/(:segment)', 'Group::permissions/$1');
        $routes->post('edit/(:segment)', 'Group::edit/$1');
        $routes->post('restore/(:segment)', 'Group::restore/$1');
        $routes->post('delete/(:segment)', 'Group::delete/$1');
        $routes->post('users/(:segment)', 'Group::users/$1');
        $routes->post('add_user_group/(:segment)', 'Group::add_user_group/$1');
        $routes->post('delete_user_group/(:segment)', 'Group::delete_user_group/$1');
        $routes->post('save_permissions/(:segment)', 'Group::save_permissions/$1');
    });
    $routes->group('permission', function ($routes) {
        $routes->post('', 'Permission::index');
        $routes->post('datatable', 'Permission::datatable');
        $routes->post('add', 'Permission::add');
        $routes->post('edit/(:segment)', 'Permission::edit/$1');
        $routes->post('restore/(:segment)', 'Permission::restore/$1');
        $routes->post('delete/(:segment)', 'Permission::delete/$1');
    });
    $routes->group('client', function ($routes) {
        $routes->post('datatable', 'Client::datatable');
        $routes->post('add', 'Client::add');
        $routes->post('permissions/(:segment)', 'Client::permissions/$1');
        $routes->post('edit/(:segment)', 'Client::edit/$1');
        $routes->post('regenerate_key/(:segment)', 'Client::regenerate_key/$1');
        $routes->post('restore/(:segment)', 'Client::restore/$1');
        $routes->post('delete/(:segment)', 'Client::delete/$1');
        $routes->post('save_permissions/(:segment)', 'Client::save_permissions/$1');
        $routes->post('save_whitelists/(:segment)', 'Client::save_whitelists/$1');
    });
    $routes->group('admin', function ($routes) {
        $routes->post('datatable', 'Admin::datatable');
        $routes->post('add', 'Admin::add');
        $routes->post('edit/(:segment)', 'Admin::edit/$1');
        $routes->post('groups/(:segment)', 'Admin::groups/$1');
        $routes->post('restore/(:segment)', 'Admin::restore/$1');
        $routes->post('delete/(:segment)', 'Admin::delete/$1');
        $routes->post('save_group/(:segment)', 'Admin::save_group/$1');
    });
});

$routes->group('request_log',['namespace' => $routes->namespace , 'filter' => 'auth_api_filter'], function ($routes) {
    $routes->post('datatable', 'RequestLog::datatable');
});