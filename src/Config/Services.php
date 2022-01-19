<?php

namespace Raydragneel\HerauthLib\Config;
use Config\Services as AppServices;
use CodeIgniter\Config\BaseService;
use CodeIgniter\Filters\Filters;
use Raydragneel\HerauthLib\Config\HerauthFilters;
use CodeIgniter\View\View;
use Config\View as ViewConfig;

class Services extends BaseService
{

    public static function filters(?FiltersConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('filters', $config);
        }

        $config = new HerauthFilters();

        return new Filters($config, AppServices::request(), AppServices::response());
    }

    public static function renderer(?string $viewPath = null, ?ViewConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('renderer', $viewPath, $config);
        }

        $paths = new HerauthPaths();
        $viewPath = $paths->viewDirectory;
        $config   = $config ?? config('View');

        return new View($config, $viewPath, AppServices::locator(), CI_DEBUG, AppServices::logger());
    }
}
