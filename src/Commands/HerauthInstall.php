<?php

namespace Raydragneel\HerauthLib\Commands;

use CodeIgniter\CLI\BaseCommand;

class HerauthInstall extends BaseCommand
{
    protected $group       = 'HerauthLib';
    protected $name        = 'herauth:install';
    protected $description = 'Instalasi Kebutuhan Librari Herauth';

    public function run(array $params)
    {
        echo command('herauth:migrate-refresh');
        echo command("db:seed Raydragneel\\\\HerauthLib\\\\Database\\\\Seeds\\\\InitSeeder");
    }
}
