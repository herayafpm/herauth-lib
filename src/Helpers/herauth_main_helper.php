<?php

if (!function_exists('herauth_base_url')) {
    function herauth_base_url($url = '')
    {
        if ($url !== '') {
            return config('App')->baseURL . "/herauth/{$url}";
        } else {
            return config('App')->baseURL . "/herauth";
        }
    }
}
if (!function_exists('herauth_asset_url')) {
    function herauth_asset_url($url = '')
    {
        return herauth_base_url('assets/' . $url);
    }
}
if (!function_exists('herauth_base_locale_url')) {
    function herauth_base_locale_url($url = '')
    {
        $request = service('request');
        return herauth_base_url(('' . $request->getLocale() ?? 'en') . (($url !== '') ? '/' . $url : ''));
    }
}
if (!function_exists('herauth_web_url')) {
    function herauth_web_url($url = '')
    {
        $request = service('request');
        return herauth_base_url(('web/' . $request->getLocale() ?? 'en') . (($url !== '') ? '/' . $url : ''));
    }
}
if (!function_exists('herauth_api_url')) {
    function herauth_api_url($url = '')
    {
        $request = service('request');
        return herauth_base_url(('api/' . $request->getLocale() ?? 'en') . (($url !== '') ? '/' . $url : ''));
    }
}
