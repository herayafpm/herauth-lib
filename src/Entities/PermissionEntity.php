<?php

namespace Raydragneel\HerauthLib\Entities;

use CodeIgniter\Entity\Entity;

class PermissionEntity extends Entity
{
    public function __construct(array $data = null)
    {
        parent::__construct($data);
    }
    protected $datamap = [];
    protected $dates   = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts   = [];

}
