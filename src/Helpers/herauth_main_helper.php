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
if (!function_exists('herauth_set_locale')) {
    function herauth_set_locale($locale)
    {
        $request = service('request');
        $segment = 0;
        if ($request->uri->getSegments()[0] === 'herauth') {
            $segment = 1;
        }
        $path = $request->uri->getPath();
        $path_ex = explode("/", $path);
        $path_ex[$segment] = $locale;
        $path = implode("/", $path_ex);
        return base_url($path);
    }
}
if (!function_exists('herauth_locale_img')) {
    function herauth_locale_img($locale)
    {
        if ($locale === 'en') {
            $locale = 'us';
        }
        return $locale;
    }
}
if (!function_exists('herauth_locale_text')) {
    function herauth_locale_text($locale)
    {
        switch ($locale) {
            case 'en':
                $locale_text = 'Inggris (US)';
                break;
            case 'id':
                $locale_text = 'Indonesia';
                break;
            default:
                $locale_text = 'Indonesia';
                break;
        }
        return $locale_text;
    }
}
