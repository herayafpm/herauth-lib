<?php

$routes->group('auth',function($routes){
    $routes->post('login','Auth::login');
});