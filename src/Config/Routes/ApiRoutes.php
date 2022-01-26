<?php
$routes->group('auth', function ($routes) {
    $routes->post('login', 'Auth::login');
});
$routes->setDefaultNamespace('Raydragneel\HerauthLib\Controllers\Api\Master');
$routes->group('master', ['filter' => 'auth_api_filter'], function ($routes) {
    $routes->group('group', function ($routes) {
        $routes->post('', 'HeraGroup::index');
        $routes->post('datatable', 'HeraGroup::datatable');
        $routes->post('add', 'HeraGroup::add');
        $routes->post('permissions/(:segment)', 'HeraGroup::permissions/$1');
        $routes->post('edit/(:segment)', 'HeraGroup::edit/$1');
        $routes->post('restore/(:segment)', 'HeraGroup::restore/$1');
        $routes->post('delete/(:segment)', 'HeraGroup::delete/$1');
        $routes->post('users/(:segment)', 'HeraGroup::users/$1');
        $routes->post('add_user_group/(:segment)', 'HeraGroup::add_user_group/$1');
        $routes->post('delete_user_group/(:segment)', 'HeraGroup::delete_user_group/$1');
        $routes->post('save_permissions/(:segment)', 'HeraGroup::save_permissions/$1');
    });
    $routes->group('permission', function ($routes) {
        $routes->post('', 'HeraPermission::index');
        $routes->post('datatable', 'HeraPermission::datatable');
        $routes->post('add', 'HeraPermission::add');
        $routes->post('edit/(:segment)', 'HeraPermission::edit/$1');
        $routes->post('restore/(:segment)', 'HeraPermission::restore/$1');
        $routes->post('delete/(:segment)', 'HeraPermission::delete/$1');
    });
    $routes->group('client', function ($routes) {
        $routes->post('datatable', 'HeraClient::datatable');
        $routes->post('add', 'HeraClient::add');
        $routes->post('permissions/(:segment)', 'HeraClient::permissions/$1');
        $routes->post('edit/(:segment)', 'HeraClient::edit/$1');
        $routes->post('regenerate_key/(:segment)', 'HeraClient::regenerate_key/$1');
        $routes->post('restore/(:segment)', 'HeraClient::restore/$1');
        $routes->post('delete/(:segment)', 'HeraClient::delete/$1');
        $routes->post('save_permissions/(:segment)', 'HeraClient::save_permissions/$1');
        $routes->post('save_whitelists/(:segment)', 'HeraClient::save_whitelists/$1');
    });
    $routes->group('admin', function ($routes) {
        $routes->post('datatable', 'HeraAdmin::datatable');
        $routes->post('add', 'HeraAdmin::add');
        $routes->post('edit/(:segment)', 'HeraAdmin::edit/$1');
        $routes->post('groups/(:segment)', 'HeraAdmin::groups/$1');
        $routes->post('restore/(:segment)', 'HeraAdmin::restore/$1');
        $routes->post('delete/(:segment)', 'HeraAdmin::delete/$1');
        $routes->post('save_group/(:segment)', 'HeraAdmin::save_group/$1');
    });
});
$routes->setDefaultNamespace('Raydragneel\HerauthLib\Controllers\Api');
$routes->group('request_log',['filter' => 'auth_api_filter'], function ($routes) {
    $routes->post('datatable', 'HeraRequestLog::datatable');
});