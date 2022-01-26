<?php

namespace Raydragneel\HerauthLib\Commands;

use CodeIgniter\CLI\BaseCommand;

class AppInstall extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'app:install';
    protected $description = 'Instalasi migrate & seeder App';

    public function run(array $params)
    {
        echo command('migrate:refresh');
        echo command("db:seed InitSeeder");
    }
}
